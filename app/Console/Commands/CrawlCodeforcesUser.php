<?php

namespace App\Console\Commands;

use App\Models\CodeforcesHandle;
use App\Models\Contest;
use App\Models\Problem;
use App\Models\Submission;
use App\Models\RatingHistory;
use App\Models\ContestParticipation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CrawlCodeforcesUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codeforces:crawl-user {handle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl specific user data from Codeforces API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $handle = $this->argument('handle');
        $this->info("Starting crawling for user: {$handle}");

        try {
            $codeforcesHandle = CodeforcesHandle::where('handle', $handle)->first();
            
            if (!$codeforcesHandle) {
                $this->error("Handle {$handle} not found in database");
                return;
            }

            // Crawl user info
            $this->crawlUserInfo($codeforcesHandle);
            
            // Crawl user rating
            $this->crawlUserRating($codeforcesHandle);
            
            // Crawl user submissions
            $this->crawlUserSubmissions($codeforcesHandle);
            
            // Update last crawled time
            $codeforcesHandle->update(['last_crawled_at' => now()]);
            
            $this->info("Crawling completed for user: {$handle}");
            
        } catch (\Exception $e) {
            $this->error('Error during crawling: ' . $e->getMessage());
            Log::error("Codeforces user crawling error for {$handle}: " . $e->getMessage());
        }
    }

    private function crawlUserInfo($codeforcesHandle)
    {
        $this->info("Crawling user info for: {$codeforcesHandle->handle}");
        
        $response = Http::timeout(30)->get('https://codeforces.com/api/user.info', [
            'handles' => $codeforcesHandle->handle
        ]);
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch user info from Codeforces API');
        }

        $data = $response->json();
        
        if ($data['status'] !== 'OK') {
            throw new \Exception('Codeforces API returned error: ' . ($data['comment'] ?? 'Unknown error'));
        }

        $userInfo = $data['result'][0];
        
        $codeforcesHandle->update([
            'current_rating' => $userInfo['rating'] ?? 0,
            'max_rating' => $userInfo['maxRating'] ?? 0,
            'rank' => $userInfo['rank'] ?? null,
            'max_rank' => $userInfo['maxRank'] ?? null,
            'contribution' => $userInfo['contribution'] ?? 0,
            'organization' => $userInfo['organization'] ?? null,
            'country' => $userInfo['country'] ?? null,
            'city' => $userInfo['city'] ?? null,
            'avatar_url' => $userInfo['avatar'] ?? null,
        ]);
    }

    private function crawlUserRating($codeforcesHandle)
    {
        $this->info("Crawling rating history for: {$codeforcesHandle->handle}");
        
        $response = Http::timeout(30)->get('https://codeforces.com/api/user.rating', [
            'handle' => $codeforcesHandle->handle
        ]);
        
        if (!$response->successful()) {
            $this->warn('Failed to fetch rating history from Codeforces API');
            return;
        }

        $data = $response->json();
        
        if ($data['status'] !== 'OK') {
            $this->warn('Codeforces API returned error for rating: ' . ($data['comment'] ?? 'Unknown error'));
            return;
        }

        $ratingChanges = $data['result'];
        
        foreach ($ratingChanges as $ratingChange) {
            $contest = Contest::where('contest_id', $ratingChange['contestId'])->first();
            
            if (!$contest) {
                continue; // Skip if contest doesn't exist
            }

            RatingHistory::updateOrCreate(
                [
                    'codeforces_handle_id' => $codeforcesHandle->id,
                    'contest_id' => $contest->id,
                ],
                [
                    'contest_name' => $ratingChange['contestName'],
                    'old_rating' => $ratingChange['oldRating'],
                    'new_rating' => $ratingChange['newRating'],
                    'rank' => $ratingChange['rank'],
                    'rating_update_time' => Carbon::createFromTimestamp($ratingChange['ratingUpdateTimeSeconds']),
                ]
            );

            // Also create contest participation
            ContestParticipation::updateOrCreate(
                [
                    'codeforces_handle_id' => $codeforcesHandle->id,
                    'contest_id' => $contest->id,
                ],
                [
                    'rank' => $ratingChange['rank'],
                    'participation_time' => Carbon::createFromTimestamp($ratingChange['ratingUpdateTimeSeconds']),
                ]
            );
        }
    }

    private function crawlUserSubmissions($codeforcesHandle)
    {
        $this->info("Crawling submissions for: {$codeforcesHandle->handle}");
        
        $response = Http::timeout(30)->get('https://codeforces.com/api/user.status', [
            'handle' => $codeforcesHandle->handle,
            'from' => 1,
            'count' => 1000 // Limit to recent 1000 submissions
        ]);
        
        if (!$response->successful()) {
            $this->warn('Failed to fetch submissions from Codeforces API');
            return;
        }

        $data = $response->json();
        
        if ($data['status'] !== 'OK') {
            $this->warn('Codeforces API returned error for submissions: ' . ($data['comment'] ?? 'Unknown error'));
            return;
        }

        $submissions = $data['result'];
        
        foreach ($submissions as $submissionData) {
            $this->processSubmission($codeforcesHandle, $submissionData);
        }
    }

    private function processSubmission($codeforcesHandle, $submissionData)
    {
        // Find the problem
        $problem = null;
        if (isset($submissionData['problem']['contestId']) && isset($submissionData['problem']['index'])) {
            $contest = Contest::where('contest_id', $submissionData['problem']['contestId'])->first();
            if ($contest) {
                $problem = Problem::where('contest_id', $contest->id)
                    ->where('index', $submissionData['problem']['index'])
                    ->first();
            }
        }

        if (!$problem) {
            return; // Skip if problem doesn't exist
        }

        Submission::updateOrCreate(
            [
                'submission_id' => $submissionData['id'],
            ],
            [
                'codeforces_handle_id' => $codeforcesHandle->id,
                'problem_id' => $problem->id,
                'creation_time' => Carbon::createFromTimestamp($submissionData['creationTimeSeconds']),
                'author_handle' => $submissionData['author']['members'][0]['handle'] ?? $codeforcesHandle->handle,
                'programming_language' => $submissionData['programmingLanguage'],
                'verdict' => $submissionData['verdict'] ?? 'PENDING',
                'testset' => $submissionData['testset'] ?? 'TESTS',
                'passed_test_count' => $submissionData['passedTestCount'] ?? 0,
                'time_consumed_millis' => $submissionData['timeConsumedMillis'] ?? 0,
                'memory_consumed_bytes' => $submissionData['memoryConsumedBytes'] ?? 0,
            ]
        );
    }
}
