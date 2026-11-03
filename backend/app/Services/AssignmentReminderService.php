<?php

namespace App\Services;

use App\Models\Assignment;
use App\Models\User;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AssignmentReminderService
{
    /**
     * Format a neat, structured, and formal WhatsApp message for assignments.
     *
     * @param Assignment $asg
     * @param string $stage 'assigned' | '10m_warning' | 'duty_started'
     * @return string
     */
    public static function buildMessage(Assignment $asg, string $stage = 'assigned'): string
    {
        $asg->loadMissing(['region', 'user.member']);

        $userName = $asg->user?->name ?? 'Anggota Panitia';
        $memberNo = $asg->user?->member_no ?? 'RJB';
        $dutyTitle = $asg->duty_name ?: $asg->role_in_post;
        $category = $asg->category ?: 'Teknis Lapangan';
        $regionName = $asg->region?->name ?? 'Wilayah Acara';
        $spotLocation = $asg->specific_location ?: ($asg->region?->description ?: '-');
        $timeStr = $asg->execution_time ?: ($asg->start_at ? $asg->start_at->format('H:i') . ' WIB' : '08:00 - Selesai WIB');
        $dateStr = $asg->start_at ? $asg->start_at->translatedFormat('l, d F Y') : Carbon::now()->translatedFormat('l, d F Y');
        $sopDesc = !empty($asg->description) ? trim($asg->description) : 'Melaksanakan koordinasi posko, cek perangkat kerja, dan standby di titik penugasan.';
        $appUrl = config('app.url', url('/'));

        if ($stage === 'assigned') {
            // ==========================================
            // NOTIFIKASI 1: SAAT ADMIN MENUGASKAN DIA
            // ==========================================
            return implode("\n", [
                "*PENETAPAN PENUGASAN RESMI*",
                "*PANITIA ROJABIYYAH & HAUL 2026*",
                "_Pondok Pesantren Al-Muhibbin Tambakberas_",
                "",
                "Assalamu'alaikum Wr. Wb.",
                "Yth. Sdr. *{$userName}* ({$memberNo})",
                "",
                "Alhamdulillah, Anda telah resmi ditetapkan oleh Panitia untuk mengemban amanah penugasan:",
                "",
                "📋 *Pos / Tugas* : *{$dutyTitle}*",
                "🏷️ *Kategori*    : {$category}",
                "📍 *Wilayah*     : {$regionName}",
                "📌 *Lokasi Spot* : {$spotLocation}",
                "📅 *Hari/Tgl*    : {$dateStr}",
                "⏰ *Waktu Tugas* : *{$timeStr}*",
                "",
                "📝 *Instruksi Lapangan*:",
                "_{$sopDesc}_",
                "",
                "💡 *Catatan Otomatis*:",
                "Sistem kami akan mengirimkan pengingat WhatsApp otomatis pada *H-10 menit* dan *saat waktu tugas dimulai*.",
                "",
                "📱 *Akses Portal Panitia*:",
                "{$appUrl}/dashboard",
                "",
                "Jazakumullah Khairan Katsiran atas khidmah panjenengan.",
                "_Wassalamu'alaikum Wr. Wb._",
                "*Koordinator Sie Media & Lapangan Haul 2026*",
            ]);
        } elseif ($stage === '10m_warning') {
            // ==========================================
            // NOTIFIKASI 2: KURANG 10 MENIT SEBELUM TUGAS
            // ==========================================
            return implode("\n", [
                "⚠️ *PENGINGAT H-10 MENIT PENUGASAN*",
                "*PANITIA ROJABIYYAH & HAUL 2026*",
                "_Pondok Pesantren Al-Muhibbin Tambakberas_",
                "",
                "Assalamu'alaikum Wr. Wb.",
                "Yth. Sdr. *{$userName}*",
                "",
                "⏰ *Pemberitahuan: Waktu Tugas Dimulai dalam 10 Menit!*",
                "",
                "📋 *Tugas*       : *{$dutyTitle}* ({$category})",
                "📍 *Lokasi Pos*  : {$regionName} - {$spotLocation}",
                "⏰ *Jadwal Mulai*: *{$timeStr}*",
                "",
                "Harap segera bersiap di titik pos tugas dan lakukan pengecekan kesiapan alat/perangkat Anda.",
                "",
                "Jika terdapat kendala darurat, segera lapor melalui menu *Lapor Kondisi Pos* di aplikasi:",
                "{$appUrl}/regions",
                "",
                "Semangat berkhidmah & jaga kesehatan!",
                "_Wassalamu'alaikum Wr. Wb._",
                "*Sie Media & Penugasan Panitia*",
            ]);
        } else {
            // ==========================================
            // NOTIFIKASI 3: PAS JAM TUGAS DIMULAI
            // ==========================================
            return implode("\n", [
                "🚀 *WAKTU PENUGASAN RESMI DIMULAI*",
                "*PANITIA ROJABIYYAH & HAUL 2026*",
                "_Pondok Pesantren Al-Muhibbin Tambakberas_",
                "",
                "Assalamu'alaikum Wr. Wb.",
                "Yth. Sdr. *{$userName}*",
                "",
                "Waktu penugasan Anda resmi *DIMULAI SEKARANG!*",
                "",
                "📋 *Tugas*       : *{$dutyTitle}*",
                "📍 *Wilayah*     : {$regionName} ({$spotLocation})",
                "⏰ *Sesi Waktu*  : *{$timeStr}*",
                "",
                "📝 *Arahan SOP Lapangan*:",
                "_{$sopDesc}_",
                "",
                "Pastikan posisi Anda standby dan dokumentasikan kelancaran posko melalui portal resmi:",
                "{$appUrl}/regions",
                "",
                "Semoga Allah SWT meridhoi setiap langkah khidmah kita.",
                "_Wassalamu'alaikum Wr. Wb._",
                "*Sie Media & Penugasan Panitia*",
            ]);
        }
    }

    /**
     * Send Notification #1: Immediately when admin assigns duty to member.
     */
    public static function sendAssignedNotification(Assignment $asg): array
    {
        $asg->loadMissing(['region', 'user']);
        $user = $asg->user;
        if (!$user) {
            return ['success' => false, 'message' => 'User penugasan tidak ditemukan.'];
        }

        $regionName = $asg->region?->name ?? 'Posko Wilayah';
        $dutyTitle = $asg->duty_name ?: $asg->role_in_post;
        $timeStr = $asg->execution_time ?: ($asg->start_at ? $asg->start_at->format('H:i') . ' WIB' : '08:00 WIB');

        // 1. In-App Notification (Always stored in database)
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'type' => 'assignment_priority',
            'data' => json_encode([
                'title' => '📋 Prioritas Tugas: Penugasan Baru!',
                'message' => "Anda resmi ditugaskan di {$dutyTitle} ({$regionName}) jadwal {$timeStr}. Cek detail dan instruksi lapangan.",
                'region_id' => $asg->region_id,
                'assignment_id' => $asg->id,
                'role_in_post' => $asg->role_in_post,
                'region_name' => $regionName,
                'url' => '/regions',
                'created_at' => now()->toIso8601String(),
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Automated WhatsApp Message
        $waResult = ['success' => false];
        $targetPhone = $user->phone_wa ?: ($user->member?->phone_wa ?? null);

        if ($targetPhone) {
            $waMsg = self::buildMessage($asg, 'assigned');
            $waResult = WhatsAppService::send($targetPhone, $waMsg);
        }

        // Mark as sent
        $asg->update([
            'notif_assigned_at' => now(),
        ]);

        return [
            'success' => true,
            'in_app' => true,
            'wa' => $waResult,
        ];
    }

    /**
     * Send Notification #2: Exactly 10 minutes before duty start time.
     */
    public static function send10MinutesReminder(Assignment $asg): array
    {
        $asg->loadMissing(['region', 'user']);
        $user = $asg->user;
        if (!$user) return ['success' => false];

        $regionName = $asg->region?->name ?? 'Posko Wilayah';
        $dutyTitle = $asg->duty_name ?: $asg->role_in_post;
        $timeStr = $asg->execution_time ?: ($asg->start_at ? $asg->start_at->format('H:i') . ' WIB' : '10 Menit Lagi');

        // 1. In-App Notification
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'type' => 'duty_reminder_10m',
            'data' => json_encode([
                'title' => '⏰ PERINGATAN H-10 MENIT: Siap di Titik Posko!',
                'message' => "Tugas Anda di {$dutyTitle} ({$regionName}) akan dimulai dalam 10 menit ({$timeStr}). Harap segera merapat ke lokasi.",
                'region_id' => $asg->region_id,
                'assignment_id' => $asg->id,
                'role_in_post' => $asg->role_in_post,
                'region_name' => $regionName,
                'url' => '/regions',
                'created_at' => now()->toIso8601String(),
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Automated WhatsApp
        $waResult = ['success' => false];
        $targetPhone = $user->phone_wa ?: ($user->member?->phone_wa ?? null);
        if ($targetPhone) {
            $waMsg = self::buildMessage($asg, '10m_warning');
            $waResult = WhatsAppService::send($targetPhone, $waMsg);
        }

        $asg->update([
            'notif_10m_at' => now(),
        ]);

        Log::info("Sent H-10m Reminder for Assignment #{$asg->id} to User #{$user->id}");

        return ['success' => true, 'wa' => $waResult];
    }

    /**
     * Send Notification #3: Exactly at duty start time.
     */
    public static function sendStartDutyNotification(Assignment $asg): array
    {
        $asg->loadMissing(['region', 'user']);
        $user = $asg->user;
        if (!$user) return ['success' => false];

        $regionName = $asg->region?->name ?? 'Posko Wilayah';
        $dutyTitle = $asg->duty_name ?: $asg->role_in_post;

        // 1. In-App Notification
        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'type' => 'duty_started',
            'data' => json_encode([
                'title' => '🚀 WAKTU TUGAS DIMULAI: Selamat Bertugas!',
                'message' => "Waktu tugas Anda di {$dutyTitle} ({$regionName}) resmi dimulai sekarang. Jalankan SOP dan laporkan kondisi pos berkala.",
                'region_id' => $asg->region_id,
                'assignment_id' => $asg->id,
                'role_in_post' => $asg->role_in_post,
                'region_name' => $regionName,
                'url' => '/regions',
                'created_at' => now()->toIso8601String(),
            ]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Automated WhatsApp
        $waResult = ['success' => false];
        $targetPhone = $user->phone_wa ?: ($user->member?->phone_wa ?? null);
        if ($targetPhone) {
            $waMsg = self::buildMessage($asg, 'duty_started');
            $waResult = WhatsAppService::send($targetPhone, $waMsg);
        }

        $asg->update([
            'notif_start_at' => now(),
        ]);

        Log::info("Sent Start Duty Notification for Assignment #{$asg->id} to User #{$user->id}");

        return ['success' => true, 'wa' => $waResult];
    }

    /**
     * Scan database and dispatch any pending H-10m and H-0 reminders.
     * Can be invoked via Artisan Schedule or real-time polling triggers.
     */
    public static function checkAndSendReminders(): array
    {
        $now = Carbon::now();
        $tenMinutesFromNow = $now->copy()->addMinutes(10);
        $count10m = 0;
        $countStart = 0;

        // 1. Check for H-10 Minutes Reminders
        // Condition: start_at is within the next 10 minutes (and not already past by more than 5 minutes)
        $pending10m = Assignment::whereNotNull('start_at')
            ->whereNull('notif_10m_at')
            ->where('start_at', '<=', $tenMinutesFromNow)
            ->where('start_at', '>=', $now->copy()->subMinutes(5))
            ->get();

        foreach ($pending10m as $asg) {
            self::send10MinutesReminder($asg);
            $count10m++;
        }

        // 2. Check for Start Duty (H-0) Reminders
        // Condition: start_at has reached now (within last 2 hours to avoid ancient stale records)
        $pendingStart = Assignment::whereNotNull('start_at')
            ->whereNull('notif_start_at')
            ->where('start_at', '<=', $now)
            ->where('start_at', '>=', $now->copy()->subHours(2))
            ->get();

        foreach ($pendingStart as $asg) {
            self::sendStartDutyNotification($asg);
            $countStart++;
        }

        return [
            'checked_at' => $now->toIso8601String(),
            'sent_10m' => $count10m,
            'sent_start' => $countStart,
        ];
    }
}

