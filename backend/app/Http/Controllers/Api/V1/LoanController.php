<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Item;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Setting;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Loan::with(['borrower.member.division', 'approver', 'loanItems.item']);

        // RBAC Filter: Anggota only sees own loans
        if ($user->role === 'anggota') {
            $query->where('borrower_id', $user->id);
        } elseif ($user->role === 'ketua_divisi') {
            // Can see loans from own division
            $divisionId = $user->member->division_id ?? null;
            if ($divisionId) {
                $query->whereHas('borrower.member', function ($q) use ($divisionId) {
                    $q->where('division_id', $divisionId);
                });
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('loan_no', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('borrower', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%")
                         ->orWhere('member_no', 'like', "%{$search}%");
                  });
            });
        }

        $loans = $query->orderBy('requested_at', 'desc')->paginate($request->input('per_page', 15));

        return response()->json($loans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'purpose' => 'required|string',
            'due_at' => 'required|date|after:now',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($request, $user) {
            // Verify stock availability
            foreach ($request->items as $reqItem) {
                $item = Item::lockForUpdate()->find($reqItem['item_id']);
                if ($item->qty_available < $reqItem['qty']) {
                    return response()->json([
                        'message' => "Stok barang '{$item->name}' tidak mencukupi (Tersedia: {$item->qty_available}, diminta: {$reqItem['qty']}).",
                    ], 422);
                }
            }

            $loanNo = 'PJ-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            $verificationCode = Str::random(32);

            $loan = Loan::create([
                'loan_no' => $loanNo,
                'borrower_id' => $user->id,
                'purpose' => $request->purpose,
                'status' => 'diajukan',
                'requested_at' => now(),
                'due_at' => $request->due_at,
                'verification_code' => $verificationCode,
            ]);

            foreach ($request->items as $reqItem) {
                LoanItem::create([
                    'loan_id' => $loan->id,
                    'item_id' => $reqItem['item_id'],
                    'qty_requested' => $reqItem['qty'],
                    'qty_approved' => 0,
                    'qty_returned' => 0,
                ]);
            }

            AuditLog::log('apply_loan', 'Loan', $loan->id, null, $loan->toArray());

            NotificationService::notifyAdmins(
                'loan_pending',
                'Permohonan Logistik Baru!',
                "Permohonan {$loan->loan_no} diajukan oleh {$user->name} ({$loan->purpose}).",
                '/loans',
                ['loan_id' => $loan->id, 'loan_no' => $loan->loan_no]
            );

            NotificationService::create(
                $user->id,
                'loan_diajukan',
                'Permohonan Peminjaman Diajukan',
                "Permohonan logistik {$loan->loan_no} ({$loan->purpose}) berhasil dikirim dan menunggu verifikasi Admin Logistik.",
                '/loans',
                ['loan_id' => $loan->id, 'loan_no' => $loan->loan_no, 'status' => 'diajukan']
            );

            return response()->json([
                'message' => 'Permohonan peminjaman berhasil diajukan.',
                'loan' => $loan->load(['borrower', 'loanItems.item']),
            ], 201);
        });
    }

    public function show($id)
    {
        $loan = Loan::with(['borrower.member.division', 'approver', 'loanItems.item'])->findOrFail($id);
        return response()->json($loan);
    }

    public function approve(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'items' => 'nullable|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty_approved' => 'required|integer|min:0',
        ]);

        return DB::transaction(function () use ($request, $id, $user) {
            $loan = Loan::with('loanItems.item')->lockForUpdate()->findOrFail($id);

            if ($loan->status !== 'diajukan') {
                return response()->json(['message' => 'Peminjaman sudah diproses sebelumnya.'], 422);
            }

            $approvedItemsMap = [];
            if ($request->has('items')) {
                foreach ($request->items as $ai) {
                    $approvedItemsMap[$ai['item_id']] = $ai['qty_approved'];
                }
            }

            foreach ($loan->loanItems as $loanItem) {
                $qtyToApprove = isset($approvedItemsMap[$loanItem->item_id])
                    ? $approvedItemsMap[$loanItem->item_id]
                    : $loanItem->qty_requested;

                $item = Item::lockForUpdate()->find($loanItem->item_id);

                if ($item->qty_available < $qtyToApprove) {
                    return response()->json([
                        'message' => "Stok '{$item->name}' tidak cukup untuk disetujui (Tersedia: {$item->qty_available}, disetujui: {$qtyToApprove}).",
                    ], 422);
                }

                // Reserve stock
                $item->qty_reserved += $qtyToApprove;
                $item->qty_available -= $qtyToApprove;
                $item->save();

                $loanItem->update(['qty_approved' => $qtyToApprove]);
            }

            $loan->update([
                'status' => 'disetujui',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            AuditLog::log('approve_loan', 'Loan', $loan->id, null, ['approved_by' => $user->id]);

            NotificationService::create(
                $loan->borrower_id,
                'loan_disetujui',
                'Peminjaman Disetujui!',
                "Permohonan logistik {$loan->loan_no} Anda telah disetujui. Silakan temui Admin Logistik untuk serah terima barang fisik.",
                '/loans',
                ['loan_id' => $loan->id, 'loan_no' => $loan->loan_no, 'status' => 'disetujui']
            );

            return response()->json([
                'message' => 'Peminjaman disetujui, stok telah dipesan (reserved).',
                'loan' => $loan->fresh(['borrower', 'approver', 'loanItems.item']),
            ]);
        });
    }

    public function reject(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'reject_reason' => 'required|string',
        ]);

        $loan = Loan::findOrFail($id);
        if ($loan->status !== 'diajukan') {
            return response()->json(['message' => 'Hanya permohonan berstatus diajukan yang dapat ditolak.'], 422);
        }

        $loan->update([
            'status' => 'ditolak',
            'reject_reason' => $request->reject_reason,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        AuditLog::log('reject_loan', 'Loan', $loan->id, null, ['reason' => $request->reject_reason]);

        NotificationService::create(
            $loan->borrower_id,
            'loan_ditolak',
            'Peminjaman Ditolak',
            "Permohonan logistik {$loan->loan_no} ditolak: " . ($request->reject_reason ?: 'Tidak memenuhi syarat.'),
            '/loans',
            ['loan_id' => $loan->id, 'loan_no' => $loan->loan_no, 'status' => 'ditolak']
        );

        return response()->json([
            'message' => 'Permohonan peminjaman telah ditolak.',
            'loan' => $loan->fresh(),
        ]);
    }

    public function cancel(Request $request, $id)
    {
        $user = $request->user();
        $loan = Loan::with('loanItems.item')->findOrFail($id);

        if ($loan->borrower_id !== $user->id && !in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        if (!in_array($loan->status, ['diajukan', 'disetujui'])) {
            return response()->json(['message' => 'Peminjaman yang sudah diserahkan tidak dapat dibatalkan.'], 422);
        }

        return DB::transaction(function () use ($loan) {
            // Release reserved stock if was approved
            if ($loan->status === 'disetujui') {
                foreach ($loan->loanItems as $loanItem) {
                    $item = Item::lockForUpdate()->find($loanItem->item_id);
                    $item->qty_reserved -= $loanItem->qty_approved;
                    $item->qty_available += $loanItem->qty_approved;
                    $item->save();
                }
            }

            $loan->update(['status' => 'dibatalkan']);

            AuditLog::log('cancel_loan', 'Loan', $loan->id);

            return response()->json(['message' => 'Peminjaman berhasil dibatalkan.']);
        });
    }

    public function handover(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'condition_notes' => 'nullable|string',
            'photo_proof' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $loan = Loan::with('loanItems.item')->lockForUpdate()->findOrFail($id);

            if ($loan->status !== 'disetujui') {
                return response()->json(['message' => 'Peminjaman harus disetujui terlebih dahulu sebelum serah terima.'], 422);
            }

            foreach ($loan->loanItems as $loanItem) {
                $item = Item::lockForUpdate()->find($loanItem->item_id);
                // Reserved released, total stock reduced while in circulation
                $item->qty_reserved -= $loanItem->qty_approved;
                $item->save();

                $loanItem->update([
                    'condition_out' => $request->condition_notes ?? 'Kondisi baik saat serah terima',
                    'photo_out' => $request->photo_proof,
                ]);
            }

            $loan->update([
                'status' => 'diserahkan',
                'handed_over_at' => now(),
            ]);

            AuditLog::log('handover_loan', 'Loan', $loan->id);

            NotificationService::create(
                $loan->borrower_id,
                'loan_diserahkan',
                'Barang Resmi Diserahkan',
                "Barang {$loan->loan_no} telah diserahkan kepada Anda. Harap dirawat & dikembalikan tepat waktu.",
                '/loans',
                ['loan_id' => $loan->id, 'loan_no' => $loan->loan_no, 'status' => 'diserahkan']
            );

            return response()->json([
                'message' => 'Barang telah berhasil diserahkan kepada peminjam.',
                'loan' => $loan->fresh(['borrower', 'loanItems.item']),
            ]);
        });
    }

    public function returnLoan(Request $request, $id)
    {
        $user = $request->user();
        $loan = Loan::with('loanItems.item')->findOrFail($id);

        if (!in_array($user->role, ['super_admin', 'admin']) && $loan->borrower_id !== $user->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'items' => 'nullable|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty_good' => 'required|integer|min:0',
            'items.*.qty_damaged' => 'required|integer|min:0',
            'items.*.condition_in' => 'nullable|string',
            'photo_proof' => 'nullable|string',
            'return_evidence_photo' => 'nullable|string',
            'notes' => 'nullable|string',
            'return_condition_notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $loan) {
            $loan = Loan::with('loanItems.item')->lockForUpdate()->findOrFail($loan->id);

            if (!in_array($loan->status, ['disetujui', 'diserahkan', 'terlambat', 'dikembalikan_sebagian'])) {
                return response()->json(['message' => 'Status peminjaman tidak valid untuk pengembalian.'], 422);
            }

            $photoProof = $request->photo_proof ?? $request->return_evidence_photo;
            $notes = $request->notes ?? $request->return_condition_notes ?? 'Kondisi barang baik & lengkap';

            $allReturned = true;
            $anyDamaged = false;

            if ($request->has('items') && is_array($request->items) && count($request->items) > 0) {
                foreach ($request->items as $retItem) {
                    $loanItem = $loan->loanItems->where('item_id', $retItem['item_id'])->first();
                    if (!$loanItem) continue;

                    $qtyGood = (int)($retItem['qty_good'] ?? 0);
                    $qtyDamaged = (int)($retItem['qty_damaged'] ?? 0);
                    $totalReturnedNow = $qtyGood + $qtyDamaged;

                    $item = Item::lockForUpdate()->find($loanItem->item_id);

                    if ($loan->status === 'disetujui') {
                        $item->qty_reserved = max(0, $item->qty_reserved - $qtyGood);
                    } else {
                        $item->qty_available += $qtyGood;
                    }

                    if ($qtyDamaged > 0) {
                        $item->qty_damaged += $qtyDamaged;
                        $anyDamaged = true;
                    }
                    $item->save();

                    $newReturnedTotal = $loanItem->qty_returned + $totalReturnedNow;
                    $loanItem->update([
                        'qty_returned' => $newReturnedTotal,
                        'condition_in' => $retItem['condition_in'] ?? $notes,
                        'photo_in' => $photoProof,
                    ]);

                    if ($newReturnedTotal < ($loanItem->qty_approved ?: $loanItem->qty_requested)) {
                        $allReturned = false;
                    }
                }
            } else {
                foreach ($loan->loanItems as $loanItem) {
                    $item = Item::lockForUpdate()->find($loanItem->item_id);
                    $qty = $loanItem->qty_approved ?: $loanItem->qty_requested;

                    if ($loan->status === 'disetujui') {
                        $item->qty_reserved = max(0, $item->qty_reserved - $qty);
                        $item->qty_available += $qty;
                    } else {
                        $item->qty_available += $qty;
                    }
                    $item->save();

                    $loanItem->update([
                        'qty_returned' => $qty,
                        'condition_in' => $notes,
                        'photo_in' => $photoProof,
                    ]);
                }
            }

            $finalStatus = 'dikembalikan';
            if (!$allReturned) {
                $finalStatus = 'dikembalikan_sebagian';
            } elseif ($anyDamaged) {
                $finalStatus = 'hilang_rusak';
            }

            $loan->update([
                'status' => $finalStatus,
                'returned_at' => now(),
                'photo_proof' => $photoProof,
                'return_notes' => $notes,
            ]);

            AuditLog::log('return_loan', 'Loan', $loan->id, null, ['status' => $finalStatus, 'notes' => $notes]);

            return response()->json([
                'message' => 'Pengembalian barang berhasil dicatat.',
                'loan' => $loan->fresh(['borrower', 'loanItems.item']),
            ]);
        });
    }

    public function generatePdf($id)
    {
        $loan = Loan::with(['borrower.member.division', 'approver', 'loanItems.item'])->findOrFail($id);

        $letterhead = Setting::get('letterhead_text', "PANITIA PERINGATAN HARI BESAR ISLAM ROJABIYYAH 2026\nSekretariat Utama");
        $appName = Setting::get('app_name', 'Administrasi Rojabiyyah 2026');
        $subTheme = Setting::get('sub_theme', "Haul & Peringatan Isro' Mi'roj 1447H / 2026M");

        $verifyUrl = url("/api/v1/public/loans/verify/" . ($loan->verification_code ?: $loan->loan_no));

        // Generate SVG/PNG QR Code safely
        $qrCode = '';
        try {
            $qrCode = base64_encode(QrCode::format('svg')->size(110)->generate($verifyUrl));
        } catch (\Throwable $e) {
            $qrCode = '';
        }

        $data = [
            'loan' => $loan,
            'letterhead' => $letterhead,
            'appName' => $appName,
            'subTheme' => $subTheme,
            'verifyUrl' => $verifyUrl,
            'qrCode' => $qrCode,
        ];

        $pdf = Pdf::loadView('pdf.loan_receipt', $data);

        return $pdf->stream('Surat_Peminjaman_' . $loan->loan_no . '.pdf');
    }

    public function verifyPublic($code)
    {
        $loan = Loan::with(['borrower.member.division', 'approver', 'loanItems.item.partner'])
            ->where('verification_code', $code)
            ->orWhere('loan_no', $code)
            ->firstOrFail();

        return response()->json([
            'valid' => true,
            'loan_no' => $loan->loan_no,
            'status' => strtoupper($loan->status),
            'borrower_name' => $loan->borrower->name ?? 'Anggota',
            'member_no' => $loan->borrower->member_no ?? '-',
            'division' => $loan->borrower->member->division->name ?? 'Panitia Lapangan',
            'purpose' => $loan->purpose,
            'requested_at' => $loan->requested_at ? $loan->requested_at->format('d M Y H:i') : '-',
            'due_at' => $loan->due_at ? $loan->due_at->format('d M Y H:i') : '-',
            'items' => $loan->loanItems->map(function ($li) {
                $item = $li->item;
                $isMitra = $item && $item->source === 'mitra';
                $ownerName = $isMitra ? ($item->partner->name ?? 'Lembaga Mitra') : 'Panitia Haul & Rojabiyyah (Milik Internal)';
                return [
                    'item_code' => $item->code ?? '-',
                    'item_name' => $item->name ?? 'Barang Logistik',
                    'source' => $item->source ?? 'internal',
                    'source_label' => $isMitra ? 'Barang Pinjaman Mitra' : 'Aset Internal Panitia',
                    'owner_name' => $ownerName,
                    'condition' => $item->condition ?? 'baik',
                    'location' => $item->storage_location ?? '-',
                    'qty_approved' => $li->qty_approved,
                    'qty_returned' => $li->qty_returned,
                ];
            }),
        ]);
    }
}

