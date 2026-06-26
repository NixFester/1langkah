@extends('layouts.guest')

@section('title', 'Masuk — 1Langkah')

@section('body')
<div class="auth-page">
    <div class="auth-left">
        <div class="auth-left-content">
            <a href="{{ route('landing') }}" class="flex items-center gap-2" style="margin-bottom:40px;text-decoration:none;color:inherit">
                <div class="sidebar-logo" style="width:32px;height:32px;font-size:16px">1</div>
                <span style="font-weight:700;font-size:16px;color:#fff">1Langkah</span>
            </a>
            <h1 style="color:#fff;font-size:28px;font-weight:800;line-height:1.3;margin-bottom:16px">Selamat datang kembali</h1>
            <p style="color:rgba(255,255,255,.6);font-size:15px;line-height:1.7">Lanjutkan belajar hari ini dan raih karir impianmu.</p>
            <div style="display:flex;flex-direction:column;gap:16px;margin-top:40px">
                <div style="display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.5);font-size:13px">
                    <span style="color:var(--success);font-weight:700">&#10003;</span> Belajar
                    <span style="flex:1;height:1px;background:rgba(255,255,255,.1)"></span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.5);font-size:13px">
                    <span style="color:var(--success);font-weight:700">&#10003;</span> Sertifikat
                    <span style="flex:1;height:1px;background:rgba(255,255,255,.1)"></span>
                </div>
                <div style="display:flex;align-items:center;gap:12px;color:rgba(255,255,255,.5);font-size:13px">
                    <span style="color:var(--gold);font-weight:700">&#9733;</span> Career
                    <span style="flex:1;height:1px;background:rgba(255,255,255,.1)"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="auth-right">
        <form class="auth-form" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <h2>Masuk ke akun kamu</h2>
            <p>Masuk untuk melanjutkan perjalanan belajarmu</p>
            <div class="social-btns" style="margin-bottom:4px">
                <button type="button" class="social-btn">G</button>
                <button type="button" class="social-btn">in</button>
            </div>
            <div class="auth-divider">atau masuk dengan email</div>
            <div class="input-group" style="margin-bottom:16px">
                <label>Email</label>
                <input class="input" type="email" name="email" placeholder="nama@email.com" required />
            </div>
            <div class="input-group" style="margin-bottom:24px">
                <label>Password</label>
                <input class="input" type="password" name="password" placeholder="Masukkan password" required />
            </div>
            <button type="submit" class="btn btn-primary btn-full btn-lg">Masuk</button>
            <div class="auth-footer">Belum punya akun? <a href="{{ route('signup') }}">Daftar sekarang</a></div>
        </form>
    </div>
</div>
@endsection
