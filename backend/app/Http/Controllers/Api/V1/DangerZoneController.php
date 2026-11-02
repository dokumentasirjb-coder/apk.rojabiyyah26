<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AuditLog;
use App\Models\CashTransaction;
use App\Models\FieldReport;
use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Member;
use App\Models\Partner;
use App\Models\ReportFollowup;
use App\Models\ReportMedia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DangerZoneController extends Controller
{
    public function preview(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'super_admin') {
            return response()->json(['message' => 'Hanya Super Admin yang berhak mengakses Danger Zone.'], 403);
        }

        return response()->json([
            'loans_count' => Loan::count(),
            'loan_items_count' => LoanItem::count(),
            'reports_count' => FieldReport::count(),
            'report_followups_count' => ReportFollowup::count(),
            'assignments_count' => Assignment::count(),
            'cash_transactions_count' => CashTransaction::count(),
            'audit_logs_count' => AuditLog::count(),
            'members_count' => User::where('role', 'anggota')->count(),
            'items_count' => Item::count(),
            'partners_count' => Partner::count(),
        ]);
    }

    public function execute(Request $request)
    {
        $user = $request->user();
        if ($user->role !== 'super_admin') {
            return response()->json(['message' => 'Hanya Super Admin yang berhak menjalankan reset.'], 403);
        }

        $request->validate([
            'password' => 'required|string',
            'confirmation_phrase' => 'required|string',
            'modules' => 'required|array|min:1',
        ]);

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Kata sandi verifikasi salah / tidak sesuai.'], 422);
        }

        $phrase = trim(strtoupper($request->confirmation_phrase));
        if ($phrase !== 'RESET OPERASIONAL' && $phrase !== 'RESET') {
            return response()->json(['message' => 'Ketik frasa konfirmasi: RESET OPERASIONAL atau RESET'], 422);
        }

        // 1. Create automatic DB snapshot / backup
        $backupDir = storage_path('app/backups');
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backupFile = $backupDir . '/backup_snapshot_' . date('Ymd_His') . '.json';
        $snapshot = [
            'timestamp' => now()->toIso8601String(),
            'loans' => Loan::with('loanItems')->get()->toArray(),
            'reports' => FieldReport::with(['media', 'followups'])->get()->toArray(),
            'cash' => CashTransaction::all()->toArray(),
            'assignments' => Assignment::all()->toArray(),
            'members' => User::where('role', 'anggota')->with('member')->get()->toArray(),
            'items' => Item::all()->toArray(),
            'partners' => Partner::all()->toArray(),
        ];
        File::put($backupFile, json_encode($snapshot, JSON_PRETTY_PRINT));

        // 2. Execute module wipe
        $modules = $request->modules;
        $deletedStats = [];

        DB::transaction(function () use ($modules, &$deletedStats) {
            // 1. Sirkulasi Peminjaman
            if (in_array('loans', $modules)) {
                $deletedStats['loans'] = Loan::count();
                LoanItem::query()->delete();
                Loan::query()->delete();

                // Reset reserved stock in inventory
                Item::query()->update(['qty_reserved' => 0]);
                foreach (Item::all() as $item) {
                    $item->recalculateAvailableStock();
                }
            }

            // 2. Laporan Posko & Kendala
            if (in_array('reports', $modules)) {
                $deletedStats['reports'] = FieldReport::count();
                ReportFollowup::query()->delete();
                ReportMedia::query()->delete();
                FieldReport::query()->delete();
            }

            // 3. Transaksi Kas Keuangan
            if (in_array('cash', $modules)) {
                $deletedStats['cash'] = CashTransaction::count();
                CashTransaction::query()->delete();
            }

            // 4. Penugasan Personil Posko
            if (in_array('assignments', $modules)) {
                $deletedStats['assignments'] = Assignment::count();
                Assignment::query()->delete();
            }

            // 5. Data Anggota Relawan (Non-Admin)
            if (in_array('members', $modules)) {
                $anggotaUsers = User::where('role', 'anggota')->get();
                $deletedStats['members'] = $anggotaUsers->count();
                $anggotaIds = $anggotaUsers->pluck('id');

                if ($anggotaIds->isNotEmpty()) {
                    // Clean assignments for anggota
                    Assignment::whereIn('user_id', $anggotaIds)->delete();

                    // Clean field reports for anggota
                    FieldReport::whereIn('reporter_id', $anggotaIds)->delete();

                    // Clean loans and loan_items requested by anggota
                    $loanIds = Loan::whereIn('borrower_id', $anggotaIds)->pluck('id');
                    if ($loanIds->isNotEmpty()) {
                        LoanItem::whereIn('loan_id', $loanIds)->delete();
                        Loan::whereIn('id', $loanIds)->delete();
                    }

                    // Delete member profiles
                    Member::whereIn('user_id', $anggotaIds)->delete();

                    // Delete user accounts of anggota
                    User::whereIn('id', $anggotaIds)->delete();

                    // Recalculate reserved stock for inventory
                    foreach (Item::all() as $item) {
                        $reserved = LoanItem::where('item_id', $item->id)
                            ->whereHas('loan', function ($q) {
                                $q->whereIn('status', ['diajukan', 'disetujui', 'diserahkan']);
                            })
                            ->sum('qty_requested');
                        $item->qty_reserved = $reserved;
                        $item->recalculateAvailableStock();
                    }
                }
            }

            // 6. Data Barang & Inventaris (Katalog Logistik)
            if (in_array('inventory', $modules)) {
                $deletedStats['items'] = Item::count();
                // Delete loan items and loans first to prevent FK violation
                LoanItem::query()->delete();
                Loan::query()->delete();
                Item::withTrashed()->forceDelete();
            }

            // 7. Data Lembaga Mitra
            if (in_array('partners', $modules)) {
                $deletedStats['partners'] = Partner::count();
                Item::query()->update(['partner_id' => null]);
                Partner::query()->delete();
            }
        });

        AuditLog::log('danger_zone_reset', 'System', null, null, [
            'modules' => $modules,
            'stats' => $deletedStats,
            'backup' => basename($backupFile),
        ]);

        return response()->json([
            'message' => 'Pembersihan data operasional selesai dengan aman. Backup snapshot telah dibuat otomatis.',
            'backup_file' => basename($backupFile),
            'deleted_stats' => $deletedStats,
        ]);
    }
}


