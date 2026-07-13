@php
    /**
     * Feature Card Component
     *
     * @param string $icon    Icon SVG or image path
     * @param string $title   Card title
     * @param string $desc    Card description
     * @param string $link    Optional link URL
     * @param string $linkText Link text
     * @param string $color   Accent color (red|emerald|purple|orange)
     */
    $icon = $icon ?? '';
    $title = $title ?? '';
    $desc = $desc ?? '';
    $link = $link ?? '#';
    $linkText = $linkText ?? 'Pelajari lebih lanjut';
    $color = $color ?? 'red';

    $colorClasses = [
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'link' => 'text-red-500'],
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'link' => 'text-emerald-500'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'link' => 'text-purple-500'],
        'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'link' => 'text-orange-500'],
    ];
    $colors = $colorClasses[$color] ?? $colorClasses['red'];
@endphp

<div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">

    <!-- Icon -->
    <div class="w-14 h-14 rounded-2xl {{ $colors['bg'] }} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform p-3">
        @if(str_starts_with($icon, 'images/'))
            <img src="{{ asset($icon) }}" alt="{{ $title }}" class="w-full h-full object-contain">
        @else
            {!! $icon !!}
        @endif
    </div>

    <!-- Content -->
    <h2 class="text-xl font-bold text-gray-900 mb-3">{{ $title }}</h2>
    <p class="text-[15px] text-gray-500 leading-relaxed mb-8 flex-1">{{ $desc }}</p>

    <!-- Link -->
    @if($link !== '#')
        <a href="{{ $link }}"
           class="inline-flex items-center gap-1.5 text-sm font-bold {{ $colors['link'] }} group-hover:gap-2 transition-all">
            {{ $linkText }}
            <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"/>
                <path d="m12 5 7 7-7 7"/>
            </svg>
        </a>
    @endif
</div>
