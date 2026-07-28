@extends('layouts.app', ['activePage' => 'online-bootcamp'])

@section('title', __('app.online_bootcamp_title'))
@section('header_title', __('app.online_bootcamp_header'))

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
                (b.mentor && b.mentor.toLowerCase().includes(query))
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
    },
    currentPage: 1,
    perPage: 12,
    get totalPages() {
        return Math.ceil(this.displayedBootcamps.length / this.perPage) || 1;
    },
    get paginatedBootcamps() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.displayedBootcamps.slice(start, start + this.perPage);
    },
    changePage(page) {
        if (page >= 1 && page <= this.totalPages) {
            this.currentPage = page;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },
    get pageNumbers() {
        let pages = [];
        for (let i = 1; i <= this.totalPages; i++) {
            if (i === 1 || i === this.totalPages || Math.abs(i - this.currentPage) <= 1) {
                if (pages.length > 0 && i - pages[pages.length - 1] > 1) {
                    pages.push('...');
                }
                pages.push(i);
            }
        }
        return pages;
    }
}" 
x-init="$watch('searchQuery', () => currentPage = 1); $watch('sortBy', () => currentPage = 1)"
class="w-full px-2 pb-8">

    <!-- Header -->
    <div class="mb-6 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ __('app.online_bootcamp_header') }}</h1>
        <p class="text-gray-500 text-base">{{ __('app.online_bootcamp_desc') }}</p>
    </div>

    <!-- Search & Sort Bar -->
    <div class="mb-6">
        <x-search-filter-bar
            :placeholder="__('app.online_search_placeholder')"
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
        :title="__('app.banner_online_title')"
        :message="__('app.banner_online_desc')"
        :stats="[
            ['value' => '7–10', 'label' => __('app.stat_meetings')],
            ['value' => __('app.stat_val_2_hours'), 'label' => __('app.stat_per_session')],
            ['value' => __('app.stat_val_30_days'), 'label' => __('app.stat_recording_access')]
        ]"
    >
        <x-slot name="icon">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </x-slot>
    </x-alert-banner>

    <!-- Results Count -->
    <x-results-count model="paginatedBootcamps" totalModel="displayedBootcamps" label="bootcamp" />

    <!-- Bootcamp Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="(b, index) in paginatedBootcamps" :key="b.id">
            <a :href="'/bootcamp/online/' + b.id" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.04)] hover:shadow-lg transition-shadow group flex flex-col h-full cursor-pointer">
                <!-- Thumbnail -->
                <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                    <template x-if="b.thumbnail">
                        <img decoding="async" loading="lazy" :src="b.thumbnail" :alt="b.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </template>
                    <template x-if="!b.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + (b.color || '#dc2626') + ',' + (b.color || '#dc2626') + 'cc)'"></div>
                    </template>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent pointer-events-none"></div>

                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-sm" x-text="index === 0 ? '{{ __('app.badge_popular') }}' : (index === 1 ? '{{ __('app.badge_new') }}' : '{{ __('app.badge_premium') }}')"></span>
                        <span class="px-3 py-1 bg-black/40 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-sm">{{ __('app.badge_intermediate') }}</span>
                    </div>
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1.5 bg-white text-red-600 text-xs font-extrabold rounded-full shadow-sm flex items-center gap-1.5 tracking-wide">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            LIVE
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-1">
                    <h2 class="text-lg font-bold text-gray-900 leading-snug mb-2 group-hover:text-red-600 transition-colors line-clamp-2" x-text="b.title"></h2>
                    <p class="text-sm text-gray-500 mb-6 font-medium" x-text="b.mentor || ''"></p>

                    <!-- Enrollment Progress -->
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-gray-400">{{ __('app.enrolled_students') }}</span>
                            <span class="text-red-500" x-text="((b.enrolledCount ?? b.enrolled_count ?? 0)) + ' {{ __('app.from_slots') }} ' + (b.totalSlots || 0)"></span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-red-600 rounded-full" :style="'width: ' + (((b.enrolledCount ?? b.enrolled_count ?? 0) / (b.totalSlots || 1)) * 100) + '%'"></div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-100 mb-5"></div>

                    <!-- Footer -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[13px] font-medium text-gray-400 mb-1" x-text="'{{ __('app.starts_on') }} ' + (b.startDate || '')"></div>
                            <div class="text-[15px] font-bold text-gray-900" x-text="b.sessions || ''"></div>
                        </div>
                        <div class="text-right">
                            <div class="text-[13px] font-medium text-gray-400 mb-1">{{ __('app.price_label') }}</div>
                            <div class="text-lg font-extrabold"
                                 :class="['Gratis', 'Free', '{{ __('app.free') }}'].includes(b.formatted_price) ? 'text-emerald-600' : 'text-red-600'"
                                 x-text="b.formatted_price"></div>
                        </div>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Pagination -->
    <div x-show="totalPages > 1" class="flex justify-center mt-8 pb-4" style="display: none;">
        <nav class="flex items-center gap-1 sm:gap-2">
            <!-- Prev Button -->
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" 
                class="p-2 sm:px-3 sm:py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>

            <!-- Page Numbers -->
            <template x-for="(page, index) in pageNumbers" :key="index">
                <div>
                    <button x-show="page !== '...'" @click="changePage(page)" 
                        :class="currentPage === page ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg border flex items-center justify-center text-sm font-medium transition-colors"
                        x-text="page">
                    </button>
                    <span x-show="page === '...'" class="px-1 sm:px-2 text-gray-400">...</span>
                </div>
            </template>

            <!-- Next Button -->
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" 
                class="p-2 sm:px-3 sm:py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </nav>
    </div>

    <!-- Empty State -->
    <x-empty-state
        x-show="displayedBootcamps.length === 0"
        :title="__('app.empty_bootcamp_title')"
        :message="__('app.empty_search_desc')"
    />
</div>
@endsection
