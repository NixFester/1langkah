@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', $quiz->title . ' - Quiz')

@section('header_title', $quiz->title)

@section('content')
<div class="px-6 py-8 sm:px-10 w-full max-w-3xl mx-auto space-y-6">

    <!-- Quiz Header -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    @php
                    $typeLabels = [
                        'pre_test' => ['label' => 'Pre-Test', 'color' => 'bg-blue-100 text-blue-700'],
                        'post_test' => ['label' => 'Post-Test', 'color' => 'bg-green-100 text-green-700'],
                        'chapter_quiz' => ['label' => 'Chapter Quiz', 'color' => 'bg-purple-100 text-purple-700'],
                    ];
                    $type = $typeLabels[$quiz->type] ?? ['label' => 'Quiz', 'color' => 'bg-gray-100 text-gray-700'];
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $type['color'] }}">
                        {{ $type['label'] }}
                    </span>
                    <span class="text-xs text-gray-500">Kursus: {{ $quiz->course->title ?? 'N/A' }}</span>
                </div>
                <h1 class="text-xl font-bold text-gray-900">{{ $quiz->title }}</h1>
                @if($quiz->description)
                <p class="text-sm text-gray-500 mt-1">{{ $quiz->description }}</p>
                @endif
            </div>
            <a href="{{ route('quiz.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <div class="flex flex-wrap gap-4 text-sm text-gray-500">
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $quiz->questions->count() }} Pertanyaan
            </div>
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Passing: {{ $quiz->passing_score }}%
            </div>
            @if($quiz->time_limit_minutes)
            <div class="flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $quiz->time_limit_minutes }} menit
            </div>
            @endif
        </div>

        <!-- Previous Attempt Warning -->
        @if($existingAttempt)
        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full {{ $existingAttempt->passed ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center flex-shrink-0">
                    @if($existingAttempt->passed)
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    @else
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    @endif
                </div>
                <div>
                    <p class="font-medium {{ $existingAttempt->passed ? 'text-green-700' : 'text-red-700' }}">
                        Kamu sudah pernah mengerjakan quiz ini
                    </p>
                    <p class="text-sm text-gray-600">
                        Score: <strong>{{ number_format($existingAttempt->score, 0) }}%</strong>
                        ({{ $existingAttempt->correct_answers }}/{{ $existingAttempt->total_questions }} benar)
                    </p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('quiz.submit', $quiz) }}" method="POST" id="quizForm">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-100 divide-y divide-gray-50">
            @foreach($quiz->questions->sortBy('order') as $index => $question)
            <div class="p-6">
                <div class="flex items-start gap-3 mb-4">
                    <span class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $question->question }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $question->points }} poin
                            @if($question->is_required)
                            • Wajib dijawab
                            @endif
                        </p>
                    </div>
                </div>

                @if(in_array($question->type, ['multiple_choice', 'true_false']))
                <div class="space-y-2 ml-11">
                    @foreach($question->answers->sortBy('order') as $answer)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-red-200 hover:bg-red-50/30 cursor-pointer transition-colors has-[:checked]:border-red-300 has-[:checked]:bg-red-50/50">
                        <input type="radio"
                            name="answers[{{ $question->id }}]"
                            value="{{ $answer->id }}"
                            class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500"
                            required>
                        <span class="text-gray-700">{{ $answer->answer_text }}</span>
                    </label>
                    @endforeach
                </div>
                @elseif($question->type === 'essay')
                <div class="ml-11">
                    <textarea
                        name="answers[{{ $question->id }}]"
                        rows="4"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Tulis jawaban kamu di sini..."></textarea>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <div class="sticky bottom-6 mt-6">
            <button type="submit"
                class="w-full py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors shadow-lg">
                Submit Quiz
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
document.getElementById('quizForm').addEventListener('submit', function(e) {
    const requiredQuestions = document.querySelectorAll('input[type="radio"]:required');
    let allAnswered = true;

    // Check if all required questions are answered
    const questionGroups = {};
    requiredQuestions.forEach(radio => {
        const name = radio.name;
        if (!questionGroups[name]) questionGroups[name] = false;
        if (radio.checked) questionGroups[name] = true;
    });

    for (const group in questionGroups) {
        if (!questionGroups[group]) {
            allAnswered = false;
            break;
        }
    }

    if (!allAnswered) {
        e.preventDefault();
        alert('Pastikan semua pertanyaan wajib dijawab!');
        return false;
    }

    return confirm('Yakin ingin submit quiz? Kamu tidak bisa mengubah jawaban setelah submit.');
});
</script>
@endpush
@endsection
