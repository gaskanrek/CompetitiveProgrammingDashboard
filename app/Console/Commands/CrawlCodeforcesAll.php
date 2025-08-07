<?php

namespace App\Console\Commands;

use App\Models\CodeforcesHandle;
use Illuminate\Console\Command;

class CrawlCodeforcesAll extends Command
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
    protected $description = 'Crawl all data from Codeforces (contests and all users)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting complete Codeforces crawling...');

        try {
            // First crawl contests and problems
            $this->info('Crawling contests and problems...');
            $this->call('codeforces:crawl-contests');
            
            // Then crawl all active users
            $this->info('Crawling all users...');
            $activeHandles = CodeforcesHandle::where('is_active', true)->get();
            
            $total = $activeHandles->count();
            $this->info("Found {$total} active handles to crawl");
            
            $processed = 0;
            foreach ($activeHandles as $handle) {
                $processed++;
                $this->info("Processing {$processed}/{$total}: {$handle->handle}");
                
                try {
                    $this->call('codeforces:crawl-user', ['handle' => $handle->handle]);
                    
                    // Add delay to avoid rate limiting
                    sleep(1);
                    
                } catch (\Exception $e) {
                    $this->error("Failed to crawl {$handle->handle}: " . $e->getMessage());
                    continue;
                }
            }
            
            $this->info('Complete Codeforces crawling finished!');
            
        } catch (\Exception $e) {
            $this->error('Error during complete crawling: ' . $e->getMessage());
        }
    }
}
