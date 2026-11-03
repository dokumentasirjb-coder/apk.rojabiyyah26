<?php

namespace App\Services;

use App\Models\User;
use App\Models\Loan;
use App\Models\Assignment;
use App\Models\FieldReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Create a notification record for a specific user.
     */
    public static function create($userId, $type, $title, $message, $url = null, $extra = [])
    {
        $id = (string) Str::uuid();
        $data = array_merge([
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'created_at' => now()->toIso8601String(),
        ], $extra);

        DB::table('notifications')->insert([
            'id' => $id,
            'user_id' => $userId,
            'type' => $type,
            'data' => json_encode($data),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $id;
    }

    /**
     * Send notification to all super admins and operational admins.
     */
    public static function notifyAdmins($type, $title, $message, $url = null, $extra = [])
    {
        $admins = User::whereIn('role', ['super_admin', 'admin'])->get();
        foreach ($admins as $adm) {
            self::create($adm->id, $type, $title, $message, $url, $extra);
        }
    }

    /**
     * Auto-sync any missing notifications for a user based on real database records.
     * Guarantees notification bell always reflects the user's real duties and logistical activities.
     */
    public static function syncForUser(User $user)
    {
        // 0. Auto-check & send H-10m and H-0 reminders in background
        AssignmentReminderService::checkAndSendReminders();

        // 1. Sync active posko assignments
        $assignments = Assignment::with('region')->where('user_id', $user->id)->get();
        foreach ($assignments as $asg) {
            $regName = $asg->region->name ?? 'Wilayah Lapangan';
            $existing = DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('type', 'assignment_priority')
                ->where(function ($query) use ($asg) {
                    $query->whereJsonContains('data->assignment_id', $asg->id)
                          ->orWhere('data', 'like', '%"assignment_id":' . $asg->id . '%')
                          ->orWhere('data', 'like', '%"assignment_id": ' . $asg->id . '%');
                })
                ->exists();

            if (!$existing) {
                self::create(
                    $user->id,
                    'assignment_priority',
                    'Prioritas Tugas: Penugasan Posko!',
                    "Anda ditugaskan di {$asg->role_in_post} ({$regName}). Harap standby dan koordinasi dengan posko.",
                    '/regions',
                    [
                        'assignment_id' => $asg->id,
                        'region_id' => $asg->region_id,
                        'region_name' => $regName,
                        'role_in_post' => $asg->role_in_post,
                    ]
                );
            }
        }

        // 1b. Sync Proyektor Assignments (Titik A-F)
        $proyektorRaw = \App\Models\Setting::get('proyektor_teams');
        if ($proyektorRaw) {
            $teams = json_decode($proyektorRaw, true);
            if (is_array($teams)) {
                foreach ($teams as $team) {
                    $titik = $team['titik'] ?? 'Titik Proyektor';

                    // Check Korwil
                    if (!empty($team['korwil']) && self::isUserMentioned($team['korwil'], $user)) {
                        $taskKey = 'proyektor_' . md5($titik . '_korwil');
                        self::createTaskNotifIfNotExists(
                            $user->id,
                            'duty_proyektor',
                            "🎯 Tugas Korwil Proyektor: {$titik}",
                            "Anda ditugaskan sebagai Koordinator Wilayah (Korwil) {$titik}. Mohon pimpin koordinasi teknis dan pengecekan display.",
                            '/pembagian-proyektor',
                            ['task_key' => $taskKey, 'titik' => $titik, 'role' => 'Korwil']
                        );
                    }

                    // Check Kabel
                    if (!empty($team['kabel']) && self::isUserMentioned($team['kabel'], $user)) {
                        $taskKey = 'proyektor_' . md5($titik . '_kabel');
                        self::createTaskNotifIfNotExists(
                            $user->id,
                            'duty_proyektor',
                            "🔌 Tugas Tim Kabel: {$titik}",
                            "Anda ditugaskan di Tim Jalur Kabel & FO di {$titik}. Harap standby dan koordinasi dengan Korwil: " . ($team['korwil'] ?? 'Korwil'),
                            '/pembagian-proyektor',
                            ['task_key' => $taskKey, 'titik' => $titik, 'role' => 'Kabel']
                        );
                    }

                    // Check Perangkat Items
                    if (!empty($team['items']) && is_array($team['items'])) {
                        foreach ($team['items'] as $item) {
                            if (!empty($item['petugas']) && self::isUserMentioned($item['petugas'], $user)) {
                                $kode = $item['kode'] ?? 'Perangkat Display';
                                $lokasi = $item['wilayah'] ?? 'Area Acara';
                                $taskKey = 'proyektor_' . md5($titik . '_' . $kode);
                                self::createTaskNotifIfNotExists(
                                    $user->id,
                                    'duty_proyektor',
                                    "📺 Tugas Petugas Layar: {$kode} ({$titik})",
                                    "Anda bertugas mengawal {$kode} di {$lokasi} ({$titik}). Harap siap di lokasi 30 menit sebelum acara.",
                                    '/pembagian-proyektor',
                                    ['task_key' => $taskKey, 'titik' => $titik, 'kode' => $kode, 'wilayah' => $lokasi]
                                );
                            }
                        }
                    }
                }
            }
        }

        // 1c. Sync Jadwal Kerja Tim Liputan (Fotografer & Videografer)
        $jadwalTimRaw = \App\Models\Setting::get('jadwal_tim');
        if ($jadwalTimRaw) {
            $jadwalList = json_decode($jadwalTimRaw, true);
            if (is_array($jadwalList)) {
                foreach ($jadwalList as $row) {
                    $hari = $row['hari'] ?? 'Hari Acara';
                    $waktu = $row['waktu'] ?? 'Waktu Acara';
                    $momen = $row['momen'] ?? 'Kegiatan';
                    $lokasi = $row['wilayah'] ?? 'Lokasi';

                    // Fotografer
                    if (!empty($row['fotografer']) && self::isUserMentioned($row['fotografer'], $user)) {
                        $taskKey = 'liputan_' . md5($hari . '_' . $waktu . '_' . $momen . '_foto');
                        self::createTaskNotifIfNotExists(
                            $user->id,
                            'duty_liputan',
                            "📸 Tugas Liputan: Fotografer ({$hari} {$waktu})",
                            "Tugas Fotografer pada momen {$momen} di {$lokasi} (Hari {$hari}, sesi {$waktu}).",
                            '/jadwal-kerja',
                            ['task_key' => $taskKey, 'hari' => $hari, 'waktu' => $waktu, 'momen' => $momen, 'role' => 'Fotografer']
                        );
                    }

                    // Videografer
                    if (!empty($row['videografer']) && self::isUserMentioned($row['videografer'], $user)) {
                        $taskKey = 'liputan_' . md5($hari . '_' . $waktu . '_' . $momen . '_video');
                        self::createTaskNotifIfNotExists(
                            $user->id,
                            'duty_liputan',
                            "🎥 Tugas Liputan: Videografer ({$hari} {$waktu})",
                            "Tugas Videografer pada momen {$momen} di {$lokasi} (Hari {$hari}, sesi {$waktu}).",
                            '/jadwal-kerja',
                            ['task_key' => $taskKey, 'hari' => $hari, 'waktu' => $waktu, 'momen' => $momen, 'role' => 'Videografer']
                        );
                    }
                }
            }
        }

        // 1d. Sync Jadwal Tim Shooting Multicam (Kameramen)
        $jadwalShootingRaw = \App\Models\Setting::get('jadwal_shooting');
        if ($jadwalShootingRaw) {
            $shootingList = json_decode($jadwalShootingRaw, true);
            if (is_array($shootingList)) {
                foreach ($shootingList as $sRow) {
                    $hari = $sRow['hari'] ?? 'Hari Acara';
                    $waktu = $sRow['waktu'] ?? 'Waktu Acara';
                    $momen = $sRow['momen'] ?? 'Acara';
                    $posisi = $sRow['shooting'] ?? 'Kamera';

                    if (!empty($sRow['kameramen']) && self::isUserMentioned($sRow['kameramen'], $user)) {
                        $taskKey = 'shooting_' . md5($hari . '_' . $waktu . '_' . $posisi . '_kamera');
                        self::createTaskNotifIfNotExists(
                            $user->id,
                            'duty_shooting',
                            "🎬 Tugas Multicam: Kameramen ({$hari} {$waktu})",
                            "Tugas Kameramen Shooting posisi {$posisi} pada momen {$momen} (Hari {$hari}, sesi {$waktu}).",
                            '/jadwal-kerja',
                            ['task_key' => $taskKey, 'hari' => $hari, 'waktu' => $waktu, 'momen' => $momen, 'posisi' => $posisi]
                        );
                    }
                }
            }
        }

        // 2. Sync loans for this user
        if ($user->role === 'anggota') {
            $loans = Loan::where('borrower_id', $user->id)->orderBy('created_at', 'desc')->limit(10)->get();
            foreach ($loans as $loan) {
                $existing = DB::table('notifications')
                    ->where('user_id', $user->id)
                    ->where(function ($query) use ($loan) {
                        $query->whereJsonContains('data->loan_id', $loan->id)
                              ->orWhere('data', 'like', '%"loan_id":' . $loan->id . '%')
                              ->orWhere('data', 'like', '%"loan_id": ' . $loan->id . '%');
                    })
                    ->where(function ($query) use ($loan) {
                        $query->whereJsonContains('data->status', $loan->status)
                              ->orWhere('data', 'like', '%"status":"' . $loan->status . '"%')
                              ->orWhere('data', 'like', '%"status": "' . $loan->status . '"%');
                    })
                    ->exists();

                if (!$existing) {
                    $statusTitles = [
                        'diajukan' => ['Permohonan Peminjaman Diajukan', "Permohonan logistik {$loan->loan_no} ({$loan->purpose}) sedang menunggu verifikasi Admin Logistik."],
                        'disetujui' => ['Peminjaman Disetujui!', "Permohonan {$loan->loan_no} telah disetujui. Silakan temui Admin Logistik untuk serah terima barang."],
                        'diserahkan' => ['Barang Sedang Digunakan', "Logistik {$loan->loan_no} telah resmi Anda terima. Batas waktu pengembalian: " . date('d M Y', strtotime($loan->due_at)) . "."],
                        'ditolak' => ['Peminjaman Ditolak', "Permohonan {$loan->loan_no} ditolak: " . ($loan->reject_reason ?: 'Tidak memenuhi syarat.')],
                        'dikembalikan' => ['Logistik Selesai Dikembalikan', "Barang {$loan->loan_no} telah selesai dikembalikan ke gudang inventaris."],
                    ];

                    $info = $statusTitles[$loan->status] ?? ['Status Peminjaman', "Status terkini {$loan->loan_no}: {$loan->status}"];
                    self::create(
                        $user->id,
                        'loan_' . $loan->status,
                        $info[0],
                        $info[1],
                        '/loans',
                        [
                            'loan_id' => $loan->id,
                            'loan_no' => $loan->loan_no,
                            'status' => $loan->status,
                        ]
                    );
                }
            }
        } else {
            // For admins: check pending loans needing attention
            $pendingLoans = Loan::with('borrower')->where('status', 'diajukan')->orderBy('created_at', 'desc')->limit(5)->get();
            foreach ($pendingLoans as $pl) {
                $borrowerName = $pl->borrower->name ?? 'Anggota';
                $existing = DB::table('notifications')
                    ->where('user_id', $user->id)
                    ->where('type', 'loan_pending')
                    ->where(function ($query) use ($pl) {
                        $query->whereJsonContains('data->loan_id', $pl->id)
                              ->orWhere('data', 'like', '%"loan_id":' . $pl->id . '%')
                              ->orWhere('data', 'like', '%"loan_id": ' . $pl->id . '%');
                    })
                    ->exists();

                if (!$existing) {
                    self::create(
                        $user->id,
                        'loan_pending',
                        'Antrean Peminjaman Baru',
                        "Permohonan logistik {$pl->loan_no} oleh {$borrowerName} ({$pl->purpose}) membutuhkan persetujuan Anda.",
                        '/loans',
                        [
                            'loan_id' => $pl->id,
                            'loan_no' => $pl->loan_no,
                        ]
                    );
                }
            }

            // For admins: check reports needing attention
            $urgentReports = FieldReport::with('region')->whereIn('status', ['rusak_kendala', 'perlu_perhatian'])->orderBy('created_at', 'desc')->limit(5)->get();
            foreach ($urgentReports as $rep) {
                $regName = $rep->region->name ?? 'Posko Lapangan';
                $existing = DB::table('notifications')
                    ->where('user_id', $user->id)
                    ->where('type', 'report_urgent')
                    ->where(function ($query) use ($rep) {
                        $query->whereJsonContains('data->report_id', $rep->id)
                              ->orWhere('data', 'like', '%"report_id":' . $rep->id . '%')
                              ->orWhere('data', 'like', '%"report_id": ' . $rep->id . '%');
                    })
                    ->exists();

                if (!$existing) {
                    self::create(
                        $user->id,
                        'report_urgent',
                        'Laporan Posko Membutuhkan Tindakan',
                        "Kendala dilaporkan di {$regName}: " . Str::limit($rep->description, 60),
                        '/reports',
                        [
                            'report_id' => $rep->id,
                            'region_id' => $rep->region_id,
                        ]
                    );
                }
            }
        }
    }

    /**
     * Check if a field string mentions the user by name or member_no.
     */
    private static function isUserMentioned(?string $fieldValue, User $user): bool
    {
        if (empty($fieldValue)) return false;
        $val = strtolower(trim($fieldValue));
        $userName = strtolower(trim($user->name));
        $memberNo = strtolower(trim($user->member_no ?? ''));

        if ($val === $userName || str_contains($val, $userName)) {
            return true;
        }

        if (!empty($memberNo) && str_contains($val, $memberNo)) {
            return true;
        }

        // If field is a short name (e.g. "Alfani") that matches user name "Alfani Ramadhani"
        if (strlen($val) >= 4 && str_contains($userName, $val)) {
            return true;
        }

        return false;
    }

    /**
     * Helper to create task notification if not already existing.
     */
    private static function createTaskNotifIfNotExists($userId, $type, $title, $message, $url, $extra)
    {
        $taskKey = $extra['task_key'] ?? '';
        $existing = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('type', $type)
            ->where(function ($query) use ($taskKey) {
                $query->whereJsonContains('data->task_key', $taskKey)
                      ->orWhere('data', 'like', '%"task_key":"' . $taskKey . '"%')
                      ->orWhere('data', 'like', '%"task_key": "' . $taskKey . '"%');
            })
            ->exists();

        if (!$existing) {
            self::create($userId, $type, $title, $message, $url, $extra);
        }
    }
}
