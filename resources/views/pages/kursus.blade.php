@extends('layouts.app', ['activePage' => 'kursus'])

@section('title', 'Kursus — 1Langkah')
@section('header_title', 'Kursus')

@section('content')
<div class="px-2" x-data="{ tab: 'semua', activeCat: 'All' }">
    <!-- Header -->
    <div class="flex items-start justify-between mb-8 -mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kursus</h1>
            <p class="text-gray-500 text-base">800+ kursus praktis dari instruktur terbaik</p>
        </div>
        <div class="flex items-center gap-2 mt-1">
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-red-50 text-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="inline-flex bg-slate-100 rounded-full p-1 mb-8 shadow-sm">
        <button @click="tab = 'semua'" 
                :class="tab === 'semua' ? 'bg-white shadow-sm ring-1 ring-black/5 font-bold' : 'font-semibold hover:text-slate-700'" 
                :style="tab === 'semua' ? 'color: #cc0000; font-size: 15px;' : 'color: #64748b; font-size: 15px;'"
                class="px-6 py-2 rounded-full transition-all">
            Semua Kursus
        </button>
        <button @click="tab = 'saya'" 
                :class="tab === 'saya' ? 'bg-white shadow-sm ring-1 ring-black/5 font-bold' : 'font-semibold hover:text-slate-700'" 
                :style="tab === 'saya' ? 'color: #cc0000; font-size: 15px;' : 'color: #64748b; font-size: 15px;'"
                class="px-6 py-2 rounded-full flex items-center gap-2 transition-all">
            Kursus Saya 
            <span :class="tab === 'saya' ? 'px-2.5 py-0.5 rounded-full text-xs font-bold' : 'font-bold'" 
                  :style="tab === 'saya' ? 'background-color: #ffe4e6; color: #cc0000;' : ''">3</span>
        </button>
    </div>

    <!-- Categories / Filters (Only for Semua Kursus) -->
    <div x-show="tab === 'semua'" class="flex items-center gap-3 mb-8 overflow-x-auto pb-2 scrollbar-hide">
        <button @click="activeCat = 'All'" 
                :class="activeCat === 'All' ? 'text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                :style="activeCat === 'All' ? 'background-color: #cc0000;' : ''"
                class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors">
            All
        </button>
        
        @foreach(['Programming', 'Design', 'AI', 'Marketing', 'Data', 'Leadership', 'Business'] as $c)
        <button @click="activeCat = '{{ $c }}'" 
                :class="activeCat === '{{ $c }}' ? 'text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                :style="activeCat === '{{ $c }}' ? 'background-color: #cc0000;' : ''"
                class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors">
            {{ $c }}
        </button>
        @endforeach

        <button class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            Filter
        </button>
    </div>

    <!-- Red Banner (Only for Kursus Saya) -->
    <div x-show="tab === 'saya'" style="display: none; background-color: #cc0000; border-radius: 20px; padding: 36px 40px;" class="text-white flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 shadow-md">
        <div class="flex items-center gap-8 sm:gap-12">
            <div class="border-r border-white/20" style="padding-right: 2rem;">
                <div style="font-size: 36px; line-height: 1;" class="font-bold mb-1 tracking-tight">3</div>
                <div style="font-size: 14px;" class="text-white/90 font-normal">Kursus aktif</div>
            </div>
            <div class="border-r border-white/20" style="padding-right: 2rem;">
                <div style="font-size: 36px; line-height: 1;" class="font-bold mb-1 tracking-tight">45%</div>
                <div style="font-size: 14px;" class="text-white/90 font-normal">Rata-rata progress</div>
            </div>
            <div>
                <div style="font-size: 36px; line-height: 1;" class="font-bold mb-1 tracking-tight">0</div>
                <div style="font-size: 14px;" class="text-white/90 font-normal">Selesai</div>
            </div>
        </div>
        <button class="transition-colors text-white font-bold rounded-full flex items-center justify-center gap-2 whitespace-nowrap" style="background-color: rgba(255,255,255,0.15); font-size: 14px; padding: 12px 24px;">
            Lihat Learning Path &rarr;
        </button>
    </div>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($courses as $index => $c)
            <div x-show="tab === 'semua' || (tab === 'saya' && {{ $index }} < 3)">
                <x-course-card :course="$c" />
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
