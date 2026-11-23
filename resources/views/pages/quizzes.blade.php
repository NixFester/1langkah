@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', __('app.my_quizzes') . ' - 1Langkah')

@php
use App\Models\TestAttempt;
@endphp

@section('header_title', __('app.my_quizzes'))

@section('content')
<div class="w-full px-0 sm:px-2 pb-8 space-y-6 sm:space-y-8">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 -mt-2">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1 sm:mb-2 tracking-tight">Tes Psikologi & Evaluasi Kuis</h1>
            <p class="text-gray-500 text-sm sm:text-base">Ikuti tes kepribadian global untuk seluruh siswa atau evaluasi dari kursus Anda.</p>
        </div>
        <a href="{{ route('quiz.history') }}" class="group bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-700 hover:text-red-600 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-[13px] sm:text-sm font-bold transition-all shadow-sm flex items-center gap-2 w-max mt-2 sm:mt-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ __('app.quiz_history') }}
            <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
        </a>
    </div>

    <!-- Global Psychology / Personality Tests (Open to ALL Students) -->
    @if(isset($globalQuizzes) && $globalQuizzes->isNotEmpty())
    <div class="bg-white rounded-3xl border-2 border-red-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-[#cc0000] to-red-700 px-6 py-5 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-white/20 text-white uppercase tracking-wider mb-1">
                        Tes Global Seluruh Siswa
                    </div>
                    <h2 class="text-lg sm:text-xl font-extrabold text-white">Tes Psikologi & Kepribadian</h2>
                </div>
            </div>
            <div class="text-xs sm:text-sm text-red-100 font-medium">
                Tersedia Preview Gratis • Buka Hasil Penuh Rp 30.000
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($globalQuizzes as $quiz)
            @php
                $lastAttempt = TestAttempt::where('user_id', Auth::id())
                    ->where('quiz_id', $quiz->id)
                    ->whereNotNull('completed_at')
                    ->latest('completed_at')
                    ->first();
            @endphp
            <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2.5 mb-2">
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                            Tes Psikologi Global
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                            ⏱ Batas Waktu: {{ $quiz->time_limit_minutes ?? 1 }} Menit
                        </span>
                        <span class="text-xs text-gray-500 font-medium">
                            • Preview {{ $quiz->free_question_limit ?: 10 }} dari {{ $quiz->questions_count ?? 20 }} Soal (Full Akses: Rp {{ number_format($quiz->unlock_price ?: 30000, 0, ',', '.') }})
                        </span>
                    </div>

                    <h3 class="text-lg font-extrabold text-gray-900">{{ $quiz->title }}</h3>
                    @if($quiz->description)
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $quiz->description }}</p>
                    @endif

                    @if($lastAttempt)
                    <div class="mt-3 flex flex-wrap items-center gap-2.5">
                        @if($lastAttempt->is_paid)
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                ✓ Full Akses Terbuka (Lunas Rp {{ number_format($lastAttempt->payment_amount ?: 30000, 0, ',', '.') }})
                            </span>
                        @else
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                                🔒 Mode Preview (Belum Bayar Full Akses)
                            </span>
                        @endif
                        <span class="text-xs text-gray-400">
                            Terakhir dikerjakan {{ $lastAttempt->completed_at->diffForHumans() }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                    @if($lastAttempt)
                    <a href="{{ route('quiz.result', $lastAttempt) }}"
                        class="w-full sm:w-auto text-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs sm:text-sm font-bold rounded-full transition-colors">
                        {{ $lastAttempt->is_paid ? 'Lihat Hasil Penuh' : 'Lihat Preview / Buka Hasil' }}
                    </a>
                    @endif
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="w-full sm:w-auto flex justify-center items-center gap-2 px-6 py-3 bg-[#cc0000] hover:bg-red-700 text-white text-xs sm:text-sm font-bold rounded-full transition-colors shadow-sm">
                        <span>{{ $lastAttempt ? 'Kerjakan Ulang Tes' : 'Mulai Tes Psikologi' }}</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if((!isset($globalQuizzes) || $globalQuizzes->isEmpty()) && $quizzesByCourse->isEmpty())
    <!-- No quizzes available -->
    <x-empty-state
        :message="__('app.enroll_to_access_quiz')"
        icon="book"
        :actionRoute="route('kursus')"
        :actionLabel="__('app.browse_courses')"
    />
    @endif

    <!-- Quizzes by Course -->
    @foreach($quizzesByCourse as $courseId => $courseQuizzes)
    @php
        $course = $courseQuizzes->first()->course;
    @endphp
    @if($course)
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="bg-gray-50/50 px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($course->pictures->where('type', 'thumbnail')->first())
                    <img decoding="async" loading="lazy" alt="" src="{{ $course->pictures->where('type', 'thumbnail')->first()->url }}" class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full" style="background: linear-gradient(135deg, {{ $course->color ?? '#667eea' }}, {{ $course->color ?? '#667eea' }}cc);"></div>
                    @endif
                </div>
                <div>
                    <h2 class="font-bold text-gray-900">{{ $course->title }}</h2>
                    <p class="text-sm text-gray-500">{{ $courseQuizzes->count() }} {{ __('app.quizzes_available') }}</p>
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
                    ->where(function ($q) use ($quiz) {
                        $q->where('quiz_id', $quiz->id)
                            ->orWhere(function ($sub) use ($quiz) {
                                $sub->whereNull('quiz_id')
                                    ->where('testable_type', \App\Models\Course::class)
                                    ->where('testable_id', $quiz->course_id)
                                    ->where('test_type', $quiz->type);
                            });
                    })
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
                        <span class="text-xs text-gray-500">{{ $quiz->questions_count ?? 0 }} {{ __('app.questions_count') }}</span>
                        <span class="text-xs text-gray-500">•</span>
                        <span class="text-xs text-gray-500">{{ __('app.passing_label') }} {{ $quiz->passing_score }}%</span>
                    </div>
                    <h2 class="font-bold text-gray-900">{{ $quiz->title }}</h2>
                    @if($quiz->description)
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($quiz->description, 80) }}</p>
                    @endif

                    @if($lastAttempt)
                    <div class="mt-2 flex items-center gap-3">
                        @if($lastAttempt->passed)
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                            {{ __('app.passed_status') }} ({{ number_format($lastAttempt->score, 0) }}%)
                        </span>
                        @else
                        <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-bold rounded-full">
                            {{ __('app.failed_status') }} ({{ number_format($lastAttempt->score, 0) }}%)
                        </span>
                        @endif
                        <span class="text-xs text-gray-400">
                            {{ __('app.last_attempt') }} {{ $lastAttempt->completed_at->diffForHumans() }}
                        </span>
                    </div>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2 mt-2 sm:mt-0 w-full sm:w-auto">
                    @if($lastAttempt?->passed)
                    <a href="{{ route('quiz.result', $lastAttempt) }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-[13px] sm:text-sm font-bold rounded-full transition-colors flex-1 sm:flex-none">
                        {{ __('app.view_result') }}
                    </a>
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="w-full sm:w-auto text-center px-4 sm:px-5 py-2 sm:py-2.5 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 text-[13px] sm:text-sm font-bold rounded-full transition-colors flex-1 sm:flex-none">
                        {{ __('app.retake') }}
                    </a>
                    @else
                    <a href="{{ route('quiz.start', $quiz) }}"
                        class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-[13px] sm:text-sm font-bold rounded-full transition-colors shadow-sm">
                        {{ __('app.start_quiz') }} <span aria-hidden="true">&rarr;</span>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endforeach

</div>
@endsection
