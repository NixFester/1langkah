@extends('layouts.app', ['activePage' => 'profil-mentor'])

@section('title', $mentor['name'] . ' — 1Langkah')

@section('content')
@php
    $m = $mentor;
    $pricePer = explode('/', $m['price'])[0];
@endphp

<a href="javascript:history.back()" class="btn btn-ghost btn-sm" style="margin-bottom:16px;text-decoration:none;display:inline-flex">&#8592; Kembali ke Mentor</a>

<div class="mentor-profile-header">
    <x-avatar :initials="$m['initials']" size="avatar-xl" :style="'width:100px;height:100px;font-size:32px;background:' . $m['color']" />
    <div style="flex:1">
        <h1 style="font-size:22px;font-weight:800;margin-bottom:4px">{{ $m['name'] }}</h1>
        <div style="font-size:14px;color:var(--text-muted);margin-bottom:8px">{{ $m['role'] }} · <span style="color:var(--primary);font-weight:600">{{ $m['company'] }}</span></div>
        <div class="flex gap-3 items-center" style="margin-bottom:12px">
            <x-stars :rating="$m['rating']" />
            <span style="font-size:13px;color:var(--text-muted)">{{ $m['rating'] }} · {{ $m['sessions'] }} sesi selesai</span>
        </div>
        <div class="mentor-stats">
            <div class="mentor-stat"><div class="mentor-stat-value">{{ $m['sessions'] }}+</div><div class="mentor-stat-label">Sesi</div></div>
            <div class="mentor-stat"><div class="mentor-stat-value">{{ $m['rating'] }}</div><div class="mentor-stat-label">Rating</div></div>
            <div class="mentor-stat"><div class="mentor-stat-value">{{ $pricePer }}</div><div class="mentor-stat-label">Per Sesi</div></div>
        </div>
    </div>
    <div style="text-align:right">
        <div style="font-size:24px;font-weight:800;color:var(--dark);margin-bottom:12px">{{ $m['price'] }}</div>
        <a href="{{ route('pembayaran', ['id' => $m['id']]) }}" class="btn btn-primary btn-lg" style="text-decoration:none">Book Session</a>
    </div>
</div>

<div class="grid-2" style="gap:24px;margin-top:24px">
    <div class="card" style="padding:24px">
        <div class="section-title" style="margin-bottom:12px">Tentang</div>
        <p style="font-size:14px;color:var(--text-secondary);line-height:1.7">{{ $m['bio'] }}</p>
    </div>
    <div class="card" style="padding:24px">
        <div class="section-title" style="margin-bottom:12px">Keahlian</div>
        <div class="flex gap-2" style="flex-wrap:wrap">
            @foreach($m['expertise'] as $e)
                <x-badge :text="$e" type="blue" />
            @endforeach
        </div>
    </div>
</div>
@endsection
