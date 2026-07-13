<!-- Bootcamp List Item Component -->
@props([
    'bootcamp' => [],
    'href' => null,
    'showProgress' => false,
])

@php
$type = $bootcamp['type'] ?? 'online';
$href = $href ?? (
    $bootcamp['id']
        ? ($type === 'online' ? route('detail-online-bootcamp', $bootcamp['id']) : route('detail-offline-bootcamp', $bootcamp['id']))
        : null
);
$title = $bootcamp['title'] ?? '';
$mentor = $bootcamp['mentor'] ?? 'Mentor';
$thumbnail = $bootcamp['thumbnail'] ?? null;
$progress = $bootcamp['progress'] ?? 0;
$attended = $bootcamp['attended'] ?? 0;
$sessions = $bootcamp['sessions'] ?? 0;
@endphp

@if($href)
    <a href="{{ $href }}" class="flex gap-4 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
@else
    <div class="flex gap-4 items-center">
@endif

    {{-- Thumbnail --}}
    <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
        @if($thumbnail)
            <img src="{{ $thumbnail }}" class="w-full h-full object-cover" alt="">
        @else
            <div class="w-full h-full flex items-center justify-center bg-blue-600 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
        @endif
    </div>

    <div class="flex-1 min-w-0">
        {{-- Type Badge --}}
        <div class="flex items-center gap-2 mb-1">
            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold {{ $type === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                {{ $type === 'online' ? 'Online' : 'Offline' }}
            </span>
        </div>

        <h2 class="text-sm font-bold text-gray-900 truncate">{{ $title }}</h2>
        <p class="text-[11px] text-gray-500 mb-2 truncate">{{ $mentor }}</p>

        @if($showProgress)
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-[10px] text-gray-500 font-medium mt-1">{{ $progress }}% ({{ $attended }}/{{ $sessions }} {{ __('app.sessions') }})</p>
        @endif
    </div>

@if($href)
    </a>
@else
    </div>
@endif
