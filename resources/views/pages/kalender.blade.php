@extends('layouts.app', ['activePage' => 'kalender'])

@section('title', 'Kalender — 1Langkah')

@section('content')
@php
    $days = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
    $eventDates = array_column($events, 'date');
    $today = 15;
    // Build calendar cells: 2 prev-month days, 31 current, 2 next-month days
    $cells = [];
    for ($i = 0; $i < 2; $i++) { $cells[] = ['day' => 30 + $i, 'class' => 'other-month']; }
    for ($d = 1; $d <= 31; $d++) {
        $cls = [];
        if ($d === $today) $cls[] = 'today';
        if (in_array($d, $eventDates)) $cls[] = 'has-event';
        $cells[] = ['day' => $d, 'class' => implode(' ', $cls)];
    }
    for ($i = 1; $i <= 2; $i++) { $cells[] = ['day' => $i, 'class' => 'other-month']; }
@endphp

<div class="flex items-center justify-between" style="margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <div>
        <div class="page-title">Kalender</div>
        <p style="font-size:14px;color:var(--text-muted);margin-top:4px">Jadwal belajar dan event kamu</p>
    </div>
    <div class="flex gap-2">
        <button class="btn btn-ghost btn-sm">&#8592;</button>
        <button class="btn btn-outline btn-sm">Juni 2025</button>
        <button class="btn btn-ghost btn-sm">&#8594;</button>
    </div>
</div>

<div class="grid-2" style="gap:24px">
    <div class="card" style="padding:24px">
        <div class="calendar-grid" style="margin-bottom:8px">
            @foreach($days as $d)<div class="cal-header">{{ $d }}</div>@endforeach
        </div>
        <div class="calendar-grid">
            @foreach($cells as $cell)
                <div class="cal-day {{ $cell['class'] }}">{{ $cell['day'] }}</div>
            @endforeach
        </div>
    </div>
    <div>
        <div class="section-title" style="margin-bottom:16px">Event Mendatang</div>
        @foreach($events as $e)
            <div class="card" style="padding:16px;margin-bottom:12px;cursor:pointer">
                <div class="flex items-center gap-3">
                    <div style="width:48px;height:48px;border-radius:var(--radius-md);background:var(--primary-bg);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:var(--primary);flex-shrink:0">{{ $e['date'] }}</div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:13px;font-weight:600;margin-bottom:2px">{{ $e['title'] }}</div>
                        <div style="font-size:11px;color:var(--text-light)"><x-icon name="clock" style="width:12px;height:12px" /> {{ $e['time'] }}</div>
                    </div>
                    <x-icon name="chevronRight" style="width:16px;height:16px;color:var(--text-light)" />
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
