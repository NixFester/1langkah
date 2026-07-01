@extends('layouts.app', ['activePage' => 'pengaturan'])

@section('title', 'Pengaturan Akun — 1Langkah')

@section('content')
@php $u = $authUser; @endphp
<div class="w-full px-2 pb-8">

<div class="page-title" style="margin-bottom:8px">Pengaturan Akun</div>
<p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">Kelola informasi profil dan keamanan akun kamu</p>

{{-- Success flash --}}
@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:var(--radius-sm);padding:12px 16px;font-size:13px;color:#065f46;margin-bottom:20px;display:flex;align-items:center;gap:8px">
        <x-icon name="check" style="width:16px;height:16px;color:#065f46" />
        {{ session('success') }}
    </div>
@endif

<div class="grid-2" style="gap:28px;align-items:start">

    {{-- Left: avatar + stats --}}
    <div>
        <div class="card" style="padding:28px;text-align:center;margin-bottom:20px">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#b91c1c);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff;margin:0 auto 16px">
                {{ strtoupper(substr($u->name, 0, 1)) }}
            </div>
            <div style="font-size:18px;font-weight:700;margin-bottom:4px">{{ $u->name }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-bottom:16px">{{ $u->email }}</div>
            <div style="display:flex;justify-content:center;gap:24px">
                <div style="text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--primary)">1,240</div>
                    <div style="font-size:11px;color:var(--text-light)">XP Total</div>
                </div>
                <div style="text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--gold)">12</div>
                    <div style="font-size:11px;color:var(--text-light)">Day Streak</div>
                </div>
                <div style="text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--success)">5</div>
                    <div style="font-size:11px;color:var(--text-light)">Sertifikat</div>
                </div>
            </div>
        </div>

        <div class="card" style="padding:20px">
            <div class="section-title" style="margin-bottom:14px">Info Akun</div>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
                <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid var(--border-light)">
                    <span style="color:var(--text-muted)">Bergabung sejak</span>
                    <span style="font-weight:600">{{ $u->created_at?->format('d M Y') ?? 'Jan 2025' }}</span>
                </div>
                <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid var(--border-light)">
                    <span style="color:var(--text-muted)">Status akun</span>
                    <x-badge text="Aktif" type="success" />
                </div>
                <div class="flex justify-between" style="padding:8px 0">
                    <span style="color:var(--text-muted)">Paket</span>
                    <x-badge text="Free" type="dark" />
                </div>
            </div>
        </div>
    </div>

    {{-- Right: edit form --}}
    <div>
        <form method="POST" action="{{ route('pengaturan.update') }}">
            @csrf

            {{-- Validation errors --}}
            @if($errors->any())
                <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:12px 16px;font-size:13px;color:#b91c1c;margin-bottom:16px">
                    <ul style="margin:0;padding-left:16px">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card" style="padding:24px;margin-bottom:20px">
                <div class="section-title" style="margin-bottom:18px">Informasi Profil</div>
                <div class="input-group" style="margin-bottom:16px">
                    <label>Nama Lengkap</label>
                    <input class="input" name="name" value="{{ old('name', $u->name) }}" required />
                </div>
                <div class="input-group" style="margin-bottom:16px">
                    <label>Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email', $u->email) }}" required />
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>Bio / Deskripsi Diri</label>
                    <textarea class="input" name="bio" rows="3" placeholder="Ceritakan tentang diri kamu, skill, atau tujuan karir...">{{ old('bio', $u->bio) }}</textarea>
                    <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block">Maks 500 karakter. Akan ditampilkan di portofolio kamu.</small>
                </div>
            </div>

            <div class="card" style="padding:24px;margin-bottom:20px">
                <div class="section-title" style="margin-bottom:4px">Ubah Password</div>
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:18px">Kosongkan jika tidak ingin mengubah password</p>
                <div class="input-group" style="margin-bottom:16px">
                    <label>Password Baru</label>
                    <input class="input" type="password" name="password" placeholder="Minimal 8 karakter" />
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>Konfirmasi Password Baru</label>
                    <input class="input" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-full">Simpan Perubahan</button>
        </form>

        <div class="card" style="padding:24px;margin-top:20px;border:1px solid #fca5a5">
            <div class="section-title" style="margin-bottom:8px;color:#b91c1c">Zona Berbahaya</div>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Tindakan di bawah ini tidak dapat dibatalkan.</p>
            <button class="btn btn-outline" style="border-color:#fca5a5;color:#b91c1c" onclick="alert('Fitur hapus akun belum tersedia.')">
                Hapus Akun
            </button>
        </div>
    </div>

</div>
</div>
@endsection