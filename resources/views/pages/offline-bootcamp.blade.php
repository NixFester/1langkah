@extends('layouts.app', ['activePage' => 'offline-bootcamp'])

@section('title', 'Offline Bootcamp — 1Langkah')
@section('header_title', 'Offline Bootcamp')

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
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Offline Bootcamp</h1>
        <p class="text-gray-500 text-base">Belajar tatap muka intensif di kampus 1Langkah — pengalaman immersive yang tak tergantikan</p>
    </div>

    <!-- Search & Sort Bar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" x-model="searchQuery" placeholder="Cari bootcamp, mentor, atau lokasi..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
            </div>

            <!-- Sort Dropdown -->
            <div class="relative">
                <select x-model="sortBy" class="appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm text-gray-700 focus:ring-2 focus:ring-red-500 focus:border-red-500 cursor-pointer">
                    <option value="newest">Terbaru</option>
                    <option value="price_low">Harga: Rendah ke Tinggi</option>
                    <option value="price_high">Harga: Tinggi ke Rendah</option>
                </select>
                <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>
    </div>

    <!-- Alert / Info Banner -->
    <div class="bg-[#3e2723] rounded-2xl p-6 md:p-8 text-white mb-10 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-md">
        <div class="flex items-center gap-5 md:gap-6">
            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="text-[22px] font-bold mb-1.5 tracking-tight">Tatap Muka · Belajar Langsung di Kampus</h3>
                <p class="text-[#d7ccc8] text-[15px] leading-relaxed max-w-2xl font-medium">Fasilitas lengkap, networking nyata, dan pengalaman belajar intensif bersama instruktur & sesama peserta.</p>
            </div>
        </div>
        <div class="flex items-center gap-8 md:gap-10 lg:pr-6">
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">3 Kota</div>
                <div class="text-[13px] text-[#bcaaa4] font-medium mt-1">Tersedia</div>
            </div>
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">Max 20</div>
                <div class="text-[13px] text-[#bcaaa4] font-medium mt-1">Peserta/batch</div>
            </div>
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">Sertifikat</div>
                <div class="text-[13px] text-[#bcaaa4] font-medium mt-1">Terverifikasi</div>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="text-sm text-gray-500 mb-4">
        Menampilkan <span class="font-semibold text-gray-900" x-text="displayedBootcamps.length"></span> bootcamp
    </div>

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
                        <span class="px-3 py-1 bg-red-700 text-white text-[11px] font-bold rounded-full shadow-sm" x-text="index === 0 ? 'Paling Diminati' : (index === 1 ? 'Weekend Class' : 'Eksklusif')"></span>
                        <span class="px-3 py-1 bg-black/40 backdrop-blur-sm text-white text-[11px] font-semibold rounded-full shadow-sm" x-text="index === 0 ? 'All Level' : (index === 1 ? 'Beginner' : 'Intermediate')"></span>
                    </div>
                    <!-- Bottom Location Badge -->
                    <div class="absolute bottom-3 left-4">
                        <div class="flex items-center gap-1.5 text-white/90 text-[12px] font-semibold tracking-wide shadow-sm drop-shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span x-text="b.location ? b.location.split(',')[0] : ''"></span>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-[17px] font-bold text-gray-900 leading-snug mb-1.5 group-hover:text-red-700 transition-colors line-clamp-2" x-text="b.title"></h3>
                    <p class="text-[13px] text-gray-500 mb-4 line-clamp-2" x-text="b.mentor || ''"></p>

                    <div class="flex items-start gap-1.5 text-[12px] text-gray-400 mb-4">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="leading-relaxed" x-text="b.location || ''"></span>
                    </div>

                    <div class="flex items-center gap-4 text-[12px] text-gray-500 font-medium mb-6">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span x-text="index === 0 ? '8 Minggu' : '8 Minggu'"></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span x-text="index === 0 ? '2× seminggu' : 'Sabtu & Minggu'"></span>
                        </div>
                    </div>

                    <!-- slot Progress -->
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-gray-400">Sisa kursi</span>
                            <span class="text-green-500">7 dari 20</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: 65%"></div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-100 mb-4"></div>

                    <!-- Footer -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 mb-1" x-text="'Mulai ' + (b.startDate || '')"></div>
                            <div class="text-[16px] font-extrabold text-black" x-text="b.price || ''"></div>
                        </div>
                        <div>
                            <span class="px-3 py-1.5 bg-red-50 text-red-600 text-[11px] font-bold rounded-full">Soft Skills</span>
                        </div>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="displayedBootcamps.length === 0" class="text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Bootcamp tidak ditemukan</h3>
        <p class="text-gray-500">Coba ubah kata kunci pencarian</p>
    </div>
</div>
@endsection
