@php
    $activePage = 'bootcamps-saya';
@endphp

@extends('layouts.app')

@section('title', 'Bootcamp Saya — 1Langkah')

@section('content')
<div class="px-6 sm:px-10 py-8 w-full space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Bootcamp Saya</h1>
            <p class="text-sm text-gray-500">{{ count($myBootcamps) }} bootcamp aktif</p>
        </div>
        <a href="{{ route('online-bootcamp') }}" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-5 py-3 text-sm font-bold transition-colors flex items-center gap-2">
            <span>Temukan Bootcamp</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @forelse($myBootcamps as $bootcamp)
                <a href="{{ route($bootcamp['type'] === 'offline' ? 'detail-offline-bootcamp' : 'detail-online-bootcamp', ['id' => $bootcamp['id']]) }}" class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow block">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-full sm:w-28 h-28 rounded-3xl bg-gray-100 overflow-hidden flex-shrink-0">
                            @if(!empty($bootcamp['thumbnail']))
                                <img src="{{ $bootcamp['thumbnail'] }}" alt="{{ $bootcamp['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-red-200"></div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900">{{ $bootcamp['title'] }}</h2>
                                    <p class="text-sm text-gray-500 mt-1">{{ $bootcamp['mentor'] }}</p>
                                </div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold {{ $bootcamp['type'] === 'offline' ? 'bg-orange-50 text-orange-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $bootcamp['type'] === 'offline' ? 'Offline' : 'Online' }}
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-500">
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ $bootcamp['sessions'] ?? '-' }}</div>
                                    <div>Sesi</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ $bootcamp['progress'] ?? 0 }}%</div>
                                    <div>Progress</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ number_format($bootcamp['rating'] ?? 0, 1) }}</div>
                                    <div>Rating</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900">{{ $bootcamp['enrolled_at'] ? date('d M Y', strtotime($bootcamp['enrolled_at'])) : '-' }}</div>
                                    <div>Terdaftar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm text-center">
                    <h2 class="text-xl font-bold text-gray-900 mb-3">Belum ada bootcamp</h2>
                    <p class="text-sm text-gray-500 mb-6">Kamu belum terdaftar di bootcamp manapun. Cari dan daftar bootcamp untuk mulai belajar intensif.</p>
                    <a href="{{ route('online-bootcamp') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-full text-sm font-bold transition-colors">
                        Temukan Bootcamp
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            @endforelse
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 text-lg mb-4">Statistik Bootcamp</h3>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="rounded-3xl bg-red-50 p-4">
                        <div class="text-3xl font-bold text-red-600">{{ $userStats['bootcamps_enrolled'] ?? 0 }}</div>
                        <div class="mt-1">Bootcamp Terdaftar</div>
                    </div>
                    <div class="rounded-3xl bg-green-50 p-4">
                        <div class="text-3xl font-bold text-green-600">{{ $userStats['bootcamps_completed'] ?? 0 }}</div>
                        <div class="mt-1">Bootcamp Selesai</div>
                    </div>
                    <div class="rounded-3xl bg-purple-50 p-4">
                        <div class="text-3xl font-bold text-purple-600">{{ $userStats['xp'] ?? 0 }}</div>
                        <div class="mt-1">Total XP</div>
                    </div>
                    <div class="rounded-3xl bg-yellow-50 p-4">
                        <div class="text-3xl font-bold text-yellow-700">{{ $userStats['certificates'] ?? 0 }}</div>
                        <div class="mt-1">Sertifikat</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-900 text-lg mb-4">Panduan Bootcamp</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex gap-3"><span class="text-red-600">•</span> Pastikan hadir di setidaknya 80% sesi untuk dapat sertifikat.</li>
                    <li class="flex gap-3"><span class="text-red-600">•</span> Simpan link Zoom dan materi sebelum kelas dimulai.</li>
                    <li class="flex gap-3"><span class="text-red-600">•</span> Hubungi mentor jika ada kendala teknis.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
