@extends('layouts.mentor')

@section('title', 'Kelola Kursus')

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER -->
    <x-page-header
        title="Kelola Kursus"
        description="Buat dan kelola kursus kamu"
        actionRoute="{{ route('mentor.courses.create') }}"
        actionLabel="Buat Kursus Baru"
    />

    @if($courses->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <x-empty-state
            message="Belum ada kursus. Mulai buat kursus pertamamu."
            icon="document"
            :actionRoute="route('mentor.courses.create')"
            actionLabel="Buat kursus pertama"
        />
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kursus</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Bab</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($courses as $course)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($course->thumbnail_url)
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold" style="background-color: {{ $course->color ?? '#3B82F6' }}">
                                        {{ substr($course->title, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $course->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $course->category }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                {{ ucfirst($course->level) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">{{ $course->chapters->count() }} bab</span>
                            <span class="text-gray-400 mx-1">•</span>
                            <span class="text-gray-600">{{ $course->chapters->sum(fn($c) => $c->videos->count()) }} video</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('mentor.courses.edit', $course) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('mentor.courses.destroy', $course) }}" class="m-0" onsubmit="return confirm('Hapus kursus ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $courses->links() }}
    @endif
</div>
@endsection
