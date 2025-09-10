<?php

namespace App\Console\Commands;

use App\Services\TelegramService;
use Illuminate\Console\Command;

class TestTelegramConnection extends Command
{
    protected $signature = 'telegram:test';
    protected $description = 'Test Telegram bot connection and fetch sample messages';

    public function handle()
    {
        $this->info('Testing Telegram Bot Connection...');
        
        $telegramService = new TelegramService();
        
        // Test connection
        $this->info('1. Testing bot connection...');
        $connectionTest = $telegramService->testConnection();
        
        if ($connectionTest['success']) {
            $this->info('✅ Bot connection successful!');
            $this->info('Bot Info: ' . $connectionTest['bot_info']['first_name'] . ' (@' . $connectionTest['bot_info']['username'] . ')');
        } else {
            $this->error('❌ Bot connection failed: ' . $connectionTest['message']);
            return;
        }
        
        // Test fetching messages
        $this->info('2. Fetching recent messages...');
        $messages = $telegramService->getChannelMessages(5);
        
        if (empty($messages)) {
            $this->warn('No messages found or unable to fetch messages');
        } else {
            $this->info('✅ Found ' . count($messages) . ' messages');
            
            foreach ($messages as $index => $message) {
                $this->line('');
                $this->info('Message ' . ($index + 1) . ':');
                $this->line('Date: ' . $message['date']->format('Y-m-d H:i:s'));
                $this->line('Text: ' . \Str::limit($message['text'], 100));
                if ($message['forward_from']) {
                    $this->line('Forwarded from: ' . $message['forward_from']);
                }
            }
        }
        
        // Test contest messages
        $this->info('3. Fetching contest-related messages...');
        $contestMessages = $telegramService->getContestMessages(3);
        
        if (empty($contestMessages)) {
            $this->warn('No contest-related messages found');
        } else {
            $this->info('✅ Found ' . count($contestMessages) . ' contest-related messages');
        }
        
        $this->info('Telegram test completed!');
    }
}
