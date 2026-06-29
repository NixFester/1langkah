@extends('layouts.app', ['activePage' => 'offline-bootcamp'])

@section('title', 'Offline Bootcamp — 1Langkah')
@section('header_title', 'Offline Bootcamp')

@section('content')
<div class="w-full px-2 pb-8">
    <!-- Header -->
    <div class="mb-8 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Offline Bootcamp</h1>
        <p class="text-gray-500 text-base">Belajar tatap muka intensif di kampus 1Langkah — pengalaman immersive yang tak tergantikan</p>
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

    <!-- Bootcamp Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($bootcamps as $b)
            <a href="{{ route('detail-offline-bootcamp', ['id' => $b['id']]) }}" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-[0_2px_12px_rgb(0,0,0,0.04)] hover:shadow-lg transition-shadow group flex flex-col h-full cursor-pointer">
                <!-- Thumbnail -->
                <div class="relative w-full aspect-[4/3] bg-gray-100 overflow-hidden">
                    @if(!empty($b['thumbnail']))
                        <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc)"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
                    
                    <!-- Top Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 {{ $loop->first ? 'bg-[#d00000]' : 'bg-[#d00000]' }} text-white text-[11px] font-bold rounded-full shadow-sm">{{ $loop->first ? 'Paling Diminati' : ($loop->iteration == 2 ? 'Weekend Class' : 'Eksklusif') }}</span>
                        <span class="px-3 py-1 bg-black/40 backdrop-blur-sm text-white text-[11px] font-semibold rounded-full shadow-sm">{{ $loop->first ? 'All Level' : ($loop->iteration == 2 ? 'Beginner' : 'Intermediate') }}</span>
                    </div>
                    <!-- Bottom Location Badge -->
                    <div class="absolute bottom-3 left-4">
                        <div class="flex items-center gap-1.5 text-white/90 text-[12px] font-semibold tracking-wide shadow-sm drop-shadow-md">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ explode(',', $b['location'])[0] }}
                        </div>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="text-[17px] font-bold text-gray-900 leading-snug mb-1.5 group-hover:text-red-700 transition-colors line-clamp-2">{{ $b['title'] }}</h3>
                    <p class="text-[13px] text-gray-500 mb-4 line-clamp-2">{{ $b['mentor'] }}</p>
                    
                    <div class="flex items-start gap-1.5 text-[12px] text-gray-400 mb-4">
                        <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="leading-relaxed">{{ $b['location'] }}</span>
                    </div>

                    <div class="flex items-center gap-4 text-[12px] text-gray-500 font-medium mb-6">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $loop->first ? '8 Minggu' : ($loop->iteration == 2 ? '8 Minggu' : '10 Minggu') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $loop->first ? '2× seminggu' : ($loop->iteration == 2 ? 'Sabtu & Minggu' : '3× seminggu') }}
                        </div>
                    </div>
                    
                    <!-- Slot Progress -->
                    @php
                        $totalSlots = 20;
                        if ($loop->iteration == 2) $totalSlots = 16;
                        if ($loop->iteration == 3) $totalSlots = 18;
                        
                        $sisa = 7;
                        if ($loop->iteration == 2) $sisa = 7;
                        if ($loop->iteration == 3) $sisa = 7;
                        
                        $percentage = (($totalSlots - $sisa) / $totalSlots) * 100;
                        
                        $colorClass = 'bg-[#f59e0b]'; // orange
                        $textColor = 'text-[#f59e0b]';
                        if ($loop->iteration == 2) {
                            $colorClass = 'bg-[#10b981]'; // green
                            $textColor = 'text-[#10b981]';
                        }
                    @endphp
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-xs font-bold mb-2.5">
                            <span class="text-gray-400">Sisa kursi</span>
                            <span class="{{ $textColor }}">{{ $sisa }} dari {{ $totalSlots }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $colorClass }} rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                    
                    <div class="w-full h-px bg-gray-100 mb-4"></div>
                    
                    <!-- Footer -->
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 mb-1">Mulai {{ $b['startDate'] }}</div>
                            <div class="text-[16px] font-extrabold text-black">{{ $b['price'] }}</div>
                        </div>
                        <div>
                            @php
                                $badgeText = 'Soft Skills';
                                if ($loop->iteration == 2) $badgeText = 'Seni & Musik';
                                if ($loop->iteration == 3) $badgeText = 'Leadership';
                            @endphp
                            <span class="px-3 py-1.5 bg-red-50 text-red-600 text-[11px] font-bold rounded-full">{{ $badgeText }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
