@php
    /**
     * Bootcamp Card Component
     *
     * @param array $bootcamp Bootcamp data from CatalogService
     * @param int   $index    Position index for conditional badges
     * @param bool  $compact  Use compact layout
     */
    $bootcamp = $bootcamp ?? [];
    $b = $bootcamp;
    $index = $index ?? 0;
    $compact = $compact ?? false;

    $detailUrl = route('detail-online-bootcamp', ['id' => $b['id']]);
    $enrolled = $b['enrolledCount'] ?? $b['enrolled_count'] ?? 0;
    $totalSlots = $b['totalSlots'] ?? $b['total_slots'] ?? 1;
    $progressPercent = min(100, ($enrolled / max(1, $totalSlots)) * 100);

    // Badge logic
    $badgeLabels = [__('app.most_wanted'), __('app.new'), __('app.premium')];
    $badge = $badgeLabels[$index % count($badgeLabels)] ?? __('app.premium');
@endphp

<a href="{{ $detailUrl }}"
   class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.04)] hover:shadow-lg transition-shadow group flex flex-col h-full cursor-pointer {{ $compact ? 'rounded-xl' : '' }}">

    <!-- Thumbnail -->
    <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden {{ $compact ? 'aspect-[16/9]' : '' }}">
        @if(!empty($b['thumbnail']))
            <img decoding="async" loading="lazy" src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full"
                 style="background:linear-gradient(135deg,{{ $b['color'] ?? '#dc2626' }},{{ $b['color'] ?? '#dc2626' }}cc)"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent pointer-events-none"></div>

        <!-- Top Badges -->
        <div class="absolute top-4 left-4 flex gap-2">
            <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-sm">{{ $badge }}</span>
            <span class="px-3 py-1 bg-black/40 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-sm">
                {{ $b['level'] ?? 'Intermediate' }}
            </span>
        </div>

        <!-- {{ __('app.live') }} Badge -->
        <div class="absolute top-4 right-4">
            <span class="px-3 py-1.5 bg-white text-red-600 text-xs font-extrabold rounded-full shadow-sm flex items-center gap-1.5 tracking-wide">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                {{ __('app.live') }}
            </span>
        </div>
    </div>

    <!-- Content -->
    <div class="p-6 flex flex-col flex-1 {{ $compact ? 'p-4' : 'p-6' }}">
        <h2 class="text-lg font-bold text-gray-900 leading-snug mb-2 group-hover:text-red-600 transition-colors line-clamp-2 {{ $compact ? 'text-base' : 'text-lg' }}">
            {{ $b['title'] }}
        </h2>
        <p class="text-sm text-gray-500 mb-6 font-medium">{{ $b['mentor'] ?? '' }}</p>

        <!-- Enrollment Progress -->
        <div class="mt-auto mb-5">
            <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                <span class="text-gray-400">{{ __('app.registered_participants') }}</span>
                <span class="text-red-500">{{ $enrolled }} {{ __('app.of') }} {{ $totalSlots }}</span>
            </div>
            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-red-600 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
            </div>
        </div>

        <div class="w-full h-px bg-gray-100 mb-5"></div>

        <!-- Footer -->
        <div class="flex items-end justify-between">
            <div>
                <div class="text-[13px] font-medium text-gray-400 mb-1">{{ __('app.starts') }} {{ $b['startDate'] ?? '' }}</div>
                <div class="text-[15px] font-bold text-gray-900">{{ $b['sessions'] ?? '' }}</div>
            </div>
            <div class="text-right">
                <div class="text-[13px] font-medium text-gray-400 mb-1">{{ __('app.price') }}</div>
                <div class="text-lg font-extrabold {{ in_array(($b['formatted_price'] ?? ''), ['Gratis', 'Free', __('app.free')]) ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ in_array(($b['formatted_price'] ?? ''), ['Gratis', 'Free', __('app.free')]) ? __('app.free') : ($b['formatted_price'] ?? 'Rp 0') }}
                </div>
            </div>
        </div>
    </div>
</a>
