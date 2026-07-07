@extends('layouts.mentor')

@section('title', 'Kelola Kursus')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Kursus</h1>
            <p class="text-sm text-gray-500">Buat dan kelola kursus kamu</p>
        </div>
        <a href="{{ route('mentor.courses.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Kursus Baru
        </a>
    </div>

    @if($courses->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Kursus</h3>
        <p class="text-gray-500 mb-6">Mulai buat kursus pertamamu</p>
        <a href="{{ route('mentor.courses.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
            Buat Kursus Baru
        </a>
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
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('mentor.courses.edit', $course) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('mentor.courses.destroy', $course) }}" class="inline" onsubmit="return confirm('Hapus kursus ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
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
