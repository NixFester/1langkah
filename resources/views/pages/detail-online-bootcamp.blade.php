@extends('layouts.app', ['activePage' => 'online-bootcamp'])

@section('title', $bootcamp['title'] . ' — 1Langkah')
@section('header_title', 'Online Bootcamp')

@section('content')
@inject('catalog', 'App\Services\CatalogService')
@php
    $b = $bootcamp;
    $allBootcamps = $catalog->bootcamps()['online'];
@endphp

<div class="w-full px-2 pb-8">
    <!-- Header (Same as Online Bootcamp) -->
    <div class="mb-8 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Online Bootcamp</h1>
        <p class="text-gray-500 text-base">Kelas intensif LIVE via Zoom bersama instruktur terbaik — terbatas!</p>
    </div>

    <!-- Alert / Info Banner -->
    <div class="bg-[#b91c1c] rounded-2xl p-6 md:p-8 text-white mb-10 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-md">
        <div class="flex items-center gap-5 md:gap-6">
            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-[22px] font-bold mb-1.5 tracking-tight">Tatap Muka LIVE via Zoom</h3>
                <p class="text-red-100 text-[15px] leading-relaxed max-w-2xl font-medium">Setiap sesi direkam dan tersedia selama 30 hari. Sertifikat kelulusan diberikan setelah menyelesaikan minimal 80% pertemuan.</p>
            </div>
        </div>
        <div class="flex items-center gap-8 md:gap-10 lg:pr-6">
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">7–10</div>
                <div class="text-[13px] text-red-200 font-medium">Pertemuan</div>
            </div>
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">2 Jam</div>
                <div class="text-[13px] text-red-200 font-medium">Per sesi</div>
            </div>
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">30 Hari</div>
                <div class="text-[13px] text-red-200 font-medium">Akses rekaman</div>
            </div>
        </div>
    </div>

    <!-- Master-Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Master List -->
        <div class="lg:col-span-4 flex flex-col gap-5">
            @foreach($allBootcamps as $item)
                @php
                    $isActive = $item['id'] == $b['id'];
                    // Get actual enrolled count from database
                    $itemEnrolledCount = \App\Models\Enrollment::where('purchasable_type', \App\Models\Bootcamp::class)
                        ->where('purchasable_id', $item['id'])
                        ->count();
                @endphp
                
                <a href="{{ route('detail-online-bootcamp', ['id' => $item['id']]) }}" class="block bg-white rounded-2xl p-5 border {{ $isActive ? 'border-red-600 shadow-[0_0_0_1px_#e11d48,0_4px_12px_rgb(0,0,0,0.05)]' : 'border-gray-200 shadow-sm hover:border-gray-300' }} transition-all">
                    <!-- Top Badges -->
                    <div class="flex gap-2 mb-3">
                        <span class="px-2.5 py-0.5 {{ $isActive ? 'bg-red-600 text-white' : 'bg-red-50 text-red-600' }} text-[11px] font-bold rounded-full">{{ $loop->first ? 'Paling Diminati' : ($loop->iteration == 2 ? 'Baru' : 'Premium') }}</span>
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-500 text-[11px] font-semibold rounded-full">Intermediate</span>
                    </div>
                    
                    <h3 class="text-[15px] font-bold text-gray-900 leading-snug mb-1">{{ $item['title'] }}</h3>
                    <p class="text-[12px] text-gray-500 mb-4">{{ $item['mentor'] }}</p>
                    
                    <!-- Enrolled Count -->
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-[11px] font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.196A3 3 0 007 18v-2m.232-.172a3 3 0 014.232 2.196A3 3 0 0013.536 16M7 8a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                            {{ $itemEnrolledCount }} siswa enrolled
                        </div>
                    </div>
                    
                    <div class="w-full h-px bg-gray-100 mb-3"></div>
                    
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 mb-0.5">Mulai {{ $item['startDate'] }}</div>
                            <div class="text-[13px] font-bold text-gray-900">{{ $item['sessions'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[11px] font-medium text-gray-400 mb-0.5">Harga</div>
                            <div class="text-[15px] font-extrabold text-[#e11d48]">{{ $item['price'] }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Right Column: Detail Pane -->
        <div class="lg:col-span-8">
            <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm lg:sticky lg:top-24">
                
                <!-- Hero Image -->
                <div class="relative w-full h-[280px] bg-gray-900">
                    @if(!empty($b['thumbnail']))
                        <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" class="w-full h-full object-cover opacity-60">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <!-- Close button -->
                    <a href="{{ route('online-bootcamp') }}" class="absolute top-4 right-4 w-8 h-8 bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                    
                    <!-- Title Overlay -->
                    <div class="absolute bottom-6 left-8 right-8">
                        <h1 class="text-2xl font-extrabold text-white mb-1.5">{{ $b['title'] }}</h1>
                        <p class="text-gray-300 text-[13px] font-medium">{{ $b['mentor'] }}</p>
                    </div>
                </div>
                
                <!-- Content Body -->
                <div class="p-8">
                    <!-- Description -->
                    <p class="text-[14px] text-gray-600 leading-relaxed mb-6 font-medium">Bootcamp intensif selama 4 minggu yang mencakup HTML, CSS, React, Node.js, dan deployment. Setiap sesi dipandu langsung oleh engineer Google dengan studi kasus nyata.</p>
                    
                    <!-- Meta info row -->
                    <div class="flex flex-wrap items-center gap-6 text-[12px] font-bold text-gray-500 mb-8">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Mulai {{ $b['startDate'] }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            {{ count($sessions) }} pertemuan LIVE
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $enrolledCount }} siswa
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex gap-4 pb-8 border-b border-gray-100">
                        @if(!empty($isEnrolled))
                            <a href="{{ route('bootcamps-saya') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-full text-center transition-colors shadow-sm text-sm">
                                Sudah Terdaftar — Lihat Bootcamp Saya
                            </a>
                        @else
                            <a href="{{ route('pembayaran', ['id' => $b['id']]) }}" class="flex-1 bg-[#d00000] hover:bg-red-700 text-white font-bold py-3 px-6 rounded-full text-center transition-colors shadow-sm text-sm">
                                Daftar Bootcamp — {{ $b['price'] }}
                            </a>
                        @endif
                        <button class="px-8 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-50 transition-colors shadow-sm text-sm">
                            Simpan
                        </button>
                    </div>
                    
                    <!-- Jadwal Section -->
                    <div class="pt-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <h3 class="text-[17px] font-bold text-gray-900">Jadwal Pertemuan LIVE</h3>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold rounded-full">{{ count($sessions) }} sesi</span>
                        </div>
                        
                        <div class="space-y-0 relative">
                            <!-- vertical line connecting timeline -->
                            <div class="absolute top-4 bottom-4 left-4 w-px bg-gray-100 z-0"></div>
                            
                            @foreach($sessions as $i => $s)
                            <div class="relative z-10 flex items-start gap-4 py-4 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                                <div class="w-8 h-8 rounded-full bg-[#d00000] text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm mt-0.5">
                                    {{ $i + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[13px] font-bold text-gray-900 mb-1">{{ $s['topic'] }}</h4>
                                    <div class="flex items-center gap-4 text-[11px] text-gray-500 font-medium">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $s['date'] }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $s['time'] }} WIB
                                        </div>
                                    </div>
                                    @if(!empty($s['password'] && $isEnrolled))
                                    <div class="mt-3 rounded-lg border border-red-100 bg-red-50 px-3 py-2 text-[11px] text-red-700">
                                        <span class="font-semibold">Password sesi:</span> {{ $s['password'] }}
                                    </div>
                                    @endif
                                </div>
                                <div class="w-7 h-7 rounded-full bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 mt-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Alert Box -->
                        <div class="mt-6 bg-[#FFFDF3] border border-[#FDF0CD] rounded-xl p-4 flex gap-3 shadow-sm items-start">
                            <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[12px] font-medium text-orange-800 leading-relaxed">
                                Link Zoom aktif 15 menit sebelum sesi dimulai. Rekaman tersedia dalam 24 jam setelah kelas selesai.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
