<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramMessage;

class TelegramCacheStatus extends Command
{
    protected $signature = 'telegram:status';
    protected $description = 'Check Telegram database status and stored messages';

    public function handle()
    {
        $this->info('Telegram Database Status');
        $this->line('=========================');
        
        // Check total messages
        $totalMessages = TelegramMessage::count();
        $activeMessages = TelegramMessage::active()->count();
        $contestMessages = TelegramMessage::contestRelated()->count();
        $recentMessages = TelegramMessage::active()->contestRelated()->notExpired()->recent(5)->get();
        
        $this->info('Total messages in database: ' . $totalMessages);
        $this->info('Active messages: ' . $activeMessages);
        $this->info('Contest-related messages: ' . $contestMessages);
        $this->info('Recent active messages: ' . $recentMessages->count());
        
        if ($recentMessages->count() > 0) {
            $this->line('');
            $this->line('Recent active messages:');
            foreach ($recentMessages as $index => $message) {
                $status = '';
                if (!$message->is_active) $status .= '[INACTIVE] ';
                if ($message->is_expired) $status .= '[EXPIRED] ';
                
                $expiry = $message->expires_at ? ' (expires ' . $message->expires_at->diffForHumans() . ')' : '';
                
                $this->line(($index + 1) . '. ' . $status . \Str::limit($message->text, 60) . ' (' . $message->time_ago . ')' . $expiry);
            }
        }
        
        $this->line('');
        $this->line('Commands:');
        $this->line('- telegram:fetch     Fetch and save new messages');
        $this->line('- telegram:expire    Expire old messages manually');
        $this->line('- telegram:cleanup   Clean old/expired messages');
    }
}
