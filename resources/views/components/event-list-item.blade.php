<!-- Event List Item Component -->
@props([
    'event' => [],
    'href' => null,
])

@php
$href = $href ?? ($event['id'] ? route('detail-event', $event['id']) : null);
$title = $event['title'] ?? '';
$date = $event['date'] ?? '';
$day = $event['day'] ?? '';
$time = $event['time'] ?? '';
$type = $event['type'] ?? 'webinar';
$color = $event['color'] ?? '#cc0000';
@endphp

@if($href)
    <a href="{{ $href }}" class="flex items-start gap-3 p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
@else
    <div class="flex items-start gap-3">
@endif

    {{-- Date Box --}}
    <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-white flex-shrink-0" style="background-color: {{ $color }}">
        <span class="text-xs font-bold leading-none">{{ $date }}</span>
    </div>

    <div class="flex-1 min-w-0">
        <h2 class="text-sm font-bold text-gray-900 truncate">{{ $title }}</h2>
        <p class="text-[11px] text-gray-500">{{ $day }}, {{ $time }}</p>
        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 capitalize">
            {{ $type }}
        </span>
    </div>

@if($href)
    </a>
@else
    </div>
@endif
