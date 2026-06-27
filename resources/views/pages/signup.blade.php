@extends('layouts.guest')

@section('title', 'Daftar — 1Langkah')

@section('body')
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-left-content">
            <a href="{{ route('landing') }}" class="flex items-center gap-2" style="margin-bottom:40px;text-decoration:none;color:inherit">
                <div class="sidebar-logo" style="width:32px;height:32px;font-size:16px">1</div>
                <span style="font-weight:700;font-size:16px;color:#fff">1Langkah</span>
            </a>
            <h1 style="color:#fff;font-size:28px;font-weight:800;line-height:1.3;margin-bottom:16px">Mulai perjalanan belajarmu</h1>
            <p style="color:rgba(255,255,255,.6);font-size:15px;line-height:1.7">Daftar gratis dan akses 800+ kursus premium dari mentor terbaik Indonesia.</p>
        </div>
    </div>
    <div class="auth-right">
        <form class="auth-form" method="POST" action="{{ route('signup.submit') }}">
            @csrf
            <h2>Buat akun baru</h2>
            <p>Mulai belajar gratis, tanpa kartu kredit</p>
            <div class="social-btns" style="margin-bottom:4px">
                <button type="button" class="social-btn">G</button>
                <button type="button" class="social-btn">in</button>
            </div>
            <div class="auth-divider">atau daftar dengan email</div>
            @if ($errors->any())
                <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:10px 14px;font-size:13px;color:#b91c1c;margin-bottom:8px">
                    <ul style="margin:0;padding-left:16px">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                <div class="input-group"><label>Nama Depan</label><input class="input" name="first_name" placeholder="John" required /></div>
                <div class="input-group"><label>Nama Belakang</label><input class="input" name="last_name" placeholder="Doe" required /></div>
            </div>
            <div class="input-group" style="margin-bottom:16px">
                <label>Email</label>
                <input class="input" type="email" name="email" placeholder="nama@email.com" required />
            </div>
            <div class="input-group" style="margin-bottom:24px">
                <label>Password</label>
                <input class="input" type="password" name="password" placeholder="Minimal 8 karakter" required minlength="8" />
            </div>
            <button type="submit" class="btn btn-primary btn-full btn-lg">Daftar Gratis</button>
            <div style="margin-top:16px;font-size:12px;color:var(--text-light);text-align:center">
                &#10003; Gratis untuk selamanya &nbsp; &#10003; Tanpa kartu kredit &nbsp; &#10003; Batalkan kapan saja
            </div>
            <div class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></div>
        </form>
    </div>
</div>
@endsection
