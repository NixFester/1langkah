@php
    /** @var \App\Services\CatalogService $catalog */
    $activePage = 'kursus';
@endphp

@extends('layouts.app')

@section('title', __('app.courses') . ' — 1Langkah')

@section('content')
<div x-data="{
    tab: 'semua',
    activeCat: 'All',
    searchQuery: '',
    activeLevel: 'All',
    sortBy: 'newest',
    showFilter: false,
    myCourseTab: 'semua',
    semuaCourseTab: 'semua',
    filteredCourses: {{ json_encode($courses) }},
    allMyCourses: {{ json_encode($myCourses) }},
    get inProgressCount() {
        return this.allMyCourses.filter(c => (c.progress || 0) < 100).length;
    },
    get completedCount() {
        return this.allMyCourses.filter(c => (c.progress || 0) >= 100).length;
    },
    get wishlistCount() {
        return this.allMyCourses.filter(c => c.is_wishlist).length;
    },
    get displayedCourses() {
        let courses = this.tab === 'saya' ? this.allMyCourses : this.filteredCourses;

        if (this.tab === 'saya' && this.myCourseTab !== 'semua') {
            if (this.myCourseTab === 'sedang_berlangsung') {
                courses = courses.filter(c => (c.progress || 0) < 100);
            } else if (this.myCourseTab === 'selesai') {
                courses = courses.filter(c => (c.progress || 0) >= 100);
            }
        } else if (this.tab === 'semua' && this.semuaCourseTab === 'wishlist') {
            courses = this.otherCourses;
        }

        // Filter by search
        if (this.searchQuery) {
            const query = this.searchQuery.toLowerCase();
            courses = courses.filter(c =>
                (c.title && c.title.toLowerCase().includes(query)) ||
                (c.mentor && c.mentor.toLowerCase().includes(query)) ||
                (c.category && c.category.toLowerCase().includes(query))
            );
        }

        // Filter by category
        if (this.activeCat !== 'All') {
            courses = courses.filter(c => c.category === this.activeCat);
        }

        // Filter by level
        if (this.activeLevel !== 'All') {
            courses = courses.filter(c => c.level === this.activeLevel);
        }

        // Sort
        if (this.sortBy === 'newest') {
            courses = [...courses].reverse();
        } else if (this.sortBy === 'rating') {
            courses = [...courses].sort((a, b) => (b.rating || 0) - (a.rating || 0));
        } else if (this.sortBy === 'price_low') {
            courses = [...courses].sort((a, b) => {
                const priceA = parseInt((a.price || '0').toString().replace(/\D/g, '')) || 0;
                const priceB = parseInt((b.price || '0').toString().replace(/\D/g, '')) || 0;
                return priceA - priceB;
            });
        } else if (this.sortBy === 'price_high') {
            courses = [...courses].sort((a, b) => {
                const priceA = parseInt((a.price || '0').toString().replace(/\D/g, '')) || 0;
                const priceB = parseInt((b.price || '0').toString().replace(/\D/g, '')) || 0;
                return priceB - priceA;
            });
        }

        return courses;
    },
    currentPage: 1,
    perPage: 12,
    get totalPages() {
        return Math.ceil(this.displayedCourses.length / this.perPage) || 1;
    },
    get paginatedCourses() {
        const start = (this.currentPage - 1) * this.perPage;
        return this.displayedCourses.slice(start, start + this.perPage);
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
x-init="$watch('tab', () => currentPage = 1); $watch('searchQuery', () => currentPage = 1); $watch('activeCat', () => currentPage = 1); $watch('activeLevel', () => currentPage = 1); $watch('sortBy', () => currentPage = 1)"
class="w-full px-2 pb-8 space-y-8">

    <!-- Header -->
    <div class="flex items-start justify-between -mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('app.courses') }}</h1>
            <p class="text-gray-500 text-base">{{ __('app.courses_subtitle') }}</p>
        </div>
    </div>

    <!-- Red Banner (Kursus Saya only) -->
    <div x-show="tab === 'saya'" x-cloak style="display: none;"
        class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-3xl p-9 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-8 sm:gap-12">
            <div class="pr-8 border-r border-white/20">
                <div class="text-3xl md:text-4xl font-bold tracking-tight mb-1">{{ $userStats['courses_enrolled'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">{{ __('app.active_courses_count') }}</div>
            </div>
            <div class="pr-8 border-r border-white/20">
                <div class="text-3xl md:text-4xl font-bold tracking-tight mb-1">{{ $userStats['courses_completed'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">{{ __('app.completed') }}</div>
            </div>
            <div>
                <div class="text-3xl md:text-4xl font-bold tracking-tight mb-1">{{ $userStats['certificates'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">{{ __('app.certificates') }}</div>
            </div>
        </div>
        <a href="{{ route('kursus-saya') }}"
            class="bg-white/15 hover:bg-white/25 text-white font-bold rounded-full px-6 py-3 text-sm flex items-center gap-2 whitespace-nowrap transition-colors">
            {!! __('app.see_learning_path') !!}
        </a>
    </div>
    <script>document.querySelector('[x-cloak]')?.removeAttribute('x-cloak')</script>
    <style>[x-cloak] { display: none !important; }</style>

    <!-- Search & Filter Bar -->
    <div @click.away="showFilter = false" class="relative z-20 bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input aria-label="'{{ __('app.search_placeholder') }}'" type="text" x-model="searchQuery" :placeholder="'{{ __('app.search_placeholder') }}'"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
            </div>

            <!-- Sort Dropdown -->
            <div class="relative w-full md:w-auto">
                <select aria-label="Input Field" x-model="sortBy" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
                    <option value="newest">{{ __('app.newest') }}</option>
                    <option value="rating">{{ __('app.highest_rating') }}</option>
                    <option value="price_low">{{ __('app.price_low_high') }}</option>
                    <option value="price_high">{{ __('app.price_high_low') }}</option>
                </select>
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>



            <!-- Filter Toggle -->
            <button @click="showFilter = !showFilter"
                :class="showFilter ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-200'"
                class="px-4 py-3 border rounded-xl text-sm font-medium flex items-center justify-center gap-2 transition-colors w-full md:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                {{ __('app.filter') }}
            </button>
        </div>

        <!-- Filter Panel -->
        <div x-show="showFilter" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-[-10px]"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-[-10px]"
             style="display: none;"
             class="absolute left-0 right-0 top-full mt-1 bg-white rounded-2xl p-5 border border-gray-100 shadow-xl z-50">
            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">{{ __('app.level') }}</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="level in ['All', 'Beginner', 'Intermediate', 'Advanced']" :key="level">
                            <button @click="activeLevel = level"
                                :class="activeLevel === level ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                                x-text="level">
                            </button>
                        </template>
                    </div>
                </div>


                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">{{ __('app.category') }}</label>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="cat in ['All', 'Programming', 'Design', 'AI', 'Marketing', 'Data', 'Leadership', 'Business']" :key="cat">
                            <button @click="activeCat = cat"
                                :class="activeCat === cat ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors"
                                x-text="cat">
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Categories (Semua only) - Moved to Filter Panel -->



    <!-- Results Count -->
    <div class="text-sm text-gray-500">
        {{ __('app.showing') }} <span class="font-semibold text-gray-900" x-text="paginatedCourses.length"></span> {{ __('app.from') }} <span class="font-semibold text-gray-900" x-text="displayedCourses.length"></span> {{ __('app.courses_count') }}
    </div>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="course in paginatedCourses" :key="course.id">
            <a :href="'/kursus/' + course.id" class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <!-- Image -->
                <div class="relative h-[140px] md:h-48 w-full bg-gray-100 overflow-hidden">
                    <template x-if="course.thumbnail">
                        <img decoding="async" loading="lazy" :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </template>
                    <template x-if="!course.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + course.color + ',' + course.color + 'dd);'"></div>
                    </template>

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex items-center gap-2 z-10">
                        <template x-if="course.badge">
                            <span class="bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm" x-text="course.badge"></span>
                        </template>
                        <span class="bg-white text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm" x-text="course.level || 'Beginner'"></span>
                    </div>

                    <!-- Bookmark Badge -->
                    <div class="absolute top-3 right-3 z-10">
                        <button @click.prevent="course.is_wishlist = !course.is_wishlist" class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center text-orange-400 hover:text-orange-500 hover:bg-orange-50 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Bottom Progress Bar (Only for Kursus Saya) -->
                    <template x-if="tab === 'saya'">
                        <div class="absolute bottom-0 left-0 w-full h-1.5 bg-gray-200 z-10">
                            <div class="h-full bg-emerald-500" :style="'width: ' + (course.progress || 0) + '%'"></div>
                        </div>
                    </template>
                </div>

                <!-- Body -->
                <div class="p-4 flex flex-col flex-grow">
                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-bold text-red-600 bg-red-50 mb-3 self-start" x-text="course.category || ''"></span>
                    <h2 class="font-bold text-gray-900 text-base leading-tight mb-2 line-clamp-2" x-text="course.title"></h2>
                    <p class="text-xs text-gray-500 mb-3" x-text="(course.mentor || '') + ' · ' + (course.mentorCompany || '')"></p>

                    <!-- Rating -->
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="flex text-yellow-400">
                            <template x-for="i in 5" :key="i">
                                <svg :class="i <= Math.floor(course.rating || 0) ? 'text-yellow-400' : 'text-gray-300'" class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </template>
                        </div>
                        <span class="text-xs font-semibold text-gray-700" x-text="course.rating ? course.rating.toFixed(1) : '0.0'"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span x-text="((course.students ?? course.enrolled_count ?? course.enrolledCount ?? 0)).toLocaleString() + ' {{ __('app.enrolled') }}'"></span>
                    </div>
                    <div class="flex items-center gap-1.5 mb-4">
                    </div>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <template x-if="tab !== 'saya'">
                            <span class="text-base font-bold"
                                  :class="['Gratis', 'Free', '{{ __('app.free') }}'].includes(course.formatted_price) ? 'text-emerald-600' : 'text-gray-900'"
                                  x-text="course.formatted_price">
                            </span>
                        </template>
                        
                        <template x-if="tab === 'saya'">
                            <div class="flex items-center justify-between w-full">
                                <div class="flex items-center text-gray-500 text-[13px]">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span x-text="(course.duration || '48') + 'h'"></span>
                                </div>
                                <span class="text-[13px] font-bold text-emerald-600" x-text="(course.progress || 0) + '% done'"></span>
                            </div>
                        </template>
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
    <div x-show="displayedCourses.length === 0" class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h2 class="text-lg font-medium text-gray-900 mb-2">{{ __('app.course_not_found') }}</h2>
        <p class="text-gray-500">{{ __('app.try_change_keyword') }}</p>
    </div>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
