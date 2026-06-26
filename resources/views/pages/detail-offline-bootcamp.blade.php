@extends('layouts.app', ['activePage' => 'detail-offline-bootcamp'])

@section('title', $bootcamp['title'] . ' — 1Langkah')

@section('content')
@php $b = $bootcamp; @endphp

<a href="javascript:history.back()" class="btn btn-ghost btn-sm" style="margin-bottom:16px;text-decoration:none;display:inline-flex">&#8592; Kembali</a>

<div class="grid-2" style="gap:32px">
    <div>
        <div style="width:100%;aspect-ratio:16/9;border-radius:var(--radius-xl);background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc);margin-bottom:20px;display:flex;align-items:center;justify-content:center">
            <div style="color:#fff;font-size:14px;font-weight:600;background:rgba(0,0,0,.3);padding:6px 16px;border-radius:999px;display:inline-flex;align-items:center;gap:6px"><x-icon name="mapPin" style="width:14px;height:14px;color:#fff" /> {{ $b['location'] }}</div>
        </div>
        <h1 style="font-size:24px;font-weight:800;margin-bottom:12px">{{ $b['title'] }}</h1>
        <div class="flex gap-3" style="margin-bottom:16px">
            <x-badge text="Offline" type="purple" />
            <x-badge :text="$b['location']" type="dark" />
        </div>
        <div style="display:flex;gap:16px;font-size:13px;color:var(--text-muted);margin-bottom:20px;flex-wrap:wrap">
            <span><x-icon name="users" style="width:16px;height:16px" /> {{ $b['participants'] }} peserta</span>
            <span><x-icon name="calendar" style="width:16px;height:16px" /> Mulai {{ $b['startDate'] }}</span>
        </div>
        <div style="font-size:28px;font-weight:800;color:var(--dark);margin-bottom:20px">{{ $b['price'] }}</div>
        <a href="{{ route('pembayaran', ['id' => $b['id']]) }}" class="btn btn-primary btn-lg btn-full" style="text-decoration:none;display:flex">Daftar Bootcamp</a>
        <div style="margin-top:16px;display:flex;gap:16px;flex-wrap:wrap">
            @foreach(['Makan Siang','Sertifikat','Laptop Station','Networking Event','Rekaman Sesi','Job Referral'] as $f)
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-muted)"><span style="color:var(--success);font-weight:700">&#10003;</span> {{ $f }}</div>
            @endforeach
        </div>
    </div>
    <div>
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:16px">Lokasi</div>
            <div style="width:100%;height:200px;background:var(--bg-gray);border-radius:var(--radius-md);display:flex;align-items:center;justify-content:center;color:var(--text-light);font-size:14px;gap:8px"><x-icon name="mapPin" style="width:32px;height:32px" /> {{ $b['location'] }}</div>
        </div>
        <div class="card" style="padding:24px">
            <div class="section-title" style="margin-bottom:16px">Yang Kamu Dapatkan</div>
            @foreach($features as $i => $f)
                <div style="display:flex;gap:10px;padding:10px 0;{{ $i < count($features) - 1 ? 'border-bottom:1px solid var(--border-light)' : '' }}">
                    <span style="color:var(--success);font-weight:700;margin-top:1px">&#10003;</span>
                    <span style="font-size:13px;color:var(--text-secondary)">{{ $f }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
