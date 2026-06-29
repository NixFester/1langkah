@extends('layouts.app', ['activePage' => 'kursus'])

@section('title', $course['title'] . ' — 1Langkah')
@section('header_title', 'Detail Kursus')

@section('content')
@php
    $c = $course;
@endphp

<!-- Hero Section (Full width, breaks out of .page-content padding) -->
<div class="-mx-7 -mt-7 relative bg-slate-900 pt-20 pb-28 px-12 overflow-hidden">
    <!-- Background Image with Dark Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Hero Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
    </div>
    <!-- Hero Content -->
    <div class="relative z-10 w-full mt-6">
        <!-- Badges -->
        <div class="flex items-center gap-3 mb-6">
            <span class="px-4 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">{{ $c['category'] ?? 'Programming' }}</span>
            <span class="px-4 py-1 bg-white text-gray-700 text-xs font-bold rounded-full">{{ $c['level'] ?? 'Intermediate' }}</span>
            @if(!empty($c['badge']))
            <span class="px-4 py-1 bg-yellow-100 text-orange-600 text-xs font-bold rounded-full">{{ $c['badge'] }}</span>
            @else
            <span class="px-4 py-1 bg-yellow-100 text-orange-600 text-xs font-bold rounded-full">Bestseller</span>
            @endif
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-5 leading-tight tracking-tight">{{ $c['title'] }}</h1>
        
        <!-- Subtitle -->
        <p class="text-lg text-gray-300 mb-8 max-w-3xl leading-relaxed">Kuasai full-stack development dari dasar hingga deployment production-ready.</p>

        <!-- Meta -->
        <div class="flex items-center gap-6 text-sm text-gray-300">
            <div class="flex items-center gap-1.5">
                <div class="flex text-yellow-400">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <span class="font-medium ml-1 text-gray-400">4.9</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1 h-1 bg-gray-500 rounded-full mr-1"></span>
                12,840 siswa
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1 h-1 bg-gray-500 rounded-full mr-1"></span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                48h
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="w-full py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Tabs Navigation -->
            <div class="bg-gray-50 p-1.5 rounded-full flex w-full overflow-x-auto scrollbar-hide shadow-inner border border-gray-100/50">
                <button class="flex-1 px-6 py-3 rounded-full text-sm font-bold bg-white text-red-600 shadow-sm border border-gray-100 whitespace-nowrap">Overview</button>
                <button class="flex-1 px-6 py-3 rounded-full text-sm font-semibold text-gray-500 hover:text-gray-700 whitespace-nowrap">Curriculum</button>
                <button class="flex-1 px-6 py-3 rounded-full text-sm font-semibold text-gray-500 hover:text-gray-700 whitespace-nowrap">Mentor</button>
                <button class="flex-1 px-6 py-3 rounded-full text-sm font-semibold text-gray-500 hover:text-gray-700 whitespace-nowrap">Reviews</button>
                <button class="flex-1 px-6 py-3 rounded-full text-sm font-semibold text-gray-500 hover:text-gray-700 whitespace-nowrap">Resources</button>
            </div>

            <!-- Apa yang akan kamu pelajari -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">Apa yang akan kamu pelajari</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    @php
                        $learnings = [
                            'Membangun aplikasi web full-stack',
                            'Menguasai React 18 + TypeScript',
                            'Backend dengan Node.js & Express',
                            'Database PostgreSQL & Prisma ORM',
                            'Deploy ke AWS & Vercel',
                            'REST API & GraphQL'
                        ];
                    @endphp
                    @foreach($learnings as $l)
                    <div class="flex items-start gap-3">
                        <div class="flex items-center justify-center w-5 h-5 rounded-full border border-green-500 text-green-500 flex-shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-sm text-gray-600 leading-relaxed font-medium">{{ $l }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Persyaratan -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                <h2 class="text-[22px] font-bold text-gray-900 mb-5 tracking-tight">Persyaratan</h2>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></span>
                        <span class="text-sm font-medium text-gray-600">Dasar logika pemrograman</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></span>
                        <span class="text-sm font-medium text-gray-600">Laptop/PC dengan akses internet</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></span>
                        <span class="text-sm font-medium text-gray-600">Tidak perlu pengalaman sebelumnya</span>
                    </li>
                </ul>
            </div>
            
        </div>

        <!-- Right Column (Sticky Sidebar) -->
        <div class="lg:col-span-1">
            <div class="bg-white border border-gray-100 rounded-3xl p-7 shadow-[0_8px_30px_rgb(0,0,0,0.05)] lg:sticky lg:top-24">
                
                <!-- Pricing -->
                <div class="mb-6">
                    <div class="text-4xl font-extrabold text-gray-900 mb-1 tracking-tight">Rp 599.000</div>
                    <div class="text-[15px] font-semibold text-gray-400 line-through">Rp 999.000</div>
                </div>

                <!-- Action Buttons -->
                <button class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors mb-3">
                    Daftar Sekarang
                </button>
                <button class="w-full py-3.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-full transition-colors mb-8 shadow-sm">
                    Coba Gratis 7 Hari
                </button>

                <!-- Features List -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[14px] font-medium text-gray-600">48h video pembelajaran</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        <span class="text-[14px] font-medium text-gray-600">Sertifikat terverifikasi</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        <span class="text-[14px] font-medium text-gray-600">Akses seumur hidup</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span class="text-[14px] font-medium text-gray-600">Download materi</span>
                    </div>
                </div>
            </div>

            <!-- Promo Banner (Outside the card) -->
            <div class="bg-[#FFFDF3] border border-[#FDF0CD] rounded-xl p-4 flex gap-3 shadow-sm mt-6 lg:sticky lg:top-[500px]">
                <span class="text-[18px]">🎉</span>
                <div>
                    <div class="text-[13px] font-bold text-orange-800 mb-0.5">Promo berlaku 2 hari lagi!</div>
                    <div class="text-[12px] font-semibold text-orange-600">Hemat 40% dari harga normal</div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
