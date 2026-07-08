@extends('layouts.mentor')

@section('title', isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz - Mentor')

@section('header_title', isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz Baru')

@section('content')
<div class="w-full space-y-6 max-w-2xl">

    <!-- PAGE HEADER -->
    <x-page-header :title="isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz Baru'" description="Form quiz untuk kursus Anda">
        <x-slot:actionSlot>
            <a href="{{ route('mentor.quizzes.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Quiz
            </a>
        </x-slot:actionSlot>
    </x-page-header>

    <x-flash-messages />

    <!-- FORM CARD -->
    <x-form-card>
        <form action="{{ isset($quiz) ? route('mentor.quizzes.update', $quiz) : route('mentor.quizzes.store') }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($quiz))
                @method('PATCH')
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kursus</label>
                <select name="course_id" required class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Quiz</label>
                <input type="text" name="title" value="{{ old('title', isset($quiz) ? $quiz->title : '') }}" required class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Pre-Test Full-Stack Web Development">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi (opsional)</label>
                <textarea name="description" rows="2" class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', isset($quiz) ? $quiz->description : '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Quiz</label>
                    <select name="type" required class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="pre_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'pre_test') ? 'selected' : '' }}>Pre-Test</option>
                        <option value="post_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'post_test') ? 'selected' : '' }}>Post-Test</option>
                        <option value="chapter_quiz" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'chapter_quiz') ? 'selected' : '' }}>Chapter Quiz</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Passing Score (%)</label>
                    <input type="number" name="passing_score" value="{{ old('passing_score', isset($quiz) ? $quiz->passing_score : 70) }}" min="0" max="100" class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Time Limit (menit, opsional)</label>
                    <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', isset($quiz) ? $quiz->time_limit_minutes : '') }}" min="1" class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="60">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada batasan waktu</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan</label>
                    <input type="number" name="order" value="{{ old('order', isset($quiz) ? $quiz->order : 0) }}" min="0" class="w-full rounded-lg border-gray-200 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', isset($quiz) ? $quiz->is_active : true) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Quiz Aktif</label>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    {{ isset($quiz) ? 'Simpan Perubahan' : 'Buat Quiz' }}
                </button>
                <a href="{{ route('mentor.quizzes.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </x-form-card>

</div>
@endsection
