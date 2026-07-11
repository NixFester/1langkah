@php
    /**
     * Alert/Info Banner Component
     *
     * @param string $type     info|success|warning|error (default: info)
     * @param string $title    Banner title
     * @param string $message  Banner message
     * @param array  $stats    Optional stats to display: [['value' => '100+', 'label' => __('app.students_count')]]
     */
    $type = $type ?? 'info';
    $title = $title ?? '';
    $message = $message ?? '';

    $colors = [
        'info' => 'bg-[#b91c1c] text-white',
        'success' => 'bg-emerald-600 text-white',
        'warning' => 'bg-amber-500 text-white',
        'error' => 'bg-red-600 text-white',
    ];
    $color = $colors[$type] ?? $colors['info'];
@endphp

<div class="rounded-2xl p-5 sm:p-6 md:p-8 {{ $color }} mb-8 sm:mb-10 flex flex-col lg:flex-row items-center justify-between gap-6 sm:gap-8 shadow-md">
    <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-5 md:gap-6 flex-1 text-center sm:text-left">
        @if(isset($icon))
            <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                {!! $icon !!}
            </div>
        @endif
        <div class="flex-1">
            @if($title)
                <h3 class="text-[20px] sm:text-[22px] font-bold mb-1.5 tracking-tight">{{ $title }}</h3>
            @endif
            @if($message)
                <p class="text-current text-[13.5px] sm:text-[15px] leading-relaxed max-w-2xl opacity-90 font-medium">{{ $message }}</p>
            @endif
        </div>
    </div>

    {{-- Optional Stats --}}
    @if(!empty($stats))
        <div class="flex items-center justify-center gap-4 sm:gap-8 md:gap-10 lg:pr-6 w-full lg:w-auto">
            @foreach($stats as $stat)
                <div class="text-center">
                    <div class="text-2xl sm:text-[28px] font-extrabold leading-tight">{{ $stat['value'] }}</div>
                    <div class="text-[11px] sm:text-[13px] opacity-80 font-medium mt-0.5 sm:mt-0">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    @endif
</div>
