<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FetchTelegramMessages extends Command
{
    protected $signature = 'telegram:fetch';
    protected $description = 'Fetch latest messages from Telegram channel';

    public function handle()
    {
        $this->info('Fetching and saving messages from Telegram channel...');
        
        try {
            $telegramService = new TelegramService();
            
            // Fetch and save to database
            $savedCount = $telegramService->fetchAndSaveMessages(10);
            
            if ($savedCount === 0) {
                $this->warn('No new contest-related messages found');
                return;
            }
            
            $this->info('✅ Successfully fetched and saved ' . $savedCount . ' messages to database');
            
            // Display recent messages from database
            $recentMessages = $telegramService->getContestMessagesFromDB(5);
            foreach ($recentMessages as $index => $message) {
                $this->line(($index + 1) . '. ' . \Str::limit($message['text'], 80) . ' (' . $message['date']->diffForHumans() . ')');
            }
            
            Log::info('Telegram messages fetched and saved successfully', ['count' => $savedCount]);
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to fetch Telegram messages: ' . $e->getMessage());
            Log::error('Failed to fetch Telegram messages', ['error' => $e->getMessage()]);
        }
    }
}
