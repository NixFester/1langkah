@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', $quiz->title . ' - Quiz')

@section('header_title', $quiz->title)

@section('content')
<div class="px-6 py-8 sm:px-10 w-full max-w-3xl mx-auto space-y-6" x-data="quizTimer()">

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
                <span id="timer-display" :class="{'text-red-600 font-bold': timeRemaining <= 60, 'text-gray-600': timeRemaining > 60}">
                    <template x-if="timeRemaining > 0">
                        <span x-text="formatTime(timeRemaining)"></span>
                    </template>
                    <template x-if="timeRemaining <= 0">
                        <span>Waktu habis!</span>
                    </template>
                </span>
            </div>
            @endif
        </div>

        <!-- Timer Progress Bar -->
        @if($quiz->time_limit_minutes)
        <div class="mt-4">
            <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                <div
                    id="timer-progress"
                    class="h-full rounded-full transition-all duration-1000"
                    :class="{
                        'bg-green-500': timeRemaining > 300,
                        'bg-yellow-500': timeRemaining <= 300 && timeRemaining > 60,
                        'bg-red-500 animate-pulse': timeRemaining <= 60
                    }"
                    :style="'width: ' + ($quiz->time_limit_minutes * 60 > 0 ? Math.min(100, (timeRemaining / (' . ($quiz->time_limit_minutes * 60) . ')) * 100) : 100) + '%'">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-1" x-show="timeRemaining <= 60 && timeRemaining > 0" x-cloak>
                ⚠️ Sisa waktu kurang dari 1 menit!
            </p>
        </div>
        @endif

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
    <form action="{{ route('quiz.submit', $quiz) }}" method="POST" id="quizForm" @submit="handleSubmit">
        @csrf

        <!-- Hidden field for timer data -->
        @if($quiz->time_limit_minutes)
        <input type="hidden" name="started_at" :value="startedAt">
        <input type="hidden" name="time_spent_seconds" :value="timeSpent">
        @endif

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
            <div class="bg-white rounded-xl shadow-lg p-4 border border-gray-100">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm text-gray-500">
                        <span id="answered-count">0</span>/{{ $quiz->questions->count() }} dijawab
                    </div>
                    @if($quiz->time_limit_minutes)
                    <div class="text-sm" :class="{'text-red-600 font-bold': timeRemaining <= 60}">
                        <span x-text="formatTime(timeRemaining)"></span>
                    </div>
                    @endif
                    <button
                        type="submit"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors">
                        Submit Quiz
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Time Warning Modal -->
    <div x-show="showTimeWarning" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/50" @click="showTimeWarning = false"></div>
        <div class="relative bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl" @click.stop>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Waktu Hampir Habis!</h3>
                <p class="text-gray-600 mb-6">
                    Sisa waktu hanya <span class="font-bold text-red-600" x-text="formatTime(timeRemaining)"></span>.
                    Segera submit jawaban kamu!
                </p>
                <button @click="showTimeWarning = false" class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition-colors">
                    Lanjutkan Quiz
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function quizTimer() {
    return {
        timeRemaining: {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes * 60 : 0 }},
        startedAt: new Date().toISOString(),
        showTimeWarning: false,
        timeSpent: 0,
        interval: null,

        init() {
            // Only start timer if quiz has time limit
            if (this.timeRemaining > 0) {
                this.interval = setInterval(() => {
                    this.timeRemaining--;
                    this.timeSpent++;

                    // Show warning at 5 minutes
                    if (this.timeRemaining === 300) {
                        this.showTimeWarning = true;
                    }

                    // Show warning at 1 minute
                    if (this.timeRemaining === 60) {
                        this.showTimeWarning = true;
                    }

                    // Auto submit when time expires
                    if (this.timeRemaining <= 0) {
                        this.submitQuiz();
                    }
                }, 1000);
            }

            // Update answered count
            this.updateAnsweredCount();
            document.querySelectorAll('input[type="radio"]').forEach(input => {
                input.addEventListener('change', () => this.updateAnsweredCount());
            });
        },

        formatTime(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },

        updateAnsweredCount() {
            const checked = document.querySelectorAll('input[type="radio"]:checked').length;
            document.getElementById('answered-count').textContent = checked;
        },

        handleSubmit(e) {
            // Check if at least one answer is selected
            const selectedAnswers = document.querySelectorAll('input[type="radio"]:checked');
            if (selectedAnswers.length === 0) {
                e.preventDefault();
                alert('Pilih jawaban untuk setidaknya satu pertanyaan!');
                return false;
            }

            if (!confirm('Yakin ingin submit quiz? Kamu tidak bisa mengubah jawaban setelah submit.')) {
                e.preventDefault();
                return false;
            }

            // Stop the timer
            if (this.interval) {
                clearInterval(this.interval);
            }

            return true;
        },

        submitQuiz() {
            if (this.interval) {
                clearInterval(this.interval);
            }

            // Show warning that quiz is being submitted
            alert('Waktu quiz telah habis! Jawaban kamu akan disubmit otomatis.');

            // Submit the form
            document.getElementById('quizForm').submit();
        }
    }
}

// Update answered count on page load
document.addEventListener('DOMContentLoaded', function() {
    const checked = document.querySelectorAll('input[type="radio"]:checked').length;
    const countEl = document.getElementById('answered-count');
    if (countEl) {
        countEl.textContent = checked;
    }
});
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endpush
