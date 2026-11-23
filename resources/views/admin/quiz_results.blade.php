@extends('layouts.app')

@section('title', __('app.quiz_results_export') . ($quiz ? ' - ' . $quiz->title : ''))

@php
    $filterAction = $quiz ? route('admin.quizzes.results', $quiz) : route('admin.quizzes.all-results');
    $csvExportUrl = ($quiz ? route('admin.quizzes.results.export-csv', $quiz) : route('admin.quizzes.all-results.export-csv')) . (request()->getQueryString() ? '?' . request()->getQueryString() : '');
    $pdfExportUrl = ($quiz ? route('admin.quizzes.results.export-pdf', $quiz) : route('admin.quizzes.all-results.export-pdf')) . (request()->getQueryString() ? '?' . request()->getQueryString() : '');
@endphp

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- HEADER & EXPORT ACTIONS -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.quizzes') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800 inline-flex items-center gap-1 mb-1.5">
                &larr; {{ __('app.back_to_quizzes') }}
            </a>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">
                {{ $quiz ? __('app.quiz_results') . ': ' . $quiz->title : __('app.quiz_results_export') }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                @if($quiz)
                    {{ $quiz->course->title ?? '-' }} • {{ __('app.passing_score') }}: {{ $quiz->passing_score }}%
                @else
                    {{ __('app.quiz_results_hub_desc') }}
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ $csvExportUrl }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Export CSV (.csv)</span>
            </a>
            <a href="{{ $pdfExportUrl }}" target="_blank" class="inline-flex items-center gap-2 bg-[#cc0000] hover:bg-red-700 text-white px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Export PDF (.pdf)</span>
            </a>
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-3">
        <a href="{{ route('admin.quizzes') }}" class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-all">
            {{ __('app.manage_quizzes') }}
        </a>
        <a href="{{ route('admin.quizzes.all-results') }}" class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold {{ !$quiz ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' }} transition-all">
            {{ __('app.all_quiz_results') }}
        </a>
        @if($quiz)
        <span class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-[#cc0000] text-white shadow-sm">
            {{ $quiz->title }}
        </span>
        @endif
    </div>

    <!-- SUMMARY STATS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('app.total_attempts') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider">{{ __('app.passed') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 mt-1">{{ $stats['passed'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
            <div class="text-xs font-bold text-red-600 uppercase tracking-wider">{{ __('app.failed') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-red-600 mt-1">{{ $stats['failed'] ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">
            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">{{ __('app.average_score') }}</div>
            <div class="text-2xl sm:text-3xl font-extrabold text-blue-600 mt-1">{{ $stats['avg_score'] ?? 0 }}%</div>
        </div>
    </div>

    <!-- SEARCH & FILTER BAR -->
    <form method="GET" action="{{ $filterAction }}" class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm space-y-3">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            <!-- Search Input -->
            <div class="md:col-span-4 relative">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_participant_placeholder') }}" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
            </div>

            @if(!$quiz)
            <!-- Course Filter -->
            <div class="md:col-span-3">
                <select name="course_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
                    <option value="">{{ __('app.all_courses') }}</option>
                    @foreach($courses ?? [] as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Quiz Filter -->
            <div class="md:col-span-3">
                <select name="quiz_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
                    <option value="">{{ __('app.all_quizzes') }}</option>
                    @foreach($quizzesList ?? [] as $qItem)
                        <option value="{{ $qItem->id }}" {{ request('quiz_id') == $qItem->id ? 'selected' : '' }}>{{ $qItem->title }} ({{ $qItem->course->title ?? '-' }})</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Status Filter -->
            <div class="{{ !$quiz ? 'md:col-span-2' : 'md:col-span-3' }}">
                <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
                    <option value="">{{ __('app.all_statuses') }}</option>
                    <option value="passed" {{ request('status') === 'passed' ? 'selected' : '' }}>{{ __('app.passed') }}</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('app.failed') }}</option>
                </select>
            </div>

            @if($quiz)
            <div class="md:col-span-2">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none" title="Dari Tanggal">
            </div>
            <div class="md:col-span-2">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none" title="Sampai Tanggal">
            </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
            @if(!$quiz)
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <span>Tanggal:</span>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-700 focus:border-red-500 outline-none">
                <span>s/d</span>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-1.5 text-xs text-gray-700 focus:border-red-500 outline-none">
            </div>
            @else
            <div></div>
            @endif

            <div class="flex items-center gap-2 ml-auto">
                @if(request()->anyFilled(['search', 'course_id', 'quiz_id', 'status', 'date_from', 'date_to']))
                    <a href="{{ $filterAction }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs sm:text-sm font-bold transition-colors">
                        Reset Filter
                    </a>
                @endif
                <button type="submit" class="px-6 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs sm:text-sm font-bold transition-colors">
                    {{ __('app.search_and_filter') }}
                </button>
            </div>
        </div>
    </form>

    <!-- RESULTS TABLE -->
    <x-data-table :paginator="$attempts">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-4 py-3 font-bold whitespace-nowrap">ID</th>
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.full_name') }}</th>
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.ttl') }}</th>
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.full_address') }}</th>
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.whatsapp_number') }}</th>
                @if(!$quiz)
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.quiz') }} / {{ __('app.course') }}</th>
                @endif
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.score') }}</th>
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.status') }}</th>
                <th class="px-4 py-3 font-bold whitespace-nowrap">{{ __('app.date') }}</th>
            </tr>
        </thead>

        @forelse($attempts as $attempt)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-xs font-mono text-gray-700 whitespace-nowrap">
                <div class="font-bold">QUIZ-{{ $attempt->id }}</div>
                <div class="text-[11px] text-gray-400">UID: {{ $attempt->user_id }}</div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                <div class="font-bold text-gray-900 text-sm">{{ $attempt->participant_name ?? $attempt->user?->name ?? '-' }}</div>
                <div class="text-xs text-blue-600 font-medium">{{ $attempt->google_email ?? $attempt->user?->email ?? '' }}</div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                {{ $attempt->formatted_ttl }}
            </td>
            <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                <div class="line-clamp-2" title="{{ $attempt->address }}">{{ $attempt->address ?? '-' }}</div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                @if($attempt->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $attempt->whatsapp)) }}" target="_blank" class="text-emerald-600 hover:underline font-medium">
                        {{ $attempt->whatsapp }}
                    </a>
                @else
                    -
                @endif
            </td>
            @if(!$quiz)
            <td class="px-4 py-3 whitespace-nowrap">
                <div class="text-sm font-bold text-gray-900">
                    @if($attempt->quiz)
                        <a href="{{ route('admin.quizzes.results', $attempt->quiz) }}" class="hover:text-red-600">{{ $attempt->quiz->title }}</a>
                    @else
                        {{ ucfirst(str_replace('_', ' ', $attempt->test_type)) }}
                    @endif
                </div>
                <div class="text-xs text-gray-500">{{ $attempt->quiz?->course?->title ?? $attempt->testable?->title ?? '-' }}</div>
            </td>
            @endif
            <td class="px-4 py-3 whitespace-nowrap">
                <div>
                    <span class="font-bold text-gray-900 text-sm">{{ number_format((float) $attempt->score, 1) }}%</span>
                    <span class="text-xs text-gray-400">({{ is_array($attempt->answers) ? count($attempt->answers) : $attempt->correct_answers }}/{{ $attempt->total_questions }})</span>
                </div>
                <div class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $attempt->personality_profile['archetype'] ?? '' }}</div>
            </td>
            <td class="px-4 py-3 whitespace-nowrap">
                @if($attempt->is_paid)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                        ✓ LUNAS (Rp {{ number_format($attempt->payment_amount ?: 30000, 0, ',', '.') }})
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                        🔒 PREVIEW (Belum Bayar)
                    </span>
                @endif
            </td>
            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                {{ $attempt->completed_at?->format('d/m/Y H:i') ?? '-' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="{{ !$quiz ? 9 : 8 }}" class="px-6 py-10 text-center text-sm text-gray-500">
                {{ __('app.no_quiz_results_yet') }}
            </td>
        </tr>
        @endforelse
    </x-data-table>
</div>
@endsection
