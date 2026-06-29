@extends('admin.layouts.app')
@section('title', isset($user) ? 'Kelola User' : 'Tambah User')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:16px;flex-wrap:wrap;">
        <div>
            <h3 style="font-size:18px;font-weight:700;margin:0;">{{ isset($user) ? 'Kelola User' : 'Tambah User Baru' }}</h3>
            <p style="margin:6px 0 0;color:#6b7280;font-size:13px;">Form ini dipakai untuk mengatur akun user, role, dan kredensial dasar.</p>
        </div>
        <a href="{{ route('admin.users') }}" style="background:#f3f4f6;color:#111827;padding:8px 14px;border-radius:6px;text-decoration:none;font-size:13px;">
            ← Kembali ke daftar
        </a>
    </div>

    <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}">
        @csrf
        @if(isset($user))
            @method('PATCH')
        @endif
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-bottom:12px;">
            <input name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Nama lengkap *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="email" value="{{ old('email', $user->email ?? '') }}" type="email" placeholder="Email *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="password" type="password" placeholder="{{ isset($user) ? 'Password baru (opsional)' : 'Password *' }}" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="password_confirmation" type="password" placeholder="Konfirmasi password" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <select name="role" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
                <option value="">-- Role --</option>
                <option value="student" {{ old('role', $user->role ?? '') === 'student' ? 'selected' : '' }}>Student</option>
                <option value="mentor" {{ old('role', $user->role ?? '') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <input name="profile_photo" value="{{ old('profile_photo', $user->profile_photo ?? '') }}" placeholder="URL foto profil" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
        </div>
        @if($errors->any())
            <p style="color:#b91c1c;font-size:12px;margin-bottom:8px;">{{ $errors->first() }}</p>
        @endif
        <button type="submit" style="background:#d10000;color:#fff;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
            {{ isset($user) ? 'Simpan Perubahan' : '+ Tambah User' }}
        </button>
    </form>
</div>
@endsection
