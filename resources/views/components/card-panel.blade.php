<!-- Card Panel Component (for sections/cards with title) -->
@props([
    'title' => null,
    'subtitle' => null,
    'actionRoute' => null,
    'actionLabel' => null,
    'actionIcon' => null,
    'class' => '',
])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden {{ $class }}">
    @if(isset($title) || isset($actionRoute))
        <div class="p-5 md:p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
            <div class="min-w-0">
                @if(isset($title))
                    <h3 class="font-bold text-gray-800 truncate">{{ $title }}</h3>
                @endif
                @if(isset($subtitle))
                    <p class="text-sm text-gray-500 mt-1 truncate">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($actionRoute))
                <a href="{{ $actionRoute }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 transition-colors flex items-center gap-1 flex-shrink-0">
                    {{ $actionLabel ?? 'Lihat Semua' }}
                    <span>→</span>
                </a>
            @endif
        </div>
    @endif

    <div class="p-6 {{ !isset($title) && !isset($actionRoute) ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>
