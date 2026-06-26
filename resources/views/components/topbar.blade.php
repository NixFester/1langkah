@php
    $user = app(\App\Services\CatalogService::class)->user();
@endphp
<div class="topbar">
    <div class="topbar-search">
        <input class="input input-search" placeholder="Cari kursus, mentor, proyek..." />
    </div>
    <div class="topbar-right">
        <div class="xp-badge">&#9889; {{ $user['xp'] }}</div>
        <a href="{{ route('kalender') }}" class="topbar-icon" style="text-decoration:none;color:inherit">
            <x-icon name="bell" />
            <div class="dot"></div>
        </a>
        <a href="{{ route('dashboard') }}" class="topbar-icon" style="text-decoration:none;color:inherit">
            <x-icon name="settings" />
        </a>
        <a href="{{ route('profil-mentor', ['id' => 301]) }}" style="text-decoration:none;color:inherit">
            <x-avatar initials="AK" size="avatar-sm" />
        </a>
    </div>
</div>
