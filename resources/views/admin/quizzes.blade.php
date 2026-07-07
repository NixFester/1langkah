@extends('layouts.app')

@section('title', 'Quizzes - Admin')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        title="Quizzes"
        description="Kelola quiz untuk setiap kursus"
        actionRoute="{{ route('admin.quizzes.create') }}"
        actionLabel="Tambah Quiz"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$quizzes">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Quiz</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Kursus</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Tipe</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Questions</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Passing Score</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Status</th>
                <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-right whitespace-nowrap">Aksi</th>
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
                {{ $quiz->questions_count }} questions
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ $quiz->passing_score }}%
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <x-stat-badge :status="$quiz->is_active ? 'active' : 'inactive'" />
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors" title="Manage Questions">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </a>
                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="m-0" onsubmit="return confirm('Hapus quiz ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="px-6 py-8">
                <x-empty-state
                    message="Belum ada quiz."
                    icon="document"
                    :actionRoute="route('admin.quizzes.create')"
                    actionLabel="Buat quiz pertama"
                />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
