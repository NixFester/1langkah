@extends('layouts.app', ['activePage' => 'event'])

@section('title', 'Event — 1Langkah')
@section('header_title', 'Event')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 28px;">Event</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Temukan dan ikuti event menarik dari 1Langkah</p>
        </div>
    </div>

    <!-- Events Grid -->
    @if(!empty($events))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <a href="{{ route('detail-event', $event['id']) }}" class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <!-- Banner -->
            <div class="relative h-40 overflow-hidden" style="background-color: {{ $event['color'] ?? '#cc0000' }}20;">
                @if(!empty($event['banner_url']))
                <img src="{{ $event['banner_url'] }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                @else
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                @endif
                <!-- Type Badge -->
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold capitalize" style="color: {{ $event['color'] ?? '#cc0000' }};">
                        {{ $event['type'] ?? 'online' }}
                    </span>
                </div>
                <!-- Status Badge -->
                @if(isset($event['status']) && $event['status'] !== 'completed')
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold capitalize text-gray-700">
                        {{ str_replace('_', ' ', $event['status']) }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Content -->
            <div class="p-5">
                <h3 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                    {{ $event['title'] }}
                </h3>

                @if(!empty($event['short_description']))
                <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                    {{ $event['short_description'] }}
                </p>
                @endif

                <!-- Meta Info -->
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-medium">{{ $event['date_display'] ?? 'TBA' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium">{{ $event['start_time'] ?? '' }}</span>
                    </div>
                </div>

                <!-- Location/Meeting URL -->
                @if(!empty($event['location']) || !empty($event['meeting_url']))
                <div class="mt-3 flex items-center gap-1.5 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="truncate">{{ $event['location'] ?? $event['meeting_url'] ?? '' }}</span>
                </div>
                @endif

                <!-- Participants -->
                @if(isset($event['registered_count']) || isset($event['max_participants']))
                <div class="mt-3 flex items-center gap-1.5 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="font-medium">
                        {{ $event['registered_count'] ?? 0 }} {{ isset($event['max_participants']) ? '/ ' . $event['max_participants'] . ' peserta' : 'terdaftar' }}
                    </span>
                </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white border border-gray-100 rounded-2xl p-12 shadow-sm text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <h3 class="font-bold text-gray-900 text-xl mb-2">Belum Ada Event</h3>
        <p class="text-gray-500 max-w-md mx-auto">Saat ini belum ada event yang tersedia. Pantau terus untuk event menarik dari 1Langkah!</p>
    </div>
    @endif

</div>
@endsection
