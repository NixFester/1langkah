@extends('layouts.app', ['activePage' => 'detail-online-bootcamp'])

@section('title', $bootcamp['title'] . ' — 1Langkah')

@section('content')
@php $b = $bootcamp; @endphp

<a href="javascript:history.back()" class="btn btn-ghost btn-sm" style="margin-bottom:16px;text-decoration:none;display:inline-flex">&#8592; Kembali</a>

<div class="grid-2" style="gap:32px">
    <div>
        <div style="width:100%;aspect-ratio:16/9;border-radius:var(--radius-xl);background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc);margin-bottom:20px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
            @if(!empty($b['thumbnail']))
                <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
            @endif
            <div style="position:relative;z-index:1;color:#fff;font-size:14px;font-weight:600;background:rgba(0,0,0,.4);padding:6px 16px;border-radius:999px">LIVE via Zoom</div>
        </div>

        @if(!empty($b['gallery']))
            <div style="display:flex;gap:8px;overflow-x:auto;margin-bottom:20px;padding-bottom:4px">
                @foreach($b['gallery'] as $img)
                    <img src="{{ $img }}" alt="{{ $b['title'] }}"
                         style="flex-shrink:0;width:120px;height:72px;object-fit:cover;border-radius:var(--radius-sm)">
                @endforeach
            </div>
        @endif
        <h1 style="font-size:24px;font-weight:800;margin-bottom:12px">{{ $b['title'] }}</h1>
        <div class="flex gap-3 items-center" style="margin-bottom:16px">
            <x-badge text="Online" type="blue" />
            <x-badge text="LIVE" type="live" />
        </div>
        <div style="display:flex;gap:16px;font-size:13px;color:var(--text-muted);margin-bottom:20px;flex-wrap:wrap">
            <span><x-icon name="users" style="width:16px;height:16px" /> {{ $b['participants'] }} peserta</span>
            <span><x-icon name="calendar" style="width:16px;height:16px" /> Mulai {{ $b['startDate'] }}</span>
            <span><x-icon name="video" style="width:16px;height:16px" /> {{ $b['sessions'] }}</span>
        </div>
        <div style="font-size:28px;font-weight:800;color:var(--dark);margin-bottom:20px">{{ $b['price'] }}</div>
        <a href="{{ route('pembayaran', ['id' => $b['id']]) }}" class="btn btn-primary btn-lg btn-full" style="text-decoration:none;display:flex">Daftar Bootcamp</a>
        <div style="margin-top:16px;display:flex;gap:16px;flex-wrap:wrap">
            @foreach(['Rekaman 30 hari','Sertifikat Terverifikasi','Project Review','Community Access','1-on-1 Mentoring'] as $f)
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)"><span style="color:var(--success);font-weight:700">&#10003;</span> {{ $f }}</div>
            @endforeach
        </div>
    </div>
    <div>
        <div class="card" style="padding:24px">
            <div class="section-title" style="margin-bottom:16px">Jadwal Sesi</div>
            @foreach($sessions as $i => $s)
                <div style="display:flex;gap:12px;padding:12px 0;{{ $i < count($sessions) - 1 ? 'border-bottom:1px solid var(--border-light)' : '' }}">
                    <div style="width:40px;text-align:center;flex-shrink:0"><div style="font-size:18px;font-weight:700;color:var(--primary)">{{ $i + 1 }}</div></div>
                    <div style="flex:1"><div style="font-size:13px;font-weight:600">{{ $s['topic'] }}</div><div style="font-size:11px;color:var(--text-light)">{{ $s['date'] }} · {{ $s['time'] }}</div></div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
