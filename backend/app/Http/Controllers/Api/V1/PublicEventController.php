<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\Region;
use App\Models\Item;
use App\Models\Slider;
use App\Models\FieldReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PublicEventController extends Controller
{
    public function overview()
    {
        $totalMembers = User::count();
        $totalRegions = Region::count();
        $totalItems = Item::count();
        $regions = Region::with('picUser:id,name,phone')
            ->select('id', 'name', 'code', 'pic_user_id', 'description')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'name' => $r->name,
                    'code' => $r->code,
                    'coordinator_name' => $r->picUser?->name ?? 'Koordinator Lapangan',
                    'coordinator_phone' => $r->picUser?->phone ?? '-',
                    'description' => $r->description,
                ];
            });
        $sliders = Slider::where('is_active', true)->orderBy('sort_order', 'asc')->get();

        // 1. Denah Proyektor Photos
        $savedDenah = Setting::get('denah_photos');
        $denahPhotos = $savedDenah ? json_decode($savedDenah, true) : null;
        if (!is_array($denahPhotos) || count($denahPhotos) === 0) {
            $denahPhotos = [
                '/uploads/denah/denah_proyektor_1.jpg',
                'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
            ];
        }

        // 2. Jadwal Tim Photos
        $savedJadwal = Setting::get('jadwal_photos');
        $jadwalPhotos = $savedJadwal ? json_decode($savedJadwal, true) : [];

        // 2b. Proyektor Diagram / Scheme Photos
        $savedProyektorPhotos = Setting::get('proyektor_photos');
        $proyektorPhotos = $savedProyektorPhotos ? json_decode($savedProyektorPhotos, true) : [];

        // 3. Pembagian Tim Proyektor Data (Google Sheet Match)
        $proyektorTeams = [
            [
                'titik' => 'Titik A',
                'korwil' => 'Hadi Tio',
                'kabel' => 'M. Abdul Muiz',
                'items' => [
                    ['kode' => 'LCD 1', 'wilayah' => 'Operator', 'petugas' => 'Operator', 'kabel' => '-'],
                    ['kode' => 'LCD 2', 'wilayah' => 'Tamu VIP Putri', 'petugas' => 'Petugas Khusus', 'kabel' => '-'],
                    ['kode' => 'LCD 3', 'wilayah' => 'Tamu VIP Putra', 'petugas' => 'Petugas Khusus', 'kabel' => '-'],
                    ['kode' => 'LCD 4', 'wilayah' => 'Halaman', 'petugas' => 'Petugas Area', 'kabel' => '-'],
                    ['kode' => 'LCD 5', 'wilayah' => 'Kantor Al-Muhibbin', 'petugas' => 'Petugas Kantor', 'kabel' => '-'],
                    ['kode' => 'Proyektor 15', 'wilayah' => 'Hambali lt 1', 'petugas' => 'Akhsanul Khakim', 'kabel' => '-'],
                    ['kode' => 'Proyektor 16', 'wilayah' => 'Hambali lt 2', 'petugas' => 'M. Azka Mirzakhan', 'kabel' => '-'],
                ],
            ],
            [
                'titik' => 'Titik B',
                'korwil' => 'M. Azrul Hikam',
                'kabel' => 'M. Albiya Riftanusa & M. Farid Syafiqur R.',
                'items' => [
                    ['kode' => 'Proyektor 1', 'wilayah' => 'Serambi Masjid Selatan', 'petugas' => 'Faza Ahmad Al-Ghozali', 'kabel' => '-'],
                    ['kode' => 'Proyektor 2', 'wilayah' => 'Dalam Masjid', 'petugas' => 'Akhsanul Khakim', 'kabel' => '-'],
                    ['kode' => 'Proyektor 3', 'wilayah' => 'Serambi Masjid Utara', 'petugas' => 'Hafidz Ainul Haq', 'kabel' => '-'],
                    ['kode' => 'Proyektor 4', 'wilayah' => 'Serambi Masjid Timur', 'petugas' => 'M. Yazid', 'kabel' => '-'],
                    ['kode' => 'Proyektor 5', 'wilayah' => 'Kanopi', 'petugas' => 'M. Dakwa Ad-Daroin', 'kabel' => '-'],
                    ['kode' => 'Proyektor 6', 'wilayah' => 'Depan Hanafi', 'petugas' => 'M. Ricky Novianto', 'kabel' => '-'],
                ],
            ],
            [
                'titik' => 'Titik C',
                'korwil' => 'M. Alwi Marzuqi',
                'kabel' => 'Alfani Ramadhani',
                'items' => [
                    ['kode' => 'Proyektor 7', 'wilayah' => 'MTs FH lt 1', 'petugas' => 'M. Zidane Niamil Akbar', 'kabel' => '-'],
                    ['kode' => 'Proyektor 8', 'wilayah' => 'MTs FH lt 2', 'petugas' => 'Mikayla Fachri Maulidy', 'kabel' => '-'],
                    ['kode' => 'Proyektor 9', 'wilayah' => 'MTs FH lt 3', 'petugas' => 'Standby Operator', 'kabel' => '-'],
                ],
            ],
            [
                'titik' => 'Titik D',
                'korwil' => 'Hagana Idham Prissmana',
                'kabel' => 'Ach. Firmansyah & Elly Iqbal Aziz A',
                'items' => [
                    ['kode' => 'Proyektor 10', 'wilayah' => 'MA FH lt 2', 'petugas' => 'Khafidh Awalul Muslihun', 'kabel' => '-'],
                    ['kode' => 'Proyektor 11', 'wilayah' => 'MA FH lt 3', 'petugas' => 'Faizzy Irsyadul Ibad', 'kabel' => '-'],
                    ['kode' => 'Proyektor 12', 'wilayah' => 'Timur Gerbang FH PA', 'petugas' => 'M Yusuf', 'kabel' => '-'],
                    ['kode' => 'Proyektor 13', 'wilayah' => 'Timur Gerbang Al-Muhibbin', 'petugas' => 'Mubarok Ramadhani', 'kabel' => '-'],
                    ['kode' => 'Proyektor 14', 'wilayah' => 'Timur Bentowak', 'petugas' => 'Syahraihan Zaki', 'kabel' => '-'],
                ],
            ],
            [
                'titik' => 'Titik E',
                'korwil' => 'M. Jalaluddin',
                'kabel' => 'M. Favian Rizal & Athaka Ari Tian',
                'items' => [
                    ['kode' => 'Proyektor 17', 'wilayah' => 'Halaman Kampus', 'petugas' => 'Rizky Maulana', 'kabel' => '-'],
                    ['kode' => 'Proyektor 18', 'wilayah' => 'Timur Kantor Rektor', 'petugas' => 'M. Cleo Nendra Alfath', 'kabel' => '-'],
                    ['kode' => 'Proyektor 19', 'wilayah' => 'Depan Gudang Kampus', 'petugas' => 'Naufal Hanif', 'kabel' => '-'],
                    ['kode' => 'Proyektor 20', 'wilayah' => 'Halaman Barat Bu Mun', 'petugas' => 'Agam Zain', 'kabel' => '-'],
                    ['kode' => 'Proyektor 21', 'wilayah' => 'Halaman Timur Bu Mun', 'petugas' => 'Ridwan Saputra', 'kabel' => '-'],
                ],
            ],
            [
                'titik' => 'Titik F',
                'korwil' => 'Dwiki Candra Putra',
                'kabel' => 'M. Mufti Zuchaily & A. Bahrillah',
                'items' => [
                    ['kode' => 'Proyektor 22', 'wilayah' => 'Halaman FH PI', 'petugas' => 'Rahma Febriana', 'kabel' => '-'],
                    ['kode' => 'Proyektor 23', 'wilayah' => 'Halaman Al-Muhibbin Putri 2', 'petugas' => 'Bilqis Sayyidatin', 'kabel' => '-'],
                ],
            ],
        ];

        // 4. Jadwal Kerja Tim Dokumentasi (Fotografer & Videografer)
        $jadwalTim = [
            // Hari Rabu
            [
                'hari' => 'Rabu',
                'waktu' => 'Pagi',
                'momen' => 'Santunan Fakir Miskin',
                'wilayah' => 'Kanopi',
                'fotografer' => 'Diaz Nanda Ilyasa, M. Habib Fath',
                'videografer' => 'Abdul Hakim',
            ],
            [
                'hari' => 'Rabu',
                'waktu' => 'Pagi',
                'momen' => 'Masak-masak',
                'wilayah' => 'Cangkring',
                'fotografer' => 'Nabil Fatich',
                'videografer' => 'Salman',
            ],
            [
                'hari' => 'Rabu',
                'waktu' => 'Pagi',
                'momen' => 'Masak-masak',
                'wilayah' => 'Syafi\'i; Dapur Muhibbin',
                'fotografer' => 'Fitri Amalia Azzahra, Bilqis Sayyidatin',
                'videografer' => '-',
            ],
            [
                'hari' => 'Rabu',
                'waktu' => 'Pagi',
                'momen' => 'Masak-masak',
                'wilayah' => 'TPQ Al-Fattah',
                'fotografer' => 'M. Al-Ghifari',
                'videografer' => '-',
            ],
            [
                'hari' => 'Rabu',
                'waktu' => 'Malam',
                'momen' => 'ISHARI',
                'wilayah' => 'Masjid',
                'fotografer' => 'M. Nidhom Fahmi, M. Afwan Immamul Muttaqin',
                'videografer' => 'M. Syarifuddin',
            ],

            // Hari Kamis
            [
                'hari' => 'Kamis',
                'waktu' => 'Pagi',
                'momen' => 'Khotmil Quran',
                'wilayah' => 'Maqbarah Mbah Djamal',
                'fotografer' => 'Talita Tazkiyah',
                'videografer' => 'Mada Churriyatul Maghfiroh',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Pagi',
                'momen' => 'Khotmil Quran',
                'wilayah' => 'Maqbarah Mbah Fattah',
                'fotografer' => 'Rahma Febriana',
                'videografer' => 'Zahwa Fathimah',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Pagi',
                'momen' => 'Khotmil Quran',
                'wilayah' => 'Maqbarah Mbah Wahab',
                'fotografer' => 'Diaz Nanda Ilyasa',
                'videografer' => 'M. Nidhom Fahmi',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Pagi',
                'momen' => 'Khotmil Quran',
                'wilayah' => 'Maqbarah Mbah Usman & Mbah Hamid',
                'fotografer' => 'M. Al-Ghifari',
                'videografer' => '-',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Pagi',
                'momen' => 'Khotmil Quran',
                'wilayah' => 'Mushollah Al-Fattah & TPQ',
                'fotografer' => 'Nabil Fatich',
                'videografer' => 'Salman',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Siang',
                'momen' => 'Prasmanan',
                'wilayah' => 'FH Putra',
                'fotografer' => 'M. Shiroth Fahmi Kalamullah',
                'videografer' => '-',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Siang',
                'momen' => 'Back Up',
                'wilayah' => 'Kondisional Lapangan',
                'fotografer' => 'M. Al-Ghifari',
                'videografer' => '-',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Panggung & Halaman',
                'fotografer' => 'M. Nidhom Fahmi, Diaz Nanda Ilyasa',
                'videografer' => 'Abdul Hakim',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Depan Kantor Al-Muhibbin',
                'fotografer' => 'M. Afwan Immamul Muttaqin',
                'videografer' => '-',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Masjid dan Kanopi',
                'fotografer' => 'M. Al-Ghifari',
                'videografer' => 'M. Syarifuddin',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Kampus & Timur Kampus',
                'fotografer' => 'M. Habib Fath',
                'videografer' => '-',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'FH Putri, Mard 2 & Mhbn PI',
                'fotografer' => 'Fitri Amalia Azzahra',
                'videografer' => 'Maulida Khurriyyatul M.',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Back Up',
                'wilayah' => 'Kondisional Lapangan',
                'fotografer' => 'M. Shiroth Fahmi Kalamullah',
                'videografer' => '-',
            ],
        ];

        // 5. Jadwal Shooting Kamera Multicam
        $jadwalShooting = [
            [
                'hari' => 'Rabu',
                'waktu' => 'Pagi',
                'momen' => 'Santunan Fakir Miskin',
                'wilayah' => 'Masjid Al-Muhibbin',
                'shooting' => 'Tengah (Center)',
                'kameramen' => 'A. Akmalul Islam',
            ],
            [
                'hari' => 'Rabu',
                'waktu' => 'Malam',
                'momen' => 'ISHARI',
                'wilayah' => 'Masjid Al-Muhibbin',
                'shooting' => 'Tengah (Center)',
                'kameramen' => 'Azam Wahyu Mas\'udin',
            ],
            [
                'hari' => 'Rabu',
                'waktu' => 'Malam',
                'momen' => 'ISHARI',
                'wilayah' => 'Masjid Al-Muhibbin',
                'shooting' => 'Kanan (Right Wing)',
                'kameramen' => 'A. Akmalul Islam',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Halaman Al-Muhibbin',
                'shooting' => 'Tengah (Main Stage)',
                'kameramen' => 'M. Nasywan',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Halaman Al-Muhibbin',
                'shooting' => 'Kiri (Left Stage)',
                'kameramen' => 'A. Akmalul Islam',
            ],
            [
                'hari' => 'Kamis',
                'waktu' => 'Malam',
                'momen' => 'Pengajian Umum',
                'wilayah' => 'Halaman Al-Muhibbin',
                'shooting' => 'Kanan (Right Stage)',
                'kameramen' => 'Azam Wahyu Mas\'udin',
            ],
        ];

        $savedProyektor = Setting::get('proyektor_teams');
        if ($savedProyektor !== null) {
            $decoded = json_decode($savedProyektor, true);
            if (is_array($decoded)) {
                $proyektorTeams = $decoded;
            }
        }

        $savedJadwalTim = Setting::get('jadwal_tim');
        if ($savedJadwalTim !== null) {
            $decoded = json_decode($savedJadwalTim, true);
            if (is_array($decoded)) {
                $jadwalTim = $decoded;
            }
        }

        $savedJadwalShooting = Setting::get('jadwal_shooting');
        if ($savedJadwalShooting !== null) {
            $decoded = json_decode($savedJadwalShooting, true);
            if (is_array($decoded)) {
                $jadwalShooting = $decoded;
            }
        }

        // SOP Proyektor & Instruksi Teknis Jadwal
        $defaultProyektorSop = [
            [
                'title' => "1. Layar Mati / 'No Signal'",
                'desc' => "Periksa kabel HDMI/VGA dari splitter cabang. Jika kabel FO, pastikan konverter FO menyala indikator hijau.",
            ],
            [
                'title' => "2. Gambar Kedip / Flicker",
                'desc' => "Ganti kabel extender atau turunkan resolusi output switcher di tenda pusat menjadi 1080i / 720p.",
            ],
            [
                'title' => "3. Kontak Darurat Koordinator",
                'desc' => "Hubungi Koordinator Proyektor melalui handy talky (HT) Channel 3 Media atau Posko Pusat.",
            ],
        ];
        $savedProyektorSop = Setting::get('proyektor_sop');
        $proyektorSop = $defaultProyektorSop;
        if ($savedProyektorSop !== null) {
            $decoded = json_decode($savedProyektorSop, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $proyektorSop = $decoded;
            }
        }

        $defaultJadwalInstruksi = [
            [
                'title' => "1. Kehadiran & Koordinasi",
                'desc' => "Seluruh personil wajib hadir di Posko Media 30 menit sebelum sesi kegiatan dimulai untuk pengecekan baterai, kartu memori, dan briefing.",
            ],
            [
                'title' => "2. Penyetoran Bahan / Footage",
                'desc' => "Selesai tiap sesi, memory card langsung diserahkan kepada tim editing/ingest di kantor media untuk backup data instan.",
            ],
            [
                'title' => "3. Live Streaming & Multicam",
                'desc' => "Kameramen multicam memastikan komunikasi intercom terhubung aktif dan mematuhi aba-aba Switcher Director.",
            ],
        ];
        $savedJadwalInstruksi = Setting::get('jadwal_instruksi');
        $jadwalInstruksi = $defaultJadwalInstruksi;
        if ($savedJadwalInstruksi !== null) {
            $decoded = json_decode($savedJadwalInstruksi, true);
            if (is_array($decoded) && count($decoded) > 0) {
                $jadwalInstruksi = $decoded;
            }
        }

        // 6. Calendar Events (Vristo Style)
        $savedCalendar = Setting::get('calendar_events');
        $calendarEvents = $savedCalendar ? json_decode($savedCalendar, true) : null;
        if (!is_array($calendarEvents) || count($calendarEvents) === 0) {
            $nowYearMonth = date('Y-m');
            $calendarEvents = [
                [
                    'id' => 'evt-1',
                    'title' => 'Pemasangan Jalur Kabel FO & Display Proyektor',
                    'date' => $nowYearMonth . '-15',
                    'time_start' => '08:00',
                    'time_end' => '16:30',
                    'type' => 'warning',
                    'location' => 'Seluruh Komplek Pondok Al-Muhibbin',
                    'pic' => 'Tim Logistik & Kabel',
                    'description' => 'Penarikan kabel fiber optik, setting switch hub, dan penempatan proyektor titik A-F.'
                ],
                [
                    'id' => 'evt-2',
                    'title' => 'Rapat Koordinasi & Briefing Koordinator Posko',
                    'date' => $nowYearMonth . '-20',
                    'time_start' => '19:30',
                    'time_end' => '22:00',
                    'type' => 'success',
                    'location' => 'Aula Pertemuan Al-Muhibbin',
                    'pic' => 'BPH & Ketua Panitia',
                    'description' => 'Evaluasi persiapan akhir seluruh divisi panitia Haul dan Rojabiyyah 2026.'
                ],
                [
                    'id' => 'evt-3',
                    'title' => 'Santunan Fakir Miskin & Bakti Sosial Jamaah',
                    'date' => $nowYearMonth . '-24',
                    'time_start' => '08:00',
                    'time_end' => '11:30',
                    'type' => 'primary',
                    'location' => 'Halaman Masjid & Kanopi',
                    'pic' => 'Sie Acara & Baksos',
                    'description' => 'Penyaluran sembako dan santunan berkah kepada masyarakat dhuafa sekitar pondok.'
                ],
                [
                    'id' => 'evt-4',
                    'title' => 'Gladi Bersih Multicam & Display Operator',
                    'date' => $nowYearMonth . '-25',
                    'time_start' => '14:00',
                    'time_end' => '17:30',
                    'type' => 'info',
                    'location' => 'Panggung Utama & Pos Operator',
                    'pic' => 'Divisi Multimedia & Dokumentasi',
                    'description' => 'Uji coba penangkapan sinyal 3 kamera multicam, sinkronisasi audio, dan feed proyektor.'
                ],
                [
                    'id' => 'evt-5',
                    'title' => 'Malam Peringatan Haul Akbar & Pengajian Umum',
                    'date' => $nowYearMonth . '-26',
                    'time_start' => '19:00',
                    'time_end' => '23:45',
                    'type' => 'danger',
                    'location' => 'Halaman Utama Ponpes Al-Muhibbin',
                    'pic' => 'Seluruh Panitia Haul',
                    'description' => 'Puncak acara Haul Ke-5 Masyayikh dihadiri masyaikh, habaib, santri, dan ribuan jamaah.'
                ],
            ];
        }

        return response()->json([
            'stats' => [
                'total_volunteers' => $totalMembers,
                'total_regions' => $totalRegions,
                'total_items' => $totalItems,
            ],
            'regions' => $regions,
            'sliders' => $sliders,
            'denah_photos' => $denahPhotos,
            'jadwal_photos' => $jadwalPhotos,
            'proyektor_photos' => $proyektorPhotos,
            'proyektor_teams' => $proyektorTeams,
            'proyektor_sop' => $proyektorSop,
            'jadwal_tim' => $jadwalTim,
            'jadwal_shooting' => $jadwalShooting,
            'jadwal_instruksi' => $jadwalInstruksi,
            'calendar_events' => $calendarEvents,
            'field_reports' => FieldReport::with(['region:id,name,code', 'reporter:id,name', 'reporter.member.division:id,name', 'media', 'followups.user:id,name'])
                ->orderBy('created_at', 'desc')
                ->take(12)
                ->get(),
            'event' => [
                'title' => Setting::get('sub_theme', 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026'),
                'theme' => 'Meneladani Jejak Perjuangan, Menjaga Sanad Keilmuan & Mempererat Ukhuwah Islamiyyah',
                'location' => 'Pondok Pesantren Al-Muhibbin, Tambakberas, Jombang, Jawa Timur',
                'target_date' => '2026-01-26 19:30:00',
            ],
        ]);
    }

    public function uploadDenah(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required',
        ]);

        $folder = public_path('uploads/denah');
        if (!File::isDirectory($folder)) {
            File::makeDirectory($folder, 0755, true, true);
        }

        $saved = Setting::get('denah_photos');
        $existing = $saved ? json_decode($saved, true) : [];
        if (!is_array($existing)) $existing = [];

        foreach ($request->photos as $photoData) {
            if (is_string($photoData) && str_starts_with($photoData, 'data:image')) {
                // Base64
                $ext = 'jpg';
                if (str_contains($photoData, 'image/png')) $ext = 'png';
                elseif (str_contains($photoData, 'image/webp')) $ext = 'webp';

                $cleaned = preg_replace('/^data:image\/\w+;base64,/', '', $photoData);
                $decoded = base64_decode($cleaned);
                $filename = 'denah_' . date('Ymd_His') . '_' . Str::random(6) . '.' . $ext;
                file_put_contents($folder . '/' . $filename, $decoded);
                $existing[] = '/uploads/denah/' . $filename;
            } elseif ($request->hasFile('photos')) {
                // Uploaded files
                foreach ($request->file('photos') as $file) {
                    $filename = 'denah_' . date('Ymd_His') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
                    $file->move($folder, $filename);
                    $existing[] = '/uploads/denah/' . $filename;
                }
                break;
            }
        }

        Setting::set('denah_photos', json_encode(array_values(array_unique($existing))));

        return response()->json([
            'message' => 'Foto denah proyektor berhasil diunggah.',
            'denah_photos' => array_values(array_unique($existing)),
        ]);
    }

    public function deleteDenah(Request $request)
    {
        $request->validate(['index' => 'required|integer']);
        $saved = Setting::get('denah_photos');
        $existing = $saved ? json_decode($saved, true) : [];
        if (is_array($existing) && isset($existing[$request->index])) {
            array_splice($existing, $request->index, 1);
            Setting::set('denah_photos', json_encode(array_values($existing)));
        }

        return response()->json([
            'message' => 'Foto denah berhasil dihapus.',
            'denah_photos' => array_values($existing),
        ]);
    }

    public function uploadJadwal(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required',
        ]);

        $folder = public_path('uploads/jadwal');
        if (!File::isDirectory($folder)) {
            File::makeDirectory($folder, 0755, true, true);
        }

        $saved = Setting::get('jadwal_photos');
        $existing = $saved ? json_decode($saved, true) : [];
        if (!is_array($existing)) $existing = [];

        foreach ($request->photos as $photoData) {
            if (is_string($photoData) && str_starts_with($photoData, 'data:image')) {
                $ext = 'jpg';
                if (str_contains($photoData, 'image/png')) $ext = 'png';
                elseif (str_contains($photoData, 'image/webp')) $ext = 'webp';

                $cleaned = preg_replace('/^data:image\/\w+;base64,/', '', $photoData);
                $decoded = base64_decode($cleaned);
                $filename = 'jadwal_' . date('Ymd_His') . '_' . Str::random(6) . '.' . $ext;
                file_put_contents($folder . '/' . $filename, $decoded);
                $existing[] = '/uploads/jadwal/' . $filename;
            }
        }

        Setting::set('jadwal_photos', json_encode(array_values(array_unique($existing))));

        return response()->json([
            'message' => 'Foto jadwal kerja berhasil diunggah.',
            'jadwal_photos' => array_values(array_unique($existing)),
        ]);
    }

    public function deleteJadwal(Request $request)
    {
        $request->validate(['index' => 'required|integer']);
        $saved = Setting::get('jadwal_photos');
        $existing = $saved ? json_decode($saved, true) : [];
        if (is_array($existing) && isset($existing[$request->index])) {
            array_splice($existing, $request->index, 1);
            Setting::set('jadwal_photos', json_encode(array_values($existing)));
        }

        return response()->json([
            'message' => 'Foto jadwal berhasil dihapus.',
            'jadwal_photos' => array_values($existing),
        ]);
    }

    public function uploadProyektorPhotos(Request $request)
    {
        $request->validate([
            'photos' => 'required|array',
            'photos.*' => 'required',
        ]);

        $folder = public_path('uploads/proyektor');
        if (!File::isDirectory($folder)) {
            File::makeDirectory($folder, 0755, true, true);
        }

        $saved = Setting::get('proyektor_photos');
        $existing = $saved ? json_decode($saved, true) : [];
        if (!is_array($existing)) $existing = [];

        foreach ($request->photos as $photoData) {
            if (is_string($photoData) && str_starts_with($photoData, 'data:image')) {
                $ext = 'jpg';
                if (str_contains($photoData, 'image/png')) $ext = 'png';
                elseif (str_contains($photoData, 'image/webp')) $ext = 'webp';

                $cleaned = preg_replace('/^data:image\/\w+;base64,/', '', $photoData);
                $decoded = base64_decode($cleaned);
                $filename = 'proyektor_' . date('Ymd_His') . '_' . Str::random(6) . '.' . $ext;
                file_put_contents($folder . '/' . $filename, $decoded);
                $existing[] = '/uploads/proyektor/' . $filename;
            }
        }

        Setting::set('proyektor_photos', json_encode(array_values(array_unique($existing))));

        return response()->json([
            'message' => 'Foto denah/skema proyektor berhasil diunggah.',
            'proyektor_photos' => array_values(array_unique($existing)),
        ]);
    }

    public function deleteProyektorPhoto(Request $request)
    {
        $request->validate(['index' => 'required|integer']);
        $saved = Setting::get('proyektor_photos');
        $existing = $saved ? json_decode($saved, true) : [];
        if (is_array($existing) && isset($existing[$request->index])) {
            array_splice($existing, $request->index, 1);
            Setting::set('proyektor_photos', json_encode(array_values($existing)));
        }

        return response()->json([
            'message' => 'Foto skema proyektor berhasil dihapus.',
            'proyektor_photos' => array_values($existing),
        ]);
    }

    public function saveProyektorTeams(Request $request)
    {
        if ($request->has('teams')) {
            $request->validate(['teams' => 'required|array']);
            Setting::set('proyektor_teams', json_encode($request->teams));
        }
        if ($request->has('sop')) {
            $request->validate(['sop' => 'required|array']);
            Setting::set('proyektor_sop', json_encode($request->sop));
        }

        return response()->json([
            'message' => 'Data pembagian tim & SOP proyektor berhasil disimpan.',
            'proyektor_teams' => $request->teams ?? json_decode(Setting::get('proyektor_teams', '[]'), true),
            'proyektor_sop' => $request->sop ?? json_decode(Setting::get('proyektor_sop', '[]'), true),
        ]);
    }

    public function saveJadwalTim(Request $request)
    {
        if ($request->has('jadwal_tim')) {
            Setting::set('jadwal_tim', json_encode($request->jadwal_tim));
        }
        if ($request->has('jadwal_shooting')) {
            Setting::set('jadwal_shooting', json_encode($request->jadwal_shooting));
        }
        if ($request->has('instruksi')) {
            $request->validate(['instruksi' => 'required|array']);
            Setting::set('jadwal_instruksi', json_encode($request->instruksi));
        }

        return response()->json([
            'message' => 'Jadwal kerja & instruksi teknis tim berhasil diperbarui.',
            'jadwal_instruksi' => $request->instruksi ?? json_decode(Setting::get('jadwal_instruksi', '[]'), true),
        ]);
    }

    public function saveHeroSettings(Request $request)
    {
        $fields = [
            'hero_title_white',
            'hero_title_gradient',
            'sub_theme',
            'hero_subtitle',
            'hero_desc',
            'wa_contact_url',
        ];
        foreach ($fields as $f) {
            if ($request->has($f)) {
                Setting::set($f, $request->input($f));
            }
        }

        return response()->json([
            'message' => 'Pengaturan teks halaman utama berhasil disimpan.',
        ]);
    }

    public function saveCalendarEvents(Request $request)
    {
        $request->validate([
            'events' => 'required|array',
        ]);
        Setting::set('calendar_events', json_encode($request->events));

        return response()->json([
            'message' => 'Jadwal kalender agenda berhasil diperbarui dan sinkron dengan halaman utama.',
            'calendar_events' => $request->events,
        ]);
    }
}

