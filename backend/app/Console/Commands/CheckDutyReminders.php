<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AssignmentReminderService;

class CheckDutyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignments:check-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periksa dan kirim otomatis notifikasi H-10 menit dan H-0 jam tugas anggota via Lonceng & WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memeriksa jadwal penugasan tugas anggota...');
        $result = AssignmentReminderService::checkAndSendReminders();
        $this->info("Selesai! H-10m terkirim: {$result['sent_10m']}, Jam mulai terkirim: {$result['sent_start']}");
        return Command::SUCCESS;
    }
}

