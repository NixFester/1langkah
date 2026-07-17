# Roadmap & Flow Whitebox Testing - 1Langkah

Dokumen ini merinci pendekatan *Whitebox Testing* untuk aplikasi **1Langkah**. Tidak seperti Blackbox yang fokus pada *User Interface* (UI), pengujian Whitebox berfokus pada verifikasi logika internal, arsitektur kode (Laravel 12, PHP 8.4), efisiensi query *database*, validasi *middleware/policy*, dan penanganan *error*.

Pengujian akan dijalankan menggunakan **PHPUnit** (framework bawaan Laravel).

---

## 1. Pendekatan Eksekusi (Testing Layers)

1. **Unit Testing (`tests/Unit`)** 
   - **Target:** Logic parsial pada Models (Accessor/Mutator), Services Class, Custom Helpers.
   - **Tujuan:** Memastikan satu fungsi berjalan mandiri tanpa memuat seluruh *framework* atau database.
2. **Feature Testing (`tests/Feature`)**
   - **Target:** Endpoint Controllers, API AlpineJS, interaksi Database, Middleware.
   - **Tujuan:** Mensimulasikan *request* HTTP dan memvalidasi *response*, perubahan pada DB, dan event trigger.
3. **Architecture Testing (Opsional dengan Pest/PHPUnit)**
   - **Target:** Standar kode, misalnya memastikan kontroler tidak memanggil query database secara langsung (wajib lewat Model/Service).

---

## 2. Roadmap Whitebox per Modul (Test Flow)

### 2.1. Autentikasi, Otorisasi, & Middleware
*Berfokus pada keamanan akses dan validasi role pada tingkat controller/router.*

- **Flow Uji Middleware Role:**
  1. *Setup:* Buat *mock user* menggunakan `UserFactory` dengan berbagai tipe (Superadmin, Mentor, Marketing, Keuangan, Student).
  2. *Action:* Simulasikan HTTP GET request (`$this->actingAs($user)->get('/keuangan/dashboard')`).
  3. *Assert:* 
     - Keuangan -> `assertStatus(200)`
     - Student -> `assertStatus(403)` (Forbidden) atau `assertRedirect`.
- **Flow Uji Security & Rate Limiting:**
  1. Hit endpoint login berulang kali (looping 10x).
  2. Pastikan percobaan ke-6 me-return error *429 Too Many Requests* (Rate Limiter).

### 2.2. Modul Keuangan (Verifikasi & Kalkulasi)
*Berfokus pada integritas data transaksional dan validasi revenue.*

- **Flow Uji State Transition (Approve/Reject Pembayaran):**
  1. *Setup:* Buat seeder transaksi dummy dengan status `'pending'`.
  2. *Action (Approve):* Kirim HTTP POST ke endpoint Approve.
  3. *Assert DB:* `assertDatabaseHas('payments', ['status' => 'approved'])`.
  4. *Assert Relasi:* Cek tabel pivot `user_courses` apakah student berhasil mendapatkan relasi akses (Enrollment terbuat).
  5. *Fail Case:* Kirim transaksi yang *sudah* approved untuk di-approve kembali. Pastikan *Exception* ditangani atau throw `422 Unprocessable Entity`.
- **Flow Uji Agregasi Revenue:**
  1. *Setup:* *Seed* 10 data transaksi *approved* di tanggal hari ini (`today`), 5 transaksi *rejected*.
  2. *Action:* Panggil fungsi atau Controller penghitung `todayRevenue()`.
  3. *Assert:* Jumlah revenue harus sama persis dengan kalkulasi `SUM(amount)` dari 10 transaksi *approved*. Transaksi *rejected* harus diabaikan.

### 2.3. Modul Mentor (Manajemen Konten & Scanner)
*Berfokus pada penanganan input, file storage, dan logika kehadiran.*

- **Flow Uji Upload Media (Storage Mock):**
  1. *Setup:* `Storage::fake('public');` untuk mencegah file tersimpan beneran.
  2. *Action:* Upload `UploadedFile::fake()->image('cover.jpg')` ke endpoint *Create Course*.
  3. *Assert:* Pastikan `Storage::disk('public')->assertExists(...)` berhasil, dan path URL tersimpan benar di kolom database.
- **Flow Uji Algoritma Validasi Scanner Kehadiran:**
  1. *Setup:* Buat *hashed string* (QR Payload) di database. Set rentang waktu (expired time).
  2. *Action:* POST endpoint `/mentor/attendance/scan-code` dengan payload QR code valid.
  3. *Assert DB:* Tabel `attendances` mencatat `user_id` yang sesuai pada rentang waktu `created_at` hari ini.
  4. *Fail Case:* Kirim payload QR yang sudah kadaluarsa, pastikan me-return JSON `['success' => false, 'message' => 'QR Kadaluarsa']`.

### 2.4. Modul Student (Player & Progress Tracking)
*Berfokus pada retensi kemajuan (progress) kursus secara presisi.*

- **Flow Uji Penyimpanan Progress:**
  1. *Setup:* *Student* sudah terdaftar di *Course A* (memiliki 5 video).
  2. *Action:* Kirim API request ke endpoint *Mark as Complete* pada video 1.
  3. *Assert DB:* Database mencatat progress = 20% (1 dari 5).
  4. *Fail Case:* Student A mengirim request *Mark as Complete* ke *Course B* yang tidak dibelinya. Pastikan terjadi respon `403 Forbidden` berdasarkan *Laravel Policy*.

### 2.5. Modul Admin (Manajemen User & Kursus)
*Berfokus pada kemampuan CRUD pengguna dan manajemen konten platform.*

- **Flow Uji Manajemen Pengguna:**
  1. *Setup:* Login sebagai pengguna dengan role `admin`.
  2. *Action:* Hit endpoint POST untuk membuat pengguna baru.
  3. *Assert DB:* Pastikan data tersimpan di tabel `users` dengan email dan role yang sesuai.
- **Flow Uji Perubahan Role:**
  1. *Setup:* Terdapat *user* dengan role `student`.
  2. *Action:* Hit endpoint PATCH `/admin/users/{user}/role` untuk mengubah role menjadi `mentor`.
  3. *Assert DB:* Pastikan kolom `role` pada *user* tersebut di database berhasil terupdate.

### 2.6. Modul Marketing (Promo & Analytics)
*Berfokus pada fungsionalitas kampanye pemasaran, pembuatan kode promo, dan pelacakan diskon.*

- **Flow Uji Kode Promo:**
  1. *Setup:* Login dengan role `marketing`.
  2. *Action:* Buat *promo code* melalui endpoint POST.
  3. *Assert DB:* Verifikasi data *promo code* tersimpan dan *Audit Log* terekam dengan tipe `PromoCode`.
- **Flow Uji Toggle Status Promo:**
  1. *Setup:* *Promo code* aktif di database.
  2. *Action:* Panggil endpoint `toggle` untuk mengubah status aktif/non-aktif.
  3. *Assert DB:* Pastikan nilai `is_active` berubah menjadi false.

### 2.7. Modul Superadmin (Audit & Override)
*Berfokus pada pemantauan tingkat tertinggi (Superadmin).*

- **Flow Uji Hak Akses & Logs:**
  1. *Setup:* Seed `audit_logs` dengan satu aksi khusus.
  2. *Action:* Akses halaman dashboard `/superadmin/audit-logs` sebagai Superadmin.
  3. *Assert:* Superadmin berhasil mengakses log (`assertStatus(200)` dan `assertSee`), sementara Admin atau role lain akan mendapat error 403.

### 2.8. Performa & Algoritma (Katalog)
*Berfokus pada optimasi Database.*

- **Flow Uji Query Eager Loading (Pencegahan N+1 Issue):**
  1. *Setup:* Pada `TestCase.php`, aktifkan `Model::preventLazyLoading(true);`.
  2. *Action:* Hit HTTP GET ke endpoint `/katalog` yang meload daftar Katalog.
  3. *Assert:* Request berjalan mulus tanpa `LazyLoadingViolationException`. Jika ada *exception*, artinya relasi (contoh: kategori alat) belum di-`with(['category'])`.
- **Flow Uji Paginasi Endpoint (API AlpineJS):**
  1. *Setup:* Seed 25 item alat.
  2. *Action:* Request `?page=2`.
  3. *Assert:* Payload JSON me-return persis 12 item (item ke 13-24), pastikan meta `last_page` adalah 3.

### 2.9. Modul Autentikasi & Checkout (E2E Flow)
*Berfokus pada perjalanan pengguna dari pendaftaran hingga pembelian.*

- **Flow Uji Registrasi & Login:**
  1. *Setup:* Simulasi *request* pendaftaran (`POST /signup`) dan login (`POST /login`).
  2. *Action:* Isi formulir dengan data pengguna valid dan *credentials* yang sesuai.
  3. *Assert DB & Auth:* Pastikan pengguna baru tercipta, sistem melakukan *auto-login*, dan *session* mengarah ke halaman `dashboard`.
- **Flow Uji Proses Checkout:**
  1. *Setup:* *User* login, sistem memiliki *Course* aktif di database.
  2. *Action:* Hit endpoint `POST /pembayaran/proses` yang mensimulasikan pemrosesan pembayaran dan *enrollment* otomatis.
  3. *Assert:* Pastikan entitas `Enrollment` terbentuk mengikat *User* ke *Course* dengan status `active`, dan *UserActivityLog* mencatat *enrolled*.

---

## 3. Infrastruktur & Perintah Eksekusi (Developer Guide)

Pastikan standar *environment testing* (seperti `DB_CONNECTION=sqlite` atau database testing terpisah di `phpunit.xml`) terkonfigurasi untuk menghindari manipulasi pada database dev/production.

**1. Menjalankan Seluruh Testing:**
```bash
php artisan test --compact
```
*(Direkomendasikan dijalankan setiap akan melakukan `git commit` / sebelum *Deployment*)*

**2. Menjalankan Tes Spesifik Pada Satu File:**
```bash
php artisan test tests/Feature/KeuanganControllerTest.php
```

**3. Menjalankan Tes Berdasarkan Nama Fungsi:**
```bash
php artisan test --filter=test_pendapatan_hari_ini_hanya_menghitung_status_approved
```

**4. Code Coverage (Laporan Cakupan Uji Kode):**
Membutuhkan `pcov` atau `Xdebug`.
```bash
php artisan test --coverage
```
Laporan ini berguna untuk melihat baris kode mana (if/else, switch) di dalam Controller/Model yang **belum tersentuh** oleh pengujian.

---

## 4. Standar Penulisan Tes (AAA Pattern)
Setiap file pengujian harus mengikuti *AAA Pattern*:
1. **Arrange (Persiapan):** Membuat Factory, Seeding database, Mocking.
2. **Act (Tindakan):** Memanggil Endpoint atau Fungsi (contoh: `$response = $this->post('/endpoint', $data);`).
3. **Assert (Validasi):** Mengevaluasi hasil (contoh: `$response->assertStatus(200);`).
