@php
    $activePage = 'kursus-saya';
@endphp

@extends('layouts.app')

@section('title', 'Kursus Saya — 1Langkah')

@section('content')
<div x-data="{
    tab: 'active',
    searchQuery: '',
    activeCat: 'All',
    sortBy: 'newest',
    filteredMyCourses: {{ json_encode($myCourses) }},
    filteredCompletedCourses: {{ json_encode($completedCourses) }},
    filteredOtherCourses: {{ json_encode($otherCourses) }},
    get displayedActive() {
        let courses = this.filteredMyCourses;
        if (this.searchQuery) {
            const query = this.searchQuery.toLowerCase();
            courses = courses.filter(c =>
                (c.title && c.title.toLowerCase().includes(query)) ||
                (c.mentor && c.mentor.toLowerCase().includes(query)) ||
                (c.category && c.category.toLowerCase().includes(query))
            );
        }
        if (this.activeCat !== 'All') {
            courses = courses.filter(c => c.category === this.activeCat);
        }
        if (this.sortBy === 'rating') {
            courses = [...courses].sort((a, b) => (b.rating || 0) - (a.rating || 0));
        } else if (this.sortBy === 'progress') {
            courses = [...courses].sort((a, b) => (b.progress || 0) - (a.progress || 0));
        }
        return courses;
    },
    get displayedCompleted() {
        let courses = this.filteredCompletedCourses;
        if (this.searchQuery) {
            const query = this.searchQuery.toLowerCase();
            courses = courses.filter(c =>
                (c.title && c.title.toLowerCase().includes(query)) ||
                (c.mentor && c.mentor.toLowerCase().includes(query))
            );
        }
        return courses;
    },
    get displayedWishlist() {
        let courses = this.filteredOtherCourses;
        if (this.searchQuery) {
            const query = this.searchQuery.toLowerCase();
            courses = courses.filter(c =>
                (c.title && c.title.toLowerCase().includes(query)) ||
                (c.mentor && c.mentor.toLowerCase().includes(query)) ||
                (c.category && c.category.toLowerCase().includes(query))
            );
        }
        return courses;
    }
}" class="w-full px-2 pb-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kursus Saya</h1>
            <p class="text-sm text-gray-500">{{ count($myCourses) }} kursus aktif</p>
        </div>
    </div>

    <!-- Stats Banner -->
    <div class="bg-gradient-to-r from-red-600 to-red-700 text-white rounded-3xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-8">
            <div class="pr-8 border-r border-white/20">
                <div class="text-3xl font-bold">{{ $userStats['courses_enrolled'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">Kursus aktif</div>
            </div>
            <div class="pr-8 border-r border-white/20">
                <div class="text-3xl font-bold">{{ $userStats['courses_completed'] ?? 0 }}</div>
                <div class="text-white/90 text-sm">Diselesaikan</div>
            </div>
            <div>
                <div class="text-3xl font-bold">{{ ($userStats['courses_completed'] ?? 0) + ($userStats['bootcamps_completed'] ?? 0) }}</div>
                <div class="text-white/90 text-sm">Sertifikat</div>
            </div>
        </div>
        <a href="{{ route('kursus') }}"
            class="bg-white/15 hover:bg-white/25 text-white font-bold rounded-full px-6 py-2.5 text-sm flex items-center gap-2 whitespace-nowrap transition-colors">
            Browse Kursus Baru
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <!-- Search & Sort Bar -->
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
                    <option value="progress">Progress Tertinggi</option>
                </select>
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="inline-flex bg-slate-100 rounded-full p-1 shadow-sm">
        <button @click="tab = 'active'"
            :class="tab === 'active' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
            :style="tab === 'active' ? 'color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-weight: bold; font-size: 14px;' : 'color: #64748b; padding: 8px 20px; border-radius: 9999px; font-weight: 500; font-size: 14px;'"
            class="transition-all cursor-pointer">
            Sedang Berlangsung (<span x-text="displayedActive.length"></span>)
        </button>
        <button @click="tab = 'done'"
            :class="tab === 'done' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
            :style="tab === 'done' ? 'color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-weight: bold; font-size: 14px;' : 'color: #64748b; padding: 8px 20px; border-radius: 9999px; font-weight: 500; font-size: 14px;'"
            class="transition-all cursor-pointer">
            Selesai (<span x-text="displayedCompleted.length"></span>)
        </button>
        <button @click="tab = 'wishlist'"
            :class="tab === 'wishlist' ? 'bg-white shadow-sm' : 'hover:bg-slate-200'"
            :style="tab === 'wishlist' ? 'color: #dc2626; padding: 8px 20px; border-radius: 9999px; font-weight: bold; font-size: 14px;' : 'color: #64748b; padding: 8px 20px; border-radius: 9999px; font-weight: 500; font-size: 14px;'"
            class="transition-all cursor-pointer">
            Wishlist (<span x-text="displayedWishlist.length"></span>)
        </button>
    </div>

    <!-- Active Courses -->
    <div x-show="tab === 'active'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="course in displayedActive" :key="course.id">
            <a :href="'/kursus/' + course.id" class="block bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group flex flex-col h-full">
                <!-- Image -->
                <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                    <template x-if="course.thumbnail">
                        <img :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </template>
                    <template x-if="!course.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + (course.color || '#dc2626') + ',' + (course.color || '#dc2626') + 'dd);'"></div>
                    </template>
                    
                    <!-- Top Badges -->
                    <div class="absolute top-3 left-3 flex items-center gap-2 z-10">
                        <!-- Bestseller badge -->
                        <span x-show="course.bestseller !== false" class="bg-[#fff8e1] text-[#d97706] text-xs font-medium px-3 py-1 rounded-full shadow-sm">Bestseller</span>
                        <span class="bg-white text-slate-700 text-xs font-medium px-3 py-1 rounded-full shadow-sm" x-text="course.level || 'Intermediate'"></span>
                    </div>
                    <!-- Right Icon -->
                    <div class="absolute top-3 right-3 z-10">
                        <div class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="6"></circle>
                                <path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Bottom Progress Bar -->
                    <div class="absolute bottom-0 left-0 w-full h-1.5 bg-gray-200 z-10">
                        <div class="h-full bg-emerald-500" :style="'width: ' + (course.progress || 0) + '%'"></div>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-4 flex flex-col flex-grow">
                    <span class="inline-block px-2.5 py-0.5 rounded-md text-[11px] font-bold text-red-600 bg-red-50 mb-3 self-start" x-text="course.category || 'Programming'"></span>
                    <h3 class="font-bold text-gray-900 text-[15px] leading-tight mb-2 line-clamp-2" x-text="course.title"></h3>
                    <p class="text-xs text-gray-500 mb-3" x-text="(course.mentor || '') + ' · ' + (course.mentorCompany || 'Google')"></p>

                    <!-- Rating -->
                    <div class="flex items-center gap-1.5 mb-4">
                        <div class="flex text-yellow-400">
                            <template x-for="i in 5" :key="i">
                                <svg :class="i <= Math.floor(course.rating || 0) ? 'text-yellow-400' : 'text-gray-300'" class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </template>
                        </div>
                        <span class="text-xs font-semibold text-gray-700" x-text="course.rating ? course.rating.toFixed(1) : '4.9'"></span>
                        <span class="text-xs text-gray-400" x-text="'(' + (course.students || '12,840').toLocaleString() + ')'"></span>
                    </div>

                    <!-- Bottom row: Duration and Progress % -->
                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center text-gray-500 text-[13px]">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-text="(course.duration || '48') + 'h'"></span>
                        </div>
                        <span class="text-[13px] font-bold text-emerald-600" x-text="(course.progress || 0) + '% done'"></span>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Empty Active -->
    <div x-show="tab === 'active' && displayedActive.length === 0" class="text-center py-12 bg-white rounded-2xl border border-gray-100">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kursus yang dimulai</h3>
        <p class="text-gray-500 mb-4">Mulai belajar dengan browse kursus yang tersedia</p>
        <a href="{{ route('kursus') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-full text-sm font-bold transition-colors">
            Browse Kursus
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <!-- Completed Courses -->
    <div x-show="tab === 'done'" style="display:none" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="course in displayedCompleted" :key="course.id">
            <a :href="'/kursus/' + course.id" class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group flex flex-col h-full">
                <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                    <template x-if="course.thumbnail">
                        <img :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </template>
                    <template x-if="!course.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,#10b981,#10b981dd);'"></div>
                    </template>
                    <div class="absolute top-3 left-3">
                        <span class="bg-emerald-500 text-white text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Selesai
                        </span>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-bold text-emerald-600 bg-emerald-50 mb-2" x-text="course.category || ''"></span>
                    <h3 class="font-bold text-gray-900 text-base leading-tight mb-2 line-clamp-2" x-text="course.title"></h3>
                    <p class="text-xs text-gray-500 mb-3" x-text="course.mentor || ''"></p>
                    <div class="mt-auto pt-3 border-t border-gray-100">
                        <span class="text-sm font-bold text-emerald-500">✓ Kursus selesai</span>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Empty Completed -->
    <div x-show="tab === 'done' && displayedCompleted.length === 0" style="display:none" class="text-center py-12 bg-white rounded-2xl border border-gray-100">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kursus yang selesai</h3>
        <p class="text-gray-500">Selesaikan kursus yang sedang berlangsung untuk mendapatkan sertifikat</p>
    </div>

    <!-- Wishlist / Other Courses -->
    <div x-show="tab === 'wishlist'" style="display:none" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="course in displayedWishlist" :key="course.id">
            <a :href="'/kursus/' + course.id" class="bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-lg transition-all duration-300 group flex flex-col h-full">
                <div class="relative h-48 w-full bg-gray-100 overflow-hidden">
                    <template x-if="course.thumbnail">
                        <img :src="course.thumbnail" :alt="course.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </template>
                    <template x-if="!course.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + (course.color || '#dc2626') + ',' + (course.color || '#dc2626') + 'dd);'"></div>
                    </template>
                    <div class="absolute top-3 left-3 flex items-center gap-2">
                        <span class="bg-white text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm" x-text="course.level || 'Beginner'"></span>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <span class="inline-block px-2.5 py-0.5 rounded-md text-xs font-bold text-red-600 bg-red-50 mb-2" x-text="course.category || ''"></span>
                    <h3 class="font-bold text-gray-900 text-base leading-tight mb-2 line-clamp-2" x-text="course.title"></h3>
                    <p class="text-xs text-gray-500 mb-3" x-text="(course.mentor || '') + ' · ' + (course.mentorCompany || '')"></p>
                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center text-gray-400 text-xs">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            <span x-text="course.rating ? course.rating.toFixed(1) : '0.0'"></span>
                        </div>
                        <span class="text-base font-bold text-gray-900" x-text="!course.price || course.price == 0 || course.price.toString().toLowerCase() === 'gratis' ? 'Gratis' : (!isNaN(course.price) ? 'Rp ' + new Intl.NumberFormat('id-ID').format(course.price) : course.price)"></span>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Empty Wishlist -->
    <div x-show="tab === 'wishlist' && displayedWishlist.length === 0" style="display:none" class="text-center py-12 bg-white rounded-2xl border border-gray-100">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Semua kursus sudah diikuti!</h3>
        <p class="text-gray-500">Tidak ada kursus lain yang tersedia</p>
    </div>

</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
