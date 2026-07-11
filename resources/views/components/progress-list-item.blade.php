<!-- Progress List Item Component -->
@props([
    'title' => '',
    'subtitle' => '',
    'progress' => 0,
    'href' => null,
    'color' => '#dc2626',
    'thumbnail' => null,
])

@if($href)
    <a href="{{ $href }}" class="flex gap-4 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
@else
    <div class="flex gap-4 items-center">
@endif

    <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
        @if($thumbnail)
            <img src="{{ $thumbnail }}" class="w-full h-full object-cover" alt="">
        @else
            <div class="w-full h-full" style="background: linear-gradient(135deg, {{ $color }}, {{ $color }}cc);"></div>
        @endif
    </div>

    <div class="flex-1 min-w-0">
        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $title }}</h4>
        @if($subtitle)
            <p class="text-[11px] text-gray-500 mb-2 truncate">{{ $subtitle }}</p>
        @endif
        <div class="flex items-center gap-2">
            <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full" style="width: {{ $progress ?? 0 }}%; background-color: {{ $color }};"></div>
            </div>
        </div>
        <p class="text-[10px] text-gray-500 font-medium mt-1">{{ $progress ?? 0 }}% {{ __('app.completed') }}</p>
    </div>

@if($href)
    </a>
@else
    </div>
@endif
