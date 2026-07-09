@extends('layouts.app', ['activePage' => 'online-bootcamp'])

@section('title', 'Online Bootcamp — 1Langkah')
@section('header_title', 'Online Bootcamp')

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
    }
}" class="w-full px-2 pb-8">

    <!-- Header -->
    <div class="mb-6 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Online Bootcamp</h1>
        <p class="text-gray-500 text-base">Kelas intensif LIVE via Zoom bersama instruktur terbaik — terbatas!</p>
    </div>

    <!-- Search & Sort Bar -->
    <div class="mb-6">
        <x-search-filter-bar
            placeholder="Cari bootcamp atau mentor..."
            :sort-options="[
                'newest' => 'Terbaru',
                'price_low' => 'Harga: Rendah ke Tinggi',
                'price_high' => 'Harga: Tinggi ke Rendah'
            ]"
        />
    </div>

    <!-- Alert / Info Banner -->
    <x-alert-banner
        type="info"
        title="Tatap Muka LIVE via Zoom"
        message="Setiap sesi direkam dan tersedia selama 30 hari. Sertifikat kelulusan diberikan setelah menyelesaikan minimal 80% pertemuan."
        :stats="[
            ['value' => '7–10', 'label' => 'Pertemuan'],
            ['value' => '2 Jam', 'label' => 'Per sesi'],
            ['value' => '30 Hari', 'label' => 'Akses rekaman']
        ]"
    >
        <x-slot name="icon">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </x-slot>
    </x-alert-banner>

    <!-- Results Count -->
    <x-results-count model="displayedBootcamps" label="bootcamp" />

    <!-- Bootcamp Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="(b, index) in displayedBootcamps" :key="b.id">
            <a :href="'/bootcamp/online/' + b.id" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.04)] hover:shadow-lg transition-shadow group flex flex-col h-full cursor-pointer">
                <!-- Thumbnail -->
                <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                    <template x-if="b.thumbnail">
                        <img :src="b.thumbnail" :alt="b.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </template>
                    <template x-if="!b.thumbnail">
                        <div class="w-full h-full" :style="'background:linear-gradient(135deg,' + (b.color || '#dc2626') + ',' + (b.color || '#dc2626') + 'cc)'"></div>
                    </template>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent pointer-events-none"></div>

                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-sm" x-text="index === 0 ? 'Paling Diminati' : (index === 1 ? 'Baru' : 'Premium')"></span>
                        <span class="px-3 py-1 bg-black/40 backdrop-blur-sm text-white text-xs font-semibold rounded-full shadow-sm">Intermediate</span>
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
                    <h3 class="text-lg font-bold text-gray-900 leading-snug mb-2 group-hover:text-red-600 transition-colors line-clamp-2" x-text="b.title"></h3>
                    <p class="text-sm text-gray-500 mb-6 font-medium" x-text="b.mentor || ''"></p>

                    <!-- Enrollment Progress -->
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-gray-400">Peserta terdaftar</span>
                            <span class="text-red-500" x-text="((b.enrolledCount ?? b.enrolled_count ?? 0)) + ' dari ' + (b.totalSlots || 0)"></span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-red-600 rounded-full" :style="'width: ' + (((b.enrolledCount ?? b.enrolled_count ?? 0) / (b.totalSlots || 1)) * 100) + '%'"></div>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-100 mb-5"></div>

                    <!-- Footer -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[13px] font-medium text-gray-400 mb-1" x-text="'Mulai ' + (b.startDate || '')"></div>
                            <div class="text-[15px] font-bold text-gray-900" x-text="b.sessions || ''"></div>
                        </div>
                        <div class="text-right">
                            <div class="text-[13px] font-medium text-gray-400 mb-1">Harga</div>
                            <div class="text-lg font-extrabold"
                                 :class="b.formatted_price === 'Gratis' ? 'text-emerald-600' : 'text-red-600'"
                                 x-text="b.formatted_price"></div>
                        </div>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Empty State -->
    <x-empty-state
        x-show="displayedBootcamps.length === 0"
        title="Bootcamp tidak ditemukan"
        message="Coba ubah kata kunci pencarian"
    />
</div>
@endsection
