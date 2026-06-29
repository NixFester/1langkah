@extends('admin.layouts.app')
@section('title', isset($event) ? 'Kelola Event' : 'Tambah Event')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:16px;flex-wrap:wrap;">
        <div>
            <h3 style="font-size:18px;font-weight:700;margin:0;">{{ isset($event) ? 'Kelola Event' : 'Tambah Event Baru' }}</h3>
            <p style="margin:6px 0 0;color:#6b7280;font-size:13px;">Form ini digunakan untuk menambah atau mengubah data event.</p>
        </div>
        <a href="{{ route('admin.events') }}" style="background:#f3f4f6;color:#111827;padding:8px 14px;border-radius:6px;text-decoration:none;font-size:13px;">
            ← Kembali ke daftar
        </a>
    </div>

    <form method="POST" action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}">
        @csrf
        @if(isset($event))
            @method('PATCH')
        @endif
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:12px;">
            <input name="title" value="{{ old('title', $event->title ?? '') }}" placeholder="Judul event *" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <select name="type" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
                <option value="">-- Tipe --</option>
                <option value="online" {{ old('type', $event->type ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ old('type', $event->type ?? '') === 'offline' ? 'selected' : '' }}>Offline</option>
                <option value="hybrid" {{ old('type', $event->type ?? '') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>
            <select name="status" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
                <option value="upcoming" {{ old('status', $event->status ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="draft" {{ old('status', $event->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="ongoing" {{ old('status', $event->status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="completed" {{ old('status', $event->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ old('status', $event->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input name="start_date" type="datetime-local" value="{{ old('start_date', isset($event) && $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="end_date" type="datetime-local" value="{{ old('end_date', isset($event) && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <input name="location" value="{{ old('location', $event->location ?? '') }}" placeholder="Lokasi / link meeting" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;">
            <textarea name="description" placeholder="Deskripsi" rows="2" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;grid-column:span 3;resize:vertical;">{{ old('description', $event->description ?? '') }}</textarea>
        </div>
        @if($errors->any())
            <p style="color:#b91c1c;font-size:12px;margin-bottom:8px;">{{ $errors->first() }}</p>
        @endif
        <button type="submit" style="background:#d10000;color:#fff;padding:8px 20px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
            {{ isset($event) ? 'Simpan Perubahan' : '+ Tambah Event' }}
        </button>
    </form>
</div>
@endsection
