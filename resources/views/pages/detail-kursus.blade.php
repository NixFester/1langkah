@extends('layouts.app', ['activePage' => 'kursus'])

@section('title', $course['title'] . ' — 1Langkah')

@section('content')
@php
    $c = $course;
    $mentorInitials = implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $c['mentor'])));
@endphp

<a href="javascript:history.back()" class="btn btn-ghost btn-sm" style="margin-bottom:16px;text-decoration:none;display:inline-flex">&#8592; Kembali ke Kursus</a>

<div class="grid-2" style="gap:32px">
    <div>
        <div style="width:100%;aspect-ratio:16/9;border-radius:var(--radius-xl);background:linear-gradient(135deg,{{ $c['color'] }},{{ $c['color'] }}cc);margin-bottom:20px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden">
            @if(!empty($c['thumbnail']))
                <img src="{{ $c['thumbnail'] }}" alt="{{ $c['title'] }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover">
            @endif
            <div style="position:relative;z-index:1;width:60px;height:60px;border-radius:50%;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;color:#fff"><x-icon name="play" /></div>
        </div>

        {{-- Gallery strip --}}
        @if(!empty($c['gallery']))
            <div style="display:flex;gap:8px;overflow-x:auto;margin-bottom:20px;padding-bottom:4px">
                @foreach($c['gallery'] as $img)
                    <img src="{{ $img }}" alt="{{ $c['title'] }}"
                         style="flex-shrink:0;width:120px;height:72px;object-fit:cover;border-radius:var(--radius-sm);cursor:pointer;border:2px solid transparent;transition:border-color .2s"
                         onmouseover="this.style.borderColor='var(--primary)'"
                         onmouseout="this.style.borderColor='transparent'">
                @endforeach
            </div>
        @endif
        <div class="flex gap-2" style="margin-bottom:16px">
            <x-badge :text="$c['category']" type="blue" />
            <x-badge :text="$c['level']" type="dark" />
            @if(! empty($c['badge']))
                <x-badge :text="$c['badge']" type="gold" />
            @endif
        </div>
        <h1 style="font-size:24px;font-weight:800;line-height:1.3;margin-bottom:12px">{{ $c['title'] }}</h1>
        <div class="flex items-center gap-3" style="margin-bottom:16px">
            <x-stars :rating="$c['rating']" />
            <span style="font-size:13px;color:var(--text-muted)">{{ $c['rating'] }} ({{ number_format($c['students']) }} siswa)</span>
        </div>
        <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;margin-bottom:24px">Kuasai skill praktis dari dasar hingga mahir dalam program intensif ini. Dirancang oleh {{ $c['mentor'] }} dari {{ $c['mentorCompany'] }} dengan materi yang langsung applicable di industri.</p>
        <div class="flex gap-3" style="flex-wrap:wrap">
            <a href="{{ route('pembayaran', ['id' => $c['id']]) }}" class="btn btn-primary btn-lg">Daftar Sekarang — {{ $c['price'] }}</a>
            <button class="btn btn-outline btn-lg">Tambah ke Wishlist</button>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:24px">
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="clock" /> 25 jam total</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="play" /> 48 video lesson</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="award" /> Sertifikat verifikasi</div>
            <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary)"><x-icon name="zap" /> Akses selamanya</div>
        </div>
    </div>
    <div>
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:16px">Tentang Mentor</div>
            <a href="{{ route('profil-mentor', ['id' => $c['mentor_id'] ?? 1]) }}" class="flex items-center gap-3" style="cursor:pointer;text-decoration:none;color:inherit">
                <x-avatar :initials="$mentorInitials" size="avatar-lg" :style="'background:' . $c['color']" />
                <div><div style="font-weight:600">{{ $c['mentor'] }}</div><div style="font-size:13px;color:var(--text-muted)">{{ $c['mentorCompany'] }}</div></div>
            </a>
        </div>
        <div class="card" style="padding:24px">
            <div class="section-title" style="margin-bottom:16px">Kurikulum</div>
            @foreach($chapters as $i => $ch)
                @php $hasVideo = !empty($ch['video_url']); @endphp
                @if($hasVideo)
                    <a href="{{ $ch['video_url'] }}" target="_blank" rel="noopener"
                       style="display:flex;align-items:center;gap:12px;padding:12px 0;text-decoration:none;color:inherit;{{ $i < count($chapters) - 1 ? 'border-bottom:1px solid var(--border-light)' : '' }}">
                @else
                    <div style="display:flex;align-items:center;gap:12px;padding:12px 0;{{ $i < count($chapters) - 1 ? 'border-bottom:1px solid var(--border-light)' : '' }}">
                @endif
                        <div style="width:28px;height:28px;border-radius:50%;background:{{ $hasVideo ? 'var(--primary-bg)' : 'var(--bg-gray)' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:{{ $hasVideo ? 'var(--primary)' : 'var(--text-muted)' }}">{{ $i + 1 }}</div>
                        <div style="flex:1">
                            <div style="font-size:13px;font-weight:600">{{ $ch['title'] }}</div>
                            <div style="font-size:11px;color:var(--text-light)">{{ $ch['lessons'] }} lessons · {{ $ch['duration'] }}</div>
                        </div>
                        @if($hasVideo)
                            <x-icon name="play" style="width:16px;height:16px;color:var(--primary)" />
                        @else
                            <x-icon name="chevronRight" style="width:16px;height:16px;color:var(--text-light)" />
                        @endif
                @if($hasVideo)
                    </a>
                @else
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
