<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\TelegramMessage;

class TelegramService
{
    private $botToken;
    private $chatId;
    private $baseUrl;

    public function __construct()
    {
        $this->botToken = config('telegram.bot_token');
        $this->chatId = config('telegram.chat_id');
        $this->baseUrl = "https://api.telegram.org/bot{$this->botToken}";
    }

    /**
     * Get recent messages from the Telegram channel
     */
    public function getChannelMessages($limit = 10)
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/getUpdates", [
                'limit' => $limit,
                'offset' => -$limit
            ]);

            if (!$response->successful()) {
                Log::error('Telegram API Error: ' . $response->body());
                return [];
            }

            $data = $response->json();
            
            if (!$data['ok']) {
                Log::error('Telegram API Error: ' . $data['description'] ?? 'Unknown error');
                return [];
            }

            $messages = [];
            foreach ($data['result'] as $update) {
                if (isset($update['channel_post']) || isset($update['message'])) {
                    $message = $update['channel_post'] ?? $update['message'];
                    
                    // Only process messages from our specific chat
                    if ($message['chat']['id'] == $this->chatId) {
                        $messages[] = $this->formatMessage($message);
                    }
                }
            }

            // Sort by date descending
            usort($messages, function($a, $b) {
                return $b['date'] <=> $a['date'];
            });

            return array_slice($messages, 0, $limit);

        } catch (\Exception $e) {
            Log::error('Telegram Service Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get contest-related messages from database
     */
    public function getContestMessagesFromDB($limit = 5)
    {
        return TelegramMessage::active()
            ->contestRelated()
            ->notExpired()
            ->recent($limit)
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->telegram_message_id,
                    'text' => $message->text,
                    'date' => $message->telegram_date,
                    'chat_title' => $message->chat_title,
                    'forward_from' => $message->forward_from,
                    'entities' => $message->entities ?? [],
                    'has_media' => $message->has_media,
                    'urls' => $message->urls,
                    'expires_at' => $message->expires_at,
                    'is_near_expiry' => $message->expires_at && $message->expires_at->diffInDays(now()) <= 1
                ];
            })->toArray();
    }

    /**
     * Fetch and save messages to database
     */
    public function fetchAndSaveMessages($limit = 10)
    {
        try {
            // Get messages from Telegram
            $telegramMessages = $this->getContestMessages($limit);
            
            $savedCount = 0;
            foreach ($telegramMessages as $message) {
                // Save to database with auto-expiry
                $telegramMessage = TelegramMessage::createFromTelegramData($message);
                
                // Set custom expiry days if different from default
                $expiryDays = config('telegram.expiry_days', 7);
                if ($expiryDays != 7) {
                    $telegramMessage->setExpiryDays($expiryDays);
                }
                
                $savedCount++;
            }
            
            // Auto-expire old messages
            $expiredCount = TelegramMessage::expireOldMessages();
            if ($expiredCount > 0) {
                Log::info('Auto-expired old Telegram messages', ['count' => $expiredCount]);
            }
            
            Log::info('Telegram messages saved to database', [
                'saved' => $savedCount, 
                'expired' => $expiredCount
            ]);
            
            return $savedCount;
            
        } catch (\Exception $e) {
            Log::error('Failed to fetch and save Telegram messages: ' . $e->getMessage());
            throw $e;
        }
    }
    public function getContestMessages($limit = 5)
    {
        $messages = $this->getChannelMessages(50); // Get more messages to filter
        
        $contestMessages = [];
        $contestKeywords = [
            'contest', 'kontes', 'competition', 'kompetisi', 
            'round', 'div', 'educational', 'global',
            'codeforces', 'atcoder', 'topcoder'
        ];

        foreach ($messages as $message) {
            $text = strtolower($message['text']);
            
            foreach ($contestKeywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $contestMessages[] = $message;
                    break;
                }
            }
            
            if (count($contestMessages) >= $limit) {
                break;
            }
        }

        return $contestMessages;
    }

    /**
     * Format message data
     */
    private function formatMessage($message)
    {
        return [
            'id' => $message['message_id'],
            'text' => $message['text'] ?? '',
            'date' => Carbon::createFromTimestamp($message['date']),
            'chat_title' => $message['chat']['title'] ?? 'Channel',
            'forward_from' => isset($message['forward_from']) ? $message['forward_from']['first_name'] : null,
            'entities' => $message['entities'] ?? [],
            'has_media' => isset($message['photo']) || isset($message['video']) || isset($message['document'])
        ];
    }

    /**
     * Extract URLs from message
     */
    public function extractUrls($message)
    {
        $urls = [];
        if (isset($message['entities'])) {
            foreach ($message['entities'] as $entity) {
                if ($entity['type'] === 'url') {
                    $start = $entity['offset'];
                    $length = $entity['length'];
                    $urls[] = substr($message['text'], $start, $length);
                }
            }
        }
        return $urls;
    }

    /**
     * Test connection to Telegram bot
     */
    public function testConnection()
    {
        try {
            $response = Http::timeout(10)->get("{$this->baseUrl}/getMe");
            
            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => $data['ok'],
                    'bot_info' => $data['result'] ?? null,
                    'message' => $data['ok'] ? 'Connection successful' : 'Connection failed'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'HTTP Error: ' . $response->status()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Exception: ' . $e->getMessage()
            ];
        }
    }
}
