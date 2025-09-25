<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jalankan command aspirasi:auto-expire tiap jam
// Jalankan command tiap menit
Schedule::command('app:aspirasi-auto-expire')->everyMinute();

// Schedule::command('app:aspirasi-auto-expire')
//     ->weeklyOn(1, '1:00'); // 1 = Senin
