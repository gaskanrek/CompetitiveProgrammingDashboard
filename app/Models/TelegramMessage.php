<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class TelegramMessage extends Model
{
    protected $fillable = [
        'telegram_message_id',
        'text',
        'telegram_date',
        'chat_title',
        'forward_from',
        'entities',
        'has_media',
        'is_contest_related',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'telegram_date' => 'datetime',
        'entities' => 'array',
        'has_media' => 'boolean',
        'is_contest_related' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Scope for active messages
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for contest-related messages
     */
    public function scopeContestRelated(Builder $query): Builder
    {
        return $query->where('is_contest_related', true);
    }

    /**
     * Scope for non-expired messages
     */
    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Scope for recent messages
     */
    public function scopeRecent(Builder $query, int $limit = 5): Builder
    {
        return $query->orderBy('telegram_date', 'desc')->limit($limit);
    }

    /**
     * Get URLs from message entities
     */
    public function getUrlsAttribute(): array
    {
        $urls = [];
        if ($this->entities) {
            foreach ($this->entities as $entity) {
                if ($entity['type'] === 'url') {
                    $start = $entity['offset'];
                    $length = $entity['length'];
                    $urls[] = substr($this->text, $start, $length);
                }
            }
        }
        return $urls;
    }

    /**
     * Check if message is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get human readable time
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->telegram_date->diffForHumans();
    }

    /**
     * Create or update message from Telegram data
     */
    public static function createFromTelegramData(array $telegramMessage): self
    {
        // Auto-set expiry (7 hari dari tanggal pesan)
        $expiresAt = $telegramMessage['date']->copy()->addDays(7);
        
        return self::updateOrCreate(
            ['telegram_message_id' => $telegramMessage['id']],
            [
                'text' => $telegramMessage['text'],
                'telegram_date' => $telegramMessage['date'],
                'chat_title' => $telegramMessage['chat_title'],
                'forward_from' => $telegramMessage['forward_from'],
                'entities' => $telegramMessage['entities'],
                'has_media' => $telegramMessage['has_media'],
                'is_contest_related' => true, // Auto-set karena sudah difilter
                'is_active' => true, // Auto-set sebagai aktif
                'expires_at' => $expiresAt,
            ]
        );
    }

    /**
     * Set custom expiry for message
     */
    public function setExpiryDays(int $days): self
    {
        $this->expires_at = $this->telegram_date->copy()->addDays($days);
        $this->save();
        return $this;
    }

    /**
     * Auto-expire old messages (can be called in scheduled job)
     */
    public static function expireOldMessages(): int
    {
        return self::where('expires_at', '<=', now())
                   ->where('is_active', true)
                   ->update(['is_active' => false]);
    }
}
