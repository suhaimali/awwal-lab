<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Clean up audit logs older than 180 days (6 months) every day at midnight
use Illuminate\Support\Facades\Schedule;
Schedule::command('audit:cleanup --days=180')->daily();
