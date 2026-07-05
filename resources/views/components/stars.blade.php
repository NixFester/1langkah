@php
    /**
     * Star Rating Display Component (Tailwind version)
     *
     * @param float  $rating   Rating value (0-5)
     * @param string $size     Size: sm|md|lg (default: md)
     * @param bool   $showValue Show numeric value (default: true)
     * @param bool   $showCount Show review count (default: false)
     * @param int    $count     Review count
     */
    $rating = $rating ?? 0;
    $size = $size ?? 'md';
    $showValue = $showValue ?? true;
    $showCount = $showCount ?? false;
    $count = $count ?? 0;

    $sizes = [
        'sm' => 'w-3.5 h-3.5',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
    ];
    $starSize = $sizes[$size] ?? $sizes['md'];
    $textSize = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-base',
    ][$size] ?? 'text-sm';
@endphp

<div class="flex items-center gap-1.5">
    <div class="flex text-yellow-400">
        @for($i = 1; $i <= 5; $i++)
            <svg class="{{ $starSize }} {{ $i <= round($rating) ? '' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
        @endfor
    </div>
    @if($showValue)
        <span class="{{ $textSize }} font-semibold text-gray-700">{{ number_format($rating, 1) }}</span>
    @endif
    @if($showCount)
        <span class="{{ $textSize }} text-gray-400">({{ number_format($count) }})</span>
    @endif
</div>
