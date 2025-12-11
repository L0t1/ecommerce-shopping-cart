<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

// Schedule the daily sales report to run every evening at 6:00 PM
Schedule::command('report:daily-sales')
    ->dailyAt('18:00')
    ->timezone(config('app.timezone', 'UTC'))
    ->onSuccess(function () {
        Log::info('Daily sales report sent successfully');
    })
    ->onFailure(function () {
        Log::error('Failed to send daily sales report');
    });

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
