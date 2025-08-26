<?php

namespace App\Console\Commands;

use App\Models\CodeforcesHandle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CrawlAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codeforces:crawl-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawl all active users data from Codeforces';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Set maximum execution time
        set_time_limit(600); // 10 minutes
        
        $this->info('Starting crawling for all active users...');

        $activeHandles = CodeforcesHandle::where('is_active', true)->get();
        
        if ($activeHandles->isEmpty()) {
            $this->warn('No active Codeforces handles found.');
            return;
        }

        $this->info("Found {$activeHandles->count()} active handles to crawl.");
        
        $bar = $this->output->createProgressBar($activeHandles->count());
        $bar->start();

        $successCount = 0;
        $errorCount = 0;

        foreach ($activeHandles as $handle) {
            try {
                $this->info("\nCrawling data for: {$handle->handle}");
                
                // Call the individual user crawling command
                Artisan::call('codeforces:crawl-user', [
                    'handle' => $handle->handle
                ]);
                
                $successCount++;
                
                // Add delay to avoid hitting API rate limits
                sleep(2); // Increased delay to be safer
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("\nError crawling {$handle->handle}: " . $e->getMessage());
                
                // Continue with next handle even if one fails
                continue;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nCrawling completed!");
        $this->info("Successful: {$successCount}, Errors: {$errorCount}");
    }
}
