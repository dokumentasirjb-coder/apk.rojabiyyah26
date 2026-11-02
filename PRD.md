# PRD: Aplikasi Web Administrasi Rojabiyyah 2026

**Versi Dokumen:** 3.0 (Revisi Komprehensif Integrasi Fitur & Pra-Hosting / Deployment)  
**Tumpukan Teknologi:** Laravel 11 (RESTful API) + Vue.js 3 SPA (Vite + Pinia + TailwindCSS) + MySQL 8  
**Bahasa Antarmuka:** Bahasa Indonesia  
**Lisensi & Hak Cipta:** © 2026 Panitia Media Haul Ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026, Pondok Pesantren Al-Muhibbin Tambakberas Jombang.

---

## Daftar Isi

1. [Ringkasan Eksekutif & Identitas Sistem](#1-ringkasan-eksekutif--identitas-sistem)
2. [Arsitektur, Tumpukan Teknologi & Standar Keamanan](#2-arsitektur-tumpukan-teknologi--standar-keamanan)
3. [Peran Pengguna & Matriks Hak Akses (RBAC)](#3-peran-pengguna--matriks-hak-akses-rbac)
4. [Kebutuhan Fungsional & Spesifikasi Fitur Terintegrasi](#4-kebutuhan-fungsional--spesifikasi-fitur-terintegrasi)
   - [4.1 Portal Login Vristo Auth-Cover & Registrasi Mandiri 2 Jalur](#41-portal-login-vristo-auth-cover--registrasi-mandiri-2-jalur)
   - [4.2 Data Anggota, Impor Spreadsheet & Otomatisasi Akun](#42-data-anggota-impor-spreadsheet--otomatisasi-akun)
   - [4.3 Pemisahan Inventaris Internal vs. Pinjaman Lembaga Mitra](#43-pemisahan-inventaris-internal-vs-pinjaman-lembaga-mitra)
   - [4.4 Sirkulasi Peminjaman & Surat Bukti Peminjaman (PDF + QR)](#44-sirkulasi-peminjaman--surat-bukti-peminjaman-pdf--qr)
   - [4.5 Tugas Anggota, Titik Posko & Pelaporan Lapangan Realtime](#45-tugas-anggota-titik-posko--pelaporan-lapangan-realtime)
   - [4.6 Publikasi Beranda Publik & Laporan Lapangan (Read-Only)](#46-publikasi-beranda-publik--laporan-lapangan-read-only)
   - [4.7 Rekapitulasi Kas Keuangan & Proteksi Sandi Kas](#47-rekapitulasi-kas-keuangan--proteksi-sandi-kas)
   - [4.8 Pengaturan Sistem Dinamis & Pengelolaan Halaman Utama](#48-pengaturan-sistem-dinamis--pengelolaan-halaman-utama)
   - [4.9 Audit Log & Pusat Pembersihan Data (Danger Zone)](#49-audit-log--pusat-pembersihan-data-danger-zone)
5. [Rancangan Basis Data (Database Schema)](#5-rancangan-basis-data-database-schema)
6. [Daftar Endpoint API Lengkap (`/api/v1`)](#6-daftar-endpoint-api-lengkap-apiv1)
7. [Checklist Kesiapan Hosting & Deployment Pra-Rilis](#7-checklist-kesiapan-hosting--deployment-pra-rilis)
8. [Riwayat Perubahan Dokumen (Changelog)](#8-riwayat-perubahan-dokumen-changelog)

---

## 1. Ringkasan Eksekutif & Identitas Sistem

**Administrasi Rojabiyyah 2026** adalah sistem informasi manajemen dan operasional terpadu yang dirancang khusus untuk mendukung kelancaran perhelatan akbar Haul Ke-5 Masyayikh & Peringatan Isra' Mi'raj (Rojabiyyah 2026) di Pondok Pesantren Al-Muhibbin Tambakberas Jombang.

Sistem ini mentransformasikan birokrasi lapangan manual menjadi alur kerja digital yang transparan, akuntabel, dan realtime, mencakup:
- **Pemisahan Pengelolaan Logistik:** Memisahkan secara ketat aset inventaris internal milik panitia sendiri dengan barang pinjaman dari lembaga/pesantren mitra luar, namun tetap menyatukannya dalam katalog peminjaman yang mudah diakses anggota.
- **Sistem Komando & Tugas Lapangan:** Pembagian pos teknis (Proyektor, Fotografer, Videografer, Perkabelan) dan pemantauan kondisi pos secara langsung dengan publikasi transparan ke halaman beranda publik.
- **Portal Otentikasi Vristo:** Antarmuka masuk petugas modern berskala enterprise dengan visual kontras tinggi, proteksi keamanan berlapis, dan 2 jalur registrasi mandiri.
- **Pusat Rekap Kas LPJ:** Pencatatan arus keuangan kas dengan verifikasi kata sandi tambahan khusus bendahara dan super admin.
- **Transparansi Informasi Publik:** Beranda publik dinamis yang menyajikan hitung mundur acara, slider dokumentasi, denah proyektor interaktif, kalender acara, jadwal kerja tim, dan papan pantau laporan lapangan *read-only*.

---

## 2. Arsitektur, Tumpukan Teknologi & Standar Keamanan

### 2.1 Arsitektur Sistem (Decoupled Single Page Application)
```
[ Klien / Browser Web (Desktop & Mobile) ]
                  │
                  ▼ HTTPS
[ Web Server (Nginx / Apache Reverse Proxy) ]
         │                               │
         ▼                               ▼
[ Frontend SPA (Vue 3 + Vite) ]   [ REST API Engine (Laravel 11) ]
                                                 │
                                                 ▼
                                        [ Basis Data MySQL 8 ]
                                        [ File Storage / Uploads ]
```

### 2.2 Tumpukan Teknologi (Technology Stack)
| Komponen | Teknologi | Keterangan |
|---|---|---|
| **Backend Framework** | Laravel 11.x (PHP 8.2+) | RESTful JSON API, Policy & Gate Authorization, Database Transactions |
| **Autentikasi API** | Laravel Sanctum | Bearer Token / Personal Access Token, Single-Session Revocation |
| **Frontend Framework** | Vue.js 3 (Composition API `<script setup>`) | Single Page Application (SPA), Vue Router 4, Pinia State Management |
| **Peralatan Build & Bundler** | Vite 8 + Rolldown | HMR instan, kompilasi aset cepat, split chunking |
| **CSS & Desain UI** | TailwindCSS 3 + Kustom Vristo Theme | Responsif *mobile-first*, High-Contrast Emerald Palette, Glassmorphism & SVG |
| **Basis Data** | MySQL 8.x / MariaDB 10.6+ | InnoDB Engine, Foreign Key Strict Integrity, Fulltext & Date Indexing |
| **Pembangkit Dokumen** | Barryvdh Laravel DomPDF / Snappy | Penerbitan Surat Bukti Peminjaman resmi ber-QR Code |
| **Pengolahan Berkas** | Intervention Image / GD Library | Kompresi foto bukti otomatis, pengacakan nama berkas unik |

### 2.3 Standar Keamanan Sistem
1. **Proteksi Kredensial:** Hash kata sandi menggunakan algoritma Bcrypt bawaan Laravel. Akun yang baru didaftarkan atau direset wajib mengubah kata sandi pada login pertama (`must_change_password`).
2. **Otorisasi Multi-Lapis:** Middleware `auth:sanctum` dipadukan dengan pemeriksaan peran dinamis pada tingkat rute backend dan penjaga navigasi (*navigation guard*) Vue Router.
3. **Proteksi Akses Modul Kas:** Akses modul keuangan kas dilindungi oleh lapis otentikasi ganda berupa *master cash password* (`/cash/verify-password`) yang hanya dimiliki oleh Super Admin dan Bendahara.
4. **Keamanan Berkas Unggahan:** Validasi MIME type ketat untuk gambar (JPEG, PNG, WEBP maks. 5MB) dan dokumen spreadsheet (XLSX, CSV maks. 5MB). File disimpan dengan nama acak bersandi (*hashed filename*).
5. **Jejak Audit (Audit Logging):** Seluruh mutasi kritis (Login, Penambahan/Perubahan Anggota, Sirkulasi Peminjaman, Mutasi Kas, Reset Data) dicatat secara terperinci beserta identitas aktor, IP Address, dan perubahan nilai data (*before-after values*).

---

## 3. Peran Pengguna & Matriks Hak Akses (RBAC)

Aplikasi menerapkan sistem hak akses berbasis peran berjenjang (*Role-Based Access Control*):

| Peran (*Role*) | Deskripsi Singkat & Tanggung Jawab |
|---|---|
| **Super Admin** | Pemilik sistem tertinggi. Memiliki akses menyeluruh, manajemen akun pengelola admin, kustomisasi pengaturan identitas, audit log, dan eksekusi Pusat Pembersihan Data (*Danger Zone*). |
| **Admin Operasional** | Pengelola logistik lapangan, manajemen anggota dan divisi, sirkulasi peminjaman, titik posko tugas, pengelolaan konten beranda utama, dan verifikasi laporan lapangan. |
| **Bendahara** | Pengelola pembukuan kas kepanitiaan. Memiliki hak penuh atas modul Rekap Uang, pencatatan transaksi masuk/keluar, lampiran nota LPJ, dan ekspor keuangan. |
| **Anggota / Petugas Lapangan** | Anggota relawan kepanitiaan. Memiliki akses portal mandiri: katalog inventaris internal & pinjaman mitra, permohonan pinjam alat, jadwal pos tugas sendiri, dan pengiriman laporan lapangan langsung dari posko. |

### Matriks Hak Akses Modul
| Modul / Fitur | Super Admin | Admin Operasional | Bendahara | Anggota Lapangan | Tamu / Publik |
|---|:---:|:---:|:---:|:---:|:---:|
| **Beranda Utama Publik & Landing Page** | R | R | R | R | R |
| **Portal Login Vristo (`/login`)** | Akses | Akses | Akses | Akses | Akses |
| **Registrasi Mandiri (Akun & Form)** | CRUD | CRUD | R | CRUD | Buat Mandiri |
| **Data Anggota & Impor Excel** | CRUDX | CRUDX | – | R (Profil) | – |
| **Divisi Tugas Panitia** | CRUD | CRUD | – | R | – |
| **Inventaris Internal (Milik Sendiri)** | CRUDX | CRUDX | R | R (Katalog) | – |
| **Pinjaman Lembaga Mitra** | CRUDX | CRUDX | R | R (Katalog) | – |
| **Sirkulasi Peminjaman (Approval)** | CRUD | CRUD | – | Ajukan & R | – |
| **Unduh & Verifikasi Surat PDF (QR)** | Cetak | Cetak | – | Cetak Milik | Verifikasi QR |
| **Titik Tugas & Posko Lapangan** | CRUD | CRUD | – | R (Tugas Saya) | – |
| **Laporan Lapangan (Kirim & Tinjau)** | CRUD | CRUD | – | Buat & R | R (Beranda) |
| **Rekap Uang & Kas (Verifikasi PIN)** | CRUDX | – | CRUDX | – | – |
| **Slider Beranda & Landing Manager** | CRUD | CRUD | – | – | R (Publik) |
| **Pengaturan Sistem & Branding** | CRUD | R | – | – | – |
| **Pusat Pembersihan Data (Danger Zone)** | Eksekusi | – | – | – | – |
| **Audit Logs** | R | R | – | – | – |

*Keterangan: **C** (Create/Buat), **R** (Read/Lihat), **U** (Update/Ubah), **D** (Delete/Hapus), **X** (Export/Unduh Data).*

---

## 4. Kebutuhan Fungsional & Spesifikasi Fitur Terintegrasi

### 4.1 Portal Login Vristo Auth-Cover & Registrasi Mandiri 2 Jalur
1. **Tata Letak Split Cover Card (Vristo Model):**
   - Halaman `/login` menggunakan desain split-card kontras tinggi dengan batas diagonal miring (*slanted polygon clip-path*) pada layar lebar.
   - Panel kiri (*Cover Side*): Latar gradien malam *Deep Emerald Night & Dark Navy* (`#022c22` &rarr; `#064e3b` &rarr; `#1e1b4b`), menampilkan logo resmi dinamis dari database, teks nama aplikasi & sub-judul acara, serta kartu ilustrasi vektor autentikasi keamanan dengan lencana perisai melayang (*floating security shield badge*).
   - Panel kanan (*Form Side*): Latar putih bersih dengan judul `SIGN IN`, input email/username dengan ikon, input kata sandi dengan ikon gembok dan tombol intip (*eye-toggle*), checkbox *"Ingat saya di perangkat ini"*, tombol submit bergradien emerald, garis pembatas *"OR"*, tombol isi-otomatis 1-klik untuk akun demo Super Admin & Anggota, serta tautan kembali ke Beranda Utama.
2. **Dual-Path Registrasi Publik:**
   - **Formulir Pendaftaran Panitia / Relawan (`/register-member`):** Khusus pendataan anggota baru tanpa mewajibkan kepemilikan akun login (nama lengkap, divisi, pendidikan, kelas, domisili, nomor WhatsApp, ukuran seragam panitia).
   - **Pendaftaran Akun Login Anggota (`/register-account`):** Khusus anggota yang ingin membuat akun login mandiri ke portal dengan menentukan username dan kata sandi sendiri.

### 4.2 Data Anggota, Impor Spreadsheet & Otomatisasi Akun
- **Pencatatan Lengkap:** Mengelola nama, nomor anggota unik (format otomatis: `RJB-001`, `RJB-002`, dst.), divisi kepanitiaan, kontak WhatsApp, ukuran seragam, dan domisili.
- **Pintasan WhatsApp Instan:** Tombol langsung membuka WhatsApp chat untuk koordinasi cepat antar petugas.
- **Impor Massal Excel/CSV:** Modal impor dengan pratinjau data valid dan deteksi anomali baris sebelum data dimasukkan ke basis data.
- **Generator Akun Otomatis:** Pembuatan akun login otomatis dari data anggota dengan kata sandi acak dan penanda wajib ganti sandi pada saat pertama kali login.

### 4.3 Pemisahan Inventaris Internal vs. Pinjaman Lembaga Mitra
1. **Menu Data Inventaris Internal (`/inventory`):**
   - Dikhususkan untuk aset milik resmi kepanitiaan Rojabiyyah / Pondok Al-Muhibbin.
   - Pelacakan stok: Stok Total, Stok Tersedia, Stok Sedang Dipinjam, dan Stok Rusak.
   - Manajemen kategori, kondisi barang (Baik / Rusak Ringan / Rusak Berat), lokasi penyimpanan, dan foto fisik barang.
2. **Menu Pinjaman Lembaga Mitra (`/partner-loans`):**
   - Dikhususkan untuk barang/alat yang dipinjam dari pondok pesantren mitra atau instansi luar.
   - Data mitra: Nama instansi/pesantren pemilik, narahubung (PIC mitra), nomor telepon mitra, dan tanggal batas wajib pengembalian ke mitra (*return due date*).
   - Ekspor data barang pinjaman mitra ke format Excel untuk kebutuhan rekonsiliasi pengembalian pasca-acara.
3. **Integrasi Katalog Peminjaman Anggota:**
   - Baik barang internal maupun barang mitra luar secara otomatis muncul dalam katalog peminjaman yang dapat diakses oleh anggota, dilengkapi dengan lencana penanda (*badge*) yang membedakan aset milik sendiri vs. barang pinjaman mitra.

### 4.4 Sirkulasi Peminjaman & Surat Bukti Peminjaman (PDF + QR)
- **Alur Sirkulasi Barang:**  
  `Diajukan (Pending) → Disetujui (Approved) → Diserahkan (Handed Over) → Dikembalikan (Returned)`  
  *(Dengan status alternatif: Ditolak / Dibatalkan).*
- **Reservasi Stok Otomatis:** Saat permohonan disetujui, kuantitas stok barang langsung dikunci (*reserved*) agar tidak terjadi peminjaman ganda (*double booking*).
- **Surat Bukti Peminjaman Resmi (PDF):**
  - Dilengkapi kop resmi panitia, nomor surat unik, daftar unit barang yang dipinjam, identitas peminjam, tanggal pinjam/kembali, dan kolom tanda tangan basah.
  - Memuat **Kode QR Verifikasi** yang dapat dipindai langsung oleh kamera untuk membuka halaman validasi publik (`/verify-loan/:code`).

### 4.5 Tugas Anggota, Titik Posko & Pelaporan Lapangan Realtime
- **Titik Tugas & Posko Lapangan (`/regions`):**
  - Penetapan pos teknis utama: Posko Proyektor (KJA, Panggung Utama, Asrama), Fotografer, Videografer, Perkabelan, Sound System, dan Posko Keamanan.
  - Penetapan Penanggung Jawab Pos (PIC) dan jadwal jam tugas anggota.
  - **Banner Prioritas "Tugas Saya":** Menampilkan kartu khusus berwarna emas di bagian atas jika akun anggota yang login sedang ditugaskan pada pos tersebut, lengkap dengan tombol langsung *"Lapor Kondisi Pos"*.
- **Laporan Lapangan Anggota (`/reports`):**
  - Petugas pos dapat mengirimkan laporan kondisi posko secara berkala dengan 3 status: **Normal (Hijau)**, **Perlu Perhatian (Kuning)**, atau **Rusak / Kendala Teknis (Merah)**.
  - Formulir laporan dilengkapi deskripsi kendala teknis dan unggahan foto kondisi langsung dari lapangan.
  - Admin dapat memberikan tanggapan tindak lanjut (*follow-up solutions*) dan memperbarui status penanganan masalah.

### 4.6 Publikasi Beranda Publik & Laporan Lapangan (Read-Only)
- **Beranda Interaktif (`HomeView.vue`):**
  - **Hero Banner:** Slider foto kegiatan dinamis, tombol hitung mundur acara, serta judul, sub-judul, dan deskripsi hero yang 100% dinamis dari pengaturan sistem.
  - **Denah Penempatan Proyektor:** Menampilkan tata letak posisi proyektor dan layar di area pengajian akbar.
  - **Kalender Acara & Timeline Haul:** Rangkaian agenda peringatan Haul Ke-5 Masyayikh.
  - **Jadwal Kerja Tim & Pembagian Proyektor:** Tabel plotting teknis tugas anggota.
  - **Papan Pantau Laporan Lapangan (Read-Only):** Menampilkan rangkuman kartu laporan langsung dari petugas posko secara transparan untuk publik/tamu, dengan batasan hanya-lihat dan tombol navigasi masuk petugas.
  - **Navigasi Navbar & Footer:** Seluruh tombol akses petugas terintegrasi langsung ke rute portal login modern `/login`.

### 4.7 Rekapitulasi Kas Keuangan & Proteksi Sandi Kas
- **Perlindungan Akses Ganda:** Halaman kas dilindungi otentikasi sandi kas (`/cash/verify-password`) khusus Super Admin dan Bendahara.
- **Pencatatan Arus Dana:** Arus dana masuk (infaq, donasi, subsidi) dan dana keluar (operasional teknis, konsumsi, transportasi, logistik) dengan lampiran bukti nota fisik/struk belanja.
- **Neraca Kas Realtime:** Kartu ringkasan total penerimaan, total pengeluaran, saldo akhir kas, dan tabel rincian transaksi dengan status penguncian periode.

### 4.8 Pengaturan Sistem Dinamis & Pengelolaan Halaman Utama
- **Kelola Halaman Utama (`/landing-manager`):** Mengunggah berkas gambar denah, memperbarui jadwal kerja tim, mengatur data kalender acara, dan menyunting teks hero beranda secara visual.
- **Foto Slider Depan (`/sliders`):** Menambah, menyunting judul & deskripsi, mengatur urutan tayang (*sort order*), dan menyalakan/mematikan banner slider depan.
- **Pengaturan Sistem (`/settings`):**
  - Tab Identitas: Nama aplikasi, sub-tema acara haul, logo sistem, alamat sekretariat, copyright footer.
  - Tab Divisi Tugas: Mengelola master daftar divisi kepanitiaan.
  - Tab Google Drive: Tautan terpusat folder Google Drive berkas kepanitiaan.
  - Tab Pengelola Admin: Menambah dan mengelola hak akses akun admin/bendahara baru.

### 4.9 Audit Log & Pusat Pembersihan Data (Danger Zone)
- **Audit Logs (`/audit-logs`):** Mencatat setiap aktivitas operasional pengguna (waktu, nama, peran, modul, aksi, detail perubahan data, IP Address).
- **Pusat Pembersihan Data / Danger Zone (`/danger-zone`):**
  - Dikhususkan hanya untuk pemegang peran Super Admin.
  - Membutuhkan input ulang kata sandi akun dan pengetikan frasa konfirmasi `RESET OPERASIONAL`.
  - Otomatis melakukan backup basis data sebelum pembersihan dilakukan.
  - Menjaga data vital panitia (akun Super Admin, master divisi, pengaturan branding, dan catatan audit log tetap aman tidak terhapus).

---

## 5. Rancangan Basis Data (Database Schema)

| Nama Tabel | Deskripsi & Tujuan | Kolom Kunci Utama |
|---|---|---|
| `users` | Akun autentikasi pengguna dan hak akses | `id`, `member_no`, `name`, `email`, `password`, `role`, `is_active`, `must_change_password`, `last_login_at` |
| `members` | Profil detail anggota relawan | `id`, `user_id`, `division_id`, `education`, `class`, `domicile`, `uniform_size`, `phone_wa`, `address` |
| `divisions` | Master divisi kepanitiaan | `id`, `name`, `description` |
| `inventory_categories` | Kategori barang logistik | `id`, `name`, `type`, `description` |
| `items` | Aset logistik (Internal & Mitra) | `id`, `code`, `name`, `source` (`internal`/`mitra`), `partner_id`, `category_id`, `qty_total`, `qty_available`, `qty_reserved`, `qty_damaged`, `condition`, `location`, `photo_path` |
| `partners` | Data instansi/lembaga mitra peminjam | `id`, `name`, `contact`, `phone`, `address`, `return_due_date` |
| `loans` | Transaksi sirkulasi peminjaman | `id`, `loan_no`, `borrower_id`, `purpose`, `status`, `requested_at`, `approved_by`, `approved_at`, `handed_over_at`, `due_at`, `returned_at`, `photo_proof` |
| `loan_items` | Rincian unit barang dalam peminjaman | `id`, `loan_id`, `item_id`, `qty_requested`, `qty_approved`, `qty_returned`, `condition_out`, `condition_in` |
| `regions` | Posko teknis & titik penugasan | `id`, `code`, `name`, `pic_user_id`, `location_desc` |
| `assignments` | Penugasan anggota pada posko | `id`, `region_id`, `user_id`, `role_in_post`, `start_at`, `end_at` |
| `field_reports` | Laporan kondisi posko lapangan | `id`, `region_id`, `reporter_id`, `status` (`normal`/`attention`/`danger`), `description`, `photo_path`, `is_public` |
| `report_followups` | Riwayat solusi tindak lanjut laporan | `id`, `report_id`, `user_id`, `solution`, `status` |
| `cash_transactions` | Catatan penerimaan & pengeluaran kas | `id`, `type` (`in`/`out`), `amount`, `date`, `category_id`, `description`, `receipt_path`, `lpj_status`, `unit_price`, `quantity`, `created_by` |
| `cash_categories` | Kategori pos anggaran keuangan | `id`, `name`, `type` |
| `sliders` | Banner gambar slider beranda depan | `id`, `title`, `description`, `image_path`, `sort_order`, `is_active` |
| `settings` | Konfigurasi sistem dinamis (Key-Value) | `id`, `key` (unik), `value` (LongText) |
| `audit_logs` | Jejak rekaman aksi audit sistem | `id`, `user_id`, `action`, `entity`, `entity_id`, `old_values`, `new_values`, `ip`, `created_at` |

---

## 6. Daftar Endpoint API Lengkap (`/api/v1`)

### 6.1 Endpoint Publik (Tanpa Autentikasi)
- `POST /api/v1/login` : Autentikasi pengguna & penerbitan token Sanctum
- `GET /api/v1/health` : Pemeriksaan status kesehatan server API
- `GET /api/v1/public/overview` : Data publik ringkasan beranda, denah, jadwal tim, dan kalender acara
- `GET /api/v1/public/sliders` : Daftar banner slider aktif untuk beranda
- `GET /api/v1/public/reports` : Daftar laporan lapangan anggota terpublish (*read-only*)
- `GET /api/v1/public/loans/verify/{code}` : Validasi keabsahan surat peminjaman melalui QR Code
- `GET /api/v1/public/options` : Opsi master divisi dan wilayah untuk formulir pendaftaran
- `POST /api/v1/public/register-member` : Pendaftaran mandiri anggota panitia baru (tanpa akun)
- `POST /api/v1/public/register-account` : Pendaftaran mandiri akun login anggota
- `GET /api/v1/settings/public` : Konfigurasi identitas publik (logo, nama aplikasi, copyright)

### 6.2 Endpoint Terproteksi (Wajib Bearer Token)
- **Autentikasi & Profil:** `POST /logout`, `GET /me`, `POST /password/change`
- **Dashboard Metrik:** `GET /dashboard/stats`
- **Manajemen Anggota:** `GET/POST /members`, `GET/PUT/DELETE /members/{id}`, `POST /members/import/preview`, `POST /members/import`, `GET /members/export`, `POST /members/{id}/create-account`
- **Divisi:** `GET/POST /divisions`, `GET/PUT/DELETE /divisions/{id}`
- **Inventaris Internal:** `GET/POST /items`, `GET/PUT/DELETE /items/{id}`, `GET /items/export`, `GET /inventory-categories`, `POST/DELETE /inventory-categories`
- **Pinjaman Lembaga Mitra:** `GET/POST /partners`, `PUT/DELETE /partners/{id}`
- **Sirkulasi Peminjaman:** `GET/POST /loans`, `GET /loans/{id}`, `POST /loans/{id}/approve`, `POST /loans/{id}/reject`, `POST /loans/{id}/handover`, `POST /loans/{id}/return`, `POST /loans/{id}/cancel`, `GET /loans/{id}/pdf`
- **Posko & Penugasan:** `GET/POST /regions`, `PUT/DELETE /regions/{id}`, `GET/POST /assignments`, `DELETE /assignments/{id}`
- **Laporan Lapangan:** `GET/POST /reports`, `GET /reports/{id}`, `POST /reports/{id}/followups`, `PATCH /reports/{id}/status`
- **Rekap Kas (Verifikasi PIN):** `POST /cash/verify-password`, `GET/POST /cash`, `PUT/DELETE /cash/{id}`, `POST /cash/{id}/void`
- **Landing Manager & Slider:** `POST /admin/landing/*` (denah, jadwal, proyektor, hero, kalender), `GET/POST /sliders`, `PUT/DELETE /sliders/{id}`
- **Pengaturan & Danger Zone:** `GET/PUT /settings`, `GET /audit-logs`, `POST /danger-zone/preview`, `POST /danger-zone/execute`

---

## 7. Checklist Kesiapan Hosting & Deployment Pra-Rilis

Sebelum mempublikasikan aplikasi ke lingkungan hosting/server produksi (*production*), pastikan seluruh poin pengujian berikut telah terpenuhi:

- [x] **Kompilasi Frontend Bersih:** `npm run build` sukses 100% tanpa error module tidak ditemukan (aset dist terkompilasi optimal).
- [x] **Struktur Database Lengkap:** Seluruh migrasi tabel dari `users`, `members`, `items`, `partners`, `loans`, `regions`, `reports`, hingga `cash_transactions` telah termigrasi dengan relasi foreign key yang valid.
- [x] **Pemisahan Logistik:** Modul Inventaris Internal dan Pinjaman Lembaga Mitra terpisah dengan benar pada navigasi Super Admin dan Anggota.
- [x] **Portal Login Vristo Aktif:** Tampilan login cover split-card dengan logo Rojabiyyah berfungsi penuh dan terhubung dengan sistem otentikasi Sanctum.
- [x] **Publikasi Laporan Lapangan:** Bagian laporan lapangan pada beranda publik dapat menampilkan pembaruan kondisi pos secara aman (*read-only*).
- [x] **Konfigurasi Lingkungan (`.env`):**
  - `APP_ENV=production`
  - `APP_DEBUG=false`
  - `APP_URL=https://domain-anda.com`
  - Koneksi database MySQL produksi dikonfigurasi dengan kredensial aman.
- [x] **Symlink Storage:** Menjalankan `php artisan storage:link` pada server produksi agar seluruh berkas bukti foto dan banner slider dapat diakses publik.
- [x] **Optimasi Cache Laravel:** Menjalankan `php artisan config:cache`, `php artisan route:cache`, dan `php artisan view:cache` di lingkungan hosting.

---

## 8. Riwayat Perubahan Dokumen (Changelog)

| Versi | Tanggal Rilis | Penulis / Pengembang | Deskripsi Pembaruan Utama |
|---|---|---|---|
| **v1.0** | 24 Oktober 2026 | Tim Pengembang | Draf inisial sistem administrasi dan perancangan awal. |
| **v2.0** | 24 Oktober 2026 | Tim Pengembang | Revisi menyeluruh: Penggantian autentikasi ke Laravel Sanctum, penambahan matriks RBAC 4 tingkat, spesifikasi peminjaman barang, dan perlindungan keamanan. |
| **v3.0** | 02 November 2026 | Tim Pengembang | **Pembaruan Lengkap Terintegrasi Pra-Hosting:**<br>1. Pemisahan data & antarmuka Inventaris Internal vs. Pinjaman Lembaga Mitra (dengan integrasi katalog peminjaman).<br>2. Sistem Penugasan Posko & Pelaporan Lapangan Realtime terintegrasi dengan Beranda Publik (Read-Only).<br>3. Redesain total Portal Login bertema Vristo Auth-Cover dengan palet kontras tinggi dan logo resmi Rojabiyyah.<br>4. Implementasi dual-path registrasi mandiri publik (Formulir Panitia & Pendaftaran Akun).<br>5. Integrasi kustomisasi hero beranda dinamis dan proteksi sandi ganda untuk modul kas.<br>6. Penyusunan checklist kesiapan hosting & deployment. |

---
*Dokumen ini merupakan panduan resmi pengembangan dan operasional teknis Sistem Administrasi Media Rojabiyyah 2026.*
