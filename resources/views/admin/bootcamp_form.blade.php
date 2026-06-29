@extends('admin.layouts.app')
@section('title', isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:16px;flex-wrap:wrap;">
        <div>
            <h3 style="font-size:18px;font-weight:700;margin:0;">{{ isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp Baru' }}</h3>
            <p style="margin:6px 0 0;color:#6b7280;font-size:13px;">Form ini digunakan untuk menambah atau mengubah data bootcamp.</p>
        </div>
        <a href="{{ route('admin.bootcamps') }}" style="background:#f3f4f6;color:#111827;padding:8px 14px;border-radius:6px;text-decoration:none;font-size:13px;">
            ← Kembali ke daftar
        </a>
    </div>

    <form method="POST" action="{{ isset($bootcamp) ? route('admin.bootcamps.update', $bootcamp) : route('admin.bootcamps.store') }}">
        @csrf
        @if(isset($bootcamp))
            @method('PATCH')
        @endif
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
            <input name="title" value="{{ old('title', $bootcamp->title ?? '') }}" placeholder="Judul bootcamp *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="mentor_name" value="{{ old('mentor_name', $bootcamp->mentor_name ?? '') }}" placeholder="Nama mentor *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <select name="type" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
                <option value="">-- Tipe --</option>
                <option value="online" {{ old('type', $bootcamp->type ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ old('type', $bootcamp->type ?? '') === 'offline' ? 'selected' : '' }}>Offline</option>
            </select>
            <input name="price" value="{{ old('price', $bootcamp->price ?? '') }}" placeholder="Harga (cth: Rp 6.500.000) *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="start_date" value="{{ old('start_date', $bootcamp->start_date ?? '') }}" placeholder="Tanggal mulai (cth: 11 Agu 2026) *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="sessions_info" value="{{ old('sessions_info', $bootcamp->sessions_info ?? '') }}" placeholder="Info sesi (cth: 7 sesi LIVE via Zoom)" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="location" value="{{ old('location', $bootcamp->location ?? '') }}" placeholder="Lokasi (untuk bootcamp offline)" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="color" value="{{ old('color', $bootcamp->color ?? '') }}" placeholder="Warna hex (cth: #667eea)" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
        </div>
        @if($errors->any())
            <p style="color:#b91c1c;font-size:12px;margin-bottom:8px;">{{ $errors->first() }}</p>
        @endif
        <button type="submit" style="background:#d10000;color:#fff;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
            {{ isset($bootcamp) ? 'Simpan Perubahan' : '+ Tambah Bootcamp' }}
        </button>
    </form>
</div>
@endsection
