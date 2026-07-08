@extends('layouts.mentor')

@section('title', isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz - Mentor')
@section('header_title', isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz Baru')

@section('content')
<div class="w-full px-2 pb-8">
    <div class="mb-6">
        <a href="{{ route('mentor.quizzes.index') }}" class="inline-flex items-center gap-2 text-[14px] text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Quiz
        </a>
    </div>

    <div class="page-title" style="margin-bottom:8px">{{ isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz Baru' }}</div>
    <p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">Formulir quiz untuk mengevaluasi pemahaman siswa di kursus Anda.</p>

    <x-flash-messages />

    <div class="card" style="padding:24px">
        <form action="{{ isset($quiz) ? route('mentor.quizzes.update', $quiz) : route('mentor.quizzes.store') }}" method="POST">
            @csrf
            @if(isset($quiz))
                @method('PATCH')
            @endif

            <div class="section-title" style="margin-bottom:18px">Informasi Quiz</div>

            <div class="input-group" style="margin-bottom:16px">
                <label>Pilih Kursus <span style="color:#cc0000">*</span></label>
                <select name="course_id" class="input" required>
                    <option value="">-- Pilih Kursus --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', isset($quiz) ? $quiz->course_id : '') == $course->id ? 'selected' : '' }}>
                            {{ $course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:16px">
                <label>Judul Quiz <span style="color:#cc0000">*</span></label>
                <input type="text" name="title" class="input" value="{{ old('title', isset($quiz) ? $quiz->title : '') }}" required placeholder="Contoh: Pre-Test Full-Stack Web Development" />
                @error('title')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:16px">
                <label>Deskripsi (Opsional)</label>
                <textarea name="description" class="input" rows="3" placeholder="Tuliskan petunjuk pengerjaan quiz...">{{ old('description', isset($quiz) ? $quiz->description : '') }}</textarea>
                @error('description')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>Tipe Quiz <span style="color:#cc0000">*</span></label>
                    <select name="type" class="input" required>
                        <option value="pre_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'pre_test') ? 'selected' : '' }}>Pre-Test</option>
                        <option value="post_test" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'post_test') ? 'selected' : '' }}>Post-Test</option>
                        <option value="chapter_quiz" {{ (old('type', isset($quiz) ? $quiz->type : '') == 'chapter_quiz') ? 'selected' : '' }}>Chapter Quiz</option>
                    </select>
                    @error('type')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>Passing Score (%)</label>
                    <input type="number" name="passing_score" class="input" value="{{ old('passing_score', isset($quiz) ? $quiz->passing_score : 70) }}" min="0" max="100" />
                    @error('passing_score')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>Time Limit (Menit)</label>
                    <input type="number" name="time_limit_minutes" class="input" value="{{ old('time_limit_minutes', isset($quiz) ? $quiz->time_limit_minutes : '') }}" min="1" placeholder="Kosongkan jika tanpa batas" />
                    @error('time_limit_minutes')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>Urutan Penampilan</label>
                    <input type="number" name="order" class="input" value="{{ old('order', isset($quiz) ? $quiz->order : 0) }}" min="0" />
                    @error('order')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="input-group" style="margin-bottom:24px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-weight:normal">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($quiz) ? $quiz->is_active : true) ? 'checked' : '' }} style="width:16px;height:16px;accent-color:#cc0000" />
                    <span style="font-size:14px;color:var(--text-primary);font-weight:600">Quiz Aktif & Tersedia untuk Siswa</span>
                </label>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:12px;border-top:1px solid var(--border-light);padding-top:20px">
                <a href="{{ route('mentor.quizzes.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">{{ isset($quiz) ? 'Simpan Perubahan' : 'Buat Quiz' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
