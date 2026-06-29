@extends('layouts.app', ['activePage' => 'online-bootcamp'])

@section('title', 'Online Bootcamp — 1Langkah')
@section('header_title', 'Online Bootcamp')

@section('content')
<div class="w-full px-2 pb-8">
    <!-- Header -->
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

    <!-- Bootcamp Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($bootcamps as $b)
            <a href="{{ route('detail-online-bootcamp', ['id' => $b['id']]) }}" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.04)] hover:shadow-lg transition-shadow group flex flex-col h-full cursor-pointer">
                <!-- Thumbnail -->
                <div class="relative w-full aspect-[16/10] bg-gray-100 overflow-hidden">
                    @if(!empty($b['thumbnail']))
                        <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc)"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent pointer-events-none"></div>
                    
                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full shadow-sm">{{ $loop->first ? 'Paling Diminati' : ($loop->iteration == 2 ? 'Baru' : 'Premium') }}</span>
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
                    <h3 class="text-lg font-bold text-gray-900 leading-snug mb-2 group-hover:text-red-600 transition-colors line-clamp-2">{{ $b['title'] }}</h3>
                    <p class="text-sm text-gray-500 mb-6 font-medium">{{ $b['mentor'] }}</p>
                    
                    <!-- Slot Progress -->
                    @php
                        $totalSlots = 40;
                        if ($loop->iteration == 2) $totalSlots = 30;
                        if ($loop->iteration == 3) $totalSlots = 25;
                        
                        $sisa = 2;
                        if ($loop->iteration == 2) $sisa = 5;
                        if ($loop->iteration == 3) $sisa = 6;
                        
                        $percentage = (($totalSlots - $sisa) / $totalSlots) * 100;
                        
                        $colorClass = 'bg-[#e11d48]'; // red-600
                        $textColor = 'text-[#e11d48]';
                        if ($sisa > 4) {
                            $colorClass = 'bg-[#f59e0b]'; // amber-500
                            $textColor = 'text-[#f59e0b]';
                        }
                    @endphp
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-gray-400">Slot tersedia</span>
                            <span class="{{ $textColor }}">{{ $sisa }} sisa dari {{ $totalSlots }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $colorClass }} rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <div class="w-full h-px bg-gray-100 mb-5"></div>
                    
                    <!-- Footer -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[13px] font-medium text-gray-400 mb-1">Mulai {{ $b['startDate'] }}</div>
                            <div class="text-[15px] font-bold text-gray-900">{{ $b['sessions'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[13px] font-medium text-gray-400 mb-1">Harga</div>
                            <div class="text-lg font-extrabold text-[#e11d48]">{{ $b['price'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
