@extends('admin.layouts.app')
@section('title', isset($course) ? 'Kelola Kursus' : 'Tambah Kursus')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:16px;flex-wrap:wrap;">
        <div>
            <h3 style="font-size:18px;font-weight:700;margin:0;">{{ isset($course) ? 'Kelola Kursus' : 'Tambah Kursus Baru' }}</h3>
            <p style="margin:6px 0 0;color:#6b7280;font-size:13px;">Form ini menampung data kursus dan dapat dipakai untuk edit maupun tambah data baru.</p>
        </div>
        <a href="{{ route('admin.courses') }}" style="background:#f3f4f6;color:#111827;padding:8px 14px;border-radius:6px;text-decoration:none;font-size:13px;">
            ← Kembali ke daftar
        </a>
    </div>

    <form method="POST" action="{{ isset($course) ? route('admin.courses.update', $course) : route('admin.courses.store') }}">
        @csrf
        @if(isset($course))
            @method('PATCH')
        @endif
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
            <input name="title" value="{{ old('title', $course->title ?? '') }}" placeholder="Judul kursus *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="mentor_name" value="{{ old('mentor_name', $course->mentor_name ?? '') }}" placeholder="Nama mentor *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="mentor_company" value="{{ old('mentor_company', $course->mentor_company ?? '') }}" placeholder="Perusahaan mentor *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="category" value="{{ old('category', $course->category ?? '') }}" placeholder="Kategori (cth: Programming) *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <select name="level" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
                <option value="">-- Level --</option>
                <option value="Beginner" {{ old('level', $course->level ?? '') === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                <option value="Intermediate" {{ old('level', $course->level ?? '') === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                <option value="Advanced" {{ old('level', $course->level ?? '') === 'Advanced' ? 'selected' : '' }}>Advanced</option>
            </select>
            <input name="price" value="{{ old('price', $course->price ?? '') }}" placeholder="Harga (cth: Rp 799.000) *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="color" value="{{ old('color', $course->color ?? '') }}" placeholder="Warna hex (cth: #667eea)" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
        </div>
        @if($errors->any())
            <p style="color:#b91c1c;font-size:12px;margin-bottom:8px;">{{ $errors->first() }}</p>
        @endif
        <button type="submit" style="background:#d10000;color:#fff;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
            {{ isset($course) ? 'Simpan Perubahan' : '+ Tambah Kursus' }}
        </button>
    </form>
</div>

@if(isset($course))
<div class="admin-card" style="margin-top:24px;">
    <h3 style="font-size:16px;font-weight:600;margin-bottom:16px;">Tambah Bab</h3>
    <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
            <input name="title" placeholder="Judul bab *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="lessons" type="number" min="1" placeholder="Jumlah lesson *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="duration" placeholder="Durasi (cth: 20 menit) *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
        </div>
        <button type="submit" style="background:#111827;color:#fff;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
            + Tambah Bab
        </button>
    </form>
</div>
@endif

@endsection
