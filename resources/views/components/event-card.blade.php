@php
    /**
     * Event Card Component
     *
     * @param array $event Event data
     * @param bool  $compact Use compact layout
     */
    $event = $event ?? [];
    $compact = $compact ?? false;

    $detailUrl = route('detail-event', $event['id']);
    $color = $event['color'] ?? '#cc0000';
@endphp

<a href="{{ $detailUrl }}"
   class="group bg-white border border-gray-100 rounded-[24px] overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.03)] hover:shadow-lg transition-all duration-300 hover:-translate-y-1 flex flex-col h-full {{ $compact ? 'rounded-2xl' : '' }}">

    <!-- Banner -->
    <div class="relative h-40 overflow-hidden {{ $compact ? 'h-32' : 'h-40' }}" style="background-color: {{ $color }}20;">
        @if(!empty($event['banner_url']))
            <img src="{{ str_starts_with($event['banner_url'], 'http') ? $event['banner_url'] : asset($event['banner_url']) }}" alt="{{ $event['title'] }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        <!-- Type Badge -->
        <div class="absolute top-3 right-3">
            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold capitalize" style="color: {{ $color }};">
                {{ $event['type'] ?? 'online' }}
            </span>
        </div>

        <!-- Status Badge -->
        @if(isset($event['status']) && $event['status'] !== 'completed')
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold capitalize text-gray-700">
                    {{ str_replace('_', ' ', $event['status']) }}
                </span>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-6 flex flex-col flex-grow {{ $compact ? 'p-4' : 'p-6' }}">
        <h3 class="text-[19px] font-bold text-[#0f172a] mb-2 leading-tight line-clamp-2 group-hover:text-red-600 transition-colors {{ $compact ? 'text-base' : 'text-[19px]' }}">
            {{ $event['title'] }}
        </h3>

        @if(!empty($event['short_description']))
            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $event['short_description'] }}</p>
        @endif

        <!-- Meta Info -->
        <div class="mt-auto pt-2 space-y-3">
            <div class="flex items-center gap-4 text-[13px] text-[#64748b]">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">{{ $event['date_display'] ?? 'TBA' }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ $event['start_time'] ?? '' }}</span>
                </div>
            </div>

            <!-- Location/Meeting URL -->
            @if(!empty($event['location']) || !empty($event['meeting_url']))
                <div class="flex items-center gap-1.5 text-[13px] text-[#64748b]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="truncate">{{ $event['location'] ?? $event['meeting_url'] ?? '' }}</span>
                </div>
            @endif

            @if(isset($event['registered_count']) || isset($event['max_participants']))
                <div class="flex items-center gap-1.5 text-[13px] text-[#64748b]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="font-medium">
                        {{ $event['registered_count'] ?? 0 }}
                        @if(isset($event['max_participants']))
                            / {{ $event['max_participants'] }} peserta
                        @else
                            terdaftar
                        @endif
                    </span>
                </div>
            @endif
        </div>
    </div>
</a>
