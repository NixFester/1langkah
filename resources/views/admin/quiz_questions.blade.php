@extends('layouts.app')

@section('title', 'Questions - ' . $quiz->title . ' - Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.quizzes') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                {{ __('app.back_to_quizzes') }}
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $quiz->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $quiz->course->title ?? 'N/A' }} • {{ ucfirst(str_replace('_', ' ', $quiz->type)) }}</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        {{ session('success') }}
    </div>
    @endif

    <!-- Add Question Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('app.add_new_question') }}</h2>
        <form action="{{ route('admin.quizzes.questions.add', $quiz) }}" method="POST" class="grid grid-cols-12 gap-4">
            @csrf
            <div class="col-span-12 md:col-span-6">
                <input aria-label="{{ __('app.write_question') }}" type="text" name="question" required
                    class="w-full min-w-0 rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="{{ __('app.write_question') }}">
            </div>
            <div class="col-span-12 md:col-span-2">
                <select aria-label="Type" name="type" class="w-full min-w-0 rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="multiple_choice">{{ __('app.multiple_choice') }}</option>
                    <option value="true_false">{{ __('app.true_false') }}</option>
                    <option value="essay">{{ __('app.essay') }}</option>
                </select>
            </div>
            <div class="col-span-6 md:col-span-1">
                <input aria-label="Pts" type="number" name="points" value="1" min="1"
                    class="w-full min-w-0 rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Pts">
            </div>
            <div class="col-span-6 md:col-span-1">
                <input aria-label="Order" type="number" name="order" value="{{ $quiz->questions->count() + 1 }}" min="0"
                    class="w-full min-w-0 rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="Order">
            </div>
            <div class="col-span-12 md:col-span-2">
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    {{ __('app.add') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Questions List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @forelse($quiz->questions->sortBy('order') as $question)
        <div class="border-b border-gray-100 last:border-b-0">
            <!-- Question Header -->
            <div class="p-6">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-sm font-bold text-gray-600 flex-shrink-0">
                                {{ $loop->iteration }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                {{ $question->type }}
                            </span>
                            <span class="text-xs text-gray-500 flex-shrink-0">{{ $question->points }} pts</span>
                            @if($question->is_required)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 flex-shrink-0">{{ __('app.required') }}</span>
                            @endif
                        </div>
                        <p class="text-gray-900 font-medium">{{ $question->question }}</p>
                        @if($question->explanation)
                        <p class="text-sm text-gray-500 mt-1 italic">{{ __('app.explanation') }}: {{ $question->explanation }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.quizzes.questions.delete', [$quiz, $question]) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('app.delete_question_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="{{ __('app.delete') }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-9V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Answers (for multiple choice / true_false) -->
            @if(in_array($question->type, ['multiple_choice', 'true_false']))
            <div class="px-6 pb-6">
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    @foreach($question->answers->sortBy('order') as $answer)
                    <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                        <form action="{{ route('admin.quizzes.answers.update', [$quiz, $question, $answer]) }}" method="POST" class="flex-1 flex items-center gap-2 sm:gap-3 min-w-0">
                            @csrf
                            @method('PUT')
                            <input aria-label="Answer Text" type="text" name="answer_text" value="{{ $answer->answer_text }}" required
                                class="flex-1 min-w-0 rounded-lg border border-gray-200 px-2 sm:px-3 py-1.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <label class="flex items-center gap-1.5 sm:gap-2 text-xs sm:text-sm flex-shrink-0">
                                <input aria-label="Is Correct" type="checkbox" name="is_correct" value="1" {{ $answer->is_correct ? 'checked' : '' }}
                                    onchange="this.form.submit()"
                                    class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500 flex-shrink-0">
                                <span class="text-gray-600">{{ __('app.correct') }}</span>
                            </label>
                        </form>
                        <form action="{{ route('admin.quizzes.answers.delete', [$quiz, $question, $answer]) }}" method="POST" class="inline flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </form>
                    </div>
                    @endforeach

                    <!-- Add Answer -->
                    <form action="{{ route('admin.quizzes.answers.add', [$quiz, $question]) }}" method="POST" class="flex items-center gap-2 pt-2 border-t border-gray-200 min-w-0">
                        @csrf
                        <input aria-label="{{ __('app.add_answer_option') }}" type="text" name="answer_text" required
                            class="flex-1 min-w-0 rounded-lg border border-gray-200 px-3 py-1.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            placeholder="{{ __('app.add_answer_option') }}">
                        <button type="submit" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors flex-shrink-0">
                            +
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p>{{ __('app.no_questions_yet') }}</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
