@php
    /**
     * Stats Row Component
     *
     * @param array $stats   Array of ['value' => '100+', 'label' => __('app.active_students')]
     * @param bool  $centered Center the stats
     */
    $stats = $stats ?? [];
    $centered = $centered ?? true;
@endphp

<div class="flex items-center justify-center{{ $centered ? '' : '-start' }} gap-4 lg:gap-8 xl:gap-14 whitespace-nowrap w-full">
    @foreach($stats as $stat)
        <div class="text-center{{ $centered ? '' : ' lg:text-left' }}">
            <div class="text-[20px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0.5 lg:mb-1 tracking-tight">
                {{ $stat['value'] }}
            </div>
            <div class="text-[11px] lg:text-[12px] xl:text-[13px] font-medium text-[#6b7280]">
                {{ $stat['label'] }}
            </div>
        </div>
    @endforeach
</div>
