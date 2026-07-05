# 1Langkah - Diagram Alur Sistem Berbasis Peran

## 1. Alur Autentikasi & Pengalihan

```mermaid
flowchart TD
    %% Login Entry
    START([Pengguna Membuka /login]) --> CREDENTIALS
    
    subgraph LOGIN["🔐 Proses Login"]
        CREDENTIALS[Masukkan Email & Kata Sandi] --> AUTH
        AUTH{Auth::attempt}
        AUTH -->|Tidak Valid| ERROR[Tampilkan Pesan Kesalahan]
        ERROR --> CREDENTIALS
        AUTH -->|Valid| ROLE_CHECK
    end
    

    subgraph REDIRECT["Pengalihan Berdasarkan Peran"]
        ROLE_CHECK{Peran Pengguna}
        ROLE_CHECK -->|superadmin| SA["/superadmin"]
        ROLE_CHECK -->|admin| ADM["/admin/dashboard"]
        ROLE_CHECK -->|keuangan| KEU["/keuangan"]
        ROLE_CHECK -->|marketing| MKT["/marketing"]
        ROLE_CHECK -->|mentor| MEN["/mentor"]
        ROLE_CHECK -->|student| STU["/dashboard"]
    end
    
    SA --> SA_DASH
    ADM --> ADM_DASH
    KEU --> KEU_DASH
    MKT --> MKT_DASH
    MEN --> MEN_DASH
    STU --> STU_DASH
    
    SA_DASH["Dashboard Superadmin"]
    ADM_DASH["Dashboard Admin"]
    KEU_DASH["Dashboard Keuangan"]
    MKT_DASH["Dashboard Marketing"]
    MEN_DASH["Dashboard Mentor"]
    STU_DASH["Dashboard Mahasiswa"]
```

---

## 2. Hierarki Peran & Kontrol Akses

```mermaid
flowchart TB
    subgraph HIERARCHY["🏛️ Hierarki Peran"]
        SA[Superadmin<br/>Level 6]
        ADM[Admin<br/>Level 5]
        KEU[Keuangan<br/>Level 4]
        MKT[Marketing<br/>Level 3]
        MEN[Mentor<br/>Level 2]
        STU[Mahasiswa<br/>Level 1]
    end
    
    SA -->|"mengelola"| ADM
    SA -->|"mengelola"| KEU
    SA -->|"mengelola"| MKT
    SA -->|"mengelola"| MEN
    ADM -->|"mengawasi"| CONTENT[Konten & Pengguna]
    KEU -->|"mengelola"| FINANCE[💰 Pembayaran]
    MKT -->|"mengelola"| CAMPAIGNS[📢 Promo]
    MEN -->|"mengajar"| STUDENTS[👨‍🎓 Mahasiswa]
    CONTENT --> STUDENTS
```

---

## 3. Alur Verifikasi Pembayaran (Alur Utama Keuangan)

```mermaid
flowchart TD
    subgraph STUDENT_FLOW["👨‍🎓 Pembayaran Mahasiswa"]
        STU_LOGIN[Mahasiswa Login] --> STU_COURSE[Melihat Kursus]
        STU_COURSE --> CLICK_ENROLL[Klik Daftar]
        CLICK_ENROLL --> CHOOSE_PAY[Pilih Metode Pembayaran]
        CHOOSE_PAY{Choose Payment Method}
        CHOOSE_PAY -->|Transfer Bank| UPLOAD_PROOF[Unggah Bukti Pembayaran]
        CHOOSE_PAY -->|Kode Promo| ENTER_CODE[Masukkan Kode Promo]
        ENTER_CODE --> UPLOAD_PROOF
        UPLOAD_PROOF --> SUBMIT_PAY[Kirim Pembayaran]
    end
    
    subgraph KEUANGAN_FLOW["💼 Verifikasi Keuangan"]
        SUBMIT_PAY --> QUEUE[📋 Antrean Pembayaran]
        KEU_LOGIN[Keuangan Login] --> KEU_DASH
        KEU_DASH[Dashboard] --> VIEW_QUEUE[Lihat Pembayaran Tertunda]
        VIEW_QUEUE --> SELECT_PAY[Pilih Pembayaran]
        SELECT_PAY --> CHECK_DETAILS[Periksa Detail Pembayaran]
        CHECK_DETAILS --> CHECK_PROOF{Cek Bukti Gambar}
        
        CHECK_PROOF -->|Jelas| ENTER_NOTES[Tambahkan Catatan Verifikasi]
        CHECK_PROOF -->|Tidak Jelas| REJECT[Tolak dengan Alasan]
        CHECK_PROOF -->|Jumlah Salah| REJECT
        
        ENTER_NOTES --> APPROVE
        APPROVE{Setujui?}
        APPROVE -->|Ya| CONFIRM_APPROVE[Konfirmasi Persetujuan]
        APPROVE -->|Tidak| REJECT
        
        CONFIRM_APPROVE --> SUCCESS
        
        REJECT --> REJECT_REASON[Masukkan Alasan Penolakan]
        REJECT_REASON --> CONFIRM_REJECT[Konfirmasi Penolakan]
        CONFIRM_REJECT --> REJECTED
    end
    
    subgraph RESULT["📊 Pembaruan Sistem"]
        SUCCESS[✅ Pembayaran Disetujui] --> ENROLL
        REJECTED[❌ Pembayaran Ditolak] --> NOTIFY_REJ
        ENROLL --> AUTO_ENROLL[Otomatis Daftarkan Mahasiswa]
        AUTO_ENROLL --> NOTIFY_STUDENT
        AUTO_ENROLL --> UPDATE_STATS[Perbarui Statistik]
        NOTIFY_REJ[Beritahu Mahasiswa tentang Penolakan] --> END_REJ([Proses Selesai])
        NOTIFY_STUDENT[Beritahu Mahasiswa tentang Pendaftaran] --> UPDATE_MENTOR[Perbarui Statistik Mentor]
        UPDATE_STATS --> UPDATE_MENTOR
        UPDATE_MENTOR --> UPDATE_MKT[Perbarui Analitik Marketing]
        UPDATE_MKT --> END_SUC([Pendaftaran Selesai])
    end
```

---

## 4. Alur Pembuatan & Penugasan Kursus

```mermaid
flowchart TD
    subgraph ADMIN_CREATE["🛠️ Admin Membuat Kursus"]
        ADM_LOGIN[Admin Login] --> ADM_COURSE[Buka Menu Kursus]
        ADM_COURSE --> CREATE_COURSE[Klik Kursus Baru]
        CREATE_COURSE --> FILL_DETAILS[Isi Detail Kursus]
        FILL_DETAILS --> SELECT_LEVEL[Pilih Level]
        SELECT_LEVEL --> CHOOSE_MENTOR
        
        CHOOSE_MENTOR{Pilih Mentor}
        CHOOSE_MENTOR -->|Mentor Ada| SELECT_EX[Pilih dari Daftar]
        CHOOSE_MENTOR -->|Mentor Baru| CREATE_MEN[Buat Profil Mentor]
        CREATE_MEN --> LINK_MEN[Tautkan Mentor ke Kursus]
        SELECT_EX --> LINK_MEN
        LINK_MEN --> PUBLISH[Terbitkan Kursus]
    end
    
    subgraph MENTOR_SETUP["👨‍🏫 Mentor Mempersiapkan"]
        PUBLISH --> MEN_NOTIFY[Mentor Menerima Notifikasi]
        MEN_NOTIFY --> MEN_LOGIN[Mentor Login]
        MEN_LOGIN --> MEN_COURSE[Lihat Kursus yang Ditugaskan]
        MEN_COURSE --> ADD_CHAPTERS[Tambahkan Bab & Video]
        ADD_CHAPTERS --> ADD_QUIZ[Tambahkan Soal Kuis]
        ADD_QUIZ --> SET_PRICE[Atur Harga]
        SET_PRICE --> COURSE_LIVE[Kursus Tayang]
    end
    
    subgraph STUDENT_ENROLL["👨‍🎓 Mahasiswa Menemukan"]
        COURSE_LIVE --> COURSE_LIST[Kursus di Katalog]
        COURSE_LIST --> STU_BROWSE[Mahasiswa Menjelajah]
        STU_BROWSE --> STU_DETAIL[Lihat Detail Kursus]
        STU_DETAIL --> STU_ENROLL{Klik Daftar}
        STU_ENROLL --> PAYMENT_FLOW
    end
    
    PAYMENT_FLOW --> PAYMENT["💳 Alur Pembayaran"]
    
    subgraph FEEDBACK_LOOP["🔄 Umpan Balik"]
        PAYMENT --> STUDENT_ENROLLED[Mahasiswa Terdaftar]
        STUDENT_ENROLLED --> COMPLETES[Menyelesaikan Kursus]
        COMPLETES --> LEAVES_RATING[Memberi Rating]
        LEAVES_RATING --> RATING_UPDATE[Rating Diperbarui]
        RATING_UPDATE --> MKT_ANALYTICS[Marketing Melihat Performa]
        MKT_ANALYTICS --> ADM_REVIEW[Admin Meninjau Statistik Mentor]
    end
```

---

## 5. Alur Kode Promo (Alur Utama Marketing)

```mermaid
flowchart TD
    subgraph MARKETING_CREATE["📢 Marketing Membuat Promo"]
        MKT_LOGIN[Marketing Login] --> MKT_DASH
        MKT_DASH --> MKT_PROMO[Buka Menu Kode Promo]
        MKT_PROMO --> CREATE_PROMO[Klik Promo Baru]
        CREATE_PROMO --> SELECT_TYPE{Tipe Diskon}
        SELECT_TYPE -->|Persentase| ENTER_PCT[Masukkan Persentase]
        SELECT_TYPE -->|Nominal Tetap| ENTER_AMT[Masukkan Nominal]
        SELECT_TYPE -->|Paket Bundle| BUNDLE[Atur Aturan Bundle]
        
        ENTER_PCT --> SET_VALUE
        ENTER_AMT --> SET_VALUE
        BUNDLE --> SET_VALUE
        SET_VALUE[Atur Nilai] --> SET_LIMIT[Atur Batas Penggunaan]
        SET_LIMIT --> SET_EXPIRY[Atur Tanggal Kadaluarsa]
        SET_EXPIRY --> SET_COND[Atur Syarat & Ketentuan]
        SET_COND --> GENERATE_CODE[Hasilkan Kode]
        GENERATE_CODE --> PROMOCODE[🎫 Kode Promo Dibuat]
    end
    
    subgraph STUDENT_USES["👨‍🎓 Mahasiswa Menggunakan Kode"]
        PROMOCODE --> SHARES[Marketing Membagikan Kode]
        SHARES --> STU_CHECKOUT[Mahasiswa di Checkout]
        STU_CHECKOUT --> ENTER_CODE[Masukkan Kode]
        ENTER_CODE --> VALIDATE{Kode Valid?}
        VALIDATE -->|Tidak Valid| SHOW_ERR[Tampilkan Kesalahan]
        VALIDATE -->|Kadaluarsa| SHOW_ERR
        VALIDATE -->|Sudah Penuh| SHOW_ERR
        SHOW_ERR --> TRY_AGAIN[Coba Lagi]
        VALIDATE -->|Valid| APPLY_DISC[Terapkan Diskon]
        TRY_AGAIN --> ENTER_CODE
        APPLY_DISC --> SHOW_FINAL[Tampilkan Harga Akhir]
        SHOW_FINAL --> CONFIRM_PAY[Konfirmasi Pembayaran]
    end
    
    subgraph TRACKING["📊 Pelacakan Sistem"]
        APPLY_DISC --> TRACK[Lacak Penggunaan Promo]
        CONFIRM_PAY --> TRACK
        TRACK --> UPDATE_COUNT[Perbarui Jumlah Penggunaan]
        UPDATE_COUNT --> UPDATE_REV[Perbarui Pendapatan]
        UPDATE_REV --> MKT_STATS[Dashboard Marketing Diperbarui]
        MKT_STATS --> CAMPAIGN_ANALYTICS[ROI Kampanye Dihitung]
    end
```

---

## 6. Alur Pelacakan Mahasiswa oleh Mentor

```mermaid
flowchart TD
    subgraph MENTOR_DASHBOARD["👨‍🏫 Dashboard Mentor"]
        MEN_LOGIN[Mentor Login] --> MEN_DASH[Dashboard]
        MEN_DASH --> VIEW_COURSES[Kursus Saya]
        VIEW_COURSES --> SELECT_COURSE{Pilih Kursus}
        SELECT_COURSE --> STUDENT_LIST[Lihat Mahasiswa Terdaftar]
        STUDENT_LIST --> STUDENT_DETAIL{Klik Mahasiswa}
        STUDENT_DETAIL --> PROGRESS[Lacak Progres]
        PROGRESS --> PROGRESS_BAR[Tampilan Bar Progres]
        PROGRESS_BAR --> LAST_ACTIVITY[Waktu Aktivitas Terakhir]
        LAST_ACTIVITY --> OVERALL_STATS[Tingkat Penyelesaian]
    end
    
    subgraph INTERACTION["💬 Interaksi Mentor-Mahasiswa"]
        PROGRESS --> SEND_NOTE[Kirim Catatan ke Mahasiswa]
        PROGRESS --> FLAGGED[Tandai Masalah Mahasiswa]
        FLAGGED --> ALERT_ADMIN[Beritahu Admin]
        PROGRESS --> MENTION_IN_SESSION[Tambahkan ke Sesi]
    end
    
    subgraph ANALYTICS["📈 Tampilan Analitik"]
        STUDENT_LIST --> BULK_STATS[Statistik Keseluruhan]
        BULK_STATS --> HEATMAP[Peta Panas Keterlibatan]
        HEATMAP --> DROP_OFF[Identifikasi Titik Putus]
        DROP_OFF --> COURSE_IMP[Perbaikan Kursus]
        COURSE_IMP --> UPDATE_CONTENT[Perbarui Konten Kursus]
    end
```

---

## 7. Alur Audit Superadmin

```mermaid
flowchart TD
    subgraph LOG_ACTIONS["📝 Sistem Mencatat Aktivitas Ini"]
        CREATE[Operasi Buat]
        UPDATE[Operasi Ubah]
        DELETE[Operasi Hapus]
        LOGIN[Login/Logout]
        PAYMENT[Verifikasi/Tolak Pembayaran]
        ROLE_CHANGE[Perubahan Peran]
    end
    
    subgraph AUDIT_VIEW["🔍 Log Audit Superadmin"]
        SA_LOGIN[Superadmin Login] --> SA_DASH
        SA_DASH --> AUDIT_LINK[Klik Log Audit]
        AUDIT_LINK --> FILTER_DATE[Filter berdasarkan Tanggal]
        FILTER_DATE --> FILTER_ROLE[Filter berdasarkan Peran]
        FILTER_ROLE --> FILTER_ACTION{Filter berdasarkan Aksi}
        FILTER_ACTION -->|Semua| ALL_ACTIONS
        FILTER_ACTION -->|Buat| CREATES_ONLY
        FILTER_ACTION -->|Ubah| UPDATES_ONLY
        FILTER_ACTION -->|Hapus| DELETES_ONLY
        FILTER_ACTION -->|Pembayaran| PAYMENTS_ONLY
        
        ALL_ACTIONS --> VIEW_LOG[Lihat Log Lengkap]
        CREATES_ONLY --> VIEW_LOG
        UPDATES_ONLY --> VIEW_LOG
        DELETES_ONLY --> VIEW_LOG
        PAYMENTS_ONLY --> VIEW_LOG
        
        VIEW_LOG --> DETAIL{Lihat Detail?}
        DETAIL -->|Ya| SHOW_CHANGES[Tampilkan Nilai Lama vs Baru]
        DETAIL -->|Tidak| EXPORT
        SHOW_CHANGES --> EXPORT
        EXPORT[Export ke CSV/PDF]
    end
    
    subgraph ALERTS["🚨 Peringatan Otomatis"]
        LOG_ACTIONS --> CHECK_SUSPICIOUS
        CHECK_SUSPICIOUS{Aktivitas Mencurigakan?}
        CHECK_SUSPICIOUS -->|Ya| SEND_ALERT[Email ke Superadmin]
        CHECK_SUSPICIOUS -->|Tidak| STORE_LOG
        SEND_ALERT --> STORE_LOG
        STORE_LOG[(Database: audit_logs)]
    end
```

---

## 8. Peta Interkoneksi Peran Lengkap

```mermaid
flowchart TB
    subgraph STUDENT_NODE["👨‍🎓 MAHASISWA"]
        S1[Menjelajah Kursus]
        S2[Daftar dengan Pembayaran]
        S3[Belajar & Menonton Video]
        S4[Menyelesaikan Kuis]
        S5[Memberi Rating & Ulasan]
        S6[Membuat Portfolio]
    end
    
    subgraph MENTOR_NODE["👨‍🏫 MENTOR"]
        M1[Membuat Konten Kursus]
        M2[Melacak Progres Mahasiswa]
        M3[Mengadakan Sesi]
        M4[Menjawab Pertanyaan]
        M5[Melihat Rating & Ulasan]
    end
    
    subgraph ADMIN_NODE["🛠️ ADMIN"]
        A1[Mengelola Semua Konten]
        A2[Menetapkan Mentor]
        A3[Menangani Masalah Pengguna]
        A4[Meninjau Laporan]
    end
    
    subgraph KEUANGAN_NODE["💼 KEUANGAN"]
        K1[Memverifikasi Pembayaran]
        K2[Melacak Pendapatan]
        K3[Menangani Pengembalian Dana]
        K4[Membuat Laporan Keuangan]
    end
    
    subgraph MARKETING_NODE["📢 MARKETING"]
        MK1[Membuat Kode Promo]
        MK2[Melacak Kampanye]
        MK3[Menganalisis Tren]
        MK4[Mengelola Promo Acara]
    end
    
    subgraph SUPERADMIN_NODE["👑 SUPERADMIN"]
        SU1[Mengelola Semua Pengguna]
        SU2[Melihat Log Audit]
        SU3[Konfigurasi Sistem]
        SU4[Menangani Eskalasi]
    end
    
    %% Student connections
    S2 -->|"Pembayaran diajukan"| K1
    S3 -->|"Progres dilacak"| M2
    S4 -->|"Kuis selesai"| M2
    S5 -->|"Rating diajukan"| MK3
    
    %% Mentor connections
    M1 -->|"Kursus dibuat"| A1
    M2 -->|"Laporan progres"| A4
    M5 -->|"Ulasan diterima"| MK3
    
    %% Admin connections
    A1 -->|"Mentor ditugaskan"| M1
    A2 -->|"Pengguna dikelola"| SU1
    A3 -->|"Dieskalasi ke"| SU4
    
    %% Keuangan connections
    K1 -->|"Diverifikasi"| S2
    K2 -->|"Data pendapatan"| MK3
    K4 -->|"Laporan dibuat"| SU4
    
    %% Marketing connections
    MK1 -->|"Promo dibuat"| S2
    MK3 -->|"Analitik dibagikan"| A4
    MK4 -->|"Acara dipromosikan"| A1
    
    %% Superadmin connections
    SU1 -->|"Peran pengguna diatur"| A1
    SU2 -->|"Entri log"| ALL[Semua Departemen]
    SU4 -->|"Resolusi"| ALL
    
    style SUPERADMIN_NODE fill:#7c3aed,color:#fff
    style ADMIN_NODE fill:#ef4444,color:#fff
    style KEUANGAN_NODE fill:#f59e0b,color:#fff
    style MARKETING_NODE fill:#ec4899,color:#fff
    style MENTOR_NODE fill:#3b82f6,color:#fff
    style STUDENT_NODE fill:#10b981,color:#fff
```

---

## 9. State Machine Pengguna

```mermaid
stateDiagram-v2
    [*] --> Tamu: Mengunjungi Situs
    
    Tamu --> Mahasiswa: Daftar
    Mahasiswa --> [*]: Akun Dihapus
    
    Mahasiswa --> Mentor: Ditunjuk sebagai Mentor
    Mahasiswa --> Admin: Dipromosikan ke Admin
    
    Mentor --> Mahasiswa: Demosikan dari Mentor
    Mentor --> Admin: Dipromosikan ke Admin
    
    Admin --> Superadmin: Dipromosikan ke Superadmin
    Admin --> Mentor: Demosikan
    Admin --> Mahasiswa: Demosikan
    
    Superadmin --> Admin: Demosikan
    Superadmin --> [*]: Akun Dihapus
    
    state Mahasiswa {
        [*] --> PenggunaGratis
        PenggunaGratis --> PenggunaPremium: Daftar di Kursus
        PenggunaPremium --> PenggunaGratis: Kursus Selesai
        PenggunaGratis --> PenggunaGratis: Menonton Konten Gratis
    }
    
    state Admin {
        [*] --> AdminKonten
        AdminKonten --> AdminKeuangan: Ditugaskan Keuangan
        AdminKeuangan --> AdminKonten: Ditugaskan Ulang
    }
```

---

## 10. Alur Data Antar Peran

```mermaid
sequenceDiagram
    participant STU as 👨‍🎓 Mahasiswa
    participant SYS as 🖥️ Sistem
    participant KEU as 💼 Keuangan
    participant ADM as 🛠️ Admin
    participant MKT as 📢 Marketing
    participant MEN as 👨‍🏫 Mentor
    
    STU->>SYS: Mengirim bukti pembayaran
    SYS->>KEU: Pembayaran dalam antrean
    KEU->>KEU: Memeriksa bukti
    KEU->>SYS: Menyetujui pembayaran
    SYS->>STU: Pendaftaran dikonfirmasi
    SYS->>MEN: Mahasiswa baru terdaftar
    
    MEN->>SYS: Membuat konten kursus
    SYS->>ADM: Konten menunggu tinjauan
    ADM->>SYS: Menyetujui konten
    SYS->>MKT: Kursus dipublikasikan
    
    MKT->>SYS: Membuat kode promo
    STU->>SYS: Menggunakan promo saat checkout
    SYS->>KEU: Pembayaran dengan diskon
    KEU->>SYS: Memverifikasi pembayaran diskon
    SYS->>MKT: Penggunaan promo dilacak
    
    Note over SYS: Superadmin memantau semua aktivitas
    Note over SYS: Log audit mencatat semuanya
```

---

## Ringkasan: Referensi Cepat

### Alur Verifikasi Pembayaran
```
Mahasiswa kirim bukti → Keuangan tinjau → Setujui/Tolak → Mahasiswa diberitahu → Terdaftar/Coba Lagi
```

### Alur Pembuatan Kursus
```
Admin buat kursus → Tetapkan mentor → Mentor tambah konten → Dipublikasikan → Mahasiswa daftar
```

### Alur Kode Promo
```
Marketing buat kode → Bagikan ke mahasiswa → Mahasiswa gunakan saat checkout → Pendapatan dilacak
```

### Ringkasan Interkoneksi
| Aksi Oleh | Mempengaruhi | Hasil |
|-----------|--------------|-------|
| Mahasiswa mendaftar | Keuangan, Mentor | Antrean pembayaran, mahasiswa baru |
| Keuangan menyetujui | Mahasiswa, Marketing | Pendaftaran, analitik |
| Marketing buat promo | Mahasiswa, Keuangan | Diskon, pendapatan |
| Mentor update kursus | Admin, Mahasiswa | Konten diperbaiki |
| Admin tetapkan mentor | Mentor | Tanggung jawab baru |
| Superadmin tinjau log | Semua | Kesehatan sistem |
