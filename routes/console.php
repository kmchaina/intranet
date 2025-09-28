<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Adoption daily aggregation (runs at 01:05 server time if enabled)
Schedule::command('adoption:aggregate-daily')->dailyAt('01:05');
// Poll engagement reminders (every 4 hours)
Schedule::command('adoption:poll-reminders')->everyFourHours();
