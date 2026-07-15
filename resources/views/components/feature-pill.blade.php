@php
    /**
     * Mini Feature Pill Component
     *
     * @param string $icon    Icon image path
     * @param string $label  Pill label
     * @param string $color  Accent color (default: red)
     */
    $icon = $icon ?? '';
    $label = $label ?? '';
    $color = $color ?? 'red';
@endphp

<div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
    @if($icon)
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
            <img decoding="async" loading="lazy" src="{{ asset($icon) }}" alt="{{ $label }}" class="w-full h-full object-contain">
        </div>
    @endif
    <span class="text-[13px] md:text-sm font-bold text-gray-800">{{ $label }}</span>
</div>
