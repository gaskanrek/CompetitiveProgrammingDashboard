<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramMessage;

class DemoExpireTelegram extends Command
{
    protected $signature = 'telegram:demo-expire';
    protected $description = 'Demo expire functionality by setting first message to expire';

    public function handle()
    {
        $message = TelegramMessage::first();
        
        if (!$message) {
            $this->error('No messages found to demo');
            return;
        }
        
        // Set message to expire 1 minute ago
        $message->expires_at = now()->subMinutes(1);
        $message->save();
        
        $this->info('✅ Set message to expire 1 minute ago for demo');
        $this->line('Message: ' . \Str::limit($message->text, 60));
        $this->line('Original expire: ' . $message->telegram_date->addDays(7)->format('Y-m-d H:i:s'));
        $this->line('Demo expire: ' . $message->expires_at->format('Y-m-d H:i:s'));
        
        $this->line('');
        $this->line('Now run "php artisan telegram:expire" to test expiry');
        
        return 0;
    }
}
