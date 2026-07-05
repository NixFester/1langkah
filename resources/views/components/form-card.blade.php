<!-- Admin Form Card Component -->
@props([
    'title' => null,
    'subtitle' => null,
])

<div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
    @if(isset($title))
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
            @if(isset($subtitle))
                <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="p-6 {{ isset($title) ? '' : '' }}">
        {{ $slot }}
    </div>
</div>
