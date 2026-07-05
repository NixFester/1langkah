@extends('layouts.mentor')

@section('title', 'Detail Siswa')

@section('header_title', $student->name)

@section('content')
    <div class="mb-6">
        <a href="{{ route('mentor.students') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Siswa Saya
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            @if($student->profile_photo)
                <img src="{{ $student->profile_photo }}" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-xl">
                    {{ substr($student->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $student->name }}</h2>
                <p class="text-gray-500">{{ $student->email }}</p>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($progressData as $data)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800">{{ $data['course']->title }}</h3>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        {{ $data['progress_percent'] }}% Selesai
                    </span>
                </div>
                <div class="w-full h-3 bg-gray-200 rounded-full mb-4">
                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ $data['progress_percent'] }}%"></div>
                </div>
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $data['completed_chapters'] }}</p>
                        <p class="text-xs text-gray-500">Bab Selesai</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-800">{{ $data['total_chapters'] }}</p>
                        <p class="text-xs text-gray-500">Total Bab</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600">{{ $data['last_activity']?->format('d/m/Y') ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Terakhir Aktif</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center text-gray-400">
                <p>Tidak ada progress kursus</p>
            </div>
        @endforelse
    </div>
@endsection
