@extends('layouts.mentor')

@section('title', isset($quiz) ? __('app.edit_quiz') : __('app.add_quiz_mentor'))
@section('header_title', isset($quiz) ? __('app.edit_quiz') : __('app.add_new_quiz'))

@section('content')
<div class="w-full px-2 pb-8">
    <div class="mb-6">
        <a href="{{ route('mentor.quizzes.index') }}" class="inline-flex items-center gap-2 text-[14px] text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back_to_quiz_list') }}
        </a>
    </div>

    <div class="page-title" style="margin-bottom:8px">{{ isset($quiz) ? __('app.edit_quiz') : __('app.add_new_quiz') }}</div>
    <p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">{{ __('app.quiz_form_desc_mentor') }}</p>

    <x-flash-messages />

    <div class="card" style="padding:24px">
        <form action="{{ isset($quiz) ? route('mentor.quizzes.update', $quiz) : route('mentor.quizzes.store') }}" method="POST">
            @csrf
            @if(isset($quiz))
                @method('PATCH')
            @endif

            <div class="section-title" style="margin-bottom:18px">{{ __('app.quiz_info') }}</div>

            <div class="input-group" style="margin-bottom:16px">
                <label>{{ __('app.select_course') }} <span style="color:#cc0000">*</span></label>
                <select aria-label="Course Id" name="course_id" class="input" required>
                    <option value="">{{ __('app.select_course') }}</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', isset($quiz) ? $quiz->course_id : '') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:16px">
                <label>{{ __('app.quiz_title') }} <span style="color:#cc0000">*</span></label>
                <input aria-label="Title" type="text" name="title" class="input" value="{{ old('title', isset($quiz) ? $quiz->title : '') }}" required placeholder="{{ __('app.example_quiz_title') }}" />
                @error('title')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:16px">
                <label>{{ __('app.description_optional') }}</label>
                <textarea aria-label="{{ __('app.quiz_desc_placeholder') }}" name="description" class="input" rows="3" placeholder="{{ __('app.quiz_desc_placeholder') }}">{{ old('description', isset($quiz) ? $quiz->description : '') }}</textarea>
                @error('description')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.quiz_type') }} <span style="color:#cc0000">*</span></label>
                    <select aria-label="Type" name="type" class="input" required>
                        <option value="pre_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'pre_test') ? 'selected' : '' }}>{{ __('app.pre_test') }}</option>
                        <option value="post_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'post_test') ? 'selected' : '' }}>{{ __('app.post_test') }}</option>
                        <option value="chapter_quiz" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'chapter_quiz') ? 'selected' : '' }}>{{ __('app.chapter_quiz') }}</option>
                    </select>
                    @error('type')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.passing_score_percent') }}</label>
                    <input aria-label="Passing Score" type="number" name="passing_score" class="input" value="{{ old('passing_score', isset($quiz) ? $quiz->passing_score : 70) }}" min="0" max="100" />
                    @error('passing_score')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.time_limit') }}</label>
                    <input aria-label="Time Limit Minutes" type="number" name="time_limit_minutes" class="input" value="{{ old('time_limit_minutes', isset($quiz) ? $quiz->time_limit_minutes : '') }}" min="1" placeholder="{{ __('app.leave_blank_no_time_limit') }}" />
                    @error('time_limit_minutes')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.order') }}</label>
                    <input aria-label="Order" type="number" name="order" class="input" value="{{ old('order', isset($quiz) ? $quiz->order : 0) }}" min="0" />
                    @error('order')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="input-group" style="margin-bottom:24px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:normal">
                    <input aria-label="Is Active" type="checkbox" name="is_active" value="1" {{ old('is_active', isset($quiz) ? $quiz->is_active : true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#cc0000" />
                    <span style="font-size:14px;color:var(--text-primary);font-weight:600">{{ __('app.quiz_active_available') }}</span>
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;border-top:1px solid var(--border-light);padding-top:20px">
                <a href="{{ route('mentor.quizzes.index') }}" class="btn btn-outline">{{ __('app.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ isset($quiz) ? __('app.save_changes') : __('app.create_quiz') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
