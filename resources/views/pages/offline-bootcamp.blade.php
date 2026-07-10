@extends('layouts.app', ['activePage' => 'offline-bootcamp'])

@section('title', __('app.offline_bootcamp_title'))
@section('header_title', __('app.offline_bootcamp_header'))

@section('content')
<div x-data="{
    searchQuery: '',
    sortBy: 'newest',
    filteredBootcamps: {{ json_encode($bootcamps) }},
    get displayedBootcamps() {
        let bootcamps = this.filteredBootcamps;

        // Filter by search
        if (this.searchQuery) {
            const query = this.searchQuery.toLowerCase();
            bootcamps = bootcamps.filter(b =>
                (b.title && b.title.toLowerCase().includes(query)) ||
                (b.mentor && b.mentor.toLowerCase().includes(query)) ||
                (b.location && b.location.toLowerCase().includes(query))
            );
        }

        // Sort
        if (this.sortBy === 'newest') {
            bootcamps = [...bootcamps].reverse();
        } else if (this.sortBy === 'price_low') {
            bootcamps = [...bootcamps].sort((a, b) => {
                const priceA = parseInt((a.price || '0').toString().replace(/\D/g, '')) || 0;
                const priceB = parseInt((b.price || '0').toString().replace(/\D/g, '')) || 0;
                return priceA - priceB;
            });
        } else if (this.sortBy === 'price_high') {
            bootcamps = [...bootcamps].sort((a, b) => {
                const priceA = parseInt((a.price || '0').toString().replace(/\D/g, '')) || 0;
                const priceB = parseInt((b.price || '0').toString().replace(/\D/g, '')) || 0;
                return priceB - priceA;
            });
        }

        return bootcamps;
    }
}" class="w-full px-2 pb-8">

    <!-- Header -->
    <div class="mb-6 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ __('app.offline_bootcamp_header') }}</h1>
        <p class="text-gray-500 text-base">{{ __('app.offline_bootcamp_desc') }}</p>
    </div>

    <!-- Search & Sort Bar -->
    <div class="mb-6">
        <x-search-filter-bar
            placeholder="{{ __('app.offline_search_placeholder') }}"
            :sort-options="[
                'newest' => __('app.sort_newest'),
                'price_low' => __('app.sort_price_low'),
                'price_high' => __('app.sort_price_high')
            ]"
        />
    </div>

    <!-- Alert / Info Banner -->
    <x-alert-banner
        type="info"
        title="{{ __('app.banner_offline_title') }}"
        message="{{ __('app.banner_offline_desc') }}"
        :stats="[
            ['value' => '3 Kota', 'label' => __('app.stat_available')],
            ['value' => 'Max 20', 'label' => __('app.stat_participants_batch')],
            ['value' => 'Sertifikat', 'label' => __('app.stat_verified')]
        ]"
    >
        <x-slot name="icon">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </x-slot>
    </x-alert-banner>

    <!-- Results Count -->
    <x-results-count model="displayedBootcamps" label="bootcamp" />

    <!-- Bootcamp Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="(b, index) in displayedBootcamps" :key="b.id">
            <a :href="'/bootcamp/offline/' + b.id" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.04)] hover:shadow-lg transition-shadow group flex flex-col h-full cursor-pointer">
                <!-- Thumbnail -->
                <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                    <template x-if="b.thumbnail">
                        <img :src="b.thumbnail" :alt="b.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </template>
                    <template x-if="!b.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + (b.color || '#3e2723') + ',' + (b.color || '#3e2723') + 'cc)'"></div>
                    </template>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>

                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-red-700 text-white text-[11px] font-bold rounded-full shadow-sm" x-text="index === 0 ? '{{ __('app.badge_popular') }}' : (index === 1 ? '{{ __('app.badge_weekend') }}' : '{{ __('app.badge_exclusive') }}')"></span>
                        <span class="px-3 py-1 bg-black/40 backdrop-blur-sm text-white text-[11px] font-semibold rounded-full shadow-sm" x-text="index === 0 ? '{{ __('app.badge_all_level') }}' : (index === 1 ? '{{ __('app.badge_beginner') }}' : '{{ __('app.badge_intermediate') }}')"></span>
                    </div>
                    <!-- Bottom Location Badge -->
                    <div class="absolute bottom-3 left-4">
                        <div class="flex items-center gap-1.5 text-white/90 text-[12px] font-semibold tracking-wide shadow-sm drop-shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span x-text="b.location ? b.location.split(',')[0] : ''"></span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-[17px] font-bold text-gray-900 leading-snug mb-1.5 group-hover:text-red-700 transition-colors line-clamp-2" x-text="b.title"></h3>
                    <p class="text-[13px] text-gray-500 mb-4 line-clamp-2" x-text="b.mentor || ''"></p>

                    <div class="flex items-start gap-1.5 text-[12px] text-gray-400 mb-4">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="leading-relaxed" x-text="b.location || ''"></span>
                    </div>

                    <div class="flex items-center gap-4 text-[12px] text-gray-500 font-medium mb-6">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span x-text="index === 0 ? '{{ __('app.duration_8_weeks') }}' : '{{ __('app.duration_8_weeks') }}'"></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span x-text="index === 0 ? '{{ __('app.frequency_2_times') }}' : '{{ __('app.frequency_weekend') }}'"></span>
                        </div>
                    </div>

                    <!-- Enrollment Progress -->
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-gray-400">{{ __('app.enrolled_students') }}</span>
                            <span class="text-green-500" x-text="((b.enrolledCount ?? b.enrolled_count ?? 0)) + ' {{ __('app.from_slots') }} ' + (b.totalSlots || 0)"></span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" :style="'width: ' + (((b.enrolledCount ?? b.enrolled_count ?? 0) / (b.totalSlots || 1)) * 100) + '%'"></div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-100 mb-4"></div>

                    <!-- Footer -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 mb-1" x-text="'{{ __('app.starts_on') }} ' + (b.startDate || '')"></div>
                            <div class="text-[16px] font-extrabold"
                                 :class="b.formatted_price === 'Gratis' ? 'text-emerald-600' : 'text-black'"
                                 x-text="b.formatted_price"></div>
                        </div>
                        <div>
                            <span class="px-3 py-1.5 bg-red-50 text-red-600 text-[11px] font-bold rounded-full">{{ __('app.soft_skills') }}</span>
                        </div>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Empty State -->
    <x-empty-state
        x-show="displayedBootcamps.length === 0"
        title="{{ __('app.empty_bootcamp_title') }}"
        message="{{ __('app.empty_search_desc') }}"
    />
</div>
@endsection
