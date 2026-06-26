@extends('layouts.guest')

@section('title', '1Langkah — AI-Powered Learning Experience Platform')

@section('body')
<!-- Navbar -->
<div class="landing-nav">
    <div class="landing-nav-inner">
        <a href="{{ route('landing') }}" class="flex items-center gap-2" style="text-decoration:none;color:inherit;cursor:pointer">
            <div class="sidebar-logo" style="width:28px;height:28px;font-size:14px">1</div>
            <span style="font-weight:700;font-size:15px;color:var(--dark)">1Langkah</span>
        </a>
        <div class="landing-nav-links">
            <a href="#features" style="cursor:pointer">Fitur</a>
            <a href="{{ route('kursus') }}">Kursus</a>
            <a href="{{ route('online-bootcamp') }}">Bootcamp</a>
            <a href="{{ route('mentor') }}">Mentor</a>
            <a href="#" style="cursor:pointer">Enterprise</a>
            <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
            <a href="{{ route('signup') }}" class="btn btn-primary btn-sm">Daftar Gratis</a>
        </div>
    </div>
</div>

<!-- Hero -->
<div class="hero-section">
    <div class="hero-content">
        <div class="hero-badge">&#10024; AI-Powered Learning Experience Platform</div>
        <h1 class="hero-title">Satu Langkah<br>Menuju Masa Depan<br><span>Lebih Baik.</span></h1>
        <p class="hero-subtitle">Kuasai skill praktis, bangun pengalaman nyata dari proyek nyata, dan dapatkan karir impianmu — semua dalam satu platform.</p>
        <div class="hero-actions">
            <a href="{{ route('signup') }}" class="btn btn-primary btn-lg">Mulai Belajar Gratis</a>
            <a href="{{ route('kursus') }}" class="btn btn-outline btn-lg" style="color:#fff;border-color:rgba(255,255,255,.3)">Jelajahi Kursus</a>
        </div>
        <div class="hero-stats">
            <div class="text-center"><div class="hero-stat-value">100K+</div><div class="hero-stat-label">Pelajar Aktif</div></div>
            <div class="text-center"><div class="hero-stat-value">800+</div><div class="hero-stat-label">Kursus Premium</div></div>
            <div class="text-center"><div class="hero-stat-value">500+</div><div class="hero-stat-label">Mentor Berpengalaman</div></div>
            <div class="text-center"><div class="hero-stat-value">95%</div><div class="hero-stat-label">Course Completion</div></div>
        </div>
    </div>
</div>

<!-- Partners -->
<div class="landing-section" style="padding-top:48px;padding-bottom:0">
    <div class="landing-container text-center">
        <p style="font-size:14px;color:var(--text-light);margin-bottom:8px">Dipercaya oleh 300+ perusahaan & institusi terkemuka di Indonesia</p>
        <div class="partner-logos">
            <span class="partner-logo">Gojek</span>
            <span class="partner-logo">Shopee</span>
            <span class="partner-logo">Traveloka</span>
            <span class="partner-logo">Bukalapak</span>
            <span class="partner-logo">Pertamina</span>
            <span class="partner-logo">Telkom</span>
            <span class="partner-logo">Blibli</span>
            <span class="partner-logo">Tiket.com</span>
        </div>
    </div>
</div>

<!-- Features -->
<div class="landing-section landing-section-alt" id="features">
    <div class="landing-container">
        <div class="landing-section-header">
            <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:8px">PLATFORM LENGKAP</div>
            <h2 class="landing-section-title">Bukan sekadar platform kursus.<br>Ini ekosistem karir kamu.</h2>
            <p class="landing-section-desc">Dari skill pertama hingga pekerjaan impian — semua dalam satu platform yang dipersonalisasi AI.</p>
        </div>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background:var(--primary-bg);color:var(--primary)"><x-icon name="ai" /></div>
                <div class="feature-title">AI Learning Assistant</div>
                <div class="feature-desc">Tutor AI 24/7 yang siap menjelaskan topik sulit, memberikan feedback, dan membantu kamu belajar lebih efektif.</div>
                <div style="margin-top:12px"><span class="section-link">Pelajari lebih lanjut &rarr;</span></div>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:var(--purple-bg);color:var(--purple)"><x-icon name="path" /></div>
                <div class="feature-title">Personalized Learning Path</div>
                <div class="feature-desc">Jalur belajar yang dibentuk oleh AI berdasarkan tujuan karir, skill level, dan preferensi belajar kamu.</div>
                <div style="margin-top:12px"><span class="section-link">Pelajari lebih lanjut &rarr;</span></div>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background:var(--blue-bg);color:var(--blue)"><x-icon name="shield" /></div>
                <div class="feature-title">Verified Skill Passport</div>
                <div class="feature-desc">Profil skill digital terverifikasi dengan QR code yang bisa langsung kamu bagikan ke recruiter.</div>
                <div style="margin-top:12px"><span class="section-link">Pelajari lebih lanjut &rarr;</span></div>
            </div>
        </div>
        <div class="platform-pills">
            <div class="platform-pill"><x-icon name="play" class="" /> Interactive Courses</div>
            <div class="platform-pill"><x-icon name="award" /> Certificates</div>
            <div class="platform-pill"><x-icon name="folder" /> Portfolio Builder</div>
            <div class="platform-pill"><x-icon name="briefcase" /> Project Marketplace</div>
            <div class="platform-pill"><x-icon name="users" /> Mentor Marketplace</div>
            <div class="platform-pill"><x-icon name="target" /> Career Center</div>
            <div class="platform-pill"><x-icon name="message" /> Community</div>
            <div class="platform-pill"><x-icon name="barChart" /> Learning Analytics</div>
        </div>
    </div>
</div>

<!-- Popular Courses -->
<div class="landing-section">
    <div class="landing-container">
        <div class="section-header" style="max-width:100%">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:4px">KURSUS TERPOPULER</div>
                <h2 class="landing-section-title" style="text-align:left;margin-bottom:0">Mulai belajar sekarang</h2>
            </div>
            <a href="{{ route('kursus') }}" class="btn btn-outline">Lihat 800+ kursus</a>
        </div>
        <div class="grid-3" style="margin-top:24px">
            @foreach(array_slice($courses, 0, 3) as $c)
                <x-course-card :course="$c" />
            @endforeach
        </div>
    </div>
</div>

<!-- Bootcamp Section -->
<div class="landing-section landing-section-alt">
    <div class="landing-container">
        <div style="display:flex;gap:48px;align-items:center;flex-wrap:wrap">
            <div style="flex:1;min-width:300px">
                <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:8px">ONLINE & OFFLINE BOOTCAMP</div>
                <h2 class="landing-section-title" style="text-align:left">Belajar intensif dengan instruktur terbaik.</h2>
                <p style="font-size:14px;color:var(--text-muted);line-height:1.7;margin-bottom:24px">7–10 sesi tatap muka LIVE via Zoom atau hadir langsung di kelas offline di 3 kota besar Indonesia.</p>
                <div class="grid-2" style="gap:12px;margin-bottom:28px">
                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="video" class="" style="width:16px;height:16px;color:var(--success)" /><span><b>Online Bootcamp</b><br><span style="color:var(--text-light);font-size:12px">Via Zoom · 7–10 sesi LIVE</span></span></div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="mapPin" style="width:16px;height:16px;color:var(--purple)" /><span><b>Offline Bootcamp</b><br><span style="color:var(--text-light);font-size:12px">Tatap muka di 3 kota</span></span></div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="clock" style="width:16px;height:16px;color:var(--blue)" /><span><b>Rekaman 30 hari</b><br><span style="color:var(--text-light);font-size:12px">Akses setelah sesi</span></span></div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="award" style="width:16px;height:16px;color:var(--gold)" /><span><b>Sertifikat</b><br><span style="color:var(--text-light);font-size:12px">Terverifikasi</span></span></div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('online-bootcamp') }}" class="btn btn-primary">Lihat Jadwal Bootcamp</a>
                    <a href="{{ route('offline-bootcamp') }}" class="btn btn-outline">Offline Bootcamp</a>
                </div>
            </div>
            <div style="flex:1;min-width:300px">
                <a href="{{ route('detail-online-bootcamp', ['id' => 101]) }}" class="card" style="overflow:hidden;cursor:pointer;display:block;text-decoration:none;color:inherit">
                    <div style="width:100%;aspect-ratio:16/10;background:linear-gradient(135deg,#f093fb,#f5576c)"></div>
                    <div class="card-body">
                        <div class="course-card-title">{{ $bootcamp['title'] }}</div>
                        <div style="display:flex;gap:16px;margin-top:8px;font-size:12px;color:var(--text-muted)">
                            <span><x-icon name="users" style="width:14px;height:14px" /> {{ $bootcamp['participants'] }} peserta</span>
                            <span><x-icon name="calendar" style="width:14px;height:14px" /> Mulai {{ $bootcamp['startDate'] }}</span>
                        </div>
                        <div style="font-size:18px;font-weight:700;color:var(--dark);margin-top:12px">{{ $bootcamp['price'] }}</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mentor Section -->
<div class="landing-section">
    <div class="landing-container">
        <div class="landing-section-header">
            <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:8px">MENTOR MARKETPLACE</div>
            <h2 class="landing-section-title">Bimbingan 1-on-1 dari para ahli.</h2>
            <p class="landing-section-desc">500+ mentor berpengalaman dari Google, Gojek, Tokopedia, dan perusahaan top lainnya.</p>
        </div>
        <div class="grid-4">
            @foreach($mentors as $m)
                <x-mentor-card :mentor="$m" />
            @endforeach
        </div>
        <div class="text-center" style="margin-top:32px">
            <a href="{{ route('mentor') }}" class="btn btn-outline">Lihat 500+ Mentor</a>
        </div>
    </div>
</div>

<!-- Enterprise -->
<div class="landing-section landing-section-alt">
    <div class="landing-container">
        <div class="landing-section-header">
            <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:8px">ENTERPRISE & GOVERNMENT</div>
            <h2 class="landing-section-title">Solusi pelatihan skala enterprise.</h2>
            <p class="landing-section-desc">Tingkatkan kompetensi tim kamu dengan program pelatihan yang terukur dan terstruktur.</p>
        </div>
        <div class="enterprise-features" style="max-width:640px;margin:0 auto">
            <div class="enterprise-feature"><span class="check">&#10003;</span><span>Dashboard analytics karyawan & tim</span></div>
            <div class="enterprise-feature"><span class="check">&#10003;</span><span>Kurikulum custom sesuai kebutuhan bisnis</span></div>
            <div class="enterprise-feature"><span class="check">&#10003;</span><span>Sertifikasi massal yang terverifikasi</span></div>
            <div class="enterprise-feature"><span class="check">&#10003;</span><span>Integrasi dengan HR system perusahaan</span></div>
            <div class="enterprise-feature"><span class="check">&#10003;</span><span>Dedicated account manager</span></div>
        </div>
        <div class="flex gap-3 justify-center" style="margin-top:32px">
            <button class="btn btn-primary">Hubungi Sales</button>
            <button class="btn btn-outline">Lihat Demo</button>
        </div>
    </div>
</div>

<!-- Testimonials -->
<div class="landing-section">
    <div class="landing-container">
        <div class="landing-section-header">
            <div style="font-size:13px;font-weight:700;color:var(--primary);margin-bottom:8px">CERITA NYATA PELAJAR</div>
            <h2 class="landing-section-title">Mereka sudah membuktikannya.</h2>
        </div>
        <div class="grid-3">
            @foreach($testimonials as $t)
                <div class="testimonial-card">
                    <div class="testimonial-quote">"{{ $t['quote'] }}"</div>
                    <div class="testimonial-author">
                        <x-avatar :initials="$t['initials']" />
                        <div><div class="testimonial-name">{{ $t['name'] }}</div><div class="testimonial-role">{{ $t['role'] }}</div></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA -->
<div class="landing-section">
    <div class="landing-container">
        <div class="cta-section" style="position:relative;z-index:1">
            <h2 style="font-size:32px;font-weight:800;margin-bottom:12px">Wujudkan karir impianmu<br>mulai hari ini.</h2>
            <p style="color:rgba(255,255,255,.6);font-size:16px;margin-bottom:32px;max-width:480px;margin-left:auto;margin-right:auto">Bergabung dengan 100,000+ pelajar yang sudah membangun karir mereka bersama 1Langkah.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('signup') }}" class="btn btn-primary btn-lg">Daftar Gratis Sekarang</a>
            </div>
            <div style="margin-top:16px;font-size:13px;color:rgba(255,255,255,.4)">Sudah punya akun? <a href="{{ route('login') }}" style="color:#fff;text-decoration:underline;cursor:pointer">Masuk di sini</a></div>
            <div style="margin-top:24px;display:flex;gap:24px;justify-content:center;font-size:12px;color:rgba(255,255,255,.4)">
                <span>&#10003; Gratis untuk selamanya</span>
                <span>&#10003; Tanpa kartu kredit</span>
                <span>&#10003; Batalkan kapan saja</span>
                <span>&#10003; 300+ hiring partner</span>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="landing-footer">
    <div class="footer-grid">
        <div>
            <div class="flex items-center gap-2" style="margin-bottom:16px">
                <div class="sidebar-logo" style="width:28px;height:28px;font-size:14px">1</div>
                <span style="font-weight:700;font-size:15px;color:#fff">1Langkah</span>
            </div>
            <p style="font-size:13px;line-height:1.7;max-width:300px">AI-Powered Learning Experience Platform · Made in Indonesia</p>
        </div>
        <div>
            <div class="footer-col-title">Platform</div>
            <a class="footer-link" href="{{ route('kursus') }}">Kursus</a>
            <a class="footer-link" href="{{ route('online-bootcamp') }}">Bootcamp Online</a>
            <a class="footer-link" href="{{ route('offline-bootcamp') }}">Bootcamp Offline</a>
            <a class="footer-link" href="{{ route('mentor') }}">Mentor</a>
            <a class="footer-link" href="#">Job Board</a>
            <a class="footer-link" href="#">Community</a>
        </div>
        <div>
            <div class="footer-col-title">Company</div>
            <a class="footer-link" href="#">Tentang Kami</a>
            <a class="footer-link" href="#">Karir</a>
            <a class="footer-link" href="#">Blog</a>
            <a class="footer-link" href="#">Partner</a>
        </div>
        <div>
            <div class="footer-col-title">Support</div>
            <a class="footer-link" href="#">Pusat Bantuan</a>
            <a class="footer-link" href="#">Kebijakan Privasi</a>
            <a class="footer-link" href="#">Syarat & Ketentuan</a>
            <a class="footer-link" href="#">Status Sistem</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} 1Langkah Technologies. All rights reserved.</span>
        <span style="opacity:.5">v2.1.0</span>
    </div>
</div>
@endsection
