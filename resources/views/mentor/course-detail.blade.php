@extends('layouts.mentor')

@section('title', 'Detail Kursus')

@section('header_title', $course->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('mentor.my-courses') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Kursus Saya
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <p class="text-3xl font-bold text-blue-600">{{ $totalStudents }}</p>
            <p class="text-sm text-gray-500">Total Siswa</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <p class="text-3xl font-bold text-green-600">{{ $completedStudents }}</p>
            <p class="text-sm text-gray-500">Selesai</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <p class="text-3xl font-bold text-purple-600">{{ $avgProgress }}%</p>
            <p class="text-sm text-gray-500">Rata-rata Progress</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 text-center">
            <p class="text-3xl font-bold text-amber-600">{{ $avgRating }}</p>
            <p class="text-sm text-gray-500">Rating</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Students --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Siswa Enrollment ({{ $totalStudents }})</h3>
            </div>
            <div class="p-6">
                @forelse($enrolledStudents as $student)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center gap-3">
                            @if($student['user']?->profile_photo)
                                <img src="{{ $student['user']->profile_photo }}" class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                    {{ substr($student['user']->name ?? 'U', 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $student['user']->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-400">{{ $student['last_activity']?->diffForHumans() ?? 'Belum aktif' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ $student['progress'] }}%</p>
                            <div class="w-24 h-2 bg-gray-200 rounded-full mt-1">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $student['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Belum ada siswa</p>
                @endforelse
            </div>
        </div>

        {{-- Ratings --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Rating & Review</h3>
            </div>
            <div class="p-6">
                @forelse($ratings as $rating)
                    <div class="py-3 border-b border-gray-100 last:border-0">
                        <div class="flex items-center gap-2 mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="text-sm text-gray-700">{{ $rating->review ?? 'Tanpa review' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $rating->user?->name ?? 'Anonymous' }}</p>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Belum ada rating</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
