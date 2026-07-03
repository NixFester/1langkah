@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

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

            <div class="pt-4 flex flex-wrap gap-3">
                <a href="{{ route('kursus-saya') }}" class="bg-white text-red-700 hover:bg-gray-50 px-6 py-2.5 rounded-full font-bold text-sm transition-colors flex items-center gap-2 shadow-lg inline-flex">
                    Lanjutkan Belajar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('kursus') }}" class="bg-white/20 hover:bg-white/30 text-white px-6 py-2.5 rounded-full font-bold text-sm transition-colors flex items-center gap-2 inline-flex">
                    Browse Kursus
                </a>
            </div>
        </div>

        <div class="relative z-10 mt-6 md:mt-0 hidden sm:block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                @if(auth()->user()->profile_photo)
                <img src="{{ auth()->user()->profile_photo }}" alt="Profile" class="w-full h-full rounded-full object-cover bg-red-900">
                @else
                <div class="w-full h-full rounded-full bg-red-800 flex items-center justify-center text-white text-3xl font-bold">
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

        <!-- Card 2: Bootcamp Aktif -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $userStats['bootcamps_enrolled'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Bootcamp Aktif</p>
            </div>
        </div>

        <!-- Card 3: Kursus Selesai -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $userStats['courses_completed'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Kursus Selesai</p>
            </div>
        </div>

        <!-- Card 4: Bootcamp Selesai -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $userStats['bootcamps_completed'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Bootcamp Selesai</p>
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
                    <a href="{{ route('detail-kursus', ['id' => $course['id']]) }}" class="flex gap-4 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
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

            <!-- Bootcamp Saya -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-lg">Bootcamp Saya</h3>
                    <a href="{{ route('bootcamps-saya') }}" class="text-xs font-bold text-red-600 hover:text-red-700">Lihat semua</a>
                </div>

                <div class="space-y-4">
                    @forelse(array_slice($myBootcamps ?? [], 0, 3) as $bootcamp)
                    <a href="{{ $bootcamp['type'] === 'online' ? route('detail-online-bootcamp', ['id' => $bootcamp['id']]) : route('detail-offline-bootcamp', ['id' => $bootcamp['id']]) }}" class="flex gap-4 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 flex-shrink-0 overflow-hidden">
                            @if(!empty($bootcamp['thumbnail']))
                            <img src="{{ $bootcamp['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                            @else
                            <div class="w-full h-full bg-blue-600 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $bootcamp['type'] === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $bootcamp['type'] === 'online' ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-gray-900 truncate mt-1">{{ $bootcamp['title'] }}</h4>
                            <p class="text-[11px] text-gray-500 mb-2 truncate">{{ $bootcamp['mentor'] ?? 'Mentor' }}</p>
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $bootcamp['progress'] ?? 0 }}%"></div>
                                </div>
                            </div>
                            <p class="text-[10px] text-gray-500 font-medium mt-1">{{ $bootcamp['progress'] ?? 0 }}% ({{ $bootcamp['attended'] ?? 0 }}/{{ $bootcamp['sessions'] ?? 0 }} sesi)</p>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <p class="text-gray-500 text-sm">Belum ada bootcamp</p>
                        <a href="{{ route('online-bootcamp') }}" class="inline-block mt-2 text-red-600 text-sm font-medium hover:underline">Browse Bootcamp</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLUMN 2 -->
        <div class="space-y-6">
            <!-- Aktivitas Terbaru -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] h-full">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-lg">Aktivitas Terbaru</h3>
                </div>

                @forelse($recentActivities ?? [] as $activity)
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <div class="w-2 h-2 rounded-full" style="background-color: {{ $activity['color'] ?? '#3b82f6' }}"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[13px] text-gray-700 truncate">{{ $activity['text'] }}</p>
                        <p class="text-[11px] text-gray-400">{{ $activity['time'] }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-gray-500 text-sm">Belum ada aktivitas terbaru</p>
                    <p class="text-gray-400 text-xs mt-1">Mulai belajar untuk melihat aktivitasmu</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- COLUMN 3 -->
        <div class="space-y-6">
            <!-- Events Mendatang -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-lg">Events Mendatang</h3>
                    <a href="{{ route('event') }}" class="text-xs font-bold text-red-600 hover:text-red-700">Lihat semua</a>
                </div>

                @forelse($upcomingEvents ?? [] as $event)
                <a href="{{ route('detail-event', ['id' => $event['id']]) }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors mb-2">
                    <div class="w-12 h-12 rounded-xl flex flex-col items-center justify-center text-white" style="background-color: {{ $event['color'] ?? '#cc0000' }}">
                        <span class="text-xs font-bold">{{ $event['date'] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 truncate">{{ $event['title'] }}</h4>
                        <p class="text-[11px] text-gray-500">{{ $event['day'] }}, {{ $event['time'] }}</p>
                        <span class="inline-block mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">
                            {{ ucfirst($event['type'] ?? 'webinar') }}
                        </span>
                    </div>
                </a>
                @empty
                <div class="text-center py-6">
                    <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p class="text-gray-500 text-sm">Tidak ada events mendatang</p>
                    <a href="{{ route('event') }}" class="inline-block mt-2 text-red-600 text-sm font-medium hover:underline">Lihat Events</a>
                </div>
                @endforelse
            </div>

            <!-- Rekomendasi Kursus -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)]">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 text-lg">Rekomendasi Kursus</h3>
                    <a href="{{ route('kursus') }}" class="text-xs font-bold text-red-600 hover:text-red-700">Lihat semua</a>
                </div>

                <div class="space-y-4">
                    @forelse($recommendedCourses ?? [] as $course)
                    <a href="{{ route('detail-kursus', ['id' => $course['id']]) }}" class="flex gap-3 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
                        <div class="w-16 h-12 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                            @if(!empty($course['thumbnail']))
                            <img src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                            @else
                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, {{ $course['color'] ?? '#dc2626' }}, {{ $course['color'] ?? '#dc2626' }}cc);">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $course['title'] }}</h4>
                            <p class="text-[11px] text-gray-500 truncate">{{ $course['mentor'] ?? 'Mentor' }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="flex items-center text-yellow-500">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-[10px] font-medium text-gray-600 ml-0.5">{{ number_format($course['rating'], 1) }}</span>
                                </div>
                                <span class="text-[10px] text-gray-400">•</span>
                                <span class="text-[10px] text-gray-500">{{ $course['enrolledCount'] ?? 0 }} peserta</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-6">
                        <svg class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        <p class="text-gray-500 text-sm">Tidak ada rekomendasi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
