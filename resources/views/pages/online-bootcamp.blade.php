@extends('layouts.app', ['activePage' => 'online-bootcamp'])

@section('title', 'Online Bootcamp — 1Langkah')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:16px">
    <div>
        <div class="page-title">Online Bootcamp</div>
        <p style="font-size:14px;color:var(--text-muted);margin-top:4px">7–10 sesi LIVE via Zoom dengan instruktur terbaik</p>
    </div>
    <div class="chips" x-data="{ active: 'Semua' }">
        @foreach(['Semua', 'Programming', 'Data Science', 'Design'] as $c)
            <span class="chip" :class="{ 'active': active === '{{ $c }}' }" @click="active = '{{ $c }}'">{{ $c }}</span>
        @endforeach
    </div>
</div>

<div class="grid-3">
    @foreach($bootcamps as $b)
        <a href="{{ route('detail-online-bootcamp', ['id' => $b['id']]) }}" class="card" style="cursor:pointer;text-decoration:none;color:inherit">
            <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc)"></div>
            <div class="card-body">
                <div class="course-card-title">{{ $b['title'] }}</div>
                <div style="font-size:12px;color:var(--text-muted);margin:8px 0">{{ $b['mentor'] }}</div>
                <div style="display:flex;gap:12px;font-size:12px;color:var(--text-muted);margin-bottom:12px">
                    <span><x-icon name="users" style="width:14px;height:14px" /> {{ $b['participants'] }} peserta</span>
                    <span><x-icon name="calendar" style="width:14px;height:14px" /> {{ $b['startDate'] }}</span>
                </div>
                <div style="font-size:11px;color:var(--text-light);margin-bottom:12px">{{ $b['sessions'] }}</div>
                <div class="flex items-center justify-between">
                    <span style="font-size:18px;font-weight:700">{{ $b['price'] }}</span>
                    <span class="btn btn-primary btn-sm" onclick="event.preventDefault();event.stopPropagation();window.location='{{ route('pembayaran', ['id' => $b['id']]) }}'">Daftar</span>
                </div>
            </div>
        </a>
    @endforeach
</div>

<div class="card" style="margin-top:24px;padding:24px;display:flex;align-items:center;gap:24px;background:linear-gradient(135deg,var(--dark),#374151);color:#fff;flex-wrap:wrap">
    <div style="flex:1;min-width:200px">
        <h3 style="font-size:18px;font-weight:700;margin-bottom:8px">Tidak menemukan yang cocok?</h3>
        <p style="font-size:13px;opacity:.7">Cek jadwal bootcamp offline di Jakarta, Bandung, dan Surabaya.</p>
    </div>
    <a href="{{ route('offline-bootcamp') }}" class="btn btn-white">Lihat Offline Bootcamp &rarr;</a>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
