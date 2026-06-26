# 1Langkah — Aplikasi Laravel 12

Porting lengkap dari showcase `index.html` (single-page vanilla JS) menjadi
aplikasi **Laravel 12** yang fully-functional dengan arsitektur berbasis
komponen Blade — meniru pola "droppable component" seperti Next.js.

Platform pembelajaran **1Langkah — AI-Powered Learning Experience Platform**
menyediakan 15 halaman: landing, login, signup, dashboard, kursus, detail
kursus, kursus saya, online bootcamp, detail online bootcamp, offline bootcamp,
detail offline bootcamp, mentor, profil mentor, kalender, dan pembayaran.

---

## Daftar Isi

1. [Persyaratan Sistem](#persyaratan-sistem)
2. [Instalasi di Komputer Lokal](#instalasi-di-komputer-lokal)
3. [Struktur Proyek](#struktur-proyek)
4. [Upload ke Shared Hosting cPanel](#upload-ke-shared-hosting-cpanel)
5. [Auto-Update dari GitHub (Push → Deploy)](#auto-update-dari-github-push--deploy)
   - [Pendekatan A — GitHub Webhook (Real-time)](#pendekatan-a--github-webhook-real-time)
   - [Pendekatan B — Cronjob Git Pull (Scheduled)](#pendekatan-b--cronjob-git-pull-scheduled)
6. [Cronjob Internal Laravel (Opsional)](#cronjob-internal-laravel-opsional)
7. [Troubleshooting](#troubleshooting)

---

## Persyaratan Sistem

### Untuk Pengembangan Lokal

| Komponen      | Versi Minimal | Catatan                                  |
|---------------|---------------|------------------------------------------|
| PHP           | 8.2+          | Disarankan 8.3 atau 8.4                  |
| Composer      | 2.6+          | Manajer dependensi PHP                   |
| Ekstensi PHP  | —             | `pdo_sqlite`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `bcmath` |
| Node.js       | 18+           | Hanya jika ingin memodifikasi CSS/JS via Vite |
| Git           | 2.30+         | Untuk clone & auto-deploy                |

### Untuk Shared Hosting cPanel

- cPanel dengan akses **File Manager** dan **Terminal** (atau SSH)
- PHP 8.2+ (atur lewat menu *MultiPHP Manager*)
- Ekstensi PHP aktif: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip`, `gd`, `bcmath`
- `exec()` / `shell_exec()` **tidak diblokir** — diperlukan untuk auto-deploy via git
- Akses ke **Cron Jobs** (menu cPanel standar)
- MySQL 5.7+ atau MariaDB 10.3+ (opsional — app ini juga jalan dengan SQLite)

---

## Instalasi di Komputer Lokal

### 1. Clone repository

```bash
git clone https://github.com/USERNAME/satu-langkah.git
cd satu-langkah
```

> Ganti `USERNAME` dengan username GitHub kamu. Jika belum di-push ke GitHub,
> ekstrak `satu-langkah.zip` lalu `cd satu-langkah`.

### 2. Install dependensi PHP

```bash
composer install
```

Jika ingin menginstall dependensi development (untuk testing):

```bash
composer install --dev
```

### 3. Salin file environment

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

Perintah ini akan mengisi `APP_KEY` di file `.env` secara otomatis.

### 5. Konfigurasi database (opsional)

Secara default, aplikasi sudah menggunakan **SQLite** dan berjalan tanpa
konfigurasi tambahan. Jika ingin memakai MySQL, edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=1langkah
DB_USERNAME=root
DB_PASSWORD=password_kamu
```

Lalu jalankan migrasi:

```bash
php artisan migrate --graceful
```

### 6. Jalankan development server

```bash
php artisan serve
```

Aplikasi akan aktif di **http://localhost:8000**.

### 7. (Opsional) Build aset via Vite

Jika kamu memodifikasi `resources/css/app.css` atau `resources/js/app.js`:

```bash
npm install
npm run dev      # mode watch selama development
npm run build    # build produksi sekali jalan
```

> Catatan: CSS utama app ini sudah disalin manual ke `public/css/app.css`,
> jadi aplikasi tetap berjalan tanpa menjalankan `npm run build`.

---

## Struktur Proyek

```
satu-langkah/
├── app/
│   ├── Http/Controllers/Pages/PageController.php   # 1 controller, 1 method per halaman
│   └── Services/CatalogService.php                 # sumber data demo (mirror dari JS `DATA`)
├── routes/
│   └── web.php                                     # 17 named routes (15 GET + 2 POST)
├── resources/views/
│   ├── layouts/
│   │   ├── guest.blade.php                         # shell untuk landing/auth (tanpa sidebar)
│   │   └── app.blade.php                           # shell untuk dashboard (sidebar + topbar)
│   ├── components/                                 # ~12 Blade components reusable
│   │   ├── icon.blade.php                          # 40 inline SVG icons
│   │   ├── badge.blade.php
│   │   ├── button.blade.php
│   │   ├── avatar.blade.php
│   │   ├── stars.blade.php
│   │   ├── progress-bar.blade.php
│   │   ├── course-card.blade.php
│   │   ├── mentor-card.blade.php
│   │   ├── stat-card.blade.php
│   │   ├── section-header.blade.php
│   │   ├── sidebar.blade.php
│   │   └── topbar.blade.php
│   └── pages/                                      # 15 page views
│       ├── landing.blade.php
│       ├── login.blade.php
│       ├── signup.blade.php
│       ├── dashboard.blade.php
│       ├── kursus.blade.php
│       ├── detail-kursus.blade.php
│       ├── kursus-saya.blade.php
│       ├── online-bootcamp.blade.php
│       ├── detail-online-bootcamp.blade.php
│       ├── offline-bootcamp.blade.php
│       ├── detail-offline-bootcamp.blade.php
│       ├── mentor.blade.php
│       ├── profil-mentor.blade.php
│       ├── kalender.blade.php
│       └── pembayaran.blade.php
├── public/
│   ├── index.php                                   # entry point Laravel
│   ├── css/app.css                                 # CSS hasil porting (design tokens + komponen)
│   └── .htaccess                                   # rewrite rule Laravel
├── database/
│   ├── migrations/                                 # 3 migrasi bawaan Laravel
│   └── seeders/
├── config/                                         # konfigurasi Laravel (12 file)
├── .env.example                                    # template environment
├── artisan                                         # CLI entry point
├── composer.json
└── README.md                                       # file ini
```

### Routing Summary

| Method | Path                          | Nama Route                 | View                           |
|--------|-------------------------------|----------------------------|--------------------------------|
| GET    | `/`                           | `landing`                  | `pages.landing`                |
| GET    | `/login`                      | `login`                    | `pages.login`                  |
| POST   | `/login`                      | `login.submit`             | redirect ke `dashboard`        |
| GET    | `/signup`                     | `signup`                   | `pages.signup`                 |
| POST   | `/signup`                     | `signup.submit`            | redirect ke `dashboard`        |
| GET    | `/dashboard`                  | `dashboard`                | `pages.dashboard`              |
| GET    | `/kursus`                     | `kursus`                   | `pages.kursus`                 |
| GET    | `/kursus/{id}`                | `detail-kursus`            | `pages.detail-kursus`          |
| GET    | `/kursus-saya`                | `kursus-saya`              | `pages.kursus-saya`            |
| GET    | `/bootcamp/online`            | `online-bootcamp`          | `pages.online-bootcamp`        |
| GET    | `/bootcamp/online/{id}`       | `detail-online-bootcamp`   | `pages.detail-online-bootcamp` |
| GET    | `/bootcamp/offline`           | `offline-bootcamp`         | `pages.offline-bootcamp`       |
| GET    | `/bootcamp/offline/{id}`      | `detail-offline-bootcamp`  | `pages.detail-offline-bootcamp`|
| GET    | `/mentor`                     | `mentor`                   | `pages.mentor`                 |
| GET    | `/mentor/{id}`                | `profil-mentor`            | `pages.profil-mentor`          |
| GET    | `/kalender`                   | `kalender`                 | `pages.kalender`               |
| GET    | `/pembayaran/{id?}`           | `pembayaran`               | `pages.pembayaran`             |

---

## Upload ke Shared Hosting cPanel

Panduan ini cocok untuk shared hosting seperti **Niagahoster, Hostinger, Rumahweb,
Domainesia, idwebhost**, dan sejenisnya.

### Langkah 1 — Persiapan di Sisi Lokal

#### 1a. Build untuk production

```bash
# Optimasi autoloader (membuang dev-dependencies)
composer install --no-dev --optimize-autoloader

# (Opsional) Build aset Vite jika kamu mengedit CSS/JS
npm install && npm run build

# Cache konfigurasi & route untuk performa produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 1b. Siapkan file `.env` untuk produksi

Salin `.env.example` menjadi `.env` lalu edit:

```env
APP_NAME="1Langkah"
APP_ENV=production
APP_KEY=base64:...            # hasil dari `php artisan key:generate`
APP_DEBUG=false
APP_URL=https://domainmu.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql           # atau sqlite jika tidak punya MySQL
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=username_1langkah # biasanya prefix nama user cPanel
DB_USERNAME=username_dbuser
DB_PASSWORD=password_db_kamu

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local
```

> ⚠️ **Penting**: Set `APP_DEBUG=false` di produksi. Jika `true`, error detail
> akan tampil ke pengunjung — risiko keamanan.

#### 1c. Kompres seluruh proyek (kecuali vendor, jika hosting mendukung Composer)

Opsi 1 — **Hosting mendukung Composer/Terminal** (ukuran zip kecil):

```bash
zip -r satu-langkah.zip satu-langkah \
  -x "satu-langkah/vendor/*" \
  -x "satu-langkah/node_modules/*" \
  -x "satu-langkah/.git/*" \
  -x "satu-langkah/storage/logs/*.log" \
  -x "satu-langkah/storage/framework/views/*" \
  -x "satu-langkah/storage/framework/sessions/*" \
  -x "satu-langkah/storage/framework/cache/data/*"
```

Opsi 2 — **Hosting TANPA Composer/Terminal** (semua disertakan, zip besar):

```bash
zip -r satu-langkah.zip satu-langkah \
  -x "satu-langkah/node_modules/*" \
  -x "satu-langkah/.git/*" \
  -x "satu-langkah/storage/logs/*.log" \
  -x "satu-langkah/storage/framework/views/*" \
  -x "satu-langkah/storage/framework/sessions/*" \
  -x "satu-langkah/storage/framework/cache/data/*"
```

### Langkah 2 — Buat Database di cPanel

1. Login ke cPanel → menu **MySQL® Database Wizard**
2. Buat database, misal: `username_1langkah`
3. Buat user database + password kuat
4. Berikan user tersebut **ALL PRIVILEGES** ke database
5. Catat: nama DB, username, password → masukkan ke `.env`

> Jika ingin memakai SQLite (lebih simpel), lewati langkah ini dan biarkan
> `DB_CONNECTION=sqlite`. File `database/database.sqlite` akan dibuat otomatis.

### Langkah 3 — Upload File

#### Via File Manager cPanel

1. cPanel → **File Manager** → masuk ke folder `public_html`
2. Klik **Upload** → pilih `satu-langkah.zip`
3. Setelah upload, **Extract** ke dalam `public_html`
4. Pindahkan semua isi folder `satu-langkah/` langsung ke `public_html/`
   (struktur akhir: `public_html/app`, `public_html/public`, `public_html/vendor`, dst.)

#### Via FTP (FileZilla, Cyberduck, dll.)

1. Connect FTP ke hosting kamu
2. Upload semua isi folder `satu-langkah/` ke dalam `public_html/`
3. Hidden files (`.env`, `.htaccess`) perlu diaktifkan di pengaturan FTP client
   ("Show hidden files" / "Server → Force showing hidden files")

### Langkah 4 — Set Document Root ke Folder `public`

Laravel WAJIB memiliki document root yang menunjuk ke folder `public/`.
Jika tidak, file `.env`, `composer.json`, dan kode sumber akan terekspos ke publik.

#### Opsi A — Via cPanel → Domains / Addon Domains / Subdomains

1. cPanel → **Domains** (atau **Addon Domains** / **Subdomains**)
2. Klik **Manage** di domain yang dipakai
3. Ubah **Document Root** dari `public_html` menjadi `public_html/public`
4. Save

#### Opsi B — Jika tidak bisa ubah document root

Pindahkan semua isi folder `public/` (file `index.php`, `.htaccess`, `css/`,
`robots.txt`) ke `public_html/`, lalu edit `index.php` agar path-nya benar:

```php
// public_html/index.php (versi yang sudah dimodifikasi)
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

Dan tambahkan baris berikut di paling atas `public_html/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ public/index.php [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Langkah 5 — Install Dependensi (jika vendor tidak diupload)

Jika hosting menyediakan **Terminal** di cPanel:

1. cPanel → **Terminal** (atau **JetApps → Terminal**)
2. Jalankan:

```bash
cd ~/public_html
composer install --no-dev --optimize-autoloader
```

Jika tidak ada Terminal, minta provider hosting untuk mengaktifkan SSH, atau
gunakan opsi 2 di Langkah 1c (upload `vendor/` dari lokal).

### Langkah 6 — Set Permission Folder

Laravel butuh folder berikut dapat ditulis (writable) oleh web server:

```
storage/                  → 775 (recursive)
storage/app/              → 775
storage/framework/        → 775
storage/logs/             → 775
bootstrap/cache/          → 775
```

Cara set via **File Manager**:

1. Klik kanan pada folder `storage` → **Permissions** → set `775` → centang **Recursive**
2. Ulangi untuk `bootstrap/cache`

Atau via Terminal/SSH:

```bash
cd ~/public_html
chmod -R 775 storage bootstrap/cache
```

> Jika masih ada error permission, coba `755` untuk folder dan `644` untuk file.
> Beberapa hosting strict menggunakan `suPHP` yang tidak suka `777`.

### Langkah 7 — Migrasi Database (jika pakai MySQL)

Via Terminal/SSH:

```bash
cd ~/public_html
php artisan migrate --force
```

Atau gunakan phpMyAdmin untuk import file SQL hasil dari lokal:

```bash
# Di komputer lokal:
php artisan migrate
mysqldump -u root -p username_1langkah > backup.sql
```

Lalu import `backup.sql` lewat **cPanel → phpMyAdmin → Import**.

### Langkah 8 — Optimasi Produksi

Setelah semua siap, jalankan via Terminal/SSH:

```bash
cd ~/public_html
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> Setiap kali kamu mengubah `.env` atau menambah route, jalankan ulang
> `php artisan config:cache` dan `php artisan route:cache`.

### Langkah 9 — Verifikasi

Buka domain kamu di browser, misal: `https://domainmu.com`. Halaman landing
1Langkah seharusnya tampil. Coba juga `/login`, `/dashboard`, `/kursus`, dll.

---

## Auto-Update dari GitHub (Push → Deploy)

Ada dua pendekatan. Pilih salah satu sesuai kebutuhan dan kemampuan hosting.

| Pendekatan | Trigger        | Kecepatan     | Kompleksitas | Cocok untuk           |
|------------|----------------|---------------|--------------|-----------------------|
| A. Webhook | Push event     | Real-time     | Sedang       | Shared hosting dengan endpoint publik |
| B. Cron    | Schedule       | ≤ 5 menit delay| Mudah       | Shared hosting dasar  |

---

### Pendekatan A — GitHub Webhook (Real-time)

Setiap kali ada push ke branch tertentu (mis. `main`), GitHub akan mengirim
POST request ke endpoint di hosting kamu, yang memicu script deploy.

#### A.1 — Buat deploy script di server

Buat file `deploy.php` di **luar** document root (mis. di `~/deploy/`):

```bash
mkdir -p ~/deploy
```

Buat file `~/deploy/deploy.sh`:

```bash
#!/bin/bash
set -e

# Konfigurasi
PROJECT_DIR="$HOME/public_html"
BRANCH="main"
LOG_FILE="$HOME/deploy/deploy.log"

echo "============================================" >> "$LOG_FILE"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Deploy started" >> "$LOG_FILE"
cd "$PROJECT_DIR"

# Pull latest changes
git fetch origin
git reset --hard "origin/$BRANCH"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Git pull done" >> "$LOG_FILE"

# Install dependencies (if vendor not committed)
if [ -f composer.json ] && [ ! -d vendor ] || [ composer.json -nt vendor/autoload.php ]; then
    composer install --no-dev --optimize-autoloader 2>&1 >> "$LOG_FILE"
fi

# Migrate database
php artisan migrate --force 2>&1 >> "$LOG_FILE"

# Clear & re-cache
php artisan config:cache 2>&1 >> "$LOG_FILE"
php artisan route:cache 2>&1 >> "$LOG_FILE"
php artisan view:cache 2>&1 >> "$LOG_FILE"
php artisan event:cache 2>&1 >> "$LOG_FILE"

# Fix permissions
chmod -R 775 storage bootstrap/cache 2>&1 >> "$LOG_FILE"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Deploy finished successfully" >> "$LOG_FILE"
```

Buat executable:

```bash
chmod +x ~/deploy/deploy.sh
```

#### A.2 — Buat webhook receiver

Buat file `~/deploy/webhook.php`:

```php
<?php
// ~/deploy/webhook.php — menerima webhook dari GitHub

$secret = 'GANTI_DENGAN_SECRET_KAMU_YANG_PANJANG_DAN_RAHASIA';
$logFile = __DIR__ . '/webhook.log';

// Baca raw payload
$payload = file_get_contents('php://input');
$signatureHeader = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if (! $signatureHeader) {
    http_response_code(403);
    exit('Missing signature');
}

// Verifikasi signature HMAC
$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);
if (! hash_equals($expected, $signatureHeader)) {
    file_put_contents($logFile, '[' . date('c') . '] Invalid signature' . PHP_EOL, FILE_APPEND);
    http_response_code(403);
    exit('Invalid signature');
}

// Cek event type (hanya terima push)
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
if ($event !== 'push') {
    http_response_code(200);
    exit('Ignored event: ' . $event);
}

// Parse payload untuk cek branch
$data = json_decode($payload, true);
$ref = $data['ref'] ?? '';
if ($ref !== 'refs/heads/main') {
    http_response_code(200);
    exit('Ignored branch: ' . $ref);
}

// Eksekusi deploy script (background, jangan block webhook)
$cmd = 'bash ' . __DIR__ . '/deploy.sh > /dev/null 2>&1 &';
exec($cmd);

file_put_contents($logFile, '[' . date('c') . '] Deploy triggered for main' . PHP_EOL, FILE_APPEND);
http_response_code(200);
echo 'Deploy triggered';
```

#### A.3 — Expose webhook ke publik

Karena file `webhook.php` ada di luar `public_html`, kita perlu akses publik.
Dua opsi:

**Opsi 1 — Pindahkan webhook.php ke dalam `public_html/`** (paling mudah):

```bash
cp ~/deploy/webhook.php ~/public_html/webhook.php
```

Lalu URL webhook: `https://domainmu.com/webhook.php`

> ⚠️ Pastikan file `deploy.sh` dan secret TETAP di luar document root.

**Opsi 2 — Buat subdomain khusus** seperti `deploy.domainmu.com` yang document
root-nya menunjuk ke `~/deploy/`. Lebih aman, tapi perlu setup tambahan.

#### A.4 — Setup Webhook di GitHub

1. Buka repository di GitHub → **Settings** → **Webhooks** → **Add webhook**
2. Isi:
   - **Payload URL**: `https://domainmu.com/webhook.php`
   - **Content type**: `application/json`
   - **Secret**: sama dengan `$secret` di `webhook.php`
   - **Which events trigger**: Just the `push` event
   - **Active**: ✅
3. Klik **Add webhook**
4. Test: lakukan push ke branch `main`, lalu cek **Recent Deliveries** di GitHub
   dan file `~/deploy/webhook.log` di server

#### A.5 — Pastikan git sudah ter-clone di server

Webhook hanya akan jalan jika folder `public_html` sudah berupa git repository.
Via SSH/Terminal cPanel:

```bash
cd ~/public_html
git init
git remote add origin https://github.com/USERNAME/satu-langkah.git
git fetch origin
git checkout -t origin/main   # atau master, sesuaikan
```

> Jika repository privat, generate deploy key dan tambahkan sebagai **Deploy Key**
> di GitHub (Settings → Deploy keys → Add deploy key).

---

### Pendekatan B — Cronjob Git Pull (Scheduled)

Pendekatan ini lebih simpel: cPanel cron menjalankan `git pull` setiap N menit.
Tidak perlu webhook, tidak perlu endpoint publik. Trade-off: ada delay maksimal
seumur cron (mis. 5 menit).

#### B.1 — Setup git di server

Via Terminal/SSH cPanel:

```bash
cd ~/public_html
git init
git remote add origin https://github.com/USERNAME/satu-langkah.git
git fetch origin
git checkout -t origin/main
```

Untuk repository privat, gunakan Personal Access Token:

```bash
git remote set-url origin https://TOKEN@github.com/USERNAME/satu-langkah.git
```

Atau setup SSH key (lebih aman):

```bash
ssh-keygen -t ed25519 -C "deploy@domainmu.com" -f ~/.ssh/id_ed25519 -N ""
cat ~/.ssh/id_ed25519.pub
# Tambahkan public key ini sebagai Deploy Key di GitHub repository
```

#### B.2 — Buat script deploy

Sama seperti [A.1](#a1--buat-deploy-script-di-server), buat `~/deploy/deploy.sh`
dengan isi yang persis sama. Set executable:

```bash
chmod +x ~/deploy/deploy.sh
```

#### B.3 — Tambahkan Cron Job di cPanel

1. cPanel → **Cron Jobs**
2. Pada section **Add New Cron Job**:
   - **Common Settings**: pilih `*/5` (Every 5 minutes) atau sesuai kebutuhan
   - **Minute**: `*/5`
   - **Hour**: `*`
   - **Day**: `*`
   - **Month**: `*`
   - **Weekday**: `*`
   - **Command**:

     ```bash
     /bin/bash /home/USERNAME/deploy/deploy.sh >> /home/USERNAME/deploy/cron.log 2>&1
     ```

     Ganti `USERNAME` dengan username cPanel kamu.

3. Klik **Add New Cron Job**

#### B.4 — Optimasi script untuk cron (opsional)

Script `deploy.sh` di atas selalu menjalankan `composer install`, `migrate`,
dan cache rebuild **setiap 5 menit**. Agar lebih efisien, tambahkan cek apakah
ada perubahan:

```bash
#!/bin/bash
set -e

PROJECT_DIR="$HOME/public_html"
BRANCH="main"
LOG_FILE="$HOME/deploy/deploy.log"

cd "$PROJECT_DIR"

# Cek apakah ada update dari remote
git fetch origin
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse "origin/$BRANCH")

if [ "$LOCAL" = "$REMOTE" ]; then
    # Tidak ada perubahan, skip
    exit 0
fi

echo "============================================" >> "$LOG_FILE"
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Update detected, deploying..." >> "$LOG_FILE"

git reset --hard "origin/$BRANCH"

if [ -f composer.json ]; then
    composer install --no-dev --optimize-autoloader 2>&1 >> "$LOG_FILE"
fi

php artisan migrate --force 2>&1 >> "$LOG_FILE"
php artisan config:cache 2>&1 >> "$LOG_FILE"
php artisan route:cache 2>&1 >> "$LOG_FILE"
php artisan view:cache 2>&1 >> "$LOG_FILE"
php artisan event:cache 2>&1 >> "$LOG_FILE"

chmod -R 775 storage bootstrap/cache 2>&1 >> "$LOG_FILE"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Deploy done" >> "$LOG_FILE"
```

Dengan versi ini, cron jalan tiap 5 menit tapi deploy hanya terjadi jika ada
push baru ke GitHub.

---

## Cronjob Internal Laravel (Opsional)

Selain cronjob untuk auto-deploy, Laravel sendiri punya **Task Scheduler** yang
butuh satu cron di cPanel untuk berjalan.

### Setup Laravel Scheduler

1. cPanel → **Cron Jobs** → **Add New Cron Job**
2. Setting:
   - **Minute**: `*`
   - **Hour**: `*`
   - **Day**: `*`
   - **Month**: `*`
   - **Weekday**: `*`
   - **Command**:

     ```bash
     /usr/local/bin/php /home/USERNAME/public_html/artisan schedule:run >> /dev/null 2>&1
     ```

     Ganti `USERNAME` dengan username cPanel kamu. Cek path PHP binary yang
     benar via cPanel → **MultiPHP Manager** atau tanya provider hosting.

3. Save

Sekarang, setiap task yang kamu definisikan di `routes/console.php` atau
`app/Console/Kernel.php` akan otomatis dijalankan sesuai schedule-nya.

---

## Troubleshooting

### 1. Halaman blank / 500 Internal Server Error

**Kemungkinan penyebab:**

- Permission folder `storage/` atau `bootstrap/cache/` tidak writable
  → Set ke `775` recursive
- `.env` tidak ada atau `APP_KEY` kosong
  → Jalankan `php artisan key:generate`
- PHP version terlalu tua
  → cPanel → **MultiPHP Manager** → set ke 8.2+
- `exec()` / `shell_exec()` diblokir (untuk auto-deploy)
  → Hubungi provider hosting untuk enable, atau pakai pendekatan cron tanpa exec

**Cek error log:**

```bash
tail -f ~/public_html/storage/logs/laravel.log
```

Atau lihat via cPanel → **Errors** atau **Logs**.

### 2. CSS / asset tidak load

- Pastikan `public/css/app.css` ter-upload
- Cek URL di view: harus menggunakan `{{ asset('css/app.css') }}`
- Jika asset masih mengarah ke `localhost`, jalankan:

  ```bash
  php artisan config:cache
  ```

### 3. "No application encryption key has been specified"

`.env` tidak ada atau `APP_KEY` kosong. Solusi:

```bash
php artisan key:generate
php artisan config:cache
```

### 4. Webhook GitHub mengembalikan 403 / "Invalid signature"

- Pastikan secret di GitHub webhook sama persis dengan `$secret` di `webhook.php`
- Cek file `~/deploy/webhook.log` untuk detail error
- Pastikan `php://input` bisa dibaca (beberapa hosting memblokirnya)

### 5. Cron tidak jalan

- Cek path PHP: beberapa shared hosting pakai `/usr/local/bin/php`, ada juga
  yang `/opt/alt/php82/usr/bin/php`. Tanya provider atau cek di cPanel.
- Pastikan script executable: `chmod +x ~/deploy/deploy.sh`
- Cek log: `tail -f ~/deploy/cron.log`
- Test manual: jalankan command cron langsung di Terminal cPanel

### 6. Git pull gagal dengan "Permission denied (publickey)"

Untuk repository privat, pastikan:

- SSH key sudah di-generate di server: `ssh-keygen -t ed25519`
- Public key sudah ditambahkan sebagai **Deploy Key** di GitHub repository
  (Settings → SSH and GPG keys → atau Settings → Deploy keys)
- Test: `ssh -T git@github.com` harus mengembalikan "Hi USERNAME!"

### 7. `composer install` gagal di shared hosting

Beberapa shared hosting membatasi memori Composer. Solusi:

```bash
COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader
```

Atau install composer secara lokal di home directory:

```bash
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=$HOME/bin --filename=composer
```

### 8. Halaman selalu redirect ke `https://localhost:8000`

Setelah deploy, jalankan:

```bash
php artisan config:cache
```

Cache config lama masih menyimpan `APP_URL=http://localhost:8000`. Setelah
direset, Laravel akan membaca `APP_URL` baru dari `.env`.

### 9. Auto-deploy terlalu sering / ngeload CPU

- Pendekatan A (webhook): pastikan hanya branch `main` yang memicu deploy
  (cek di `webhook.php`)
- Pendekatan B (cron): pakai versi script di B.4 yang cek dulu apakah ada
  perubahan sebelum deploy penuh

### 10. Mau rollback ke versi sebelumnya

```bash
cd ~/public_html
git log --oneline -10              # lihat 10 commit terakhir
git reset --hard <commit-hash>     # rollback ke commit tertentu
php artisan migrate:rollback       # rollback database (jika perlu)
php artisan config:cache
php artisan route:cache
```

---

## Lisensi

Project ini adalah porting showcase UI. Gunakan dengan bijak sesuai hukum
hak cipta yang berlaku. Library Laravel dan dependensi tunduk pada lisensi
masing-masing (MIT, BSD, dll).

---

## Bantuan & Kontribusi

Jika menemukan bug atau ingin menambah fitur:

1. Fork repository
2. Buat branch fitur: `git checkout -b feat/nama-fitur`
3. Commit perubahan: `git commit -m "feat: tambah X"`
4. Push: `git push origin feat/nama-fitur`
5. Buat **Pull Request** ke branch `main`

Untuk pertanyaan teknis seputar deployment, cek dulu section
[Troubleshooting](#troubleshooting) sebelum menghubungi maintainer.

---

**Selamat mengembangkan! 🚀**
