@extends('layouts.app', ['activePage' => 'kursus-saya'])

@section('title', 'Kursus Saya — 1Langkah')

@section('content')
<div class="page-title" style="margin-bottom:8px">Kursus Saya</div>
<p style="font-size:14px;color:var(--text-muted);margin-bottom:24px">{{ count($myCourses) }} kursus aktif</p>

<div class="tabs" x-data="{ tab: 'active' }">
    <div class="tab" :class="{ 'active': tab === 'active' }" @click="tab = 'active'">Sedang Berjalan ({{ count($myCourses) }})</div>
    <div class="tab" :class="{ 'active': tab === 'done' }" @click="tab = 'done'">Selesai (2)</div>
    <div class="tab" :class="{ 'active': tab === 'wishlist' }" @click="tab = 'wishlist'">Wishlist ({{ count($otherCourses) }})</div>
</div>

<div x-show="tab === 'active'">
    <div class="grid-3">
        @foreach($myCourses as $c)
            <x-course-card :course="$c" />
        @endforeach
    </div>
</div>

<div x-show="tab === 'done'" style="display:none">
    <div class="grid-3">
        <div class="card" style="padding:24px;text-align:center;color:var(--text-muted)">
            <x-icon name="award" style="width:32px;height:32px;color:var(--gold);margin-bottom:12px" />
            <div style="font-size:14px;font-weight:600;color:var(--dark);margin-bottom:4px">UI/UX Design Mastery</div>
            <div style="font-size:12px">Selesai pada 12 Mei 2025 · Nilai: A</div>
        </div>
        <div class="card" style="padding:24px;text-align:center;color:var(--text-muted)">
            <x-icon name="award" style="width:32px;height:32px;color:var(--gold);margin-bottom:12px" />
            <div style="font-size:14px;font-weight:600;color:var(--dark);margin-bottom:4px">Digital Marketing 101</div>
            <div style="font-size:12px">Selesai pada 2 Apr 2025 · Nilai: A+</div>
        </div>
    </div>
</div>

<div x-show="tab === 'wishlist'" style="display:none">
    <div class="grid-3">
        @foreach($otherCourses as $c)
            <x-course-card :course="$c" />
        @endforeach
    </div>
</div>

<div style="margin-top:32px">
    <x-section-header title="Rekomendasi untuk Kamu" linkText="Lihat Semua" :linkHref="route('kursus')" />
    <div class="grid-3">
        @foreach(array_slice($otherCourses, 0, 3) as $c)
            <x-course-card :course="$c" />
        @endforeach
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
