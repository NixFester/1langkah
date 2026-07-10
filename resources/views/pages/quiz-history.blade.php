@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', __('app.quiz_history') . ' - 1Langkah')

@section('header_title', __('app.quiz_history'))

@section('content')
<div class="px-0 sm:px-2 py-4 sm:py-8 w-full space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">{{ __('app.quiz_history') }}</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">{{ __('app.all_quizzes_taken') }}</p>
        </div>
        <a href="{{ route('quiz.index') }}" class="group bg-white border border-gray-200 hover:border-red-200 hover:bg-red-50 text-gray-700 hover:text-red-600 px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-[13px] sm:text-sm font-bold transition-all shadow-sm flex items-center gap-2 whitespace-nowrap w-max mt-2 sm:mt-0">
            <span class="group-hover:-translate-x-1 transition-transform">&larr;</span>
            {{ __('app.back_to_quizzes') }}
        </a>
    </div>

    @if($attempts->isEmpty())
    <!-- No history -->
    <x-empty-state
        :message="__('app.no_quizzes_taken')"
        icon="book"
        :actionRoute="route('quiz.index')"
        :actionLabel="__('app.start_quiz')"
    />
    @else
    <!-- History Table -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.type') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.score') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.status') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.date') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($attempts as $attempt)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $attempt->testable->title ?? __('app.course') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $typeLabels = [
                            'pre_test' => ['label' => 'Pre-Test', 'color' => 'bg-blue-100 text-blue-700'],
                            'post_test' => ['label' => 'Post-Test', 'color' => 'bg-green-100 text-green-700'],
                            'chapter_quiz' => ['label' => 'Chapter Quiz', 'color' => 'bg-purple-100 text-purple-700'],
                        ];
                        $type = $typeLabels[$attempt->test_type] ?? ['label' => 'Quiz', 'color' => 'bg-gray-100 text-gray-700'];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $type['color'] }}">
                            {{ $type['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900">{{ number_format($attempt->score, 1) }}%</span>
                        <span class="text-gray-400 text-sm">
                            ({{ $attempt->correct_answers }}/{{ $attempt->total_questions }})
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($attempt->passed)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                            ✓ {{ __('app.passed') }}
                        </span>
                        @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                            ✗ {{ __('app.failed') }}
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $attempt->completed_at?->format('d M Y, H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('quiz.result', $attempt) }}"
                            class="text-red-600 hover:text-red-700 font-medium">
                            {{ __('app.view') }} →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($attempts->hasPages())
    <div class="flex justify-center">
        {{ $attempts->links() }}
    </div>
    @endif
    @endif

</div>
@endsection
