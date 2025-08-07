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
        $this->info('Starting crawling for all active users...');

        $activeHandles = CodeforcesHandle::where('is_active', true)->get();
        
        if ($activeHandles->isEmpty()) {
            $this->warn('No active Codeforces handles found.');
            return;
        }

        $this->info("Found {$activeHandles->count()} active handles to crawl.");
        
        $bar = $this->output->createProgressBar($activeHandles->count());
        $bar->start();

        foreach ($activeHandles as $handle) {
            try {
                $this->info("\nCrawling data for: {$handle->handle}");
                
                // Call the individual user crawling command
                Artisan::call('codeforces:crawl-user', [
                    'handle' => $handle->handle
                ]);
                
                // Add delay to avoid hitting API rate limits
                sleep(1);
                
            } catch (\Exception $e) {
                $this->error("\nError crawling {$handle->handle}: " . $e->getMessage());
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nCrawling completed for all users!");
    }
}
