@extends('layouts.marketing')

@section('title', 'Dashboard Marketing')

@section('header_title', 'Dashboard Marketing')

@section('content')
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span class="text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Promo Aktif</p>
                    <p class="text-3xl font-bold text-pink-600">{{ $promoStats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Siswa Baru Hari Ini</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $studentStats['new_today'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Enrollments Minggu Ini</p>
                    <p class="text-3xl font-bold text-green-600">{{ $enrollmentStats['this_week'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Siswa</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $studentStats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Promo Overview --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Promo Codes Aktif</h3>
            <a href="{{ route('marketing.promo-codes.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition-colors">
                + Buat Promo Baru
            </a>
        </div>
        <div class="p-6">
            @if($activePromos->isEmpty())
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <p>Belum ada promo code aktif</p>
                    <a href="{{ route('marketing.promo-codes.create') }}" class="text-pink-600 hover:text-pink-700 font-medium mt-2 inline-block">
                        Buat promo pertama →
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($activePromos as $promo)
                        <div class="p-4 bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg border border-pink-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="px-2 py-1 bg-pink-500 text-white rounded text-xs font-bold">{{ $promo->code }}</span>
                                <span class="text-xs text-gray-500">
                                    {{ $promo->remaining_uses !== null ? $promo->remaining_uses . ' tersisa' : 'Unlimited' }}
                                </span>
                            </div>
                            <p class="font-medium text-gray-800 mb-1">{{ $promo->name }}</p>
                            <p class="text-2xl font-bold text-pink-600">{{ $promo->type_label }}</p>
                            <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                @if($promo->max_uses)
                                    <div class="h-full bg-pink-500 rounded-full" style="width: {{ min(100, $promo->usage_percentage) }}%"></div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Courses --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Kursus Terpopuler</h3>
            </div>
            <div class="p-6">
                @if($topCourses->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <p>Belum ada data kursus</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($topCourses as $course)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $course->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $course->category }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                                    <p class="text-xs text-gray-400">siswa</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Top Promo Codes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Promo Paling Banyak Digunakan</h3>
            </div>
            <div class="p-6">
                @if($topPromos->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <p>Belum ada promo yang digunakan</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($topPromos as $promo)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $promo->code }}</p>
                                    <p class="text-sm text-gray-500">{{ $promo->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-pink-600">{{ $promo->used_count }}</p>
                                    <p class="text-xs text-gray-400">kali digunakan</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
