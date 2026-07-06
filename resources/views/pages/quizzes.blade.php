@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', 'Quizzes - 1Langkah')

@php
use App\Models\TestAttempt;
@endphp

@section('header_title', 'Quiz Saya')

@section('content')
<div class="w-full px-0 sm:px-2 pb-8 space-y-6 sm:space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">Quiz Kursus</h1>
            <p class="text-gray-500 text-sm sm:text-base">Kumpulkan quiz untuk menyelesaikan kursus</p>
        </div>
        <a href="{{ route('quiz.history') }}" class="group bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-700 hover:text-red-600 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-[13px] sm:text-sm font-bold transition-all shadow-sm flex items-center gap-2 w-max mt-2 sm:mt-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Riwayat Quiz
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    @if($quizzesByCourse->isEmpty())
    <!-- No quizzes available -->
    <x-empty-state
        message="Daftar kursus untuk mengakses materi dan quiz."
        icon="book"
        :actionRoute="route('kursus')"
        actionLabel="Browse Kursus"
    />
    @endif

    <!-- Quizzes by Course -->
    @foreach($quizzesByCourse as $courseId => $courseQuizzes)
    @php
        $course = $courseQuizzes->first()->course;
    @endphp
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="bg-gray-50/50 px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($course->pictures->where('type', 'thumbnail')->first())
                    <img src="{{ $course->pictures->where('type', 'thumbnail')->first()->url }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full" style="background: linear-gradient(135deg, {{ $course->color ?? '#667eea' }}, {{ $course->color ?? '#667eea' }}cc);"></div>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ $course->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $courseQuizzes->count() }} quiz tersedia</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-50">
            @foreach($courseQuizzes as $quiz)
            @php
                $typeLabels = [
                    'pre_test' => ['label' => 'Pre-Test', 'color' => 'bg-blue-100 text-blue-700'],
                    'post_test' => ['label' => 'Post-Test', 'color' => 'bg-green-100 text-green-700'],
                    'chapter_quiz' => ['label' => 'Chapter Quiz', 'color' => 'bg-purple-100 text-purple-700'],
                ];
                $type = $typeLabels[$quiz->type] ?? ['label' => 'Quiz', 'color' => 'bg-gray-100 text-gray-700'];

                $lastAttempt = TestAttempt::where('user_id', Auth::id())
                    ->where('testable_type', Course::class)
                    ->where('testable_id', $quiz->course_id)
                    ->where('test_type', $quiz->type)
                    ->whereNotNull('completed_at')
                    ->latest('completed_at')
                    ->first();
            @endphp
            <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-6">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $type['color'] }}">
                            {{ $type['label'] }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $quiz->questions_count ?? 0 }} pertanyaan</span>
                        <span class="text-xs text-gray-500">•</span>
                        <span class="text-xs text-gray-500">Passing: {{ $quiz->passing_score }}%</span>
                    </div>
                    <h4 class="font-bold text-gray-900">{{ $quiz->title }}</h4>
                    @if($quiz->description)
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($quiz->description, 80) }}</p>
                    @endif

                    @if($lastAttempt)
                    <div class="mt-2 flex items-center gap-3">
                        @if($lastAttempt->passed)
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                            ✓ Lulus ({{ number_format($lastAttempt->score, 0) }}%)
                        </span>
                        @else
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                            ✗ Gagal ({{ number_format($lastAttempt->score, 0) }}%)
                        </span>
                        @endif
                        <span class="text-xs text-gray-400">
                            Terakhir: {{ $lastAttempt->completed_at->diffForHumans() }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto">
                    @if($lastAttempt?->passed)
                    <a href="{{ route('quiz.result', $lastAttempt) }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[13px] sm:text-sm font-bold rounded-full transition-colors flex-1 sm:flex-none">
                        Lihat Hasil
                    </a>
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 text-[13px] sm:text-sm font-bold rounded-full transition-colors flex-1 sm:flex-none">
                        Ulangi
                    </a>
                    @else
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-[13px] sm:text-sm font-bold rounded-full transition-colors shadow-sm">
                        Mulai Quiz <span aria-hidden="true">&rarr;</span>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

</div>
@endsection
