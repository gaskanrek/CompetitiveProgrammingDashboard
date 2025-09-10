<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Telegram Bot integration to fetch contest-related
    | messages from Telegram channel.
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),
    
    /*
    |--------------------------------------------------------------------------
    | Message Settings
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | Message Settings
    |--------------------------------------------------------------------------
    */
    
    'max_messages' => 10,
    'expiry_days' => env('TELEGRAM_EXPIRY_DAYS', 7), // Auto-expire setelah X hari
    'contest_keywords' => [
        'contest', 'kontes', 'competition', 'kompetisi', 
        'round', 'div', 'educational', 'global',
        'codeforces', 'atcoder', 'topcoder', 'leetcode'
    ],
];
