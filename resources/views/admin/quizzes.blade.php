@extends('layouts.app')

@section('title', 'Course Quizzes - Admin')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.quizzes')"
        :description="__('app.quizzes_desc')"
        actionRoute="{{ route('admin.quizzes.create') }}"
        :actionLabel="__('app.add_quiz')"
    />

    <x-flash-messages />

    <!-- SEARCH & FILTER BAR -->
    <form method="GET" action="{{ route('admin.quizzes') }}" class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center gap-3">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_quiz_placeholder') }}" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
        </div>
        <select name="course_id" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
            <option value="">{{ __('app.all_courses') }}</option>
            @foreach($courses ?? [] as $course)
                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
            @endforeach
        </select>
        <select name="type" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
            <option value="">{{ __('app.all_types') }}</option>
            <option value="pre_test" {{ request('type') === 'pre_test' ? 'selected' : '' }}>Pre-Test</option>
            <option value="post_test" {{ request('type') === 'post_test' ? 'selected' : '' }}>Post-Test</option>
            <option value="chapter_quiz" {{ request('type') === 'chapter_quiz' ? 'selected' : '' }}>Chapter Quiz</option>
        </select>
        <div class="flex items-center gap-2">
            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl text-sm font-bold transition-colors">
                {{ __('app.filter') }}
            </button>
            @if(request()->anyFilled(['search', 'course_id', 'type']))
                <a href="{{ route('admin.quizzes') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-bold transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <!-- DATA TABLE -->
    <x-data-table :paginator="$quizzes">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.quiz') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.course') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.type') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.questions') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.passing_score') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.status') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-right whitespace-nowrap">{{ __('app.action') }}</th>
            </tr>
        </thead>

        @forelse($quizzes as $quiz)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <div class="font-medium text-gray-900">{{ $quiz->title }}</div>
                @if($quiz->description)
                    <div class="text-sm text-gray-500 mt-0.5 max-w-[200px] truncate">{{ $quiz->description }}</div>
                @endif
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $quiz->course->title ?? 'N/A' }}
                </span>
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <x-stat-badge :type="$quiz->type" />
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ $quiz->questions_count }} {{ __('app.questions') }}
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ $quiz->passing_score }}%
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <x-stat-badge :status="$quiz->is_active ? 'active' : 'inactive'" />
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors" title="{{ __('app.manage_questions') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.edit') }}
                    </a>
                    <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_quiz_confirm') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="px-6 py-8">
                <x-empty-state
                    :message="__('app.no_quizzes')"
                    icon="document"
                    :actionRoute="route('admin.quizzes.create')"
                    :actionLabel="__('app.create_first_quiz')"
                />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
