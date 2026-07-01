@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', 'Quizzes - 1Langkah')

@php
use App\Models\TestAttempt;
@endphp

@section('header_title', 'Quiz Saya')

@section('content')
<div class="px-6 py-8 sm:px-10 w-full space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quiz Kursus</h1>
            <p class="text-sm text-gray-500 mt-1">Kumpulkan quiz untuk menyelesaikan kursus</p>
        </div>
        <a href="{{ route('quiz.history') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">
            Lihat Riwayat →
        </a>
    </div>

    @if($quizzesByCourse->isEmpty())
    <!-- No quizzes available -->
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Belum Ada Quiz Tersedia</h3>
        <p class="text-gray-500 mb-4">Daftar kursus untuk mengakses quiz.</p>
        <a href="{{ route('kursus') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-full transition-colors">
            Browse Kursus
        </a>
    </div>
    @endif

    <!-- Quizzes by Course -->
    @foreach($quizzesByCourse as $courseId => $courseQuizzes)
    @php
        $course = $courseQuizzes->first()->course;
    @endphp
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
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
            <div class="p-6 flex items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
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

                <div class="flex items-center gap-2">
                    @if($lastAttempt?->passed)
                    <a href="{{ route('quiz.result', $lastAttempt) }}"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-full transition-colors">
                        Lihat Hasil
                    </a>
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="px-4 py-2 bg-white border border-gray-200 hover:border-gray-300 text-gray-700 text-sm font-medium rounded-full transition-colors">
                        Ulangi Quiz
                    </a>
                    @else
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-full transition-colors">
                        Mulai Quiz →
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
