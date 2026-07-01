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
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=7f1d1d&color=ffffff" alt="Admin Profile" class="w-full h-full rounded-full object-cover">
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- 4 STAT CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                    <x-icon name="users" class="w-5 h-5" />
                </div>
                <span class="bg-green-50 text-green-600 text-xs font-bold px-2 py-1 rounded-full">Active</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $stats['users'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Total Users</p>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-500">
                    <x-icon name="book" class="w-5 h-5" />
                </div>
                <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2 py-1 rounded-full">Live</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $stats['courses'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Total Courses</p>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                    <x-icon name="award" class="w-5 h-5" />
                </div>
                <span class="bg-yellow-50 text-yellow-600 text-xs font-bold px-2 py-1 rounded-full">Intensive</span>
            </div>
            <div>
                <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $stats['bootcamps'] ?? 0 }}</h3>
                <p class="text-sm font-medium text-gray-500">Bootcamps</p>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="bg-green-50 text-green-600 text-xs font-bold px-2 py-1 rounded-full">+8% growth</span>
            </div>
            <div>
                <h3 class="text-2xl font-extrabold text-gray-900 mb-1 mt-1">{{ $stats['revenue'] ?? 'Rp 0' }}</h3>
                <p class="text-sm font-medium text-gray-500">Revenue Mock</p>
            </div>
        </div>
    </div>

    <!-- MAIN GRID SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- COLUMN 1: Recent Users -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-900 text-lg">Pendaftar Terbaru</h3>
                <a href="{{ route('admin.users') }}" class="text-xs font-bold text-red-600 hover:text-red-700">Kelola User</a>
            </div>
            
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
                <div class="flex items-center justify-center h-full text-sm text-gray-400">
                    Belum ada data pendaftar.
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- COLUMN 2: Recent Courses -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-900 text-lg">Kursus Terbaru</h3>
                <a href="{{ route('admin.courses') }}" class="text-xs font-bold text-red-600 hover:text-red-700">Kelola Kursus</a>
            </div>
            
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
                        <div class="text-[12px] font-bold text-gray-900">Rp {{ number_format((float) ($course->price ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-center h-full text-sm text-gray-400">
                    Belum ada data kursus.
                </div>
                @endforelse
            </div>
        </div>

    </div>
    
</div>
@endsection