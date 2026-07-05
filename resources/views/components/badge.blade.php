@php
    /**
     * Badge Component
     *
     * @param string $text     Badge text
     * @param string $type     Badge type: primary|success|warning|danger|info|gold|purple|orange|dark|white
     * @param string $size     Size: sm|md|lg
     * @param bool   $rounded  Use rounded-full (default: true)
     */
    $text = $text ?? '';
    $type = $type ?? 'primary';
    $size = $size ?? 'md';
    $rounded = $rounded ?? true;

    $typeClasses = [
        'primary' => 'bg-red-100 text-red-600',
        'success' => 'bg-emerald-100 text-emerald-700',
        'warning' => 'bg-amber-100 text-amber-700',
        'danger' => 'bg-red-100 text-red-600',
        'info' => 'bg-blue-100 text-blue-600',
        'gold' => 'bg-yellow-100 text-yellow-800',
        'purple' => 'bg-purple-100 text-purple-700',
        'orange' => 'bg-orange-100 text-orange-600',
        'dark' => 'bg-gray-800 text-white',
        'white' => 'bg-white text-gray-700',
        // Special variants
        'bestseller' => 'bg-[#FFF4ED] text-[#F97316]',
        'hot' => 'bg-rose-50 text-rose-600',
    ];

    $sizeClasses = [
        'sm' => 'text-[10px] px-2 py-0.5',
        'md' => 'text-xs px-2.5 py-1',
        'lg' => 'text-sm px-3 py-1.5',
    ];

    $classes = $typeClasses[$type] ?? $typeClasses['primary'];
    $sizeCls = $sizeClasses[$size] ?? $sizeClasses['md'];
    $roundedCls = $rounded ? 'rounded-full shadow-sm' : 'rounded-lg';
@endphp

<span class="badge {{ $classes }} {{ $sizeCls }} {{ $roundedCls }} font-bold inline-flex items-center">
    {{ $text }}
</span>
