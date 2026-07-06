@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full px-0 sm:px-2 pb-8 space-y-4 sm:space-y-6">

    <!-- HERO SECTION -->
    <div class="bg-gradient-to-br from-[#cc0000] to-[#aa0000] rounded-2xl sm:rounded-3xl p-6 sm:p-10 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-lg sm:shadow-xl">
        <!-- Glow effect -->
        <div class="absolute -right-20 -top-20 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-red-500 rounded-full blur-[60px] sm:blur-[80px] pointer-events-none opacity-40"></div>

        <div class="relative z-10 text-white w-full md:w-2/3 space-y-3 sm:space-y-4">
            <div class="text-white/90 font-medium flex items-center gap-2 text-sm sm:text-base">
                Selamat datang kembali! 👋
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">{{ auth()->user()->name ?? 'User' }}</h1>
            <p class="text-white/80 text-[13px] sm:text-base leading-relaxed max-w-lg">{{ auth()->user()->bio ?? 'Belum ada bio. Tambahkan di pengaturan untuk memperbarui profil Anda.' }}</p>

            <div class="pt-3 sm:pt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('kursus-saya') }}" class="bg-white text-[#cc0000] hover:bg-gray-50 px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 shadow-sm inline-flex w-full sm:w-auto">
                    Lanjutkan Belajar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('kursus') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 inline-flex w-full sm:w-auto">
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
        <x-stat-card :value="$userStats['courses_enrolled'] ?? 0" label="Kursus Aktif" icon="book" color="red" />
        <x-stat-card :value="$userStats['bootcamps_enrolled'] ?? 0" label="Bootcamp Aktif" icon="users" color="blue" />
        <x-stat-card :value="$userStats['courses_completed'] ?? 0" label="Kursus Selesai" icon="check" color="green" />
        <x-stat-card :value="$userStats['bootcamps_completed'] ?? 0" label="Bootcamp Selesai" icon="folder" color="purple" />
    </div>

    <!-- MAIN GRID SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left and Middle sections (2 Columns spanning 2 of the 3 columns) -->
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Lanjutkan Belajar -->
            <x-card-panel title="Lanjutkan Belajar" :actionRoute="route('kursus-saya')" actionLabel="Lihat semua">
                <div class="space-y-5 flex-1">
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
                    <x-empty-state message="Belum ada kursus yang dimulai" icon="inbox" :actionRoute="route('kursus')" actionLabel="Browse Kursus" />
                    @endforelse
                </div>
            </x-card-panel>

            <!-- Events Mendatang -->
            <x-card-panel title="Events Mendatang" :actionRoute="route('event')" actionLabel="Lihat semua">
                <div class="space-y-4 flex-1">
                    @forelse($upcomingEvents ?? [] as $event)
                    <a href="{{ route('detail-event', ['id' => $event['id']]) }}" class="flex items-start gap-3 p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
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
                    <x-empty-state message="Tidak ada events mendatang" icon="calendar" :actionRoute="route('event')" actionLabel="Lihat Events" />
                    @endforelse
                </div>
            </x-card-panel>
            
            <!-- Bootcamp Saya -->
            <x-card-panel title="Bootcamp Saya" :actionRoute="route('bootcamps-saya')" actionLabel="Lihat semua">
                <div class="space-y-4 flex-1">
                    @forelse(array_slice($myBootcamps ?? [], 0, 3) as $bootcamp)
                    <x-list-item
                        :href="route($bootcamp['type'] === 'online' ? 'detail-online-bootcamp' : 'detail-offline-bootcamp', ['id' => $bootcamp['id']])"
                        :thumbnail="$bootcamp['thumbnail'] ?? null"
                        :title="$bootcamp['title']"
                        :subtitle="$bootcamp['mentor'] ?? 'Mentor'"
                        :progress="$bootcamp['progress'] ?? 0"
                        progressColor="blue"
                        :meta="($bootcamp['progress'] ?? 0) . '% (' . ($bootcamp['attended'] ?? 0) . '/' . ($bootcamp['sessions'] ?? 0) . ' sesi)'"
                        :badge="['text' => $bootcamp['type'] === 'online' ? 'Online' : 'Offline', 'class' => $bootcamp['type'] === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700']"
                    />
                    @empty
                    <x-empty-state message="Belum ada bootcamp" icon="users" :actionRoute="route('online-bootcamp')" actionLabel="Browse Bootcamp" />
                    @endforelse
                </div>
            </x-card-panel>

            <!-- Rekomendasi Kursus -->
            <x-card-panel title="Rekomendasi Kursus" :actionRoute="route('kursus')" actionLabel="Lihat semua">
                <div class="space-y-4 flex-1">
                    @forelse($recommendedCourses ?? [] as $course)
                    <a href="{{ route('detail-kursus', ['id' => $course['id']]) }}" class="flex gap-3 items-center hover:bg-gray-50 p-2 -mx-2 rounded-xl transition-colors">
                        <div class="w-16 h-12 rounded-lg bg-gray-100 flex-shrink-0 overflow-hidden">
                            @if(!empty($course['thumbnail']))
                            <img src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                            @else
                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, {{ $course['color'] ?? '#dc2626' }}, {{ $course['color'] ?? '#dc2626' }}cc);">
                                <x-icon name="book" class="w-5 h-5 text-white" />
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
                    <x-empty-state message="Tidak ada rekomendasi" icon="sparkles" />
                    @endforelse
                </div>
            </x-card-panel>

        </div>

        <!-- Right section (1 Column) -->
        <div class="space-y-6">
            <!-- Prestasi & Badge -->
            <x-card-panel title="Prestasi & Badge" :actionRoute="route('achievement')" actionLabel="Lihat semua">
                <div class="space-y-3">
                    @if(!empty($userAchievements) && $userAchievements->count() > 0)
                        @foreach($userAchievements->take(3) as $achievement)
                        <div class="flex items-center gap-3 p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $achievement->achievement->name ?? 'Achievement' }}</p>
                                <p class="text-xs text-gray-500">{{ $achievement->earned_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <x-empty-state message="Belum ada achievement. Mulai belajar untuk membuka badge!" icon="trophy" />
                    @endif
                </div>
            </x-card-panel>

            <!-- Aktivitas Terbaru -->
            <x-card-panel title="Aktivitas Terbaru">
                <div class="space-y-4">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-center gap-3 p-3 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="w-2 h-2 rounded-full" style="background-color: {{ $activity['color'] ?? '#3b82f6' }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] text-gray-700 truncate">{{ $activity['text'] }}</p>
                            <p class="text-[11px] text-gray-400">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @empty
                    <x-empty-state message="Belum ada aktivitas terbaru. Mulai belajar untuk melihat aktivitasmu." icon="clock" />
                    @endforelse
                </div>
            </x-card-panel>
        </div>
    </div>

</div>
@endsection
