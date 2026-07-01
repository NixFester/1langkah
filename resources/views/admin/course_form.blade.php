@extends('layouts.app')

@section('title', isset($course) ? 'Kelola Kursus' : 'Tambah Kursus')

@section('content')
<div x-data="{
    chapters: [],
    addChapter() {
        this.chapters.push({ title: '', lessons: 1, duration: '', video_url: '', thumbnail_url: '', description: '' });
    },
    removeChapter(index) {
        this.chapters.splice(index, 1);
    }
}" class="px-6 py-8 sm:px-10 w-full max-w-5xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($course) ? 'Kelola Kursus: ' . $course->title : 'Tambah Kursus Baru' }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ isset($course) ? 'Kelola detail kursus, informasi mentor, dan bab pembelajaran.' : 'Form ini menampung data kursus baru.' }}
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.courses') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <ul class="text-sm font-medium list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- COURSE DETAILS FORM CARD -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Detail Kursus</h3>
        </div>
        <form method="POST" action="{{ isset($course) ? route('admin.courses.update', $course) : route('admin.courses.store') }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @if(isset($course))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Title -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Kursus <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $course->title ?? '') }}" placeholder="Masukkan judul kursus" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category', $course->category ?? '') }}" placeholder="Contoh: Programming" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Mentor Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Mentor <span class="text-red-500">*</span></label>
                    <input type="text" name="mentor_name" value="{{ old('mentor_name', $course->mentor_name ?? '') }}" placeholder="Nama mentor" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Mentor Company -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Perusahaan Mentor <span class="text-red-500">*</span></label>
                    <input type="text" name="mentor_company" value="{{ old('mentor_company', $course->mentor_company ?? '') }}" placeholder="Perusahaan mentor" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Level -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Level <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="level" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                            <option value="">-- Pilih Level --</option>
                            <option value="Beginner" {{ old('level', $course->level ?? '') === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('level', $course->level ?? '') === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('level', $course->level ?? '') === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                    <input type="text" name="price" value="{{ old('price', $course->price ?? '') }}" placeholder="Contoh: 799000 (tanpa Rp/titik)" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Warna (Opsional)</label>
                    <input type="text" name="color" value="{{ old('color', $course->color ?? '') }}" placeholder="Kode hex, cth: #667eea" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
            </div>

            <!-- Hidden chapters data for submission -->
            <template x-for="(chapter, index) in chapters" :key="index">
                <div>
                    <input type="hidden" :name="'chapters[' + index + '][title]'" :value="chapter.title">
                    <input type="hidden" :name="'chapters[' + index + '][lessons]'" :value="chapter.lessons">
                    <input type="hidden" :name="'chapters[' + index + '][duration]'" :value="chapter.duration">
                    <input type="hidden" :name="'chapters[' + index + '][video_url]'" :value="chapter.video_url">
                    <input type="hidden" :name="'chapters[' + index + '][thumbnail_url]'" :value="chapter.thumbnail_url">
                    <input type="hidden" :name="'chapters[' + index + '][description]'" :value="chapter.description">
                </div>
            </template>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($course) ? 'Simpan Perubahan' : '+ Tambah Kursus' }}
                </button>
            </div>
        </form>
    </div>

    @if(!isset($course))
    <!-- ADD CHAPTERS SECTION (Only shown when creating new course) -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Tambah Bab Pembelajaran</h3>
                <p class="text-xs text-gray-500 mt-1">Tambahkan bab untuk kursus ini (opsional)</p>
            </div>
            <button type="button" @click="addChapter()" class="bg-gray-900 hover:bg-black text-white text-sm font-bold py-2 px-4 rounded-full flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Bab
            </button>
        </div>

        <!-- Dynamic Chapters List -->
        <div class="p-6 space-y-4" x-show="chapters.length > 0">
            <template x-for="(chapter, index) in chapters" :key="index">
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 relative">
                    <button type="button" @click="removeChapter(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Judul Bab <span class="text-red-500">*</span></label>
                            <input type="text" x-model="chapter.title" placeholder="Contoh: Pengenalan HTML" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Lesson <span class="text-red-500">*</span></label>
                            <input type="number" x-model="chapter.lessons" min="1" placeholder="5" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Durasi <span class="text-red-500">*</span></label>
                            <input type="text" x-model="chapter.duration" placeholder="45 Menit" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Video URL</label>
                            <input type="url" x-model="chapter.video_url" placeholder="https://youtube.com/watch?v=xxx" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                            <textarea x-model="chapter.description" rows="2" placeholder="Deskripsi singkat bab (opsional)" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900 resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="chapters.length === 0" class="p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <p class="text-gray-500 text-sm">Klik "Tambah Bab" untuk menambahkan bab pembelajaran</p>
        </div>
    </div>
    @endif

    @if(isset($course))
    <!-- CHAPTERS SECTION (Shown when editing existing course) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ADD CHAPTER -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-1 h-fit">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Bab Baru</h3>
                <p class="text-xs text-gray-500 mt-1">Video URL dan Thumbnail bersifat opsional</p>
            </div>
            <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Bab <span class="text-red-500">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Pengenalan HTML" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Lesson <span class="text-red-500">*</span></label>
                    <input type="number" name="lessons" min="1" placeholder="5" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Total Durasi <span class="text-red-500">*</span></label>
                    <input type="text" name="duration" placeholder="Contoh: 45 Menit" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Video URL</label>
                    <input type="url" name="video_url" placeholder="https://youtube.com/watch?v=xxx" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Thumbnail URL</label>
                    <input type="url" name="thumbnail_url" placeholder="https://contoh.com/thumbnail.jpg" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat bab (opsional)" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors resize-none"></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors shadow-lg w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Bab
                    </button>
                </div>
            </form>
        </div>

        <!-- LIST CHAPTERS -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-2">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Daftar Bab Pembelajaran</h3>
                <span class="bg-gray-200 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full">{{ $course->chapters->count() }} Bab</span>
            </div>

            @if($course->chapters->isEmpty())
                <div class="p-8 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada bab untuk kursus ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">Thumbnail</th>
                                <th class="px-6 py-4 font-bold">Judul Bab</th>
                                <th class="px-6 py-4 font-bold text-center">Lessons</th>
                                <th class="px-6 py-4 font-bold text-center">Durasi</th>
                                <th class="px-6 py-4 font-bold text-center">Video</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($course->chapters as $index => $chapter)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="w-12 h-8 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                                        @if($chapter->thumbnail_url)
                                        <img src="{{ $chapter->thumbnail_url }}" class="w-full h-full object-cover" alt="">
                                        @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">{{ $chapter->title }}</div>
                                            @if($chapter->description)
                                            <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($chapter->description, 40) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $chapter->lessons }} Lesson</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm text-gray-500">{{ $chapter->duration }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($chapter->video_url)
                                    <a href="{{ $chapter->video_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 inline-flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </a>
                                    @else
                                    <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
    @endif

</div>
@endsection
