<?php

namespace App\Console\Commands;

use App\Models\Contest;
use App\Models\Problem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CrawlCodeforcesContests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codeforces:crawl-contests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl contests data from Codeforces API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Codeforces contests crawling...');

        try {
            // Crawl contests
            $this->crawlContests();
            
            // Crawl problems
            $this->crawlProblems();
            
            $this->info('Codeforces contests crawling completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error during crawling: ' . $e->getMessage());
            Log::error('Codeforces crawling error: ' . $e->getMessage());
        }
    }

    private function crawlContests()
    {
        $this->info('Crawling contests...');
        
        $response = Http::timeout(30)->get('https://codeforces.com/api/contest.list');
        
        if (!$response->successful()) {
            throw new \Exception('Failed to fetch contests from Codeforces API');
        }

        $data = $response->json();
        
        if ($data['status'] !== 'OK') {
            throw new \Exception('Codeforces API returned error: ' . ($data['comment'] ?? 'Unknown error'));
        }

        $contests = $data['result'];
        $processed = 0;

        foreach ($contests as $contestData) {
            $this->processContest($contestData);
            $processed++;
            
            if ($processed % 50 === 0) {
                $this->info("Processed {$processed} contests...");
            }
        }

        $this->info("Processed total {$processed} contests");
    }

    private function processContest($contestData)
    {
        $startTime = null;
        if (isset($contestData['startTimeSeconds'])) {
            $startTime = Carbon::createFromTimestamp($contestData['startTimeSeconds']);
        }

        Contest::updateOrCreate(
            ['contest_id' => $contestData['id']],
            [
                'name' => $contestData['name'],
                'type' => $contestData['type'] ?? 'CF',
                'phase' => $contestData['phase'],
                'frozen' => $contestData['frozen'] ?? false,
                'duration_seconds' => $contestData['durationSeconds'] ?? null,
                'start_time' => $startTime,
                'relative_time_seconds' => null, // Skip this field for now due to datetime overflow issues
                'prepared_by_id' => $contestData['preparedBy'] ?? null,
                'website_url' => $contestData['websiteUrl'] ?? null,
                'description' => $contestData['description'] ?? null,
                'difficulty' => $contestData['difficulty'] ?? null,
                'kind' => $contestData['kind'] ?? null,
                'icpc_region' => $contestData['icpcRegion'] ?? null,
                'country' => $contestData['country'] ?? null,
                'city' => $contestData['city'] ?? null,
                'season' => $contestData['season'] ?? null,
            ]
        );
    }

    private function crawlProblems()
    {
        $this->info('Crawling problems...');
        
        $response = Http::timeout(30)->get('https://codeforces.com/api/problemset.problems');
        
        if (!$response->successful()) {
            $this->warn('Failed to fetch problems from Codeforces API');
            return;
        }

        $data = $response->json();
        
        if ($data['status'] !== 'OK') {
            $this->warn('Codeforces API returned error for problems: ' . ($data['comment'] ?? 'Unknown error'));
            return;
        }

        $problems = $data['result']['problems'];
        $problemStatistics = $data['result']['problemStatistics'];
        
        // Create a map of problem statistics
        $statisticsMap = [];
        foreach ($problemStatistics as $stat) {
            $key = $stat['contestId'] . '-' . $stat['index'];
            $statisticsMap[$key] = $stat['solvedCount'];
        }

        $processed = 0;

        foreach ($problems as $problemData) {
            $this->processProblem($problemData, $statisticsMap);
            $processed++;
            
            if ($processed % 100 === 0) {
                $this->info("Processed {$processed} problems...");
            }
        }

        $this->info("Processed total {$processed} problems");
    }

    private function processProblem($problemData, $statisticsMap)
    {
        $contest = Contest::where('contest_id', $problemData['contestId'])->first();
        
        if (!$contest) {
            return; // Skip if contest doesn't exist
        }

        $key = $problemData['contestId'] . '-' . $problemData['index'];
        $solvedCount = $statisticsMap[$key] ?? 0;

        Problem::updateOrCreate(
            [
                'contest_id' => $contest->id,
                'index' => $problemData['index']
            ],
            [
                'problem_id' => $problemData['contestId'] . $problemData['index'],
                'name' => $problemData['name'],
                'rating' => $problemData['rating'] ?? null,
                'tags' => $problemData['tags'] ?? [],
                'solved_count' => $solvedCount,
                'type' => $problemData['type'] ?? 'PROGRAMMING',
                'points' => $problemData['points'] ?? null,
                'problem_url' => "https://codeforces.com/contest/{$problemData['contestId']}/problem/{$problemData['index']}"
            ]
        );
    }
}
