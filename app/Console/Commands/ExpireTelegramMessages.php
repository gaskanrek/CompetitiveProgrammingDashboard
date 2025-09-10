<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramMessage;

class ExpireTelegramMessages extends Command
{
    protected $signature = 'telegram:expire';
    protected $description = 'Expire old Telegram messages based on expiry date';

    public function handle()
    {
        $this->info('Expiring old Telegram messages...');
        
        $expiredCount = TelegramMessage::expireOldMessages();
        
        if ($expiredCount === 0) {
            $this->info('No messages to expire.');
        } else {
            $this->info("✅ Expired {$expiredCount} old messages");
        }
        
        // Show current active messages
        $activeCount = TelegramMessage::active()->notExpired()->count();
        $this->line("Active messages remaining: {$activeCount}");
        
        return 0;
    }
}
