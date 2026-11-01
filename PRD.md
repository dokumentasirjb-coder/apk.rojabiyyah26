# PRD: Aplikasi Web Administrasi Rojabiyyah 2026

**Versi:** 2.0 (revisi menyeluruh)
**Stack:** Laravel (REST API) + Vue.js 3 (SPA) + MySQL
**Bahasa antarmuka:** Indonesia

---

## Daftar Isi

1. [Ringkasan Sistem](#1-ringkasan-sistem)
2. [Arsitektur dan Teknologi](#2-arsitektur-dan-teknologi)
3. [Keamanan](#3-keamanan)
4. [Peran Pengguna dan Hak Akses (RBAC)](#4-peran-pengguna-dan-hak-akses-rbac)
5. [Kebutuhan Fungsional](#5-kebutuhan-fungsional)
6. [Rancangan Basis Data](#6-rancangan-basis-data)
7. [Daftar Endpoint API](#7-daftar-endpoint-api)
8. [Kebutuhan Non-Fungsional](#8-kebutuhan-non-fungsional)
9. [Tahapan Pengembangan dan Prioritas](#9-tahapan-pengembangan-dan-prioritas)
10. [Risiko dan Mitigasi](#10-risiko-dan-mitigasi)
11. [Kriteria Penerimaan](#11-kriteria-penerimaan)
12. [Daftar Perubahan dari Draf Awal](#12-daftar-perubahan-dari-draf-awal)

---

## 1. Ringkasan Sistem

Administrasi Rojabiyyah 2026 adalah aplikasi web untuk mengelola operasional kepanitiaan berskala besar secara transparan, akuntabel, dan mendekati realtime. Sistem mengintegrasikan:

- Inventaris dan logistik (aset internal dan barang pinjaman lembaga mitra)
- Peminjaman barang: permohonan, persetujuan, bukti serah terima, dan Surat Bukti Peminjaman (PDF) resmi
- Data anggota dengan impor massal spreadsheet dan pembuatan akun otomatis
- Portal mandiri anggota
- Penugasan posko dan pelaporan lapangan realtime
- Rekap kas keuangan dengan bukti nota, grafik, dan ekspor
- Manajemen admin dengan kontrol akses berbasis peran (RBAC)
- Pembersihan data (Danger Zone), branding, tautan Google Drive, dan slider beranda

**Tujuan:** menggantikan pencatatan manual dan spreadsheet yang terpisah-pisah, menyediakan jejak audit, dan mempercepat koordinasi antar divisi dan posko.

**Perkiraan skala (asumsi, perlu dikonfirmasi):** 100–500 anggota terdaftar, 5–10 admin/pengelola, 50–100 pengguna bersamaan saat puncak acara.

---

## 2. Arsitektur dan Teknologi

| Lapisan | Pilihan | Catatan |
|---|---|---|
| Backend | Laravel (versi LTS terbaru), RESTful API | Validasi dengan Form Request, otorisasi dengan Policy/Gate |
| Frontend | Vue.js 3 SPA (Vite, Pinia, Vue Router) | Responsif, mobile-first |
| Database | MySQL 8 | Migrasi dan seeder Laravel |
| Autentikasi | **Laravel Sanctum (mode SPA, berbasis cookie)** | Pengganti Dual-Token JWT; lebih sederhana dan aman untuk SPA di domain induk yang sama |
| Antrean/Cache | Redis (atau driver database untuk skala kecil) | Antrean untuk PDF, impor, notifikasi |
| Penyimpanan file | Laravel Filesystem: disk lokal privat atau S3-compatible | File bukti hanya diakses lewat signed URL |
| Realtime | Laravel Reverb (WebSocket) dengan fallback polling 30 detik | Laporan lapangan dan notifikasi dashboard |
| Server | Nginx + PHP-FPM, HTTPS wajib | Security headers diatur di Nginx/middleware |

**Prinsip arsitektur:** Frontend dan Backend terpisah penuh (Decoupled Architecture); komunikasi hanya lewat REST API berversi (`/api/v1/...`). Frontend dan API sebaiknya berada di domain induk yang sama (mis. `app.domain.id` dan `api.domain.id`) agar cookie Sanctum berfungsi.

---

## 3. Keamanan

- **Autentikasi:** Laravel Sanctum mode SPA. Cookie sesi `httpOnly`, `Secure`, `SameSite=Lax`, dengan proteksi CSRF (`/sanctum/csrf-cookie`). Axios memakai `withCredentials: true`; interceptor menangani respons 401 (arahkan ke login) dan 419 (muat ulang CSRF).
- **Alternatif:** jika kelak butuh klien mobile native, tambahkan token pribadi Sanctum. Jika tetap memilih JWT, gunakan `tymon/jwt-auth` dengan access token 15 menit dan refresh token 7 hari via cookie `httpOnly`, lengkap dengan rotasi dan daftar cabut (revocation).
- **Kata sandi:** hash bcrypt/Argon2 bawaan Laravel, minimal 8 karakter. Akun hasil impor **wajib ganti password saat login pertama** (tanpa password default yang mudah ditebak).
- **Proteksi server:** pembatasan login (mis. 5 percobaan/menit per IP+akun), rate limiting global API, CORS dibatasi ke domain frontend, middleware security headers (mis. `bepsvpt/secure-headers`) atau konfigurasi Nginx (HSTS, X-Content-Type-Options, X-Frame-Options, CSP), sanitasi input, dan proteksi SQL injection lewat Eloquent/query binding. *(Catatan: Helmet pada draf awal adalah library Node.js dan tidak berlaku untuk Laravel.)*
- **Unggah file:** validasi MIME dan ekstensi, batas ukuran (foto maks. 5 MB, video maks. 50 MB, spreadsheet maks. 5 MB), nama file diacak, kompresi gambar otomatis, pemeriksaan ekstensi ganda, disimpan di disk privat.
- **Perlindungan data pribadi:** data anggota (nomor WhatsApp, domisili, pendidikan) hanya terlihat oleh peran berwenang; anggota hanya melihat profilnya sendiri; ekspor data tercatat di audit log; ada kebijakan retensi dan penghapusan data setelah acara selesai.
- **Audit log:** semua aksi penting (login, tambah/ubah/hapus, persetujuan, ekspor, reset) dicatat beserta pengguna, aksi, objek, waktu, dan IP.

---

## 4. Peran Pengguna dan Hak Akses (RBAC)

Draf awal hanya memiliki Admin dan Anggota dengan hak setara. Revisi ini memakai peran berjenjang agar prinsip *least privilege* terpenuhi.

| Peran | Deskripsi |
|---|---|
| **Super Admin** | Pemilik sistem; akses penuh, kelola admin, Danger Zone, pengaturan sistem. Minimal 1, disarankan 2 (cadangan). |
| **Admin** | Pengelola operasional: anggota, inventaris, peminjaman, penugasan, laporan, slider. Tidak dapat mengelola Super Admin atau Danger Zone. |
| **Bendahara** | Khusus modul kas: catat, ubah, ekspor. Hanya-lihat untuk modul lain yang relevan. |
| **Ketua Divisi** | Melihat anggota divisinya, mengajukan peminjaman atas nama divisi, melihat penugasan divisinya. |
| **PIC Posko** | Membuat dan memperbarui laporan lapangan untuk posko yang dipegangnya, melihat anggota posko. |
| **Anggota** | Portal mandiri: profil sendiri, katalog, permohonan peminjaman, laporan lapangan (jika bertugas di posko). |

### Matriks Hak Akses

Keterangan: **C** buat, **R** lihat, **U** ubah, **D** hapus, **A** setujui, **X** ekspor, **–** tanpa akses, **(sendiri)** hanya data miliknya, **(div)** hanya divisinya, **(posko)** hanya posko yang dipegang.

| Modul | Super Admin | Admin | Bendahara | Ketua Divisi | PIC Posko | Anggota |
|---|---|---|---|---|---|---|
| Dashboard ringkasan | R | R | R (kas) | R (div) | R (posko) | R (sendiri) |
| Data anggota | CRUDX | CRUDX | – | R (div) | R (posko) | R/U (sendiri) |
| Impor anggota | C | C | – | – | – | – |
| Inventaris internal | CRUDX | CRUDX | R | R | R | R (katalog) |
| Pinjaman mitra | CRUDX | CRUDX | R | R | R | R (katalog) |
| Peminjaman (ajukan) | C | C | – | C | C | C |
| Peminjaman (setujui/serah terima/kembali) | A | A | – | – | – | – |
| Penugasan dan posko | CRUD | CRUD | – | R (div) | R (posko) | R (sendiri) |
| Laporan lapangan | CRUD | CRU | – | R | C/R/U (posko) | C/R (posko) |
| Tindak lanjut laporan | U | U | – | – | – | – |
| Rekap kas | CRUDX | R | CRUDX | – | – | – |
| Slider beranda | CRUD | CRUD | – | – | – | – |
| Pengaturan dan branding | CRUD | R | – | – | – | – |
| Kelola admin | CRUD | – | – | – | – | – |
| Audit log | R | R (terbatas) | – | – | – | – |
| Danger Zone | Ya | Tidak | Tidak | Tidak | Tidak | Tidak |

Implementasi: `spatie/laravel-permission` atau Policy Laravel; matriks ini menjadi dasar seeder izin.

---

## 5. Kebutuhan Fungsional

### 5.1 Autentikasi dan Manajemen Akun
- Login memakai ID anggota (mis. `RJB-001`) atau email; logout; ganti password; reset password oleh Super Admin atau Admin (email tidak wajib).
- Status akun aktif/nonaktif; akun nonaktif langsung kehilangan sesi.
- Proteksi Super Admin: tidak dapat dihapus atau dinonaktifkan peran lain, dan sistem menolak tindakan yang membuat jumlah Super Admin menjadi nol.
- Kelola pengelola: tambah Admin/Bendahara, atur peran, aktif/nonaktif, reset password.

### 5.2 Data Anggota dan Impor Excel/CSV
- **Impor massal** `.xlsx`, `.xls`, `.csv` lewat modal; berkas besar diproses asinkron lewat antrean.
- **Pemetaan kolom cerdas:** NO, NAMA (wajib), DEV (divisi), PENDIDIKAN, KELAS, DOMISILI, SERAGAM, NO WA; toleran terhadap variasi huruf, spasi, dan sinonim.
- **Validasi dan pratinjau sebelum simpan:** tampilkan baris valid, baris bermasalah (nama kosong, nomor WA tidak valid, duplikat), dan ringkasan jumlah. Pengguna memilih *lewati baris bermasalah* atau *batal*.
- **Deteksi duplikat** berdasarkan nama + nomor WA; opsi lewati/perbarui.
- **Nomor induk otomatis** berurutan (RJB-001, RJB-002, …), dibuat dalam transaksi database dengan penguncian agar tidak ganda saat impor bersamaan. Format tetap bertambah setelah 999 (RJB-1000).
- **Akun login otomatis** dengan password sementara acak dan wajib ganti saat login pertama; daftar kredensial awal dapat diunduh sekali oleh admin (PDF/Excel).
- **Template Excel** standar dapat diunduh.
- **Pencarian dan filter:** divisi, peran, status, pencarian nama instan (debounce, paginasi sisi server).
- **Riwayat impor:** siapa, kapan, jumlah berhasil/gagal.

### 5.3 Portal Mandiri Anggota
- Beranda: titik tugas, permohonan berjalan, riwayat peminjaman, pengumuman.
- Profil: ubah biodata, domisili, nomor WhatsApp, foto profil (pratinjau langsung, pemangkasan otomatis).
- Akses pintas ke Google Drive berkas resmi dan jadwal penugasan.
- Katalog logistik: cari, lihat stok tersedia, ajukan permohonan.

### 5.4 Inventaris dan Logistik
- **Dua sumber:** Inventaris Internal dan Pinjaman Lembaga Mitra (menyimpan nama instansi pemilik, kontak, dan tanggal wajib kembali ke mitra).
- **Stok:** total, tersedia, dipesan (reserved), rusak; kondisi Baik / Rusak Ringan / Rusak Berat. Stok tersedia dihitung otomatis dan tidak pernah negatif.
- Foto barang, kode barang unik, label/QR, lokasi simpan, kategori.
- **Custom Fields** (teks, angka, dropdown) memakai tabel definisi dan tabel nilai (EAV terbatas) atau kolom JSON.
- Ekspor `.xlsx`.
- *Soft delete*; barang yang punya riwayat peminjaman tidak dapat dihapus permanen.

### 5.5 Sirkulasi Peminjaman dan Surat PDF

**Status:** `Diajukan → Disetujui → Diserahkan → Dikembalikan` (cabang: `Ditolak`, `Dibatalkan`, `Terlambat`, `Dikembalikan Sebagian`, `Hilang/Rusak`).

1. **Permohonan:** anggota memilih barang, jumlah, keperluan, tanggal pinjam dan rencana kembali. Sistem memeriksa ketersediaan stok pada rentang tanggal tersebut.
2. **Persetujuan:** admin meninjau; dapat menyetujui sebagian jumlah, menolak dengan alasan wajib, atau meminta revisi. Stok **dipesan (reserved)** saat disetujui dan **dikurangi** saat serah terima, sehingga tidak ada stok hilang jika serah terima batal.
3. **Serah terima:** unggah foto bukti kondisi barang, catatan kondisi, dan nama penerima.
4. **Pengembalian:** pemeriksaan kondisi per unit; stok kembali sesuai kondisi (barang rusak masuk kolom rusak, bukan tersedia).

**Kasus khusus:**
- *Stok tidak cukup:* permohonan ditolak otomatis saat diajukan, atau masuk daftar tunggu sesuai kebijakan admin.
- *Terlambat:* penanda otomatis setelah lewat tanggal rencana kembali; pengingat H-1 dan saat terlambat.
- *Pengembalian sebagian:* sisa jumlah tetap berstatus dipinjam.
- *Barang rusak/hilang:* dicatat dengan foto, memerlukan tindak lanjut admin (mis. penggantian).
- *Pembatalan:* pemohon dapat membatalkan sebelum serah terima; stok reserved dilepas.
- *Barang pinjaman mitra:* pengingat jatuh tempo pengembalian ke instansi asal.

**Surat Bukti Peminjaman (PDF):** kop organisasi (dari pengaturan), nomor surat otomatis, data peminjam, daftar barang, kode QR yang mengarah ke halaman verifikasi, dan kolom tanda tangan. Dibuat lewat antrean (mis. `barryvdh/laravel-dompdf` atau Snappy).

### 5.6 Kontrol Wilayah, Penugasan, dan Pelaporan Lapangan
- Posko/titik tugas: Panggung Utama, Dokumentasi, Live Streaming, Foto, Video, Proyektor, Perkabelan (dapat ditambah), lengkap dengan PIC dan anggota.
- Laporan berkala: status Normal / Perlu Perhatian / Rusak-Kendala, deskripsi, foto/video bukti (unggah dengan progres dan kompresi).
- Tindak lanjut admin: timeline dan status `Open → In Progress → Resolved`, catatan solusi, penanggung jawab.
- **Realtime:** laporan baru dan perubahan status disiarkan lewat Laravel Reverb; dashboard admin menampilkan lencana notifikasi. Fallback polling bila WebSocket tidak tersedia.
- Laporan berstatus *Rusak/Kendala* memicu notifikasi ke admin.

### 5.7 Rekap Keuangan Kas
- Transaksi kas masuk/keluar: nominal (`DECIMAL(15,2)`), tanggal, kategori, uraian, penanggung jawab, bukti nota (foto/PDF).
- **Integritas data:** transaksi tidak dihapus permanen; koreksi lewat pembatalan/jurnal balik, setiap perubahan tercatat di audit log. Periode dapat *dikunci* oleh Bendahara setelah rekonsiliasi.
- Ringkasan: total masuk, total keluar, saldo bersih; grafik 6 bulan terakhir dengan filter periode dan kategori.
- Ekspor `.xlsx` dan PDF.
- Hanya Super Admin dan Bendahara yang dapat mengubah data kas.

### 5.8 Navigasi Dashboard
- **Ringkasan:** metrik utama.
- **Layanan dan Operasional:** Penugasan, Inventaris Internal, Pinjaman Mitra, Peminjaman, Data Anggota, Kontrol Wilayah, Rekap Kas.
- **Konten dan Berkas:** Slider Beranda, Google Drive.
- **Pengaturan dan Sistem:** Kelola Pengelola, Branding, Audit Log, Backup.
- Menu ditampilkan sesuai izin peran.

### 5.9 Pusat Pembersihan Data (Danger Zone)
Diperketat dari draf awal:
- **Hanya Super Admin.**
- Wajib **input ulang password** dan frasa verifikasi `RESET OPERASIONAL`; opsional OTP.
- **Backup otomatis** (dump database + arsip file) dibuat dan diverifikasi *sebelum* reset; reset dibatalkan jika backup gagal.
- Pilihan cakupan: pilih modul yang direset (peminjaman, laporan, kas, penugasan, log), bukan semuanya sekaligus.
- **Pratinjau dampak** (jumlah baris yang akan dihapus) sebelum eksekusi.
- Berjalan di antrean dalam transaksi; hasilnya dicatat di audit log, yang **tidak ikut terhapus**.
- Data yang dilindungi: akun admin, master kategori, wilayah, konfigurasi identitas dasar, dan audit log.
- Pemulihan dari backup dimungkinkan selama 30 hari.

### 5.10 Kustomisasi Identitas, Google Drive, dan Dokumen PRD
- Branding: nama sistem, logo, sub-tema acara (Haul & Rojabiyyah), teks kop surat.
- Tautan folder Google Drive terpusat (validasi format URL; hanya tautan, tanpa menyimpan kredensial Google).
- Custom Fields dinamis.
- Unduh dokumen PRD & Flowchart `.docx` dari Pengaturan Sistem (hanya Super Admin/Admin).

### 5.11 Slider Beranda Publik
- Unggah banner (rasio dan resolusi dibatasi, dikompres otomatis), judul, deskripsi, urutan, aktif/nonaktif, jadwal tayang opsional.

### 5.12 Notifikasi *(baru)*
- Notifikasi dalam aplikasi untuk: permohonan baru, persetujuan/penolakan, pengingat jatuh tempo, keterlambatan, laporan kendala.
- Opsional: WhatsApp/email lewat gateway pihak ketiga, diaktifkan di pengaturan, dengan persetujuan anggota karena memakai nomor pribadi.

### 5.13 Backup dan Pemulihan *(baru)*
- Backup database harian otomatis (mis. `spatie/laravel-backup`), plus backup file bukti mingguan.
- Salinan disimpan di lokasi terpisah dari server utama; uji pemulihan minimal sekali sebelum hari-H.
- Retensi: harian 14 hari, mingguan 8 minggu.

---

## 6. Rancangan Basis Data

| Tabel | Kolom Utama | Relasi |
|---|---|---|
| `users` | id, member_no (unik), name, email, password, role, phone_wa, is_active, must_change_password, last_login_at | punya satu `members` |
| `members` | id, user_id, division_id, education, class, domicile, uniform_size, photo_path | milik `users`, `divisions` |
| `divisions` | id, name | punya banyak `members` |
| `roles`, `permissions` | (paket permission) | many-to-many dengan `users` |
| `inventory_categories` | id, name | punya banyak `items` |
| `items` | id, code (unik), name, source (internal/mitra), partner_id, category_id, qty_total, qty_available, qty_reserved, qty_damaged, condition, photo_path, location, deleted_at | milik `categories`, `partners` |
| `partners` | id, name, contact, return_due_date | punya banyak `items` |
| `custom_field_defs` | id, entity, label, type, options | punya banyak `custom_field_values` |
| `custom_field_values` | id, def_id, entity_id, value | milik `custom_field_defs` |
| `loans` | id, loan_no, borrower_id, purpose, status, requested_at, approved_by, approved_at, handed_over_at, due_at, returned_at, reject_reason, pdf_path | milik `users`; punya banyak `loan_items` |
| `loan_items` | id, loan_id, item_id, qty_requested, qty_approved, qty_returned, condition_out, condition_in, photo_out, photo_in | milik `loans`, `items` |
| `regions` | id, name, pic_user_id | punya banyak `assignments`, `field_reports` |
| `assignments` | id, region_id, user_id, role_in_post, start_at, end_at | milik `regions`, `users` |
| `field_reports` | id, region_id, reporter_id, status, description, created_at | punya banyak `report_media`, `report_followups` |
| `report_media` | id, report_id, path, type | milik `field_reports` |
| `report_followups` | id, report_id, user_id, status, solution, created_at | milik `field_reports` |
| `cash_transactions` | id, type (in/out), amount, date, category_id, description, receipt_path, created_by, voided_at, locked | milik `cash_categories` |
| `cash_categories` | id, name | punya banyak `cash_transactions` |
| `import_batches` | id, user_id, filename, total, success, failed, report_path, created_at | milik `users` |
| `notifications` | id, user_id, type, data, read_at | milik `users` |
| `audit_logs` | id, user_id, action, entity, entity_id, old_values, new_values, ip, created_at | milik `users` |
| `settings` | key, value (identitas, kop, tautan Drive) | – |
| `sliders` | id, title, description, image_path, sort_order, is_active, starts_at, ends_at | – |

Tambahkan indeks pada kolom pencarian dan relasi (`member_no`, `division_id`, `status`, `due_at`, `date`).

---

## 7. Daftar Endpoint API

Prefix: `/api/v1`

| Kelompok | Endpoint |
|---|---|
| Auth | `POST /login`, `POST /logout`, `GET /me`, `POST /password/change` |
| Anggota | `GET/POST /members`, `GET/PUT/DELETE /members/{id}`, `POST /members/import/preview`, `POST /members/import`, `GET /members/template` |
| Profil | `GET/PUT /profile`, `POST /profile/photo` |
| Inventaris | `GET/POST /items`, `GET/PUT/DELETE /items/{id}`, `GET /items/export` |
| Mitra | `GET/POST /partners`, `PUT/DELETE /partners/{id}` |
| Peminjaman | `GET/POST /loans`, `GET /loans/{id}`, `POST /loans/{id}/approve`, `/reject`, `/handover`, `/return`, `/cancel`, `GET /loans/{id}/pdf` |
| Wilayah | `GET/POST /regions`, `PUT/DELETE /regions/{id}`, `GET/POST /assignments` |
| Laporan | `GET/POST /reports`, `GET /reports/{id}`, `POST /reports/{id}/followups`, `PATCH /reports/{id}/status` |
| Kas | `GET/POST /cash`, `PUT /cash/{id}`, `POST /cash/{id}/void`, `GET /cash/summary`, `GET /cash/export?format=xlsx\|pdf` |
| Slider | `GET/POST /sliders`, `PUT/DELETE /sliders/{id}` |
| Pengaturan | `GET/PUT /settings`, `GET /settings/prd-document` |
| Admin | `GET/POST /admins`, `PUT/DELETE /admins/{id}` |
| Sistem | `GET /audit-logs`, `GET /notifications`, `POST /backups`, `POST /danger-zone/preview`, `POST /danger-zone/execute` |
| Publik | `GET /public/sliders`, `GET /public/loans/verify/{code}` |

**Standar API:** respons JSON seragam (`data`, `message`, `errors`), paginasi, kode HTTP baku (401, 403, 404, 419, 422, 429), dan dokumentasi OpenAPI/Swagger (mis. `dedoc/scramble`).

---

## 8. Kebutuhan Non-Fungsional

- **Kinerja:** halaman utama dimuat < 3 detik pada 4G; respons API umum < 500 ms; paginasi sisi server; *eager loading* untuk mencegah N+1.
- **Ketersediaan:** target 99% selama periode acara; pemantauan dasar (log error, health check, mis. Sentry).
- **Responsif:** prioritas mobile untuk anggota dan PIC posko di lapangan; unggah foto/video tetap stabil pada sinyal lemah (kompresi dan percobaan ulang).
- **Kompatibilitas browser:** dua versi terbaru Chrome, Edge, Firefox, Safari.
- **Aksesibilitas:** kontras warna, label formulir, ukuran tombol sentuh memadai.
- **Bahasa:** Bahasa Indonesia.
- **Pengujian:** pengujian otomatis (PHPUnit/Pest untuk API, Vitest untuk frontend), pengujian alur kritis (peminjaman, impor, kas, Danger Zone), dan UAT oleh perwakilan panitia.
- **Hosting:** VPS atau cloud ringan (minimal 2 vCPU, 4 GB RAM), HTTPS (Let's Encrypt), CI/CD sederhana, lingkungan *staging* terpisah dari *production*.

---

## 9. Tahapan Pengembangan dan Prioritas

| Tahap | Cakupan | Prioritas | Perkiraan Durasi |
|---|---|---|---|
| **0. Persiapan** | Konfirmasi kebutuhan, desain UI, ERD final, setup repo, staging, CI | Wajib | 1–2 minggu |
| **1. Inti (MVP)** | Autentikasi dan RBAC, data anggota + impor, inventaris, peminjaman + PDF, audit log, backup | **Wajib** | 4–6 minggu |
| **2. Operasional** | Penugasan dan posko, laporan lapangan realtime, kas dan ekspor, notifikasi | Tinggi | 3–4 minggu |
| **3. Penyempurnaan** | Branding, Google Drive, slider, Custom Fields, unduh PRD, Danger Zone | Sedang | 2–3 minggu |
| **4. Rilis** | UAT, uji beban, perbaikan, pelatihan admin, go-live, pendampingan hari-H | Wajib | 2 minggu |

Durasi bersifat perkiraan untuk tim kecil (2–3 pengembang) dan perlu disesuaikan dengan tenggat acara. Fitur Tahap 3 dapat digeser ke setelah acara bila waktu terbatas.

---

## 10. Risiko dan Mitigasi

| Risiko | Mitigasi |
|---|---|
| Cakupan terlalu besar untuk satu rilis | Pembagian tahap dan fokus MVP |
| Kebocoran data pribadi anggota | RBAC ketat, HTTPS, audit log, batas ekspor |
| Kehilangan data / reset tidak sengaja | Backup otomatis, Danger Zone hanya Super Admin |
| Sinyal lemah di lokasi acara | Kompresi unggahan, percobaan ulang, mode ringan |
| Server kelebihan beban saat puncak acara | Cache, antrean, uji beban, kapasitas cadangan |
| Ketergantungan pada satu admin | Minimal 2 Super Admin, dokumentasi operasional |

---

## 11. Kriteria Penerimaan

- Anggota hasil impor dapat login dan wajib mengganti password awal.
- Stok tidak pernah negatif dan selalu sinkron dengan status peminjaman.
- Surat PDF dapat diverifikasi lewat kode QR.
- Setiap aksi penting tercatat di audit log.
- Danger Zone hanya dapat dijalankan Super Admin setelah backup sukses.
- Seluruh halaman inti nyaman dipakai di layar ponsel.

---

## 12. Daftar Perubahan dari Draf Awal

1. Mengganti Helmet (Node.js) dan Dual-Token JWT dengan Laravel Sanctum; security headers lewat middleware/Nginx.
2. Menambahkan peran Bendahara, Ketua Divisi, dan PIC Posko beserta matriks hak akses.
3. Menambahkan ERD, daftar endpoint, dan standar API.
4. Memperinci alur peminjaman (stok tidak cukup, terlambat, sebagian, rusak, batal).
5. Memperketat Danger Zone (Super Admin, password, backup, pratinjau, audit).
6. Menambahkan audit log, backup, notifikasi, perlindungan data pribadi, batasan unggahan, dan mekanisme realtime.
7. Menambahkan kebutuhan non-fungsional, tahapan, risiko, dan kriteria penerimaan.
8. Memperbaiki impor anggota: pratinjau, deteksi duplikat, password sementara wajib ganti.
