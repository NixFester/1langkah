<!-- List Item Component -->
@props([
    'href' => null,
    'thumbnail' => null,
    'title' => '',
    'subtitle' => null,
    'meta' => null,
    'action' => null,
    'color' => null,
    'badge' => null,
    'progress' => null,
    'progressColor' => 'red',
])

@php
$progressColorClass = match($progressColor) {
    'blue' => 'bg-blue-600',
    'green' => 'bg-green-600',
    'purple' => 'bg-purple-600',
    'red' => 'bg-red-600',
    default => 'bg-red-600',
};
@endphp

@if($href)
<a href="{{ $href }}" class="flex items-center gap-3 hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
@else
<div class="flex items-center gap-3 hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
@endif

    {{-- Thumbnail --}}
    @if($thumbnail)
    <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
        @if(is_string($thumbnail) && (Str::startsWith($thumbnail, 'http') || Str::startsWith($thumbnail, '/')))
            <img src="{{ $thumbnail }}" class="w-full h-full object-cover" alt="">
        @elseif($color)
            <div class="w-full h-full" style="background: linear-gradient(135deg, {{ $color }}, {{ $color }}cc);"></div>
        @endif
    </div>
    @endif

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
            @if($badge)
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $badge['class'] ?? 'bg-blue-100 text-blue-700' }}">
                {{ $badge['text'] }}
            </span>
            @endif
        </div>
        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $title }}</h4>
        @if($subtitle)
        <p class="text-[11px] text-gray-500 truncate">{{ $subtitle }}</p>
        @endif

        {{-- Progress bar --}}
        @if($progress !== null)
        <div class="flex items-center gap-2 mt-2">
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full {{ $progressColorClass }} rounded-full" style="width: {{ $progress }}%"></div>
            </div>
            @if($meta)
            <span class="text-[10px] text-gray-500 font-medium whitespace-nowrap">{{ $meta }}</span>
            @endif
        </div>
        @endif
    </div>

    {{-- Action slot --}}
    @if(isset($action))
    <div class="flex-shrink-0">
        {{ $action }}
    </div>
    @endif

@if($href)
</a>
@else
</div>
@endif
