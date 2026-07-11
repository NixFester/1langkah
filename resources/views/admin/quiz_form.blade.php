@extends('layouts.app')

@section('title', isset($quiz) ? __('app.edit_quiz') : __('app.add_quiz_admin'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header :title="isset($quiz) ? __('app.edit_quiz') : __('app.add_new_quiz')" :description="__('app.quiz_form_desc')">
        <x-slot:actionSlot>
            <a href="{{ route('admin.quizzes') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('app.back') }}
            </a>
        </x-slot:actionSlot>
    </x-page-header>

    <x-flash-messages />

    <!-- FORM CARD -->
    <x-form-card>
        <form action="{{ isset($quiz) ? route('admin.quizzes.update', $quiz) : route('admin.quizzes.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($quiz))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.course') }} <span class="text-red-500">*</span></label>
                    <select name="course_id" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">{{ __('app.select_course') }}</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', isset($quiz) ? $quiz->course_id : '') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.quiz_title') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', isset($quiz) ? $quiz->title : '') }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors" :placeholder="__('app.example_quiz_title')">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.description_optional') }}</label>
                    <textarea name="description" rows="3" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors resize-y">{{ old('description', isset($quiz) ? $quiz->description : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.quiz_type') }} <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="pre_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'pre_test') ? 'selected' : '' }}>{{ __('app.pre_test') }}</option>
                        <option value="post_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'post_test') ? 'selected' : '' }}>{{ __('app.post_test') }}</option>
                        <option value="chapter_quiz" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'chapter_quiz') ? 'selected' : '' }}>{{ __('app.chapter_quiz') }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.passing_score_percent') }} <span class="text-red-500">*</span></label>
                    <input type="number" name="passing_score" value="{{ old('passing_score', isset($quiz) ? $quiz->passing_score : 70) }}" min="0" max="100" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.time_limit_optional') }}</label>
                    <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', isset($quiz) ? $quiz->time_limit_minutes : '') }}" min="1" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors" :placeholder="__('app.example_60')">
                    <p class="text-xs text-gray-500 mt-1">{{ __('app.leave_blank_no_time_limit') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.order') }}</label>
                    <input type="number" name="order" value="{{ old('order', isset($quiz) ? $quiz->order : 0) }}" min="0" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <div class="md:col-span-2 pt-2">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($quiz) ? $quiz->is_active : true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm font-bold text-gray-700">{{ __('app.active_quiz') }}</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('admin.quizzes') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 px-8 rounded-full text-sm transition-colors w-full sm:w-auto text-center">
                    {{ __('app.cancel') }}
                </a>
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto text-center">
                    {{ isset($quiz) ? __('app.save_changes') : __('app.add_quiz_plus') }}
                </button>
            </div>
        </form>
    </x-form-card>

</div>
@endsection
