@php
    /** @var \App\Services\CatalogService $catalog */
    $activePage = 'kursus';
@endphp

@extends('layouts.app')

@section('title', 'Kursus — 1Langkah')

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
    }
}" class="w-full px-2 pb-8 space-y-8">

    <!-- Header -->
    <div class="flex items-start justify-between -mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kursus</h1>
            <p class="text-gray-500 text-base">800+ kursus praktis dari instruktur terbaik</p>
        </div>
    </div>

    <!-- Red Banner (Kursus Saya only) -->
    <div x-show="tab === 'saya'" x-cloak style="display: none;"
        class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-3xl p-9 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-8 sm:gap-12">
            <div class="pr-8 border-r border-white/20">
                <div class="text-4xl font-bold tracking-tight mb-1">{{ $userStats['courses_enrolled'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">Kursus aktif</div>
            </div>
            <div class="pr-8 border-r border-white/20">
                <div class="text-4xl font-bold tracking-tight mb-1">{{ $userStats['courses_completed'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">Diselesaikan</div>
            </div>
            <div>
                <div class="text-4xl font-bold tracking-tight mb-1">{{ $userStats['certificates'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">Sertifikat</div>
            </div>
        </div>
        <a href="{{ route('kursus-saya') }}"
            class="bg-white/15 hover:bg-white/25 text-white font-bold rounded-full px-6 py-3 text-sm flex items-center gap-2 whitespace-nowrap transition-colors">
            Lihat Learning Path &rarr;
        </a>
    </div>
    <script>document.querySelector('[x-cloak]')?.removeAttribute('x-cloak')</script>
    <style>[x-cloak] { display: none !important; }</style>

    <!-- Search & Filter Bar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari kursus, mentor, atau kategori..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
            </div>

            <!-- Sort Dropdown -->
            <div class="relative">
                <select x-model="sortBy" class="appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
                    <option value="newest">Terbaru</option>
                    <option value="rating">Rating Tertinggi</option>
                    <option value="price_low">Harga: Rendah ke Tinggi</option>
                    <option value="price_high">Harga: Tinggi ke Rendah</option>
                </select>
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>



            <!-- Filter Toggle -->
            <button @click="showFilter = !showFilter"
                :class="showFilter ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-200'"
                class="px-4 py-3 border rounded-xl text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter
            </button>
        </div>

        <!-- Filter Panel -->
        <div x-show="showFilter" x-collapse class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex flex-col gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Level</label>
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

                <!-- Tampilan Filter (Semua Kursus only) -->
                <div x-show="tab === 'semua'" x-cloak>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Tampilan</label>
                    <div class="flex flex-wrap gap-2">
                        <button @click="semuaCourseTab = 'semua'"
                            :class="semuaCourseTab === 'semua' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                            Semua Tampilan
                        </button>
                        <button @click="semuaCourseTab = 'wishlist'"
                            :class="semuaCourseTab === 'wishlist' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                            Wishlist <span x-show="semuaCourseTab !== 'wishlist'" class="bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="wishlistCount"></span><span x-show="semuaCourseTab === 'wishlist'" class="bg-white/20 text-white px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="wishlistCount"></span>
                        </button>
                    </div>
                </div>

                <!-- Status Filter (Kursus Saya only) -->
                <div x-show="tab === 'saya'" x-cloak>
                    <label class="block text-xs font-medium text-gray-500 mb-2">Status</label>
                    <div class="flex flex-wrap gap-2">
                        <button @click="myCourseTab = 'semua'"
                            :class="myCourseTab === 'semua' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                            Semua Status
                        </button>
                        <button @click="myCourseTab = 'sedang_berlangsung'"
                            :class="myCourseTab === 'sedang_berlangsung' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                            Sedang Berlangsung <span x-show="myCourseTab !== 'sedang_berlangsung'" class="bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="inProgressCount"></span><span x-show="myCourseTab === 'sedang_berlangsung'" class="bg-white/20 text-white px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="inProgressCount"></span>
                        </button>
                        <button @click="myCourseTab = 'selesai'"
                            :class="myCourseTab === 'selesai' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1">
                            Selesai <span x-show="myCourseTab !== 'selesai'" class="bg-gray-200 text-gray-500 px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="completedCount"></span><span x-show="myCourseTab === 'selesai'" class="bg-white/20 text-white px-1.5 py-0.5 rounded text-[10px] font-bold" x-text="completedCount"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="inline-flex bg-slate-100 rounded-full p-1 shadow-sm">
        <button @click="tab = 'semua'"
            :class="tab === 'semua' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
            :style="tab === 'semua' ? 'color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-weight: bold; font-size: 14px;' : 'color: #64748b; padding: 8px 20px; border-radius: 9999px; font-weight: 500; font-size: 14px;'"
            class="transition-all cursor-pointer">
            Semua Kursus
            <span x-show="searchQuery || activeCat !== 'All' || activeLevel !== 'All'" style="display: none;" class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-[10px] rounded-full">Filtered</span>
        </button>
        <button @click="tab = 'saya'"
            :class="tab === 'saya' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
            :style="tab === 'saya' ? 'color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-weight: bold; font-size: 14px;' : 'color: #64748b; padding: 8px 20px; border-radius: 9999px; font-weight: 500; font-size: 14px;'"
            class="transition-all cursor-pointer">
            Kursus Saya
            <span :class="tab === 'saya' ? 'ml-1 px-2 py-0.5 rounded-full text-[11px] font-bold' : 'ml-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-gray-200 text-gray-500'"
                :style="tab === 'saya' ? 'background-color: #fee2e2; color: #dc2626;' : ''">
                {{ count($myCourses) }}
            </span>
        </button>
    </div>

    <!-- Categories (Semua only) -->
    <div x-show="tab === 'semua'" class="flex items-center gap-3 overflow-x-auto pb-2 -mt-4">
        <button @click="activeCat = 'All'"
            :class="activeCat === 'All' ? 'text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
            :style="activeCat === 'All' ? 'background-color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;' : 'padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;'"
            class="py-2 transition-colors whitespace-nowrap cursor-pointer">
            All
        </button>
        @foreach(['Programming', 'Design', 'AI', 'Marketing', 'Data', 'Leadership', 'Business'] as $cat)
            <button @click="activeCat = '{{ $cat }}'"
                :class="activeCat === '{{ $cat }}' ? 'text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                :style="activeCat === '{{ $cat }}' ? 'background-color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;' : 'padding: 8px 20px; border-radius: 9999px; font-size: 14px; font-weight: 500;'"
                class="py-2 transition-colors whitespace-nowrap cursor-pointer">
                {{ $cat }}
            </button>
        @endforeach
    </div>



    <!-- Results Count -->
    <div class="text-sm text-gray-500">
        Menampilkan <span class="font-semibold text-gray-900" x-text="displayedCourses.length"></span> kursus
    </div>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="course in displayedCourses" :key="course.id">
            <a :href="'/kursus/' + course.id" class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group">
                <!-- Image -->
                <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                    <template x-if="course.thumbnail">
                        <img :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </template>
                    <template x-if="!course.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + course.color + ',' + course.color + 'dd);'"></div>
                    </template>

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex items-center gap-2 z-10">
                        <span class="bg-white text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm" x-text="course.level || 'Beginner'"></span>
                        <template x-if="course.badge">
                            <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm" x-text="course.badge"></span>
                        </template>
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
                    <h3 class="font-bold text-gray-900 text-base leading-tight mb-2 line-clamp-2" x-text="course.title"></h3>
                    <p class="text-xs text-gray-500 mb-3" x-text="(course.mentor || '') + ' · ' + (course.mentorCompany || '')"></p>

                    <!-- Rating -->
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="flex text-yellow-400">
                            <template x-for="i in 5" :key="i">
                                <svg :class="i <= Math.floor(course.rating || 0) ? 'text-yellow-400' : 'text-gray-300'" class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </template>
                        </div>
                        <span class="text-xs font-semibold text-gray-700" x-text="course.rating ? course.rating.toFixed(1) : '0.0'"></span>
                        <span class="text-xs text-gray-400" x-text="'(' + (course.students || 0).toLocaleString() + ')'"></span>
                    </div>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <template x-if="tab !== 'saya'">
                            <span class="text-base font-bold text-gray-900" x-text="course.price || 'Gratis'"></span>
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

    <!-- Empty State -->
    <div x-show="displayedCourses.length === 0" class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Kursus tidak ditemukan</h3>
        <p class="text-gray-500">Coba ubah kata kunci pencarian atau filter</p>
    </div>
</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
