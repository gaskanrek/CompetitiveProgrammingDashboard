<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule crawling tasks
Schedule::command('codeforces:crawl-contests')
    ->dailyAt('02:00') // Daily at 2 AM
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/crawler.log'));

Schedule::command('codeforces:crawl-all')
    ->weeklyOn(1, '03:00') // Every Monday at 3 AM
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/crawler.log'));

// Clean up old logs
Schedule::command('log:clear')
    ->monthly()
    ->appendOutputTo(storage_path('logs/maintenance.log'));
