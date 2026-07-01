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
            <div style="position:relative; width:96px; height:96px; margin:0 auto 16px;">
                <!-- Main Avatar -->
                <div style="width:100%; height:100%; border-radius:50%; background:linear-gradient(135deg,var(--primary),#b91c1c); display:flex; align-items:center; justify-content:center; font-size:48px; font-weight:700; color:#fff; border:3px solid #fee2e2; overflow:hidden;">
                    @if(isset($u->avatar) && $u->avatar)
                        <img src="{{ Storage::url($u->avatar) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    @endif
                </div>
                <!-- Camera Upload Badge -->
                <label for="avatar_upload_main" style="position:absolute; bottom:0; right:0; width:32px; height:32px; background-color:var(--primary); border:3px solid #fff; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#fff; transition:transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <circle cx="12" cy="13" r="3"></circle>
                    </svg>
                </label>
                <input type="file" id="avatar_upload_main" name="avatar" accept="image/jpeg,image/png,image/jpg" style="display:none;" form="profile-form">
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
        <form id="profile-form" method="POST" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
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