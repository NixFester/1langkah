@extends('layouts.app', ['activePage' => 'offline-bootcamp'])

@section('title', 'Offline Bootcamp — 1Langkah')

@section('content')
<div style="margin-bottom:24px">
    <div class="page-title">Offline Bootcamp</div>
    <p style="font-size:14px;color:var(--text-muted);margin-top:4px">Tatap muka langsung di Jakarta, Bandung, dan Surabaya</p>
</div>

<div class="grid-3">
    @foreach($bootcamps as $b)
        <a href="{{ route('detail-offline-bootcamp', ['id' => $b['id']]) }}" class="card" style="cursor:pointer;text-decoration:none;color:inherit">
            <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc);position:relative;overflow:hidden">
                @if(!empty($b['thumbnail']))
                    <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
                @endif
            </div>
            <div class="card-body">
                <div class="course-card-title">{{ $b['title'] }}</div>
                <div style="font-size:12px;color:var(--text-muted);margin:8px 0">{{ $b['mentor'] }}</div>
                <div style="display:flex;gap:12px;font-size:12px;color:var(--text-muted);margin-bottom:12px">
                    <span><x-icon name="users" style="width:14px;height:14px" /> {{ $b['participants'] }} peserta</span>
                    <span><x-icon name="mapPin" style="width:14px;height:14px" /> {{ $b['location'] }}</span>
                </div>
                <div style="font-size:11px;color:var(--text-light);margin-bottom:12px"><x-icon name="calendar" style="width:14px;height:14px" /> Mulai {{ $b['startDate'] }}</div>
                <div class="flex items-center justify-between">
                    <span style="font-size:18px;font-weight:700">{{ $b['price'] }}</span>
                    <span class="btn btn-primary btn-sm" onclick="event.preventDefault();event.stopPropagation();window.location='{{ route('pembayaran', ['id' => $b['id']]) }}'">Daftar</span>
                </div>
            </div>
        </a>
    @endforeach
</div>
@endsection
