<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\InventoryCategory;
use App\Models\Item;
use App\Models\Partner;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with(['category', 'partner']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $items = $query->orderBy('name', 'asc')->paginate($request->input('per_page', 20));

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:items,code',
            'source' => 'required|in:internal,mitra',
            'partner_id' => 'nullable|exists:partners,id',
            'category_id' => 'nullable|exists:inventory_categories,id',
            'qty_total' => 'required|integer|min:0',
            'qty_damaged' => 'nullable|integer|min:0',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'location' => 'nullable|string|max:255',
            'photo_path' => 'nullable|string',
        ]);

        $code = $request->code ?: $this->generateNextItemCode();
        $qtyDamaged = $request->qty_damaged ?? 0;
        $qtyAvailable = max(0, $request->qty_total - $qtyDamaged);

        $item = Item::create([
            'code' => $code,
            'name' => $request->name,
            'source' => $request->source,
            'partner_id' => $request->partner_id,
            'category_id' => $request->category_id,
            'qty_total' => $request->qty_total,
            'qty_available' => $qtyAvailable,
            'qty_reserved' => 0,
            'qty_damaged' => $qtyDamaged,
            'condition' => $request->condition ?? 'baik',
            'location' => $request->location,
            'photo_path' => $request->photo_path,
        ]);

        AuditLog::log('create', 'Item', $item->id, null, $item->toArray());

        return response()->json([
            'message' => 'Barang inventaris berhasil ditambahkan.',
            'item' => $item->load(['category', 'partner']),
        ], 201);
    }

    public function show($id)
    {
        $item = Item::with(['category', 'partner'])->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'source' => 'required|in:internal,mitra',
            'partner_id' => 'nullable|exists:partners,id',
            'category_id' => 'nullable|exists:inventory_categories,id',
            'qty_total' => 'required|integer|min:' . $item->qty_reserved,
            'qty_damaged' => 'nullable|integer|min:0',
            'condition' => 'nullable|in:baik,rusak_ringan,rusak_berat',
            'location' => 'nullable|string|max:255',
            'photo_path' => 'nullable|string',
        ]);

        $oldValues = $item->toArray();

        $qtyDamaged = $request->qty_damaged ?? $item->qty_damaged;
        $qtyAvailable = max(0, $request->qty_total - $item->qty_reserved - $qtyDamaged);

        $item->update([
            'name' => $request->name,
            'code' => $request->code,
            'source' => $request->source,
            'partner_id' => $request->partner_id,
            'category_id' => $request->category_id,
            'qty_total' => $request->qty_total,
            'qty_available' => $qtyAvailable,
            'qty_damaged' => $qtyDamaged,
            'condition' => $request->condition ?? $item->condition,
            'location' => $request->location,
            'photo_path' => $request->has('photo_path') ? $request->photo_path : $item->photo_path,
        ]);

        AuditLog::log('update', 'Item', $item->id, $oldValues, $item->toArray());

        return response()->json([
            'message' => 'Barang inventaris berhasil diperbarui.',
            'item' => $item->load(['category', 'partner']),
        ]);
    }

    public function destroy($id)
    {
        $item = Item::withCount(['loanItems as active_loans_count' => function ($q) {
            $q->whereHas('loan', function ($lq) {
                $lq->whereIn('status', ['diajukan', 'disetujui', 'diserahkan']);
            });
        }])->findOrFail($id);

        if ($item->active_loans_count > 0) {
            return response()->json([
                'message' => 'Barang sedang dipinjam atau dipesan, tidak dapat dihapus.',
            ], 422);
        }

        $oldValues = $item->toArray();
        $item->delete(); // Soft delete

        AuditLog::log('delete', 'Item', $id, $oldValues, null);

        return response()->json([
            'message' => 'Barang berhasil dihapus.',
        ]);
    }

    public function export(Request $request)
    {
        $query = Item::with(['category', 'partner']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        $items = $query->orderBy('name', 'asc')->get();

        $headers = ['NO', 'KODE', 'NAMA BARANG', 'KATEGORI', 'SUMBER', 'INSTANSI MITRA', 'TOTAL UNIT', 'TERSEDIA', 'RESERVED', 'RUSAK', 'KONDISI', 'LOKASI'];

        $rows = [];
        $no = 1;
        foreach ($items as $item) {
            $rows[] = [
                $no++,
                $item->code,
                $item->name,
                $item->category->name ?? '-',
                strtoupper($item->source),
                $item->partner->name ?? '-',
                (int)$item->qty_total,
                (int)$item->qty_available,
                (int)$item->qty_reserved,
                (int)$item->qty_damaged,
                strtoupper(str_replace('_', ' ', $item->condition)),
                $item->location ?? '-',
            ];
        }

        return ExcelExportService::streamStyled(
            filename: 'Inventaris_Rojabiyyah_' . date('Ymd_His') . '.xlsx',
            sheetTitle: 'Katalog Inventaris',
            mainTitle: 'DAFTAR INVENTARIS & LOGISTIK ROJABIYYAH 2026',
            subtitle: 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026',
            headers: $headers,
            rows: $rows,
            options: [
                'header_bg' => '1E293B', // Slate-800
                'alignments' => [
                    1 => 'center',
                    2 => 'center',
                    5 => 'center',
                    7 => 'center',
                    8 => 'center',
                    9 => 'center',
                    10 => 'center',
                    11 => 'center',
                ],
                'formats' => [
                    7 => '#,##0',
                    8 => '#,##0',
                    9 => '#,##0',
                    10 => '#,##0',
                ],
                'text_columns' => [2],
            ]
        );
    }

    public function template()
    {
        $headers = ['KODE', 'NAMA BARANG', 'KATEGORI', 'SUMBER', 'TOTAL UNIT', 'RUSAK', 'KONDISI', 'LOKASI'];
        $rows = [
            ['BRG-0001', 'Handy Talky Baofeng BF-888S', 'Elektronik & Komunikasi', 'internal', 10, 0, 'baik', 'Gudang Utama Lt. 1'],
            ['BRG-0002', 'Kabel Roll 25 Meter', 'Kelistrikan', 'internal', 5, 1, 'rusak_ringan', 'Posko Lapangan'],
            ['BRG-0003', 'Proyektor Epson EB-X500', 'Multimedia', 'mitra', 2, 0, 'baik', 'Ruang Media Center'],
        ];

        return ExcelExportService::streamStyled(
            filename: 'Template_Impor_Inventaris_Rojabiyyah.xlsx',
            sheetTitle: 'Template Inventaris',
            mainTitle: 'FORMAT TEMPLATE IMPOR BARANG INVENTARIS',
            subtitle: 'Isi data barang sesuai kolom di bawah ini lalu unggah melalui menu Impor Excel',
            headers: $headers,
            rows: $rows,
            options: [
                'header_bg' => '312E81', // Indigo-900
                'alignments' => [
                    1 => 'center',
                    4 => 'center',
                    5 => 'center',
                    6 => 'center',
                    7 => 'center',
                ],
                'formats' => [
                    5 => '#,##0',
                    6 => '#,##0',
                ],
                'text_columns' => [1],
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
        $existingCodes = Item::pluck('code')->toArray();

        foreach ($rows as $index => $row) {
            $name = trim($row['nama_barang'] ?? $row['nama'] ?? $row['name'] ?? '');
            $code = trim($row['kode'] ?? $row['code'] ?? '');
            $category = trim($row['kategori'] ?? $row['category'] ?? '');
            $source = strtolower(trim($row['sumber'] ?? $row['source'] ?? 'internal'));
            if (!in_array($source, ['internal', 'mitra'])) {
                $source = 'internal';
            }
            $total = (int)($row['total_unit'] ?? $row['total'] ?? $row['qty_total'] ?? 1);
            $damaged = (int)($row['rusak'] ?? $row['qty_damaged'] ?? 0);
            $condition = strtolower(trim($row['kondisi'] ?? $row['condition'] ?? 'baik'));
            if (!in_array($condition, ['baik', 'rusak_ringan', 'rusak_berat'])) {
                $condition = 'baik';
            }
            $location = trim($row['lokasi'] ?? $row['location'] ?? '');

            if (empty($name)) {
                $invalidRows[] = [
                    'row' => $index + 2,
                    'data' => $row,
                    'error' => 'Nama barang wajib diisi.',
                ];
                continue;
            }

            $isDuplicate = !empty($code) && in_array($code, $existingCodes);

            $validRows[] = [
                'row' => $index + 2,
                'code' => $code,
                'name' => $name,
                'category' => $category,
                'source' => $source,
                'qty_total' => max(1, $total),
                'qty_damaged' => max(0, $damaged),
                'condition' => $condition,
                'location' => $location,
                'is_duplicate' => $isDuplicate,
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
            'skip_duplicates' => 'nullable|boolean',
        ]);

        $rows = $request->input('rows');
        $skipDuplicates = $request->input('skip_duplicates', true);

        return DB::transaction(function () use ($rows, $skipDuplicates) {
            $successCount = 0;
            $failedCount = 0;

            // Cache categories
            $categories = InventoryCategory::all()->keyBy(function ($c) {
                return strtolower(trim($c->name));
            });

            foreach ($rows as $item) {
                $name = trim($item['name'] ?? '');
                $code = trim($item['code'] ?? '');

                if (empty($name)) {
                    $failedCount++;
                    continue;
                }

                if (!empty($code)) {
                    $existing = Item::where('code', $code)->first();
                    if ($existing) {
                        if ($skipDuplicates) {
                            $failedCount++;
                            continue;
                        }
                    }
                } else {
                    $code = $this->generateNextItemCode();
                }

                // Category
                $catName = trim($item['category'] ?? '');
                $categoryId = null;
                if (!empty($catName)) {
                    $catKey = strtolower($catName);
                    if ($categories->has($catKey)) {
                        $categoryId = $categories->get($catKey)->id;
                    } else {
                        $newCat = InventoryCategory::create(['name' => $catName, 'type' => 'INVENTORY']);
                        $categories->put($catKey, $newCat);
                        $categoryId = $newCat->id;
                    }
                }

                $qtyTotal = (int)($item['qty_total'] ?? 1);
                $qtyDamaged = (int)($item['qty_damaged'] ?? 0);
                $qtyAvailable = max(0, $qtyTotal - $qtyDamaged);

                Item::create([
                    'code' => $code,
                    'name' => $name,
                    'source' => $item['source'] ?? 'internal',
                    'category_id' => $categoryId,
                    'qty_total' => $qtyTotal,
                    'qty_available' => $qtyAvailable,
                    'qty_reserved' => 0,
                    'qty_damaged' => $qtyDamaged,
                    'condition' => $item['condition'] ?? 'baik',
                    'location' => $item['location'] ?? null,
                ]);

                $successCount++;
            }

            AuditLog::log('import', 'Item', null, null, [
                'success' => $successCount,
                'failed' => $failedCount,
            ]);

            return response()->json([
                'message' => "Berhasil mengimpor {$successCount} barang inventaris.",
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

    public function categories()
    {
        return response()->json(InventoryCategory::withCount('items')->orderBy('name', 'asc')->get());
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
        ]);
        $cat = InventoryCategory::create([
            'name' => $request->name,
            'type' => $request->input('type', 'INVENTORY'),
            'description' => $request->description,
        ]);
        return response()->json($cat, 201);
    }

    public function destroyCategory($id)
    {
        $cat = InventoryCategory::findOrFail($id);
        $cat->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus.']);
    }

    public function partners()
    {
        return response()->json(Partner::withCount('items')->get());
    }

    public function storePartner(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'contact' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'return_due_date' => 'nullable|date',
        ]);
        $partner = Partner::create($request->all());
        return response()->json($partner, 201);
    }

    public function updatePartner(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);
        $request->validate([
            'name' => 'required|string',
            'contact' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
            'return_due_date' => 'nullable|date',
        ]);
        $partner->update($request->all());
        return response()->json($partner);
    }

    public function destroyPartner($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        return response()->json(['message' => 'Mitra berhasil dihapus.']);
    }

    private function generateNextItemCode(): string
    {
        $latest = Item::orderBy('id', 'desc')->first();
        $next = $latest ? $latest->id + 1 : 1;
        return sprintf('BRG-%04d', $next);
    }
}

