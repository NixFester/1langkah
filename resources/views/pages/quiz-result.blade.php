@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', __('app.quiz_result') . ' - 1Langkah')

@section('header_title', __('app.quiz_result'))

@section('content')
<div class="px-6 py-8 sm:px-10 w-full max-w-2xl mx-auto space-y-6">

    <!-- Result Card -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 md:p-8 text-center">
        <!-- Score Circle -->
        <div class="w-32 h-32 mx-auto mb-6 relative">
            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45" stroke="#e5e7eb" stroke-width="10" fill="none"/>
                <circle cx="50" cy="50" r="45"
                    stroke="{{ $attempt->passed ? '#22c55e' : '#ef4444' }}"
                    stroke-width="10"
                    fill="none"
                    stroke-linecap="round"
                    stroke-dasharray="{{ $attempt->score * 2.83 }} 283"
                    class="transition-all duration-1000"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-3xl font-bold text-gray-900">{{ number_format($attempt->score, 0) }}%</span>
            </div>
        </div>

        <!-- Result Message -->
        <h1 class="text-2xl font-bold mb-2 {{ $attempt->passed ? 'text-green-600' : 'text-red-600' }}">
            {{ $attempt->passed ? __('app.congrats_passed') : __('app.not_passed') }}
        </h1>

        <p class="text-gray-500 mb-6">
            @if($attempt->passed)
            {{ __('app.passed_message') }}
            @else
            {{ __('app.failed_message', ['score' => $quiz->passing_score ?? 70]) }}
            @endif
        </p>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-gray-900">{{ $attempt->correct_answers }}</div>
                <div class="text-sm text-gray-500">{{ __('app.correct_answers') }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-gray-900">{{ $attempt->total_questions }}</div>
                <div class="text-sm text-gray-500">{{ __('app.total_questions') }}</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-gray-900">{{ $quiz->passing_score ?? 70 }}%</div>
                <div class="text-sm text-gray-500">{{ __('app.passing_score') }}</div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @if(!$attempt->passed)
            <a href="{{ route('quiz.start', $quiz) }}"
                class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors">
                {{ __('app.try_again') }}
            </a>
            @endif
            <a href="{{ route('quiz.index') }}"
                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-full transition-colors">
                {{ __('app.back_to_quizzes') }}
            </a>
            <a href="{{ route('kursus-saya') }}"
                class="px-6 py-3 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium rounded-full transition-colors">
                {{ __('app.view_my_courses') }}
            </a>
        </div>
    </div>

    <!-- Quiz Info -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="font-bold text-gray-900 mb-4">{{ __('app.quiz_details') }}</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Quiz</span>
                <p class="font-medium text-gray-900">{{ $quiz->title }}</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.type') }}</span>
                <p class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $quiz->type) }}</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.completion_time') }}</span>
                <p class="font-medium text-gray-900">{{ $attempt->completed_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.duration') }}</span>
                <p class="font-medium text-gray-900">
                    @if($attempt->started_at && $attempt->completed_at)
                        {{ $attempt->started_at->diffInMinutes($attempt->completed_at) }} {{ __('app.minutes') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
