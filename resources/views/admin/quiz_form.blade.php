@extends('layouts.app')

@section('title', isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz - Admin')

@section('content')
<div class="max-w-2xl">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('admin.quizzes') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Quizzes
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz Baru' }}</h1>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Form -->
    <form action="{{ isset($quiz) ? route('admin.quizzes.update', $quiz) : route('admin.quizzes.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
        @csrf
        @if(isset($quiz))
            @method('PUT')
        @endif

        <!-- Course -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Kursus</label>
            <select name="course_id" required class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                <option value="">Pilih Kursus</option>
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

        <!-- Title -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Judul Quiz</label>
            <input type="text" name="title" value="{{ old('title', isset($quiz) ? $quiz->title : '') }}" required
                class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Contoh: Pre-Test Full-Stack Web Development">
            @error('title')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (opsional)</label>
            <textarea name="description" rows="2"
                class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                placeholder="Deskripsi quiz...">{{ old('description', isset($quiz) ? $quiz->description : '') }}</textarea>
            @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Type & Passing Score Row -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Quiz</label>
                <select name="type" required class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="pre_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'pre_test' ? 'selected' : '') }}>Pre-Test</option>
                    <option value="post_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'post_test' ? 'selected' : '') }}>Post-Test</option>
                    <option value="chapter_quiz" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'chapter_quiz' ? 'selected' : '') }}>Chapter Quiz</option>
                </select>
                @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Passing Score -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Passing Score (%)</label>
                <input type="number" name="passing_score" value="{{ old('passing_score', isset($quiz) ? $quiz->passing_score : 70) }}" min="0" max="100"
                    class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                @error('passing_score')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Time Limit & Order Row -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Time Limit -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Time Limit (menit, opsional)</label>
                <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', isset($quiz) ? $quiz->time_limit_minutes : '') }}" min="1"
                    class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    placeholder="60">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan waktu</p>
                @error('time_limit_minutes')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                <input type="number" name="order" value="{{ old('order', isset($quiz) ? $quiz->order : 0) }}" min="0"
                    class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                @error('order')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Active Status -->
        <div class="flex items-center gap-3">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($quiz) ? $quiz->is_active : true) ? 'checked' : '' }}
                class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
            <label for="is_active" class="text-sm font-medium text-gray-700">Quiz Aktif</label>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-3 pt-4 border-t">
            <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                {{ isset($quiz) ? 'Simpan Perubahan' : 'Buat Quiz' }}
            </button>
            <a href="{{ route('admin.quizzes') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
