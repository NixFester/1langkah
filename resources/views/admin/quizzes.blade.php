@extends('layouts.app')

@section('title', 'Quizzes - Admin')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('app.quizzes') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('app.quizzes_desc') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.quizzes.all-results') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>{{ __('app.quiz_results_export') }}</span>
            </a>
            <a href="{{ route('admin.quizzes.all-results.export-csv') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>CSV</span>
            </a>
            <a href="{{ route('admin.quizzes.all-results.export-pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 bg-gray-900 hover:bg-black text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>PDF</span>
            </a>
            <a href="{{ route('admin.quizzes.create') }}" class="inline-flex items-center gap-2 bg-[#cc0000] hover:bg-red-700 text-white px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <span>+ {{ __('app.add_quiz') }}</span>
            </a>
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    <div class="flex items-center gap-2 border-b border-gray-200 pb-3">
        <a href="{{ route('admin.quizzes') }}" class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-[#cc0000] text-white shadow-sm transition-all">
            {{ __('app.manage_quizzes') }} ({{ $quizzes->total() }})
        </a>
        <a href="{{ route('admin.quizzes.all-results') }}" class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-all flex items-center gap-2">
            <span>{{ __('app.quiz_results_export') }}</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full bg-red-50 text-red-600 font-extrabold">{{ $totalAttempts ?? 0 }}</span>
        </a>
    </div>

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
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.participants') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.status') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-right whitespace-nowrap">{{ __('app.action') }}</th>
            </tr>
        </thead>

        @forelse($quizzes as $quiz)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <a href="{{ route('admin.quizzes.results', $quiz) }}" class="font-bold text-gray-900 hover:text-red-600 transition-colors">{{ $quiz->title }}</a>
                <div class="text-xs text-gray-400 mt-0.5">Passing: {{ $quiz->passing_score }}%</div>
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
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <a href="{{ route('admin.quizzes.results', $quiz) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                    <span>{{ $quiz->attempts_count ?? 0 }} {{ __('app.participants') }}</span>
                    <span>&rarr;</span>
                </a>
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <x-stat-badge :status="$quiz->is_active ? 'active' : 'inactive'" />
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-1.5">
                    <a href="{{ route('admin.quizzes.results', $quiz) }}" class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors" title="{{ __('app.quiz_results') }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>{{ __('app.quiz_results') }}</span>
                    </a>
                    <a href="{{ route('admin.quizzes.results.export-csv', $quiz) }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors" title="Export CSV">
                        CSV
                    </a>
                    <a href="{{ route('admin.quizzes.results.export-pdf', $quiz) }}" target="_blank" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors" title="Export PDF">
                        PDF
                    </a>
                    <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors" title="{{ __('app.manage_questions') }}">
                        {{ __('app.questions') }}
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
