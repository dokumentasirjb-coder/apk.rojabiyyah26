# Aplikasi Web Administrasi Rojabiyyah 2026

Aplikasi Web Terpadu untuk Manajemen Operasional Kepanitiaan Skala Besar (Haul Masyayikh & Peringatan Isro' Mi'roj 1447 H / 2026 M), dibangun penuh sesuai spesifikasi [PRD.md](PRD.md).

---

## 🛠️ Arsitektur & Teknologi

- **Backend API**: Laravel LTS (PHP 8.3)
  - Autentikasi: Laravel Sanctum (Token & SPA Stateful Mode)
  - Otorisasi: Role-Based Access Control (RBAC 6 Peran)
  - Dokumen Resmi: Barryvdh DomPDF + SimpleSoftwareIO QrCode (SVG)
  - Impor & Ekspor: Maatwebsite Excel + PhpSpreadsheet
- **Frontend SPA**: Vue.js 3 (Vite)
  - State Management: Pinia
  - Routing: Vue Router 4 (Guards & Permissions)
  - Styling: Tailwind CSS (Mobile-First, Tema Emerald/Gold/Slate)
  - Icons: Lucide Icons
- **Database**: SQLite (Development) / MySQL 8 (Production ready lewat migrations & seeders)

---

## 👥 Akun Akses Pengujian (Demo Login)

Semua akun menggunakan password awal: `password123`

| Peran | Login (Email / ID Anggota) | Password | Hak Akses Utama |
|---|---|---|---|
| **Super Admin** | `superadmin@rojabiyyah.id` / `RJB-001` | `password123` | Akses penuh seluruh sistem, Danger Zone, Pengaturan Branding |
| **Admin Operasional** | `admin@rojabiyyah.id` / `RJB-002` | `password123` | Kelola Anggota, Impor Excel, Inventaris, Approval Peminjaman |
| **Bendahara** | `bendahara@rojabiyyah.id` / `RJB-003` | `password123` | Modul Kas Masuk/Keluar, Void Transaksi, Summary & Ekspor |
| **Ketua Divisi** | `multimedia@rojabiyyah.id` / `RJB-004` | `password123` | Peminjaman atas nama divisi, pantau anggota divisi |
| **PIC Posko** | `poskopanggung@rojabiyyah.id` / `RJB-005` | `password123` | Pelaporan lapangan kendala posko & input respon solusi |
| **Anggota** | `anggota@rojabiyyah.id` / `RJB-006` | `password123` | Ajukan peminjaman, katalog, lihat penugasan posko |

---

## 🚀 Cara Menjalankan Aplikasi

### Cara 1: Sekali Klik (Windows)
Cukup jalankan file:
```cmd
run-all.bat
```
Script otomatis membuka dua jendela terminal:
1. **Backend Laravel API** di `http://localhost:8000`
2. **Frontend Vue 3 SPA** di `http://localhost:5173`

Buka browser Anda di: **`http://localhost:5173`**

---

### Cara 2: Manual Terminal

**1. Menjalankan Backend:**
```powershell
$env:PATH = "C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64;C:\composer;$env:PATH"
cd backend
php artisan serve --port=8000
```

**2. Menjalankan Frontend:**
```powershell
cd frontend
npm run dev
```

---

## ✨ Fitur-Fitur Utama Sesuai PRD

1. **Dual-Credential Login & Wajib Ganti Password**:
   - Login mendukung format Email atau ID Anggota (`RJB-xxx`).
   - Akun baru hasil impor atau anggota baru yang memiliki status `must_change_password` wajib mengganti password saat pertama kali login.

2. **Data Anggota & Impor Massal Cerdas**:
   - Impor format `.xlsx`, `.xls`, `.csv`.
   - Pemetaan kolom: `NO, NAMA, DEV, PENDIDIKAN, KELAS, DOMISILI, SERAGAM, NO WA`.
   - Pratinjau validasi baris, deteksi duplikat nomor WA, penomoran urut otomatis (`RJB-001`, `RJB-002`, dst.), dan akun login otomatis.

3. **Inventaris Internal & Pinjaman Mitra**:
   - Manajemen aset internal dan barang pinjaman mitra (dengan nama instansi, kontak PIC, dan jatuh tempo pengembalian).
   - Perhitungan otomatis stok: *Tersedia = Total - Reserved - Rusak* (anti-minus).

4. **Sirkulasi Peminjaman & Surat Bukti PDF Ber-QR**:
   - Alur status lengkap: `Diajukan -> Disetujui -> Diserahkan -> Dikembalikan` (serta cabang `Ditolak`, `Dibatalkan`, `Dikembalikan Sebagian`, `Hilang/Rusak`).
   - Surat Bukti Peminjaman (PDF) resmi berformat kop panitia dengan barcode QR verifikasi publik.
   - Halaman publik verifikasi keaslian surat: `/verify-loan/:code`.

5. **Kontrol Wilayah, Posko & Pelaporan Lapangan**:
   - Manajemen posko (Panggung Utama, Live Streaming, Keamanan, Medis, dll).
   - Penugasan personil panitia ke posko.
   - Pelaporan status realtime (`Normal`, `Perlu Perhatian`, `Kendala/Rusak`) dengan tindak lanjut timeline solusi (`Open -> In Progress -> Resolved`).

6. **Rekap Kas Keuangan & Integritas Data**:
   - Pencatatan transaksi kas masuk & keluar, bukti nota pembayaran, kategori transaksi.
   - Tanpa penghapusan permanen (menggunakan mekanisme `void` / jurnal balik disertai alasan wajib).
   - Ringkasan kas dan ekspor CSV.

7. **Pusat Pembersihan Data (Danger Zone)**:
   - Khusus Super Admin dengan verifikasi password dan frasa wajib: `RESET OPERASIONAL`.
   - **Backup Otomatis (Snapshot DB)** dibuat sebelum penghapusan data.
   - Pilihan modul reset (Peminjaman, Laporan, Kas, Penugasan).
   - Data akun admin, master kategori inventaris, dan **Audit Log** tetap terlindungi aman.

8. **Audit Log Menyeluruh**:
   - Seluruh aksi login, penambahan data, perubahan, approval, serah terima, void kas, dan reset operasional tercatat rapi beserta IP pengguna dan timestamp.

