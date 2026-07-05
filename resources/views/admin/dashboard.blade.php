@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- HERO SECTION -->
    <div class="bg-[#cc0000] rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between relative overflow-hidden shadow-xl">
        <!-- Glow effect -->
        <div class="absolute -right-20 -top-20 w-[400px] h-[400px] bg-red-600 rounded-full blur-[80px] pointer-events-none opacity-50"></div>
        
        <div class="relative z-10 text-white w-full md:w-2/3 space-y-4">
            <div class="text-white/80 font-medium flex items-center gap-2">
                Selamat bekerja kembali! 🚀
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold">{{ auth()->user()->name ?? 'Administrator' }}</h1>
            <p class="text-white/80 text-sm sm:text-base">System Administrator Panel</p>
            
            <div class="flex flex-wrap items-center gap-3 pt-2">
                <div class="bg-red-800/50 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                    <x-icon name="users" class="w-4 h-4 text-blue-200" />
                    {{ $stats['users'] ?? 0 }} Total Users
                </div>
                <div class="bg-red-800/50 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                    <x-icon name="book" class="w-4 h-4 text-orange-200" />
                    {{ $stats['courses'] ?? 0 }} Courses
                </div>
                <div class="bg-red-800/50 backdrop-blur-sm border border-red-500/30 rounded-full px-4 py-2 text-sm font-medium flex items-center gap-2">
                    <x-icon name="award" class="w-4 h-4 text-yellow-200" />
                    {{ $stats['bootcamps'] ?? 0 }} Bootcamps
                </div>
            </div>
        </div>
        
        <div class="relative z-10 mt-6 md:mt-0 hidden sm:block">
            <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                @if(auth()->user()->profile_photo)
                <img src="{{ auth()->user()->profile_photo }}" alt="Admin Profile" class="w-full h-full rounded-full object-cover bg-red-900">
                @else
                <div class="w-full h-full rounded-full bg-[#7f1d1d] flex items-center justify-center text-white text-3xl md:text-5xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                @endif
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- 4 STAT CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <x-stat-card :value="$stats['users'] ?? 0" label="Total Users" icon="users" color="blue" />
        <x-stat-card :value="$stats['courses'] ?? 0" label="Total Courses" icon="book" color="purple" />
        <x-stat-card :value="$stats['bootcamps'] ?? 0" label="Bootcamps" icon="award" color="amber" />
        <x-stat-card :value="$stats['revenue'] ?? 'Rp 0'" label="Revenue Mock" color="green" />
    </div>

    <!-- MAIN GRID SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Recent Users -->
        <x-card-panel title="Pendaftar Terbaru" :actionRoute="route('admin.users')" actionLabel="Kelola User">
            <div class="space-y-4 flex-1">
                @forelse($recentUsers ?? [] as $user)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-10 h-10 rounded-full object-cover" alt="">
                        <div>
                            <h4 class="text-[13px] font-bold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-[11px] text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full">Aktif</span>
                </div>
                @empty
                <x-empty-state message="Belum ada data pendaftar." icon="users" />
                @endforelse
            </div>
        </x-card-panel>

        <!-- Recent Courses -->
        <x-card-panel title="Kursus Terbaru" :actionRoute="route('admin.courses')" actionLabel="Kelola Kursus">
            <div class="space-y-4 flex-1">
                @forelse($recentCourses ?? [] as $course)
                <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0 text-red-600">
                            <x-icon name="book" class="w-5 h-5" />
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-[13px] font-bold text-gray-900 truncate pr-4">{{ $course->title }}</h4>
                            <p class="text-[11px] text-gray-500 truncate">Mentor: {{ $course->mentor_name }}</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <div class="text-[12px] font-bold text-gray-900">{{ $course->formatted_price }}</div>
                    </div>
                </div>
                @empty
                <x-empty-state message="Belum ada data kursus." icon="book" />
                @endforelse
            </div>
        </x-card-panel>

    </div>
    
</div>
@endsection