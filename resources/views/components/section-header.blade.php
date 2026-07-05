@php
    /**
     * Section Header Component
     *
     * @param string $badge     Badge text (optional)
     * @param string $badgeIcon Badge icon SVG path (optional)
     * @param string $badgeColor Badge color (default: red)
     * @param string $title    Section title
     * @param string $subtitle Section subtitle (optional)
     * @param string $link     View all link URL (optional)
     * @param string $linkText View all link text (optional)
     */
    $badge = $badge ?? '';
    $badgeIcon = $badgeIcon ?? '';
    $badgeColor = $badgeColor ?? 'red';
    $title = $title ?? '';
    $subtitle = $subtitle ?? '';
    $link = $link ?? '';
    $linkText = $linkText ?? 'Lihat semua';

    $badgeColors = [
        'red' => ['bg' => 'bg-red-100/50', 'border' => 'border-red-200', 'text' => 'text-red-700', 'icon' => 'text-red-600'],
        'emerald' => ['bg' => 'bg-emerald-100/50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'icon' => 'text-emerald-600'],
        'purple' => ['bg' => 'bg-purple-100/50', 'border' => 'border-purple-200', 'text' => 'text-purple-700', 'icon' => 'text-purple-600'],
    ];
    $colors = $badgeColors[$badgeColor] ?? $badgeColors['red'];
@endphp

<div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
    <div>
        @if($badge)
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full {{ $colors['bg'] }} border {{ $colors['border'] }} mb-4">
                @if($badgeIcon)
                    <svg class="w-3.5 h-3.5 {{ $colors['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $badgeIcon !!}
                    </svg>
                @endif
                <span class="text-[11px] font-bold tracking-[0.15em] {{ $colors['text'] }} uppercase">{{ $badge }}</span>
            </div>
        @endif
        <h2 class="text-4xl md:text-[42px] font-extrabold text-[#0f172a] tracking-tight">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-gray-500 text-base mt-2">{{ $subtitle }}</p>
        @endif
    </div>
    @if($link)
        <a href="{{ $link }}"
           class="inline-flex items-center gap-1.5 text-[15px] font-bold text-[#D10000] hover:text-[#b30000] transition-colors md:pb-2 whitespace-nowrap">
            {{ $linkText }}
            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    @endif
</div>
