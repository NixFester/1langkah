@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-6 py-8 sm:px-10 w-full space-y-6">

    <!-- HERO SECTION -->
    <div class="bg-[#cc0000] rounded-3xl p-8 sm:p-10 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-xl">
        <!-- Glow effect -->
        <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-red-600 rounded-full blur-[80px] pointer-events-none opacity-50"></div>

        <div class="relative z-10 text-white w-full md:w-2/3 space-y-4">
            <div class="text-white/80 font-medium flex items-center gap-2">
                Selamat datang kembali! 👋
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold">{{ auth()->user()->name ?? 'User' }}</h1>
            <p class="text-white/80 text-sm sm:text-base">{{ auth()->user()->bio ?? 'Belum ada bio. Tambahkan di pengaturan.' }}</p>

            <div class="pt-4">
                <a href="{{ route('kursus-saya') }}" class="bg-white text-red-700 hover:bg-gray-50 px-6 py-2.5 rounded-full font-bold text-sm transition-colors flex items-center gap-2 shadow-lg inline-flex">
                    Lanjutkan Belajar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="relative z-10 mt-6 md:mt-0 hidden sm:block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                @if(auth()->user()->profile_photo)
                <img src="{{ auth()->user()->profile_photo }}" alt="Profile" class="w-full h-full rounded-full object-cover bg-red-900">
                @else
                <div class="w-full h-full rounded-full bg-red-800 flex items-center justify-center text-3xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                @endif
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- 4 STAT CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <!-- Card 1: Kursus Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $userStats['courses_enrolled'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Kursus Aktif</p>
            </div>
        </div>

        <!-- Card 2: Sertifikat -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ ($userStats['courses_completed'] ?? 0) + ($userStats['bootcamps_completed'] ?? 0) }}</h3>
                <p class="text-sm font-medium text-gray-500">Sertifikat</p>
            </div>
        </div>

        <!-- Card 3: XP -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ number_format($userStats['xp'] ?? 0) }}</h3>
                <p class="text-sm font-medium text-gray-500">Total XP</p>
            </div>
        </div>

        <!-- Card 4: Skills -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $userStats['skills_count'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Skills</p>
            </div>
        </div>
    </div>

    <!-- MAIN GRID SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- COLUMN 1 -->
        <div class="space-y-6">
            <!-- Lanjutkan Belajar -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-lg">Lanjutkan Belajar</h3>
                    <a href="{{ route('kursus-saya') }}" class="text-xs font-bold text-red-600 hover:text-red-700">Lihat semua</a>
                </div>

                <div class="space-y-5">
                    @forelse(array_slice($activeCourses ?? [], 0, 3) as $course)
                    <a href="{{ route('detail-kursus', $course['id']) }}" class="flex gap-4 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                            @if(!empty($course['thumbnail']))
                            <img src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                            @else
                            <div class="w-full h-full" style="background: linear-gradient(135deg, {{ $course['color'] ?? '#dc2626' }}, {{ $course['color'] ?? '#dc2626' }}cc);"></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $course['title'] }}</h4>
                            <p class="text-[11px] text-gray-500 mb-2 truncate">{{ $course['mentor'] ?? 'Mentor' }}</p>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-600 rounded-full" style="width: {{ $course['progress'] ?? 0 }}%"></div>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-medium mt-1">{{ $course['progress'] ?? 0 }}% selesai</p>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <p class="text-gray-500 text-sm">Belum ada kursus yang dimulai</p>
                        <a href="{{ route('kursus') }}" class="inline-block mt-3 text-red-600 text-sm font-medium hover:underline">Browse Kursus</a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- ⛔ Skill Overview - GAMIFY NOTE -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] relative">
                <div class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded-full">⚠️ Fitur gamify belum dibuat</div>
                <h3 class="font-bold text-gray-900 text-lg mb-6">Skill Overview</h3>
                <div class="relative w-full aspect-square max-w-[200px] mx-auto flex items-center justify-center">
                    <!-- SVG Radar Chart Mockup -->
                    <svg viewBox="0 0 100 100" class="w-full h-full text-gray-200">
                        <polygon points="50,10 90,30 90,70 50,90 10,70 10,30" fill="none" stroke="currentColor" stroke-width="0.5"/>
                        <polygon points="50,30 70,40 70,60 50,70 30,60 30,40" fill="none" stroke="currentColor" stroke-width="0.5"/>
                        <line x1="50" y1="50" x2="50" y2="10" stroke="currentColor" stroke-width="0.5"/>
                        <line x1="50" y1="50" x2="90" y2="30" stroke="currentColor" stroke-width="0.5"/>
                        <line x1="50" y1="50" x2="90" y2="70" stroke="currentColor" stroke-width="0.5"/>
                        <line x1="50" y1="50" x2="50" y2="90" stroke="currentColor" stroke-width="0.5"/>
                        <line x1="50" y1="50" x2="10" y2="70" stroke="currentColor" stroke-width="0.5"/>
                        <line x1="50" y1="50" x2="10" y2="30" stroke="currentColor" stroke-width="0.5"/>

                        <!-- Value Polygon -->
                        <polygon points="50,20 75,35 60,75 50,80 25,65 20,40" fill="rgba(204, 0, 0, 0.1)" stroke="#cc0000" stroke-width="1.5"/>
                        <circle cx="50" cy="20" r="1.5" fill="#cc0000"/>
                        <circle cx="75" cy="35" r="1.5" fill="#cc0000"/>
                        <circle cx="60" cy="75" r="1.5" fill="#cc0000"/>
                        <circle cx="50" cy="80" r="1.5" fill="#cc0000"/>
                        <circle cx="25" cy="65" r="1.5" fill="#cc0000"/>
                        <circle cx="20" cy="40" r="1.5" fill="#cc0000"/>
                    </svg>
                    <!-- Labels -->
                    <span class="absolute top-[-10px] left-1/2 -translate-x-1/2 text-[10px] text-gray-500 font-medium">Frontend</span>
                    <span class="absolute top-[25%] right-[-10px] text-[10px] text-gray-500 font-medium">Backend</span>
                    <span class="absolute bottom-[25%] right-[-10px] text-[10px] text-gray-500 font-medium">Design</span>
                    <span class="absolute bottom-[-10px] left-1/2 -translate-x-1/2 text-[10px] text-gray-500 font-medium">Data</span>
                    <span class="absolute bottom-[25%] left-[-10px] text-[10px] text-gray-500 font-medium">Cloud</span>
                    <span class="absolute top-[25%] left-[-10px] text-[10px] text-gray-500 font-medium">AI/ML</span>
                </div>
            </div>
        </div>

        <!-- COLUMN 2 -->
        <div class="space-y-6">
            <!-- ⛔ Leaderboard - GAMIFY NOTE -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] h-full relative">
                <div class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded-full">⚠️ Fitur gamify belum dibuat</div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-lg">Leaderboard</h3>
                    <span class="bg-yellow-50 text-yellow-700 text-[10px] font-bold px-2 py-1 rounded-full">Top 10%</span>
                </div>

                <div class="space-y-1">
                    <!-- Rank 1 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors">
                        <span class="text-xs font-bold text-gray-400 w-4">#1</span>
                        <div class="w-9 h-9 rounded-full bg-red-700 text-white flex items-center justify-center text-xs font-bold">AF</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-bold text-gray-900 truncate">Ahmad Fauzi</h4>
                            <p class="text-[11px] text-gray-500">12,480 XP</p>
                        </div>
                        <span class="text-lg">👑</span>
                    </div>
                    <!-- Rank 2 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors">
                        <span class="text-xs font-bold text-gray-400 w-4">#2</span>
                        <div class="w-9 h-9 rounded-full bg-red-800 text-white flex items-center justify-center text-xs font-bold">SR</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-bold text-gray-900 truncate">Siti Rahma</h4>
                            <p class="text-[11px] text-gray-500">11,920 XP</p>
                        </div>
                        <span class="text-lg">🥈</span>
                    </div>
                    <!-- Rank 3 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors">
                        <span class="text-xs font-bold text-gray-400 w-4">#3</span>
                        <div class="w-9 h-9 rounded-full bg-red-900 text-white flex items-center justify-center text-xs font-bold">DP</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-bold text-gray-900 truncate">Dito Pratama</h4>
                            <p class="text-[11px] text-gray-500">10,750 XP</p>
                        </div>
                        <span class="text-lg">🥉</span>
                    </div>
                    <!-- Rank 4 (You) -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl bg-red-50/80 border border-red-100/50">
                        <span class="text-xs font-bold text-red-400 w-4">#4</span>
                        <div class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center text-xs font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-bold text-red-700 truncate">You ({{ explode(' ', auth()->user()->name ?? 'User')[0] }})</h4>
                            <p class="text-[11px] text-red-500">{{ number_format($userStats['xp'] ?? 0) }} XP</p>
                        </div>
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <!-- Rank 5 -->
                    <div class="flex items-center gap-3 p-3 rounded-2xl hover:bg-gray-50 transition-colors">
                        <span class="text-xs font-bold text-gray-400 w-4">#5</span>
                        <div class="w-9 h-9 rounded-full bg-red-950 text-white flex items-center justify-center text-xs font-bold">MS</div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[13px] font-bold text-gray-900 truncate">Maya Sari</h4>
                            <p class="text-[11px] text-gray-500">8,920 XP</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLUMN 3 -->
        <div class="space-y-6">
            <!-- ⛔ Progress Minggu Ini - GAMIFY NOTE -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] relative">
                <div class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded-full">⚠️ Fitur gamify belum dibuat</div>
                <h3 class="font-bold text-gray-900 text-lg mb-6">Progress Minggu Ini</h3>

                <!-- Bar Chart Mockup -->
                <div class="flex items-end justify-between h-32 mb-6 px-2 gap-2">
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-t-sm h-12 relative group"><div class="absolute inset-0 bg-gray-200 group-hover:bg-gray-300 transition-colors rounded-t-sm"></div></div>
                        <span class="text-[10px] text-gray-400">Mon</span>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-t-sm h-16 relative group"><div class="absolute inset-0 bg-gray-200 group-hover:bg-gray-300 transition-colors rounded-t-sm"></div></div>
                        <span class="text-[10px] text-gray-400">Tue</span>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-red-100 rounded-t-sm h-24 relative group"><div class="absolute inset-0 bg-red-500 rounded-t-sm"></div></div>
                        <span class="text-[10px] text-red-500 font-bold">Wed</span>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-t-sm h-20 relative group"><div class="absolute inset-0 bg-gray-200 group-hover:bg-gray-300 transition-colors rounded-t-sm"></div></div>
                        <span class="text-[10px] text-gray-400">Thu</span>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-t-sm h-10 relative group"><div class="absolute inset-0 bg-gray-200 group-hover:bg-gray-300 transition-colors rounded-t-sm"></div></div>
                        <span class="text-[10px] text-gray-400">Fri</span>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-t-sm h-8 relative group"><div class="absolute inset-0 bg-gray-200 group-hover:bg-gray-300 transition-colors rounded-t-sm"></div></div>
                        <span class="text-[10px] text-gray-400">Sat</span>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <div class="w-full bg-gray-100 rounded-t-sm h-14 relative group"><div class="absolute inset-0 bg-gray-200 group-hover:bg-gray-300 transition-colors rounded-t-sm"></div></div>
                        <span class="text-[10px] text-gray-400">Sun</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-gray-500">Total jam minggu ini</span>
                        <span class="text-sm font-bold text-gray-900">0h</span>
                    </div>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500">Target minggu ini</span>
                        <span class="text-sm font-bold text-gray-900">20h</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gray-300 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- ⛔ Aktivitas Terbaru - GAMIFY NOTE -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] relative">
                <div class="absolute top-2 right-2 bg-yellow-100 text-yellow-800 text-[10px] font-bold px-2 py-1 rounded-full">⚠️ Fitur gamify belum dibuat</div>
                <h3 class="font-bold text-gray-900 text-lg mb-6">Aktivitas Terbaru</h3>
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 text-sm">Belum ada aktivitas terbaru</p>
                </div>
            </div>
        </div>

    </div>

    <!-- AI RECOMMENDATION -->
    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-3xl p-6 md:p-8 border border-purple-100">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-purple-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <h3 class="font-extrabold text-gray-900 text-lg">Rekomendasi AI untuk Kamu</h3>
                <p class="text-xs font-medium text-gray-500">Berdasarkan skill gaps dan tujuan karir kamu</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Rec 1 -->
            <div class="bg-white rounded-2xl p-5 border border-purple-100 hover:shadow-md transition-shadow cursor-pointer group">
                <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-3 inline-block">AI Pick</span>
                <h4 class="font-bold text-sm text-gray-900 group-hover:text-purple-600 transition-colors mb-1">UI/UX Design Mastery</h4>
                <p class="text-[11px] text-gray-500">Sari Dewi</p>
            </div>
            <!-- Rec 2 -->
            <div class="bg-white rounded-2xl p-5 border border-purple-100 hover:shadow-md transition-shadow cursor-pointer group">
                <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-3 inline-block">AI Pick</span>
                <h4 class="font-bold text-sm text-gray-900 group-hover:text-purple-600 transition-colors mb-1">Digital Marketing Strategy</h4>
                <p class="text-[11px] text-gray-500">Rina Kusuma</p>
            </div>
            <!-- Rec 3 -->
            <div class="bg-white rounded-2xl p-5 border border-purple-100 hover:shadow-md transition-shadow cursor-pointer group">
                <span class="bg-purple-100 text-purple-700 text-[10px] font-bold px-2.5 py-1 rounded-full mb-3 inline-block">AI Pick</span>
                <h4 class="font-bold text-sm text-gray-900 group-hover:text-purple-600 transition-colors mb-1">Leadership & Management Excellence</h4>
                <p class="text-[11px] text-gray-500">Dewi Rahayu</p>
            </div>
        </div>
    </div>

</div>
@endsection
