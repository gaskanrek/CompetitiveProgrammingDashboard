<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TelegramMessage;
use Carbon\Carbon;

class CleanupTelegramMessages extends Command
{
    protected $signature = 'telegram:cleanup {--days=30 : Delete messages older than X days} {--dry-run : Show what would be deleted without actually deleting}';
    protected $description = 'Clean up old Telegram messages from database';

    public function handle()
    {
        $days = $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $cutoffDate = Carbon::now()->subDays($days);
        
        $this->info('Telegram Messages Cleanup');
        $this->line('==========================');
        $this->line('Cutoff date: ' . $cutoffDate->format('Y-m-d H:i:s'));
        $this->line('Delete messages older than: ' . $days . ' days');
        
        // Find old messages
        $oldMessages = TelegramMessage::where('telegram_date', '<', $cutoffDate);
        $count = $oldMessages->count();
        
        if ($count === 0) {
            $this->info('No old messages found to delete.');
            return;
        }
        
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No messages will actually be deleted');
            $this->line('');
            $this->line('Messages that would be deleted:');
            
            $messages = $oldMessages->get();
            foreach ($messages as $index => $message) {
                $this->line(($index + 1) . '. ' . \Str::limit($message->text, 60) . ' (' . $message->telegram_date->format('Y-m-d') . ')');
            }
            
            $this->line('');
            $this->warn('Total: ' . $count . ' messages would be deleted');
            $this->line('Run without --dry-run to actually delete these messages');
        } else {
            if ($this->confirm("Are you sure you want to delete {$count} old messages?")) {
                $deleted = $oldMessages->delete();
                $this->info("✅ Successfully deleted {$deleted} old messages");
            } else {
                $this->info('Cleanup cancelled');
            }
        }
    }
}
