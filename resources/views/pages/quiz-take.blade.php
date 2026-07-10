@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', $quiz->title . ' - Quiz')
@section('header_title', __('app.take_quiz'))

@section('content')
<div x-data="quizTimer()" class="w-full max-w-4xl mx-auto pb-24 pt-2">
    
    <!-- Quiz Info Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ $quiz->title }}</h1>
            @if($quiz->description)
            <p class="text-gray-500 text-sm leading-relaxed max-w-2xl">{{ $quiz->description }}</p>
            @endif
            
            @if($existingAttempt)
            <div class="mt-4 inline-flex items-center gap-2 bg-blue-50 border border-blue-100 rounded-lg px-3 py-2 text-xs text-blue-800">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ __('app.previous_score') }} <strong class="font-bold">{{ number_format($existingAttempt->score, 0) }}%</strong></span>
            </div>
            @endif
        </div>

        <div class="flex items-center gap-4 sm:gap-6 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-4 md:pt-0 border-gray-100">
            <div class="text-left md:text-right">
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('app.progress') }}</div>
                <div class="text-sm font-bold text-gray-900"><span id="answered-count">0</span> / {{ $quiz->questions->count() }} {{ __('app.answered_count') }}</div>
            </div>
            @if($quiz->time_limit_minutes)
            <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-2.5 flex items-center gap-3">
                <div class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </div>
                <span class="font-mono font-bold text-red-600 text-lg sm:text-xl tracking-tight" id="timer-display" x-text="formatTime(timeRemaining)" :class="{'animate-pulse text-red-700': timeRemaining <= 60}"></span>
            </div>
            @endif
        </div>
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('quiz.submit', $quiz) }}" method="POST" id="quizForm" @submit="handleSubmit">
        @csrf
        @if($quiz->time_limit_minutes)
        <input type="hidden" name="started_at" :value="startedAt">
        <input type="hidden" name="time_spent_seconds" :value="timeSpent">
        @endif

        <div class="space-y-8">
            @foreach($quiz->questions->sortBy('order') as $index => $question)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 hover:shadow-md transition-shadow">
                
                <!-- Question Header -->
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 font-bold flex items-center justify-center flex-shrink-0 text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="pt-1.5 flex-1">
                            <h3 class="text-[17px] font-semibold text-gray-900 leading-relaxed">{{ $question->question }}</h3>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 sm:pl-0 pl-14 flex-shrink-0">
                        @if($question->is_required)
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold uppercase tracking-wider rounded-md">{{ __('app.required') }}</span>
                        @endif
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold uppercase tracking-wider rounded-md">{{ $question->points }} {{ __('app.points') }}</span>
                    </div>
                </div>

                <!-- Answers -->
                <div class="ml-0 sm:ml-14">
                    @if(in_array($question->type, ['multiple_choice', 'true_false']))
                        <div class="grid gap-3">
                            @foreach($question->answers->sortBy('order') as $answer)
                            <label class="group relative flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 transition-all duration-200">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" class="absolute opacity-0 w-0 h-0" required>
                                <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-red-600 flex items-center justify-center bg-white mr-3.5 mt-0.5 transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-600 scale-0 group-has-[:checked]:scale-100 transition-transform"></div>
                                </div>
                                <span class="text-gray-700 font-medium text-sm sm:text-[15px] group-has-[:checked]:text-red-900 leading-snug w-full block">
                                    {{ $answer->answer_text }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    @elseif($question->type === 'essay')
                        <textarea name="answers[{{ $question->id }}]" rows="5" 
                            style="box-sizing: border-box;"
                            class="w-full border-2 border-gray-200 rounded-xl p-4 text-gray-700 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none resize-y transition-all bg-gray-50 focus:bg-white" 
                            placeholder="{{ __('app.write_answer_here') }}" required></textarea>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Floating Submit Button -->
        <button type="submit" class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 z-50 px-8 py-4 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold rounded-full transition-all shadow-[0_8px_30px_rgba(220,38,38,0.4)] hover:shadow-[0_12px_40px_rgba(220,38,38,0.5)] hover:-translate-y-1 flex items-center justify-center gap-2 group">
            <span>{{ __('app.finish_and_submit') }}</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </button>
    </form>
</div>

<!-- Time Warning Modal -->
<div x-show="showTimeWarning" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm" x-transition.opacity>
    <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl" @click.stop x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-red-50 flex items-center justify-center text-red-600 border-4 border-red-100">
            <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('app.time_running_out') }}</h3>
        <p class="text-gray-600 mb-6 text-sm">{{ __('app.time_remaining_less_than') }}<span class="font-bold text-red-600" x-text="formatTime(timeRemaining)"></span>.</p>
        <button @click="showTimeWarning = false" type="button" class="w-full py-3.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl transition-colors">
            {{ __('app.resume_quiz') }}
        </button>
    </div>
</div>

@endsection

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
            if (this.timeRemaining > 0) {
                this.interval = setInterval(() => {
                    this.timeRemaining--;
                    this.timeSpent++;
                    if (this.timeRemaining === 300 || this.timeRemaining === 60) {
                        this.showTimeWarning = true;
                    }
                    if (this.timeRemaining <= 0) {
                        this.submitQuiz();
                    }
                }, 1000);
            }
            
            // Initial count
            this.updateAnsweredCount();
            
            // Listen to inputs
            document.querySelectorAll('input[type="radio"], textarea').forEach(el => {
                el.addEventListener('change', () => this.updateAnsweredCount());
                el.addEventListener('keyup', () => this.updateAnsweredCount());
            });
            
            // Prevent accidental horizontal scroll restorations
            window.scrollTo({ left: 0, behavior: 'instant' });
        },

        formatTime(seconds) {
            if (seconds < 0) return '00:00';
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        updateAnsweredCount() {
            let answered = 0;
            const groups = {};
            document.querySelectorAll('input[type="radio"]:checked').forEach(r => groups[r.name] = true);
            document.querySelectorAll('textarea').forEach(t => { if(t.value.trim().length > 0) groups[t.name] = true; });
            const count = Object.keys(groups).length;
            
            const deskCount = document.getElementById('answered-count');
            const mobCount = document.getElementById('answered-count-mobile');
            if(deskCount) deskCount.textContent = count;
            if(mobCount) mobCount.textContent = count;
        },

        handleSubmit(e) {
            const total = {{ $quiz->questions->count() }};
            const answered = document.getElementById('answered-count') ? parseInt(document.getElementById('answered-count').textContent) : 0;
            
            if (answered < total) {
                const msg = `{{ __('app.confirm_incomplete_submit') }}`.replace(':answered', answered).replace(':total', total);
                if (!confirm(msg)) {
                    e.preventDefault();
                    return false;
                }
            } else {
                if (!confirm(`{{ __('app.confirm_submit') }}`)) {
                    e.preventDefault();
                    return false;
                }
            }
            if (this.interval) clearInterval(this.interval);
            return true;
        },

        submitQuiz() {
            if (this.interval) clearInterval(this.interval);
            alert(`{{ __('app.time_up_auto_submit') }}`);
            document.getElementById('quizForm').submit();
        }
    }
}
</script>
@endpush
