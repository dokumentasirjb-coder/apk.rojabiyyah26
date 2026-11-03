# Panduan Hosting & Deployment: Aplikasi Administrasi Rojabiyyah 2026

Panduan ini berisi langkah-langkah praktis untuk mempublikasikan (*deploy*) aplikasi **Administrasi Rojabiyyah 2026** ke server produksi (*cPanel / Shared Hosting* atau *VPS Ubuntu/Debian*).

---

## 1. Kredensial Akun Bawaan (Default Seeder)

Gunakan akun ini saat pertama kali sistem berhasil di-*deploy*:

| Peran | Login (Email / ID) | Kata Sandi | Keterangan |
|---|---|---|---|
| **Super Admin** | `superadmin@rojabiyyah.id` atau `RJB-001` | `password123` | Hak akses penuh, pengaturan & kelola pengelola |
| **Admin Operasional** | `admin@rojabiyyah.id` atau `RJB-002` | `password123` | Manajemen logistik, anggota & landing page |
| **Bendahara** | `bendahara@rojabiyyah.id` atau `RJB-003` | `password123` | Khusus rekapitulasi kas keuangan |
| **Anggota (Contoh)** | `ahmad` atau `ahmad@rojabiyyah.id` | `password123` | Portal mandiri anggota & pelaporan lapangan |
| **Sandi Akses Kas** | – | `kas2026` | PIN verifikasi tambahan modul kas keuangan |

> [!IMPORTANT]
> Segera ganti kata sandi akun-akun di atas melalui menu **Pengaturan Akun** setelah berhasil login pertama kali di server produksi!

---

## 2. Opsi A: Hosting di cPanel / Shared Hosting (Paling Umum)

### Langkah 1: Persiapan Berkas
1. Kompilasi frontend lokal dengan perintah:
   ```bash
   cd frontend
   npm run build
   ```
   Hasil kompilasi siap pakai berada di dalam folder `frontend/dist`. Berkas `.htaccess` sudah otomatis terpasang untuk mencegah error 404 saat *refresh* halaman.

### Langkah 2: Unggah ke cPanel File Manager
1. **Frontend (Tampilan Web):**
   - Buka **cPanel &rarr; File Manager &rarr; `public_html`**.
   - Ekstrak atau salin seluruh isi dari dalam folder `frontend/dist` langsung ke dalam `public_html` (termasuk file `.htaccess` dan `index.html`).
2. **Backend (API Laravel):**
   - Buat folder baru di luar `public_html`, misalnya: `/home/username/backend_rojabiyyah`.
   - Unggah seluruh isi folder `backend/` ke dalam folder tersebut (kecuali folder `backend/node_modules` jika ada).
   - Pastikan file `.env` sudah dikonfigurasi dengan kredensial database cPanel.

### Langkah 3: Konfigurasi Database MySQL cPanel
1. Buka **cPanel &rarr; MySQL Databases**.
2. Buat database baru (misal: `user_rojabiyyah2026`).
3. Buat pengguna database baru dan hubungkan ke database dengan mencentang **ALL PRIVILEGES**.
4. Edit file `/home/username/backend_rojabiyyah/.env`:
   ```env
   APP_NAME="Administrasi Rojabiyyah 2026"
   APP_ENV=production
   APP_KEY=base64:... (gunakan key yang sudah digenerate)
   APP_DEBUG=false
   APP_URL=https://domain-anda.com

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=user_rojabiyyah2026
   DB_USERNAME=user_dbuser
   DB_PASSWORD=PasswordDatabaseAnda

   FRONTEND_URL=https://domain-anda.com
   CORS_ALLOWED_ORIGINS=https://domain-anda.com
   ```

### Langkah 4: Hubungkan Backend ke Web Root
Jika frontend dan backend di domain yang sama (`https://domain-anda.com`):
1. Buat folder `api` di dalam `public_html` atau gunakan sub-domain terpisah (misal: `api.domain-anda.com`).
2. Arahkan Document Root sub-domain `api.domain-anda.com` ke `/home/username/backend_rojabiyyah/public`.
3. Jalankan Terminal cPanel atau SSH:
   ```bash
   cd /home/username/backend_rojabiyyah
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

---

## 3. Opsi B: Hosting di VPS (Ubuntu / Debian dengan Nginx)

### Langkah 1: Kebutuhan Server
- OS: Ubuntu 22.04 / 24.04 LTS
- PHP 8.2 atau 8.3 dengan ekstensi: `php-fpm`, `php-mysql`, `php-mbstring`, `php-xml`, `php-curl`, `php-zip`, `php-gd`
- Nginx & MySQL 8.0 Server
- Composer

### Langkah 2: Konfigurasi Virtual Host Nginx
Contoh konfigurasi Nginx gabungan (Frontend SPA + Backend API di satu domain):

```nginx
server {
    listen 80;
    server_name domain-anda.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    server_name domain-anda.com;

    # SSL Certificate
    ssl_certificate /etc/letsencrypt/live/domain-anda.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/domain-anda.com/privkey.pem;

    # Root Frontend (dist)
    root /var/www/rojabiyyah/frontend/dist;
    index index.html;

    # Frontend Single Page Application fallback
    location / {
        try_files $uri $uri/ /index.html;
    }

    # API Backend Laravel
    location /api {
        alias /var/www/rojabiyyah/backend/public;
        try_files $uri $uri/ @laravel;

        location ~ \.php$ {
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME /var/www/rojabiyyah/backend/public/index.php;
            fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        }
    }

    location @laravel {
        rewrite /api/(.*)$ /index.php?/$1 last;
    }

    # Storage & Uploads
    location /storage {
        alias /var/www/rojabiyyah/backend/storage/app/public;
        access_log off;
        expires 30d;
    }
}
```

### Langkah 3: Eksekusi Perintah Rilis di VPS
```bash
cd /var/www/rojabiyyah/backend
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 4. Verifikasi Akhir Pasca-Hosting

Setelah sistem aktif online, lakukan uji coba singkat berikut:
1. Akses `https://domain-anda.com` &rarr; Beranda publik muncul, teks hero terbaca, slider foto tampil.
2. Akses `https://domain-anda.com/login` &rarr; Muncul halaman Vristo Auth-Cover. Coba login dengan `superadmin@rojabiyyah.id` / `password123`.
3. Akses menu **Data Inventaris Internal** & **Pinjaman Lembaga Mitra** &rarr; Pastikan data terpisah rapi.
4. Akses menu **Tugas Anggota & Titik Tugas** &rarr; Coba kirim 1 laporan kondisi lapangan, lalu cek Beranda Section 5 apakah laporan terpublikasi (*read-only*).
5. Akses menu **Rekap Uang** &rarr; Masukkan kata sandi kas `kas2026`.

Sistem siap beroperasi untuk mendukung perhelatan Haul Ke-5 & Rojabiyyah 2026!

