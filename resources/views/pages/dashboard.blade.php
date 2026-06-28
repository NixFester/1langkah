@extends('layouts.app', ['activePage' => 'dashboard'])

@section('title', 'Dashboard — 1Langkah')

@section('content')
@php
    $u = $user;
    $maxHours = max(array_map(fn($w) => $w['hours'], $weeklyHours));
@endphp

<!-- Welcome Banner -->
<div style="background:linear-gradient(135deg,var(--primary),#b91c1c);border-radius:var(--radius-xl);padding:28px 32px;color:#fff;display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:16px">
    <div>
        <p style="font-size:14px;opacity:.85">Selamat datang kembali! &#128075;</p>
        <h2 style="font-size:22px;font-weight:700;margin:4px 0">{{ $u['name'] }}</h2>
        <p style="font-size:13px;opacity:.7">Chairman ICE · Full-Stack Developer</p>
    </div>
    <div style="display:flex;gap:24px;align-items:center;flex-wrap:wrap">
        <div style="text-align:center"><div style="font-size:20px;font-weight:700">{{ $u['streak'] }} hari streak</div><div style="font-size:11px;opacity:.7">Career: {{ $u['careerReady'] }}%</div></div>
        <a href="{{ route('kursus-saya') }}" class="btn btn-white">Lanjutkan Belajar &rarr;</a>
    </div>
</div>

<!-- Stats -->
<div class="grid-4" style="margin-bottom:28px">
    <x-stat-card value="3"     label="Kursus Aktif"      change="+2 baru"        icon="book" />
    <x-stat-card value="5"     label="Sertifikat"        change="+1 bln ini"     icon="award" />
    <x-stat-card value="18.3h" label="Jam Belajar"       change="+8h minggu ini" icon="clock" />
    <x-stat-card value="76%"   label="Career Readiness"  change="+5% bulan ini"  icon="target" />
</div>

<div class="grid-2" style="margin-bottom:28px">
    <!-- Continue Learning -->
    <div>
        <x-section-header title="Lanjutkan Belajar" linkText="Lihat semua" :linkHref="route('kursus-saya')" />
        @foreach($activeCourses as $c)
            <a href="{{ route('detail-kursus', ['id' => $c['id']]) }}" class="card" style="display:flex;gap:14px;padding:14px;margin-bottom:12px;cursor:pointer;text-decoration:none;color:inherit">
                <div style="width:80px;height:60px;border-radius:var(--radius-sm);background:linear-gradient(135deg,{{ $c['color'] }},{{ $c['color'] }}cc);flex-shrink:0;position:relative;overflow:hidden">
                    @if(!empty($c['thumbnail']))
                        <img src="{{ $c['thumbnail'] }}" alt="{{ $c['title'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                    @endif
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:13px;font-weight:600;margin-bottom:2px">{{ $c['title'] }}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-bottom:8px">{{ $c['mentor'] }}</div>
                    <x-progress-bar :pct="$c['progress']" />
                    <div style="font-size:10px;color:var(--text-light);margin-top:4px">{{ $c['progress'] }}% selesai</div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Weekly Progress -->
    <div>
        <x-section-header title="Progress Minggu Ini" />
        <div class="card" style="padding:20px">
            <div class="flex justify-between" style="margin-bottom:4px"><span style="font-size:13px;color:var(--text-muted)">Total jam minggu ini</span><b style="font-size:24px">18.3h</b></div>
            <div style="margin:16px 0"><span style="font-size:12px;color:var(--text-muted)">Target minggu ini</span><x-progress-bar :pct="76" color="green" /></div>
            <div style="display:flex;gap:4px;align-items:flex-end;height:80px;padding-top:8px">
                @foreach($weeklyHours as $i => $w)
                    <div style="flex:1;text-align:center">
                        <div style="height:{{ $w['hours'] / $maxHours * 60 }}px;background:{{ $i === 3 ? 'var(--primary)' : 'var(--bg-gray)' }};border-radius:4px 4px 0 0;margin:0 auto"></div>
                        <div style="font-size:10px;color:var(--text-light);margin-top:4px">{{ $w['day'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="grid-3" style="margin-bottom:28px">
    <!-- Skill Overview -->
    <div class="card" style="padding:20px">
        <div class="section-title" style="margin-bottom:16px">Skill Overview</div>
        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($skills as $s)
                <div>
                    <div class="flex justify-between" style="font-size:12px;margin-bottom:4px"><span>{{ $s['name'] }}</span><span style="color:var(--text-light)">{{ $s['pct'] }}%</span></div>
                    <x-progress-bar :pct="$s['pct']" :color="$s['color']" />
                </div>
            @endforeach
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="card" style="padding:20px">
        <div class="flex items-center justify-between" style="margin-bottom:16px">
            <div class="section-title">Leaderboard</div>
            <x-badge text="Top 10%" type="primary" />
        </div>
        @foreach($leaderboard as $l)
            @php
                $rankIcon = $l['rank'] <= 3 ? ['','&#128081;','&#129352;','&#129353;'][$l['rank']] : (string) $l['rank'];
                $rankColor = $l['rank'] <= 3 ? 'var(--gold)' : 'var(--text-light)';
                $rowStyle = ! empty($l['isMe']) ? 'background:var(--primary-bg);margin:0 -12px;padding:8px 12px;border-radius:var(--radius-sm)' : '';
            @endphp
            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;{{ $rowStyle }}">
                <span style="font-size:14px;font-weight:700;width:20px;color:{{ $rankColor }}">{!! $rankIcon !!}</span>
                <x-avatar :initials="$l['initials']" size="avatar-sm" />
                <div style="flex:1;font-size:13px;{{ ! empty($l['isMe']) ? 'font-weight:600' : '' }}">{{ $l['name'] }}</div>
                <span style="font-size:12px;color:var(--text-light)">{{ $l['xp'] }}</span>
            </div>
        @endforeach
    </div>

    <!-- Activity -->
    <div class="card" style="padding:20px">
        <div class="section-title" style="margin-bottom:12px">Aktivitas Terbaru</div>
        @foreach($activities as $a)
            <div class="activity-item">
                <div class="activity-dot" style="background:{{ $a['color'] }}"></div>
                <div><div class="activity-text">{{ $a['text'] }}</div><div class="activity-time">{{ $a['time'] }}</div></div>
            </div>
        @endforeach
    </div>
</div>

<!-- AI Recommendations -->
<x-section-header title="Rekomendasi AI untuk Kamu" />
<p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Berdasarkan skill gaps dan tujuan karir kamu</p>
<div class="grid-3">
    @foreach(array_slice($newCourses, 0, 3) as $c)
        <x-course-card :course="$c" :aiPick="true" />
    @endforeach
</div>
@endsection
