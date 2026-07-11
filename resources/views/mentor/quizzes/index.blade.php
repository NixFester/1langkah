@extends('layouts.mentor')

@section('title', __('app.manage_quiz_mentor'))
@section('header_title', __('app.manage_quizzes'))

@section('content')
<div class="w-full space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.manage_quizzes')"
        :description="__('app.manage_quiz_desc')"
        actionRoute="{{ route('mentor.quizzes.create') }}"
        :actionLabel="__('app.add_quiz')"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$quizzes">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.quiz') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.course') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.type') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.questions') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.passing_score') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.status') }}</th>
                <th class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">{{ __('app.action') }}</th>
            </tr>
        </template>

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
                    <a href="{{ route('mentor.quizzes.questions', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors" :title="__('app.manage_questions')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                    <a href="{{ route('mentor.quizzes.edit', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.edit') }}
                    </a>
                    <form method="POST" action="{{ route('mentor.quizzes.destroy', $quiz) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_quiz_confirm') }}')">
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
                    :actionRoute="route('mentor.quizzes.create')"
                    :actionLabel="__('app.create_first_quiz')"
                />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
