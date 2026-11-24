@props(['class' => 'h-[174px]'])

<div {{ $attributes->merge(['class' => "relative flex items-center justify-center {$class}"]) }} aria-hidden="true">
    <div class="absolute h-36 w-36 rounded-full border border-[#D5D8EC]"></div>
    <div class="absolute h-28 w-28 rounded-full bg-white/80"></div>
    <svg class="z-10 h-20 w-20 text-[#8580BE]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 14V9a8 8 0 0 1 16 0v5 M7 17V9a5 5 0 0 1 10 0v9 M10 21V9a2 2 0 0 1 4 0v12 M4 18v2" />
    </svg>
    @php
        $nodes = [
            ['bg' => 'bg-[#F2B3A6]', 'icon' => 'barChart', 'deg' => -90],
            ['bg' => 'bg-[#B4C9EE]', 'icon' => 'sparkles', 'deg' => -18],
            ['bg' => 'bg-[#C8B8E5]', 'icon' => 'book', 'deg' => 54],
            ['bg' => 'bg-[#B2D9C5]', 'icon' => 'user', 'deg' => 126],
            ['bg' => 'bg-[#EDD59E]', 'icon' => 'award', 'deg' => 198],
        ];
    @endphp
    @foreach($nodes as $node)
        <span class="absolute flex h-9 w-9 items-center justify-center rounded-xl {{ $node['bg'] }}"
            style="transform: rotate({{ $node['deg'] }}deg) translateX(79px) rotate({{ -$node['deg'] }}deg);">
            <x-icon :name="$node['icon']" class="h-[18px] w-[18px] text-stone-700" />
        </span>
    @endforeach
</div>
