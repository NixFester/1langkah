# Update Log (Sejak 17 Juli 2026)

Berikut adalah rangkuman pengerjaan dan pembaruan sistem yang telah diselesaikan sejak 17 Juli 2026:

### 27 Juli 2026
**Update:** Mengoptimalkan layout dashboard, menyempurnakan hero image halaman landing, dan build aset production.

**Detail:**
- Mengoptimalkan *layout dashboard*, menyembunyikan elemen gamifikasi (seperti *leaderboard*), dan memperbarui terjemahan antarmuka.
- Menambahkan gambar *hero* baru dan memperbarui komponen statistik (2000+ pelajar, 10+ kursus, 95% puas) pada halaman *landing* dan pendaftaran (*signup*).
- Menyesuaikan format gambar *hero* dengan mengembalikannya ke format PNG agar mendapatkan *blending* desain yang sempurna.
- Melakukan *build* pada seluruh aset terbaru (CSS/JS) agar siap dirilis ke *production*.

### 25 Juli 2026
**Update:** Mengimplementasikan fitur unggah gambar terkompresi, redesign halaman About, dan perbaikan path ikon.

**Detail:**
- Mengimplementasikan `ImageService` untuk mengompresi gambar secara otomatis dan mengubah formatnya menjadi WebP dengan batasan *upload* diperbesar hingga 20MB.
- Memperbaiki kelancaran unggah *file* pada form profil pengguna, pengelolaan materi *courses*, dan konfigurasi *bootcamp*.
- Mendesain ulang struktur tata letak bagian *Leadership* di halaman *About* menjadi lebih profesional serta menyisipkan *quote* inspiratif.
- Mengaktifkan *language switcher*, menyusun isi konten komprehensif pada halaman *About*, serta membuat sistem gambar *fallback* pada katalog.
- Memperbaiki logika *path resolution* ikon (menggunakan `base_path`) untuk meningkatkan keandalan pemuatan ikon.
- Memperbarui referensi *manifest* dan berkas aset gaya (*stylesheet*) untuk halaman *About*.

### 24 Juli 2026
**Update:** Penambahan informasi kontak bisnis pada footer.

**Detail:**
- Menambahkan bagian khusus untuk informasi kontak (telepon/email) dan alamat perusahaan di *footer* halaman *landing*, guna memenuhi standar kepatuhan verifikasi *payment gateway* (Xendit).

### 22 Juli 2026
**Update:** Implementasi halaman error khusus (*custom error pages*).

**Detail:**
- Mengembangkan halaman *error* kustom terpadu (seperti 404, 500, dll) yang menggunakan ikonografi dan layout yang sama dengan identitas desain (*branding*) aplikasi utama.
