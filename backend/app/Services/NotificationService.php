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
}
