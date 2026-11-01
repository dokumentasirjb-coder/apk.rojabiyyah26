<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $query = CashTransaction::with(['category', 'creator']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        $transactions = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate($request->input('per_page', 20));

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'bendahara'])) {
            return response()->json(['message' => 'Hanya Super Admin dan Bendahara yang berhak mencatat kas.'], 403);
        }

        $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'category_id' => 'required|exists:cash_categories,id',
            'description' => 'required|string',
            'receipt_path' => 'nullable|string',
        ]);

        $trx = CashTransaction::create([
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'receipt_path' => $request->receipt_path,
            'created_by' => $user->id,
            'locked' => false,
        ]);

        AuditLog::log('create', 'CashTransaction', $trx->id, null, $trx->toArray());

        return response()->json([
            'message' => 'Transaksi kas berhasil dicatat.',
            'transaction' => $trx->load(['category', 'creator']),
        ], 201);
    }

    public function voidTransaction(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['super_admin', 'bendahara'])) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'void_reason' => 'required|string',
        ]);

        $trx = CashTransaction::findOrFail($id);

        if ($trx->locked) {
            return response()->json(['message' => 'Transaksi kas pada periode ini telah dikunci dan tidak dapat dibatalkan.'], 422);
        }

        if ($trx->voided_at) {
            return response()->json(['message' => 'Transaksi kas sudah dibatalkan sebelumnya.'], 422);
        }

        $old = $trx->toArray();
        $trx->update([
            'voided_at' => now(),
            'void_reason' => $request->void_reason,
        ]);

        AuditLog::log('void', 'CashTransaction', $trx->id, $old, ['reason' => $request->void_reason]);

        return response()->json([
            'message' => 'Transaksi kas berhasil dibatalkan (void). Saldo telah disesuaikan.',
            'transaction' => $trx,
        ]);
    }

    public function summary()
    {
        $totalIn = CashTransaction::active()->where('type', 'in')->sum('amount');
        $totalOut = CashTransaction::active()->where('type', 'out')->sum('amount');
        $balance = $totalIn - $totalOut;

        // Monthly stats for chart
        $driver = DB::connection()->getDriverName();
        $dateExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', date) as month"
            : "DATE_FORMAT(date, '%Y-%m') as month";

        $monthly = CashTransaction::active()
            ->selectRaw("{$dateExpr}, type, sum(amount) as total")
            ->groupBy('month', 'type')
            ->orderBy('month', 'asc')
            ->get();

        return response()->json([
            'total_in' => (float)$totalIn,
            'total_out' => (float)$totalOut,
            'balance' => (float)$balance,
            'monthly' => $monthly,
        ]);
    }

    public function categories()
    {
        return response()->json(CashCategory::withCount('transactions')->get());
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:cash_categories,name',
            'type' => 'required|in:in,out,both',
        ]);

        $cat = CashCategory::create($request->all());
        return response()->json($cat, 201);
    }

    public function export(Request $request)
    {
        $query = CashTransaction::with(['category', 'creator']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        $transactions = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        $headers = ['NO', 'ID TRX', 'TANGGAL', 'JENIS KAS', 'KATEGORI', 'URAIAN / KETERANGAN', 'MASUK (RP)', 'KELUAR (RP)', 'STATUS', 'DICATAT OLEH'];

        $rows = [];
        $no = 1;
        foreach ($transactions as $trx) {
            $dateStr = $trx->date instanceof \DateTimeInterface ? $trx->date->format('Y-m-d') : (string)$trx->date;
            $rows[] = [
                $no++,
                'TRX-' . str_pad($trx->id, 5, '0', STR_PAD_LEFT),
                $dateStr,
                strtoupper($trx->type === 'in' ? 'KAS MASUK' : 'KAS KELUAR'),
                $trx->category->name ?? '-',
                $trx->description,
                $trx->type === 'in' ? (float)$trx->amount : 0,
                $trx->type === 'out' ? (float)$trx->amount : 0,
                $trx->voided_at ? 'BATAL (VOID)' : 'VALID',
                $trx->creator->name ?? '-',
            ];
        }

        return ExcelExportService::streamStyled(
            filename: 'Rekap_Kas_Rojabiyyah_' . date('Ymd_His') . '.xlsx',
            sheetTitle: 'Rekap Kas Keuangan',
            mainTitle: 'BUKU KAS & LAPORAN KEUANGAN ROJABIYYAH 2026',
            subtitle: 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026',
            headers: $headers,
            rows: $rows,
            options: [
                'header_bg' => '065F46', // Emerald-800
                'alignments' => [
                    1 => 'center',
                    2 => 'center',
                    3 => 'center',
                    4 => 'center',
                    7 => 'right',
                    8 => 'right',
                    9 => 'center',
                ],
                'formats' => [
                    7 => '#,##0',
                    8 => '#,##0',
                ],
                'text_columns' => [2],
            ]
        );
    }

    public function template()
    {
        $headers = ['TANGGAL', 'JENIS', 'KATEGORI', 'JUMLAH', 'URAIAN'];
        $rows = [
            [date('Y-m-d'), 'in', 'Infaq & Sedekah', 5000000, 'Infaq dari hamba Allah untuk konsumsi'],
            [date('Y-m-d'), 'out', 'Logistik & Operasional', 750000, 'Pembelian perlengkapan sound system'],
            [date('Y-m-d'), 'in', 'Sponsorship', 10000000, 'Dana sponsorship pendukung haul'],
        ];

        return ExcelExportService::streamStyled(
            filename: 'Template_Impor_Kas_Rojabiyyah.xlsx',
            sheetTitle: 'Template Kas',
            mainTitle: 'FORMAT TEMPLATE IMPOR TRANSAKSI KAS',
            subtitle: 'Isi transaksi kas sesuai kolom di bawah ini lalu unggah melalui menu Impor Kas',
            headers: $headers,
            rows: $rows,
            options: [
                'header_bg' => '065F46', // Emerald-800
                'alignments' => [
                    1 => 'center',
                    2 => 'center',
                    4 => 'right',
                ],
                'formats' => [
                    4 => '#,##0',
                ],
            ]
        );
    }

    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:5120',
        ]);

        $file = $request->file('file');
        $rows = $this->parseUploadedSpreadsheet($file);

        $validRows = [];
        $invalidRows = [];

        foreach ($rows as $index => $row) {
            $date = trim($row['tanggal'] ?? $row['date'] ?? date('Y-m-d'));
            $type = strtolower(trim($row['jenis'] ?? $row['type'] ?? 'in'));
            if (str_contains($type, 'keluar') || $type === 'out') {
                $type = 'out';
            } else {
                $type = 'in';
            }
            $category = trim($row['kategori'] ?? $row['category'] ?? 'Umum');
            $rawAmount = preg_replace('/[^0-9.]/', '', str_replace(',', '.', (string)($row['jumlah'] ?? $row['amount'] ?? 0)));
            $amount = (float)$rawAmount;
            $desc = trim($row['uraian'] ?? $row['keterangan'] ?? $row['description'] ?? '');

            if ($amount <= 0 || empty($desc)) {
                $invalidRows[] = [
                    'row' => $index + 2,
                    'data' => $row,
                    'error' => $amount <= 0 ? 'Jumlah nominal harus lebih dari 0.' : 'Uraian keterangan wajib diisi.',
                ];
                continue;
            }

            $validRows[] = [
                'row' => $index + 2,
                'date' => $date,
                'type' => $type,
                'category' => $category,
                'amount' => $amount,
                'description' => $desc,
            ];
        }

        return response()->json([
            'total_rows' => count($rows),
            'valid_count' => count($validRows),
            'invalid_count' => count($invalidRows),
            'valid_rows' => $validRows,
            'invalid_rows' => $invalidRows,
            'count' => count($validRows),
            'preview' => $validRows,
        ]);
    }

    public function importExecute(Request $request)
    {
        $request->validate([
            'rows' => 'required|array|min:1',
        ]);

        $rows = $request->input('rows');
        $userId = auth()->id() ?? 1;

        return DB::transaction(function () use ($rows, $userId) {
            $successCount = 0;
            $failedCount = 0;

            // Cache cash categories
            $categories = CashCategory::all()->keyBy(function ($c) {
                return strtolower(trim($c->name));
            });

            foreach ($rows as $item) {
                $amount = (float)($item['amount'] ?? 0);
                $desc = trim($item['description'] ?? '');
                $type = in_array($item['type'] ?? '', ['in', 'out']) ? $item['type'] : 'in';

                if ($amount <= 0 || empty($desc)) {
                    $failedCount++;
                    continue;
                }

                // Category lookup or creation
                $catName = trim($item['category'] ?? 'Umum');
                $catKey = strtolower($catName);
                if ($categories->has($catKey)) {
                    $categoryId = $categories->get($catKey)->id;
                } else {
                    $newCat = CashCategory::create(['name' => $catName, 'type' => 'both']);
                    $categories->put($catKey, $newCat);
                    $categoryId = $newCat->id;
                }

                CashTransaction::create([
                    'type' => $type,
                    'amount' => $amount,
                    'date' => $item['date'] ?? now()->toDateString(),
                    'category_id' => $categoryId,
                    'description' => $desc,
                    'created_by' => $userId,
                    'locked' => false,
                ]);

                $successCount++;
            }

            AuditLog::log('import', 'CashTransaction', null, null, [
                'success' => $successCount,
                'failed' => $failedCount,
            ]);

            return response()->json([
                'message' => "Berhasil mengimpor {$successCount} transaksi kas.",
                'success_count' => $successCount,
                'failed_count' => $failedCount,
            ]);
        });
    }

    private function parseUploadedSpreadsheet($file): array
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = [];

        if (in_array($extension, ['csv', 'txt'])) {
            if (($handle = fopen($path, 'r')) !== false) {
                $firstLine = fgets($handle);
                rewind($handle);
                $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';

                $header = null;
                while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
                    if (!$header) {
                        if (isset($data[0])) {
                            $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);
                        }
                        $header = array_map(function ($h) {
                            return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', (string)$h)));
                        }, $data);
                    } else {
                        if (empty(array_filter($data, fn($v) => trim((string)$v) !== ''))) continue;
                        $row = [];
                        foreach ($header as $i => $colName) {
                            $val = trim((string)($data[$i] ?? ''));
                            if (str_starts_with($val, "'")) $val = substr($val, 1);
                            $row[$colName] = $val;
                        }
                        $rows[] = $row;
                    }
                }
                fclose($handle);
            }
        } else {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
                $worksheet = $spreadsheet->getActiveSheet();
                $sheetData = $worksheet->toArray();
                if (!empty($sheetData)) {
                    $headerRow = array_shift($sheetData);
                    if (isset($headerRow[0])) {
                        $headerRow[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string)$headerRow[0]);
                    }
                    $header = array_map(function ($h) {
                        return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '_', (string)$h)));
                    }, $headerRow);

                    foreach ($sheetData as $data) {
                        if (empty(array_filter($data, fn($v) => trim((string)$v) !== ''))) continue;
                        $row = [];
                        foreach ($header as $i => $colName) {
                            $val = trim((string)($data[$i] ?? ''));
                            if (str_starts_with($val, "'")) $val = substr($val, 1);
                            $row[$colName] = $val;
                        }
                        $rows[] = $row;
                    }
                }
            } catch (\Exception $e) {
                // Fallback empty
            }
        }

        return $rows;
    }
}

