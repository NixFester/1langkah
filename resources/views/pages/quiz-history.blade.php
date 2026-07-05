@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', 'Riwayat Quiz - 1Langkah')

@section('header_title', 'Riwayat Quiz')

@section('content')
<div class="px-6 py-8 sm:px-10 w-full space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Quiz</h1>
            <p class="text-sm text-gray-500 mt-1">Semua quiz yang pernah kamu kerjakan</p>
        </div>
        <a href="{{ route('quiz.index') }}" class="text-red-600 hover:text-red-700 font-medium">
            Kembali ke Quiz →
        </a>
    </div>

    @if($attempts->isEmpty())
    <!-- No history -->
    <x-empty-state
        message="Kamu belum mengerjakan quiz apapun."
        icon="book"
        :actionRoute="route('quiz.index')"
        actionLabel="Mulai Quiz"
    />
    @else
    <!-- History Table -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($attempts as $attempt)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $attempt->testable->title ?? 'Kursus' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $typeLabels = [
                            'pre_test' => ['label' => 'Pre-Test', 'color' => 'bg-blue-100 text-blue-700'],
                            'post_test' => ['label' => 'Post-Test', 'color' => 'bg-green-100 text-green-700'],
                            'chapter_quiz' => ['label' => 'Chapter Quiz', 'color' => 'bg-purple-100 text-purple-700'],
                        ];
                        $type = $typeLabels[$attempt->test_type] ?? ['label' => 'Quiz', 'color' => 'bg-gray-100 text-gray-700'];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $type['color'] }}">
                            {{ $type['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900">{{ number_format($attempt->score, 1) }}%</span>
                        <span class="text-gray-400 text-sm">
                            ({{ $attempt->correct_answers }}/{{ $attempt->total_questions }})
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($attempt->passed)
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                            ✓ Lulus
                        </span>
                        @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                            ✗ Gagal
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $attempt->completed_at?->format('d M Y, H:i') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('quiz.result', $attempt) }}"
                            class="text-red-600 hover:text-red-700 font-medium">
                            Lihat →
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($attempts->hasPages())
    <div class="flex justify-center">
        {{ $attempts->links() }}
    </div>
    @endif
    @endif

</div>
@endsection
