<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Division;
use App\Models\ImportBatch;
use App\Models\Member;
use App\Models\User;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('member.division');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_no', 'like', "%{$search}%")
                  ->orWhere('phone_wa', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('registration_source')) {
            $src = $request->registration_source;
            if ($src === 'account') {
                $query->where('registration_source', 'account')->whereNotNull('email');
            } elseif ($src === 'google_form') {
                $query->where(function ($q) {
                    $q->where('registration_source', 'google_form')
                      ->orWhereNull('email');
                });
            }
        }

        if ($request->filled('division_id')) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($request->filled('domicile')) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('domicile', $request->domicile);
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $totalCount = User::count();
        $accountCount = User::where('registration_source', 'account')->whereNotNull('email')->count();
        $googleFormCount = User::where(function ($q) {
            $q->where('registration_source', 'google_form')
              ->orWhereNull('email');
        })->count();

        $paginated = $query->orderBy('member_no', 'asc')->paginate($request->input('per_page', 15));

        $result = $paginated->toArray();
        $result['stats'] = [
            'total' => $totalCount,
            'account' => $accountCount,
            'google_form' => $googleFormCount,
        ];

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'member_no' => 'nullable|string|max:50|unique:users,member_no',
            'email' => 'nullable|email|unique:users,email',
            'role' => 'nullable|string',
            'phone_wa' => 'nullable|string|max:30',
            'division_id' => 'nullable|exists:divisions,id',
            'domicile' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:100',
            'class' => 'nullable|string|max:100',
            'uniform_size' => 'nullable|string|max:10',
            'photo_path' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
            'is_active' => 'nullable|boolean',
        ]);

        return DB::transaction(function () use ($request) {
            $memberNo = $request->filled('member_no') ? $request->member_no : $this->generateNextMemberNo();
            $tempPassword = $request->filled('password') ? $request->password : Str::random(8);
            $isActive = $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : true;
            $hasAccount = $request->boolean('has_account') || $request->filled('email');
            $regSource = $hasAccount ? 'account' : $request->input('registration_source', 'google_form');

            $user = User::create([
                'member_no' => $memberNo,
                'name' => $request->name,
                'email' => $hasAccount ? $request->email : null,
                'password' => Hash::make($tempPassword),
                'role' => $request->role ?? 'anggota',
                'registration_source' => $regSource,
                'phone_wa' => $request->phone_wa,
                'is_active' => $isActive,
                'must_change_password' => $request->boolean('must_change_password', false),
            ]);

            $member = Member::create([
                'user_id' => $user->id,
                'division_id' => $request->division_id,
                'education' => $request->education,
                'class' => $request->class,
                'domicile' => $request->domicile,
                'uniform_size' => $request->uniform_size,
                'photo_path' => $request->photo_path,
                'address' => $request->address,
            ]);

            if ($request->filled('region_id')) {
                \App\Models\Assignment::create([
                    'region_id' => $request->region_id,
                    'user_id' => $user->id,
                    'role_in_post' => 'Anggota Posko Lapangan',
                ]);
            }

            AuditLog::log('create', 'Member', $user->id, null, $user->toArray());

            return response()->json([
                'message' => 'Anggota berhasil ditambahkan.',
                'user' => $user->load('member.division'),
                'temp_password' => $tempPassword,
            ], 201);
        });
    }

    public function show($id)
    {
        $user = User::with('member.division')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::with('member')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'member_no' => 'nullable|string|max:50|unique:users,member_no,' . $user->id,
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'role' => 'nullable|string',
            'phone_wa' => 'nullable|string|max:30',
            'is_active' => 'nullable|boolean',
            'division_id' => 'nullable|exists:divisions,id',
            'domicile' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:100',
            'class' => 'nullable|string|max:100',
            'uniform_size' => 'nullable|string|max:10',
            'photo_path' => 'nullable|string',
            'address' => 'nullable|string',
            'region_id' => 'nullable|exists:regions,id',
        ]);

        // Prevent changing super_admin status if it leaves 0 super admins
        if ($user->role === 'super_admin' && $request->filled('role') && $request->role !== 'super_admin') {
            $superAdminCount = User::where('role', 'super_admin')->where('is_active', true)->count();
            if ($superAdminCount <= 1) {
                return response()->json(['message' => 'Tidak dapat mengubah peran Super Admin terakhir.'], 422);
            }
        }

        $oldValues = $user->toArray();

        $updateData = [
            'name' => $request->name,
            'member_no' => $request->filled('member_no') ? $request->member_no : $user->member_no,
            'role' => $request->role ?? $user->role,
            'phone_wa' => $request->phone_wa,
            'is_active' => $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : $user->is_active,
        ];

        if ($request->has('email')) {
            $updateData['email'] = $request->email ?: null;
            if ($request->filled('email')) {
                $updateData['registration_source'] = 'account';
            }
        }

        if ($request->filled('registration_source')) {
            $updateData['registration_source'] = $request->registration_source;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        $memberData = [
            'division_id' => $request->division_id,
            'education' => $request->education,
            'class' => $request->class,
            'domicile' => $request->domicile,
            'uniform_size' => $request->uniform_size,
            'address' => $request->address,
        ];
        if ($request->has('photo_path')) {
            $memberData['photo_path'] = $request->photo_path;
        }

        $user->member()->updateOrCreate(
            ['user_id' => $user->id],
            $memberData
        );

        if ($request->filled('region_id')) {
            \App\Models\Assignment::updateOrCreate(
                ['user_id' => $user->id],
                ['region_id' => $request->region_id, 'role_in_post' => 'Anggota Posko Lapangan']
            );
        }

        AuditLog::log('update', 'Member', $user->id, $oldValues, $user->toArray());

        return response()->json([
            'message' => 'Data anggota berhasil diperbarui.',
            'user' => $user->fresh('member.division'),
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['message' => 'Akun Super Admin tidak dapat dihapus.'], 403);
        }

        $oldValues = $user->toArray();
        $user->delete();

        AuditLog::log('delete', 'Member', $id, $oldValues, null);

        return response()->json(['message' => 'Anggota berhasil dihapus.']);
    }

    public function createAccount(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string|in:super_admin,admin,bendahara,anggota,pic_posko,ketua_divisi',
        ]);

        $password = $request->filled('password') ? $request->password : Str::random(8);

        $user->update([
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role ?? ($user->role ?: 'anggota'),
            'registration_source' => 'account',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        AuditLog::log('create_account', 'User', $user->id, null, [
            'email' => $user->email,
            'assigned_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => "Akun login berhasil diaktifkan untuk {$user->name}.",
            'user' => $user->fresh('member.division'),
            'credentials' => [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'member_no' => $user->member_no,
            ],
        ]);
    }

    public function revokeAccount(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'super_admin') {
            return response()->json(['message' => 'Tidak dapat mencabut akses login Super Admin.'], 422);
        }

        $user->tokens()->delete();
        $user->update([
            'email' => null,
            'registration_source' => 'google_form',
        ]);

        AuditLog::log('revoke_account', 'User', $user->id, null, [
            'revoked_by' => auth()->id(),
        ]);

        return response()->json([
            'message' => "Akses login untuk {$user->name} berhasil dicabut. Data tetap tersimpan sebagai pendaftar formulir.",
            'user' => $user->fresh('member.division'),
        ]);
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
        $existingPhones = User::whereNotNull('phone_wa')->pluck('phone_wa')->toArray();

        foreach ($rows as $index => $row) {
            $name = trim($row['nama'] ?? $row['name'] ?? '');
            $phone = trim($row['no_wa'] ?? $row['wa'] ?? $row['telepon'] ?? $row['phone'] ?? '');
            $division = trim($row['dev'] ?? $row['divisi'] ?? $row['division'] ?? '');
            $education = trim($row['pendidikan'] ?? '');
            $class = trim($row['kelas'] ?? '');
            $domicile = trim($row['domisili'] ?? '');
            $uniform = trim($row['seragam'] ?? '');

            if (empty($name)) {
                $invalidRows[] = [
                    'row' => $index + 2,
                    'data' => $row,
                    'error' => 'Nama wajib diisi.',
                ];
                continue;
            }

            $isDuplicate = in_array($phone, $existingPhones) && !empty($phone);

            $validRows[] = [
                'row' => $index + 2,
                'name' => $name,
                'phone_wa' => $phone,
                'division' => $division,
                'education' => $education,
                'class' => $class,
                'domicile' => $domicile,
                'uniform_size' => $uniform,
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

        return DB::transaction(function () use ($rows, $skipDuplicates, $request) {
            $createdUsers = [];
            $successCount = 0;
            $failedCount = 0;

            // Cache divisions
            $divisions = Division::all()->keyBy(function ($d) {
                return strtolower(trim($d->name));
            });

            foreach ($rows as $item) {
                $name = trim($item['name'] ?? '');
                $phone = trim($item['phone_wa'] ?? '');

                if (empty($name)) {
                    $failedCount++;
                    continue;
                }

                if (!empty($phone)) {
                    $existing = User::where('phone_wa', $phone)->first();
                    if ($existing) {
                        if ($skipDuplicates) {
                            $failedCount++;
                            continue;
                        }
                    }
                }

                // Find or create division
                $divName = trim($item['division'] ?? '');
                $divisionId = null;
                if (!empty($divName)) {
                    $divKey = strtolower($divName);
                    if ($divisions->has($divKey)) {
                        $divisionId = $divisions->get($divKey)->id;
                    } else {
                        $newDiv = Division::create(['name' => $divName]);
                        $divisions->put($divKey, $newDiv);
                        $divisionId = $newDiv->id;
                    }
                }

                $memberNo = $this->generateNextMemberNo();
                $tempPassword = Str::random(8);

                $user = User::create([
                    'member_no' => $memberNo,
                    'name' => $name,
                    'password' => Hash::make($tempPassword),
                    'role' => 'anggota',
                    'registration_source' => 'google_form',
                    'phone_wa' => $phone ?: null,
                    'is_active' => true,
                    'must_change_password' => true,
                ]);

                Member::create([
                    'user_id' => $user->id,
                    'division_id' => $divisionId,
                    'education' => $item['education'] ?? null,
                    'class' => $item['class'] ?? null,
                    'domicile' => $item['domicile'] ?? null,
                    'uniform_size' => $item['uniform_size'] ?? null,
                ]);

                $createdUsers[] = [
                    'member_no' => $memberNo,
                    'name' => $name,
                    'temp_password' => $tempPassword,
                    'phone_wa' => $phone,
                ];

                $successCount++;
            }

            ImportBatch::create([
                'user_id' => auth()->id() ?? 1,
                'filename' => 'Import_Spreadsheet_' . now()->format('Ymd_His'),
                'total' => count($rows),
                'success' => $successCount,
                'failed' => $failedCount,
            ]);

            AuditLog::log('import', 'Member', null, null, [
                'success' => $successCount,
                'failed' => $failedCount,
            ]);

            return response()->json([
                'message' => "Berhasil mengimpor {$successCount} anggota.",
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'created_credentials' => $createdUsers,
            ]);
        });
    }

    public function export(Request $request)
    {
        $query = User::with('member.division');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_no', 'like', "%{$search}%")
                  ->orWhere('phone_wa', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('division_id')) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($request->filled('domicile')) {
            $query->whereHas('member', function ($q) use ($request) {
                $q->where('domicile', $request->domicile);
            });
        }

        if ($request->filled('registration_source')) {
            $src = $request->registration_source;
            if ($src === 'account') {
                $query->where('registration_source', 'account')->whereNotNull('email');
            } elseif ($src === 'google_form') {
                $query->where(function ($q) {
                    $q->where('registration_source', 'google_form')
                      ->orWhereNull('email');
                });
            }
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $members = $query->orderBy('member_no', 'asc')->get();

        $headers = ['NO', 'NO ANGGOTA', 'NAMA LENGKAP', 'SUMBER / TIPE DATA', 'EMAIL LOGIN', 'NO WHATSAPP', 'ROLE', 'DIVISI', 'PENDIDIKAN', 'KELAS / TINGKAT', 'DOMISILI', 'UKURAN SERAGAM', 'ALAMAT ASAL', 'STATUS'];

        $rows = [];
        $no = 1;
        foreach ($members as $m) {
            $hasLogin = (!empty($m->email) && $m->registration_source === 'account');
            $sourceLabel = $hasLogin ? 'AKUN LOGIN APLIKASI' : 'GOOGLE FORMULIR';

            $rows[] = [
                $no++,
                $m->member_no ?? '-',
                $m->name,
                $sourceLabel,
                $m->email ?? '-',
                $m->phone_wa ?: '-',
                strtoupper($m->role),
                $m->member->division->name ?? '-',
                $m->member->education ?? '-',
                $m->member->class ?? '-',
                $m->member->domicile ?? '-',
                $m->member->uniform_size ?? '-',
                $m->member->address ?? '-',
                $m->is_active ? 'AKTIF' : 'NONAKTIF',
            ];
        }

        $sub = 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026';
        if ($request->registration_source === 'account') {
            $sub .= ' • Kategori: Akun Login Anggota';
        } elseif ($request->registration_source === 'google_form') {
            $sub .= ' • Kategori: Pendaftar Google Formulir';
        }

        return ExcelExportService::streamStyled(
            filename: 'Data_Anggota_Rojabiyyah_' . date('Ymd_His') . '.xlsx',
            sheetTitle: 'Data Anggota',
            mainTitle: 'DATA ANGGOTA & RELAWAN PANITIA ROJABIYYAH 2026',
            subtitle: $sub,
            headers: $headers,
            rows: $rows,
            options: [
                'header_bg' => '0F766E', // Teal-700
                'alignments' => [
                    1 => 'center',
                    2 => 'center',
                    4 => 'center',
                    6 => 'center',
                    7 => 'center',
                    8 => 'center',
                    10 => 'center',
                    12 => 'center',
                    14 => 'center',
                ],
                'text_columns' => [2, 6],
            ]
        );
    }

    public function template()
    {
        $headers = ['NO', 'NAMA', 'DEV', 'PENDIDIKAN', 'KELAS', 'DOMISILI', 'SERAGAM', 'NO WA'];
        $rows = [
            [1, 'Ahmad Dani', 'Divisi Acara', 'S1 Teknik Informatika', 'Tingkat 3', 'Asrama Barat', 'L', '081234567890'],
            [2, 'Muhammad Ali', 'Divisi Logistik', 'D3 Manajemen', 'Tingkat 2', 'Asrama Timur', 'XL', '081234567891'],
            [3, 'Fatimah Zahra', 'Divisi Konsumsi', 'SMA / MA', 'Kelas XII', 'Pondok Putri', 'M', '085712345678'],
        ];

        return ExcelExportService::streamStyled(
            filename: 'Template_Impor_Anggota_Rojabiyyah.xlsx',
            sheetTitle: 'Template Anggota',
            mainTitle: 'FORMAT TEMPLATE IMPOR DATA ANGGOTA & PANITIA',
            subtitle: 'Isi data sesuai kolom di bawah ini lalu unggah melalui menu Impor Excel',
            headers: $headers,
            rows: $rows,
            options: [
                'header_bg' => '312E81', // Indigo-900
                'alignments' => [1 => 'center', 5 => 'center', 7 => 'center', 8 => 'center'],
                'text_columns' => [8],
            ]
        );
    }

    public function divisions()
    {
        return response()->json(Division::withCount('members')->get());
    }

    public function publicOptions()
    {
        return response()->json([
            'divisions' => Division::orderBy('name')->get(['id', 'name']),
            'regions' => \App\Models\Region::orderBy('name')->get(['id', 'name']),
            'educations' => [
                'SD / MI',
                'SMP / MTs',
                'SMA / MA / SMK',
                'Pondok Pesantren / Madrasah Diniyah',
                'Mahad Aly',
                'Diploma (D1 - D4)',
                'Sarjana (S1)',
                'Pascasarjana (S2 / S3)',
                'Lainnya / Umum',
            ],
            'domiciles' => [
                'Pondok Pesantren Induk (Asrama Putra)',
                'Pondok Pesantren Induk (Asrama Putri)',
                'Komplek Ndalem Kasepuhan',
                'Warga Sekitar Pesantren / Santri Kalong',
                'Luar Kota / Alumni',
            ],
            'uniform_sizes' => ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
        ]);
    }

    public function publicRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'education' => 'nullable|string|max:100',
            'class' => 'nullable|string|max:100',
            'domicile' => 'nullable|string|max:255',
            'uniform_size' => 'nullable|string|max:10',
            'phone_wa' => 'nullable|string|max:30',
            'region_id' => 'nullable|exists:regions,id',
            'notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($request) {
            $memberNo = $this->generateNextMemberNo();
            $tempPassword = Str::random(10);

            $user = User::create([
                'member_no' => $memberNo,
                'name' => $request->name,
                'email' => null,
                'password' => Hash::make($tempPassword),
                'role' => 'anggota',
                'registration_source' => 'google_form',
                'phone_wa' => $request->phone_wa,
                'is_active' => true,
                'must_change_password' => true,
            ]);

            $member = Member::create([
                'user_id' => $user->id,
                'division_id' => $request->division_id,
                'education' => $request->education,
                'class' => $request->class,
                'domicile' => $request->domicile,
                'uniform_size' => $request->uniform_size,
            ]);

            if ($request->filled('region_id')) {
                \App\Models\Assignment::create([
                    'region_id' => $request->region_id,
                    'user_id' => $user->id,
                    'role_in_post' => 'Anggota Posko Lapangan',
                ]);
            }

            AuditLog::log('public_register', 'Member', $user->id, null, [
                'member_no' => $memberNo,
                'name' => $request->name,
            ]);

            return response()->json([
                'message' => 'Pendaftaran anggota berhasil!',
                'member_no' => $memberNo,
                'name' => $user->name,
            ], 201);
        });
    }

    public function publicRegisterAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_wa' => 'nullable|string|max:30',
            'domicile' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        return DB::transaction(function () use ($request) {
            $memberNo = $this->generateNextMemberNo();

            $user = User::create([
                'member_no' => $memberNo,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'anggota',
                'registration_source' => 'account',
                'phone_wa' => $request->phone_wa,
                'is_active' => true,
                'must_change_password' => false,
            ]);

            Member::create([
                'user_id' => $user->id,
                'domicile' => $request->domicile,
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            AuditLog::log('register_account', 'User', $user->id, null, ['ip' => $request->ip()]);

            return response()->json([
                'message' => 'Pendaftaran akun berhasil.',
                'token' => $token,
                'user' => $user->load('member'),
            ], 201);
        });
    }

    private function generateNextMemberNo(): string
    {
        $latestUser = User::where('member_no', 'like', 'RJB-%')
            ->orderByRaw('CAST(SUBSTRING(member_no, 5) AS UNSIGNED) DESC')
            ->lockForUpdate()
            ->first();

        if (!$latestUser || !$latestUser->member_no) {
            return 'RJB-001';
        }

        $parts = explode('-', $latestUser->member_no);
        $nextNum = isset($parts[1]) ? (int)$parts[1] + 1 : 1;

        if ($nextNum < 1000) {
            return sprintf('RJB-%03d', $nextNum);
        }

        return 'RJB-' . $nextNum;
    }

    private function parseUploadedSpreadsheet($file): array
    {
        $path = $file->getRealPath();
        $extension = strtolower($file->getClientOriginalExtension());
        $rows = [];

        if (in_array($extension, ['csv', 'txt'])) {
            if (($handle = fopen($path, 'r')) !== false) {
                // Auto-detect delimiter
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
                        // Skip empty rows
                        if (empty(array_filter($data, fn($v) => trim((string)$v) !== ''))) {
                            continue;
                        }
                        $row = [];
                        foreach ($header as $i => $colName) {
                            $val = trim((string)($data[$i] ?? ''));
                            // Strip leading quote prefix if exported from Excel text formula
                            if (str_starts_with($val, "'")) {
                                $val = substr($val, 1);
                            }
                            $row[$colName] = $val;
                        }
                        $rows[] = $row;
                    }
                }
                fclose($handle);
            }
        } else {
            // For XLSX/XLS via PhpSpreadsheet
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
                            if (str_starts_with($val, "'")) {
                                $val = substr($val, 1);
                            }
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

