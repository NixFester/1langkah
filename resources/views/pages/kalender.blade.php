@extends('layouts.app')

@section('title', 'Kalender')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header Section -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 28px;">Kalender</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Jadwal belajar & deadline kamu</p>
        </div>
        <div style="display: flex; align-items: center; gap: 20px; font-weight: bold; font-size: 13px;">
            <div style="display: flex; align-items: center; gap: 8px;"><div style="border-radius: 999px; width: 10px; height: 10px; background-color: #cc0000;"></div><span class="text-gray-600">Bootcamp</span></div>
            <div style="display: flex; align-items: center; gap: 8px;"><div style="border-radius: 999px; width: 10px; height: 10px; background-color: #3b82f6;"></div><span class="text-gray-600">Sesi Mentor</span></div>
            <div style="display: flex; align-items: center; gap: 8px;"><div style="border-radius: 999px; width: 10px; height: 10px; background-color: #f59e0b;"></div><span class="text-gray-600">Deadline</span></div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm" style="display: flex; align-items: center; justify-content: space-between; padding: 14px;">
        <button style="padding: 8px; color: #9ca3af; border-radius: 12px; cursor: pointer; background: transparent; border: none;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <h2 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 18px;">Juli 2026</h2>
        <button style="padding: 8px; color: #9ca3af; border-radius: 12px; cursor: pointer; background: transparent; border: none;">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <div class="calendar-layout" style="display: flex; gap: 24px; align-items: flex-start;">
        
        <!-- Left: Calendar Grid -->
        <div class="bg-white border border-gray-100 shadow-md overflow-hidden" style="flex: 1; min-width: 0; border-radius: 24px;">
            <!-- Days header -->
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); border-bottom: 1px solid #f3f4f6;">
                @foreach(['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'] as $day)
                    <div style="padding: 16px 0; text-align: center; font-weight: 800; color: #9ca3af; letter-spacing: 0.05em; font-size: 11px;">{{ $day }}</div>
                @endforeach
            </div>
            
            <!-- Grid cells -->
            <div class="calendar-grid" style="display: grid; grid-template-columns: repeat(7, 1fr);">
                <!-- Empty cells before day 1 (Assuming 1st is Tuesday for July 2026, so 2 empty cells: Min, Sen) -->
                <div class="border-r border-b border-gray-50" style="background-color: #fffafb;"></div>
                <div class="border-r border-b border-gray-50" style="background-color: #fffafb;"></div>
                
                <!-- Day 1-31 -->
                @for($i = 1; $i <= 31; $i++)
                    <div class="border-r border-b border-gray-50 p-1 sm:p-2 flex flex-col relative hover:bg-gray-50 transition-colors cursor-pointer {{ $i == 28 ? 'bg-red-50' : '' }}">
                        <span class="font-extrabold text-gray-900 text-center mb-1" style="font-size: 13px;">{{ $i }}</span>
                        
                        <!-- Mock Events -->
                        <div class="space-y-1 overflow-hidden hide-scrollbar">
                        @if($i == 7 || $i == 9 || $i == 14 || $i == 16 || $i == 21 || $i == 23 || $i == 28 || $i == 30)
                            <div class="px-1.5 py-1 rounded font-bold truncate text-center hidden sm:block" style="background-color: #fef2f2; color: #cc0000; font-size: 10px;">Full-Stack Boot...</div>
                            <div class="rounded-full mx-auto sm:hidden mt-1" style="width: 6px; height: 6px; background-color: #cc0000;"></div>
                        @endif
                        @if($i == 10 || $i == 18 || $i == 25)
                            <div class="px-1.5 py-1 rounded font-bold truncate text-center hidden sm:block" style="background-color: #eff6ff; color: #2563eb; font-size: 10px;">Sesi Mentor: R...</div>
                            <div class="rounded-full mx-auto sm:hidden mt-0.5" style="width: 6px; height: 6px; background-color: #2563eb;"></div>
                        @endif
                        @if($i == 14 || $i == 24 || $i == 30)
                            <div class="px-1.5 py-1 rounded font-bold truncate text-center hidden sm:block" style="background-color: #fffbeb; color: #d97706; font-size: 10px;">Deadline: UI De...</div>
                            <div class="rounded-full mx-auto sm:hidden mt-0.5" style="width: 6px; height: 6px; background-color: #f59e0b;"></div>
                        @endif
                        @if($i == 17 || $i == 24 || $i == 31)
                            <div class="px-1.5 py-1 rounded font-bold truncate text-center hidden sm:block" style="background-color: #fef2f2; color: #cc0000; font-size: 10px;">Data Science: D...</div>
                            <div class="rounded-full mx-auto sm:hidden mt-0.5" style="width: 6px; height: 6px; background-color: #cc0000;"></div>
                        @endif
                        @if($i == 12 || $i == 19 || $i == 26)
                            <div class="px-1.5 py-1 rounded font-bold truncate text-center hidden sm:block" style="background-color: #fef2f2; color: #cc0000; font-size: 10px;">UI/UX Bootcamp</div>
                            <div class="rounded-full mx-auto sm:hidden mt-0.5" style="width: 6px; height: 6px; background-color: #cc0000;"></div>
                        @endif
                        </div>
                    </div>
                @endfor
                
                <!-- Empty cells after 31 (ends on Thursday) -> 2 cells for Friday, Saturday -->
                <div class="border-b border-gray-50 border-r" style="background-color: #fffafb;"></div>
                <div class="border-b border-gray-50" style="background-color: #fffafb;"></div>
            </div>
        </div>

        <!-- Right: Side Panels -->
        <div style="width: 340px; flex-shrink: 0; display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Date Detail Placeholder -->
            <div class="bg-white border border-gray-100 shadow-md" style="border-radius: 24px; padding: 40px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; height: 240px;">
                <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="font-bold text-gray-500 mb-1" style="font-size: 16px;">Pilih tanggal</h3>
                <p class="text-xs text-gray-400 font-medium">untuk melihat detail agenda</p>
            </div>

            <!-- Agenda Terdekat -->
            <div class="bg-white border border-gray-100 shadow-md" style="border-radius: 24px; padding: 32px;">
                <h3 class="font-extrabold text-gray-900 tracking-tight mb-8" style="font-size: 20px;">Agenda Terdekat</h3>
                
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Item 1 -->
                    <div class="flex items-start gap-4 group cursor-pointer">
                        <div class="w-12 h-12 rounded-full flex flex-col items-center justify-center text-white flex-shrink-0 group-hover:scale-105 transition-transform shadow-sm" style="background-color: #cc0000;">
                            <span class="font-extrabold leading-none mt-0.5" style="font-size: 18px;">28</span>
                            <span class="font-bold leading-none mt-0.5" style="font-size: 10px;">Jul</span>
                        </div>
                        <div class="flex-1 mt-0.5">
                            <h4 class="font-bold text-gray-900 transition-colors leading-tight mb-1" style="font-size: 14px;">Full-Stack: Database & Prisma</h4>
                            <p class="text-xs text-gray-400 font-medium">19.00 WIB</p>
                        </div>
                    </div>
                    
                    <!-- Item 2 -->
                    <div class="flex items-start gap-4 group cursor-pointer">
                        <div class="w-12 h-12 rounded-full bg-amber-500 flex flex-col items-center justify-center text-white flex-shrink-0 group-hover:scale-105 transition-transform shadow-sm">
                            <span class="font-extrabold leading-none mt-0.5" style="font-size: 18px;">30</span>
                            <span class="font-bold leading-none mt-0.5" style="font-size: 10px;">Jul</span>
                        </div>
                        <div class="flex-1 mt-0.5">
                            <h4 class="font-bold text-gray-900 transition-colors leading-tight mb-1" style="font-size: 14px;">Deadline: Portfolio Submission</h4>
                            <p class="text-xs text-gray-400 font-medium">23.59 WIB</p>
                        </div>
                    </div>
                    
                    <!-- Item 3 -->
                    <div class="flex items-start gap-4 group cursor-pointer">
                        <div class="w-12 h-12 rounded-full flex flex-col items-center justify-center text-white flex-shrink-0 group-hover:scale-105 transition-transform shadow-sm" style="background-color: #cc0000;">
                            <span class="font-extrabold leading-none mt-0.5" style="font-size: 18px;">31</span>
                            <span class="font-bold leading-none mt-0.5" style="font-size: 10px;">Jul</span>
                        </div>
                        <div class="flex-1 mt-0.5">
                            <h4 class="font-bold text-gray-900 transition-colors leading-tight mb-1" style="font-size: 14px;">Data Science: Deep Learning</h4>
                            <p class="text-xs text-gray-400 font-medium">20.00 WIB</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.calendar-grid {
    grid-auto-rows: 90px;
}
@media (min-width: 640px) {
    .calendar-grid {
        grid-auto-rows: 110px;
    }
}
</style>
@endsection
