<?php

namespace Database\Seeders;

use App\Models\CashCategory;
use App\Models\Division;
use App\Models\InventoryCategory;
use App\Models\Item;
use App\Models\Partner;
use App\Models\Region;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\User;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $defaultSettings = [
            'app_name' => 'Administrasi Rojabiyyah 2026',
            'sub_theme' => "Peringatan Haul Masyayikh & Isro' Mi'roj 1447 H / 2026 M",
            'letterhead_text' => "PANITIA ROJABIYYAH 2026\nSekretariat Utama Gedung Serbaguna Lt. 2\nKontak: 0812-3456-7890 | Website: rojabiyyah.id",
            'gdrive_folder_url' => 'https://drive.google.com/drive/folders/1l91WsFBV_sZ89DlvzvSXlgedX0pOwsfa?usp=sharing',
            'logo_path' => null,
        ];

        foreach ($defaultSettings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 2. Divisions
        $divisions = [
            'Badan Pengurus Harian (BPH)',
            'Divisi Acara',
            'Divisi Logistik & Perlengkapan',
            'Divisi Dokumentasi & Multimedia',
            'Divisi Keamanan & Ketertiban',
            'Divisi Konsumsi',
            'Divisi Humas & Tamu',
            'Divisi Medis',
        ];

        $divisionModels = [];
        foreach ($divisions as $name) {
            $divisionModels[$name] = Division::firstOrCreate(['name' => $name]);
        }

        // 3. Inventory Categories
        $invCategories = [
            'Sound System & Audio',
            'Kabel & Kelistrikan',
            'Kamera, Lensa & Video',
            'Proyektor & Layar LED',
            'Tenda, Meja & Kursi',
            'Dekorasi & Panggung',
            'Alat Komunikasi & HT',
        ];

        $invCatModels = [];
        foreach ($invCategories as $name) {
            $invCatModels[$name] = InventoryCategory::firstOrCreate(['name' => $name]);
        }

        // 4. Mitra / Partners
        $mitra1 = Partner::firstOrCreate([
            'name' => 'SMK TI Annuriyyah',
            'contact' => '0812-9988-7766 (Pak Ahmad)',
            'return_due_date' => '2026-03-01',
        ]);
        $mitra2 = Partner::firstOrCreate([
            'name' => 'Sound Rental Berkah Abadi',
            'contact' => '0857-1122-3344 (Cak Munir)',
            'return_due_date' => '2026-02-28',
        ]);

        // 5. Users and Roles
        $seedPassword = Hash::make(env('SEED_DEFAULT_PASSWORD', 'password123'));
        $mustChange = (bool) env('SEED_REQUIRE_PASSWORD_CHANGE', true);

        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@rojabiyyah.id'],
            [
                'member_no' => 'RJB-001',
                'name' => 'KH. Ahmad Fauzan (Super Admin)',
                'password' => $seedPassword,
                'role' => 'super_admin',
                'phone_wa' => '081234567801',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $superAdmin->id], [
            'division_id' => $divisionModels['Badan Pengurus Harian (BPH)']->id,
            'domicile' => 'Pondok Pesantren Induk',
            'uniform_size' => 'XL',
        ]);

        // Admin Operasional
        $admin = User::firstOrCreate(
            ['email' => 'admin@rojabiyyah.id'],
            [
                'member_no' => 'RJB-002',
                'name' => 'M. Rizqi Akbar (Admin)',
                'password' => $seedPassword,
                'role' => 'admin',
                'phone_wa' => '081234567802',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $admin->id], [
            'division_id' => $divisionModels['Divisi Logistik & Perlengkapan']->id,
            'domicile' => 'Asrama Putra Blok C',
            'uniform_size' => 'L',
        ]);

        // Bendahara
        $bendahara = User::firstOrCreate(
            ['email' => 'bendahara@rojabiyyah.id'],
            [
                'member_no' => 'RJB-003',
                'name' => 'H. Syamsul Arifin (Bendahara)',
                'password' => $seedPassword,
                'role' => 'bendahara',
                'phone_wa' => '081234567803',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $bendahara->id], [
            'division_id' => $divisionModels['Badan Pengurus Harian (BPH)']->id,
            'domicile' => 'Komplek Asatidz No. 04',
            'uniform_size' => 'L',
        ]);

        // Ketua Divisi Multimedia
        $ketuaDiv = User::firstOrCreate(
            ['email' => 'multimedia@rojabiyyah.id'],
            [
                'member_no' => 'RJB-004',
                'name' => 'Farhan Al-Ghifari (Ketua Multimedia)',
                'password' => $seedPassword,
                'role' => 'ketua_divisi',
                'phone_wa' => '081234567804',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $ketuaDiv->id], [
            'division_id' => $divisionModels['Divisi Dokumentasi & Multimedia']->id,
            'domicile' => 'Gedung Multimedia Lt. 1',
            'uniform_size' => 'M',
        ]);

        // PIC Posko
        $picPosko = User::firstOrCreate(
            ['email' => 'poskopanggung@rojabiyyah.id'],
            [
                'member_no' => 'RJB-005',
                'name' => 'Irfan Maulana (PIC Panggung)',
                'password' => $seedPassword,
                'role' => 'pic_posko',
                'phone_wa' => '081234567805',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $picPosko->id], [
            'division_id' => $divisionModels['Divisi Acara']->id,
            'domicile' => 'Asrama Santri Al-Falah',
            'uniform_size' => 'L',
        ]);

        // Anggota Biasa
        $anggota = User::firstOrCreate(
            ['email' => 'anggota@rojabiyyah.id'],
            [
                'member_no' => 'RJB-006',
                'name' => 'Zaidan Baihaqi (Anggota)',
                'password' => $seedPassword,
                'role' => 'anggota',
                'phone_wa' => '081234567806',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $anggota->id], [
            'division_id' => $divisionModels['Divisi Dokumentasi & Multimedia']->id,
            'domicile' => 'Kamar 12 Asrama Timur',
            'uniform_size' => 'M',
        ]);

        // Anggota Ahmad
        $ahmad = User::firstOrCreate(
            ['email' => 'ahmad@rojabiyyah.id'],
            [
                'member_no' => 'RJB-007',
                'name' => 'Ahmad (Anggota)',
                'password' => $seedPassword,
                'role' => 'anggota',
                'phone_wa' => '081234567807',
                'is_active' => true,
                'must_change_password' => $mustChange,
            ]
        );
        Member::firstOrCreate(['user_id' => $ahmad->id], [
            'division_id' => $divisionModels['Divisi Dokumentasi & Multimedia']->id,
            'domicile' => 'Asrama Putra Al-Ikhlas',
            'uniform_size' => 'L',
        ]);

        // 6. Regions / Posko
        $poskoPanggung = Region::firstOrCreate(
            ['name' => 'Panggung Utama'],
            [
                'pic_user_id' => $picPosko->id,
                'description' => 'Area panggung inti dan pusat acara pengajian akbar.',
            ]
        );

        $poskoLive = Region::firstOrCreate(
            ['name' => 'Posko Live Streaming & Dokumentasi'],
            [
                'pic_user_id' => $ketuaDiv->id,
                'description' => 'Pusat kendali siaran YouTube, audio master, dan rekaman liputan.',
            ]
        );

        Region::firstOrCreate(
            ['name' => 'Posko Keamanan Utama'],
            [
                'pic_user_id' => $admin->id,
                'description' => 'Area gerbang masuk, pos transit tamu VIP, dan parkir rombongan.',
            ]
        );

        // 7. Inventory Items
        $items = [
            [
                'code' => 'BRG-001',
                'name' => 'Wireless Mic Shure BLX24/PG58 (Set)',
                'source' => 'internal',
                'category_id' => $invCatModels['Sound System & Audio']->id,
                'qty_total' => 4,
                'qty_available' => 4,
                'qty_reserved' => 0,
                'qty_damaged' => 0,
                'condition' => 'baik',
                'location' => 'Gudang Sound Rak A1',
            ],
            [
                'code' => 'BRG-002',
                'name' => 'Sony A7 IV + Lensa 24-70mm GM II',
                'source' => 'internal',
                'category_id' => $invCatModels['Kamera, Lensa & Video']->id,
                'qty_total' => 2,
                'qty_available' => 2,
                'qty_reserved' => 0,
                'qty_damaged' => 0,
                'condition' => 'baik',
                'location' => 'Dry Box Ruang Multimedia',
            ],
            [
                'code' => 'BRG-003',
                'name' => 'Kabel Roll 50 Meter Heavy Duty',
                'source' => 'internal',
                'category_id' => $invCatModels['Kabel & Kelistrikan']->id,
                'qty_total' => 10,
                'qty_available' => 10,
                'qty_reserved' => 0,
                'qty_damaged' => 0,
                'condition' => 'baik',
                'location' => 'Gudang Logistik Kotak C3',
            ],
            [
                'code' => 'BRG-004',
                'name' => 'Proyektor Epson 5000 Lumens (Mitra TI)',
                'source' => 'mitra',
                'partner_id' => $mitra1->id,
                'category_id' => $invCatModels['Proyektor & Layar LED']->id,
                'qty_total' => 3,
                'qty_available' => 3,
                'qty_reserved' => 0,
                'qty_damaged' => 0,
                'condition' => 'baik',
                'location' => 'Lab SMK TI Annuriyyah (Dipinjam)',
            ],
            [
                'code' => 'BRG-005',
                'name' => 'HT Baofeng UV-82 Dual Band',
                'source' => 'internal',
                'category_id' => $invCatModels['Alat Komunikasi & HT']->id,
                'qty_total' => 15,
                'qty_available' => 15,
                'qty_reserved' => 0,
                'qty_damaged' => 0,
                'condition' => 'baik',
                'location' => 'Kotak Keamanan Sentral',
            ],
        ];

        foreach ($items as $itemData) {
            Item::firstOrCreate(['code' => $itemData['code']], $itemData);
        }

        // 8. Cash Categories & Sample Transactions
        $catKasAwal = CashCategory::firstOrCreate(['name' => 'Saldo Kas Awal', 'type' => 'in']);
        $catInfaq = CashCategory::firstOrCreate(['name' => 'Infaq & Donasi Jamaah', 'type' => 'in']);
        $catSponsor = CashCategory::firstOrCreate(['name' => 'Sponsorship Lembaga', 'type' => 'in']);
        $catKonsumsi = CashCategory::firstOrCreate(['name' => 'Konsumsi Rapat & Panitia', 'type' => 'out']);
        $catLogistik = CashCategory::firstOrCreate(['name' => 'Pembelian & Sewa Alat', 'type' => 'out']);

        \App\Models\CashTransaction::firstOrCreate(
            ['description' => 'Penerimaan Kas Awal dari Panitia Pusat'],
            [
                'type' => 'in',
                'amount' => 15000000.00,
                'date' => '2026-01-10',
                'category_id' => $catKasAwal->id,
                'created_by' => $bendahara->id,
            ]
        );

        \App\Models\CashTransaction::firstOrCreate(
            ['description' => 'Sponsorship PT Sumber Barokah'],
            [
                'type' => 'in',
                'amount' => 10000000.00,
                'date' => '2026-01-15',
                'category_id' => $catSponsor->id,
                'created_by' => $bendahara->id,
            ]
        );

        \App\Models\CashTransaction::firstOrCreate(
            ['description' => 'Belanja Konsumsi Rapat Perdana Seluruh Divisi'],
            [
                'type' => 'out',
                'amount' => 1250000.00,
                'date' => '2026-01-18',
                'category_id' => $catKonsumsi->id,
                'created_by' => $bendahara->id,
            ]
        );

        // 9. Slider Beranda
        Slider::firstOrCreate(
            ['title' => 'Selamat Datang di Portal Panitia Rojabiyyah 2026'],
            [
                'description' => 'Sistem Administrasi Terpadu: Kelola Inventaris, Peminjaman Logistik, Penugasan Posko, dan Kas Keuangan.',
                'image_path' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Slider::firstOrCreate(
            ['title' => 'Haul Akbar & Isro\' Mi\'roj 1447 H'],
            [
                'description' => 'Tingkatkan soliditas dan profesionalitas koordinasi lapangan demi kelancaran seluruh rangkaian acara.',
                'image_path' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&w=1200&q=80',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );
    }
}
