<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal pemeriksaan otomatis notifikasi H-10 menit & H-0 tugas anggota
Schedule::command('assignments:check-reminders')->everyMinute();

