@extends('layouts.app')

@section('title', 'Portofolio - ' . ($portfolio['user']['name'] ?? 'User'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Portofolio</h1>
            <p class="text-sm text-gray-500">Skills dan pencapaian dari kursus & bootcamp</p>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-gradient-to-r from-[#cc0000] to-red-700 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-white/20 p-1">
                @if(!empty($portfolio['user']['profile_photo']))
                <img src="{{ $portfolio['user']['profile_photo'] }}" alt="{{ $portfolio['user']['name'] ?? 'User' }}" class="w-full h-full rounded-full object-cover">
                @else
                <div class="w-full h-full rounded-full bg-white/30 flex items-center justify-center text-3xl font-bold">
                    {{ $portfolio['user']['initials'] ?? 'ME' }}
                </div>
                @endif
            </div>
            <div class="text-center md:text-left flex-1">
                <h2 class="text-2xl font-bold">{{ $portfolio['user']['name'] ?? 'User' }}</h2>
                <p class="text-white/80 text-sm mt-1 max-w-lg">
                    {{ $portfolio['user']['bio'] ?: 'Portofolio pengguna' }}
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-1.5 text-sm">
                        <span class="font-bold">{{ $portfolio['user']['xp'] ?? 0 }}</span> XP
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-1.5 text-sm">
                        Bergabung {{ $portfolio['user']['joined_at'] ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-[#cc0000]">{{ $portfolio['stats']['courses_completed'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Kursus Selesai</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-green-600">{{ $portfolio['stats']['bootcamps_completed'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Bootcamp Selesai</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-purple-600">{{ $portfolio['stats']['skills_acquired'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Skills</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-orange-500">{{ $portfolio['stats']['reviews_written'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Reviews</div>
        </div>
    </div>

    <!-- Achievements -->
    @if(!empty($portfolio['achievements']))
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-lg mb-4">Achievements</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($portfolio['achievements'] as $achievement)
            <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl px-4 py-3 flex items-center gap-3" title="{{ $achievement['desc'] }}">
                <span class="text-2xl">{{ $achievement['icon'] }}</span>
                <span class="text-sm font-medium text-gray-700">{{ $achievement['name'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Skills Section -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-lg mb-4">Skills yang Dipelajari</h3>
        @if(!empty($portfolio['skills']))
        <div class="flex flex-wrap gap-2">
            @foreach($portfolio['skills'] as $skill)
            <div class="bg-gray-100 rounded-full px-4 py-2 flex items-center gap-2">
                <span class="text-sm font-medium text-gray-700">{{ $skill['name'] }}</span>
                @if($skill['rating'] > 0)
                <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ number_format((float) ($skill['rating'] ?? 0), 1) }}★
                </span>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-sm">Belum ada skills.</p>
        @endif
    </div>

    <!-- Courses Completed (Sorted by Rating) -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-lg mb-4">Kursus yang Telah Diselesaikan</h3>
        @if(!empty($portfolio['courses']))
        <div class="space-y-4">
            @foreach($portfolio['courses'] as $course)
            <div class="flex gap-4 items-center p-4 bg-gray-50 rounded-xl">
                <div class="w-16 h-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($course['thumbnail'])
                    <img src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-900 truncate">{{ $course['title'] }}</h4>
                    <p class="text-xs text-gray-500">{{ $course['category'] ?? '' }} • Selesai {{ $course['completed_at'] }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($course['rating'] > 0)
                    <span class="bg-yellow-100 text-yellow-700 text-sm font-bold px-2 py-1 rounded-full">
                        {{ number_format((float) ($course['rating'] ?? 0), 1) }}★
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-sm">Belum ada kursus yang diselesaikan.</p>
        @endif
    </div>

    <!-- Bootcamps Completed (Sorted by Rating) -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
        <h3 class="font-bold text-gray-900 text-lg mb-4">Bootcamp yang Telah Diselesaikan</h3>
        @if(!empty($portfolio['bootcamps']))
        <div class="space-y-4">
            @foreach($portfolio['bootcamps'] as $bootcamp)
            <div class="flex gap-4 items-center p-4 bg-gray-50 rounded-xl">
                <div class="w-16 h-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($bootcamp['thumbnail'])
                    <img src="{{ $bootcamp['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-900 truncate">{{ $bootcamp['title'] }}</h4>
                    <p class="text-xs text-gray-500">{{ ucfirst($bootcamp['type']) }} • Selesai {{ $bootcamp['completed_at'] }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if($bootcamp['rating'] > 0)
                    <span class="bg-yellow-100 text-yellow-700 text-sm font-bold px-2 py-1 rounded-full">
                        {{ number_format((float) ($bootcamp['rating'] ?? 0), 1) }}★
                    </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-sm">Belum ada bootcamp yang diselesaikan.</p>
        @endif
    </div>

</div>

@endsection
