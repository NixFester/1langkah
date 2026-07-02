@extends('layouts.app')

@section('title', 'Kelola Kursus: ' . $course->title)

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div x-data="{
    activeTab: 'curriculum',
    expandedChapter: null,
    toggleChapter(chapterId) {
        this.expandedChapter = this.expandedChapter === chapterId ? null : chapterId;
    }
}" class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $course->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola detail kursus, bab pembelajaran, video, dan resources.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <a href="{{ route('admin.courses') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <a href="{{ route('detail-kursus', $course) }}" target="_blank" class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Lihat Kursus
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

    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">Detail Kursus</h3>
        </div>
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="p-6 space-y-4">
            @csrf
            @method('PATCH')
            <input type="hidden" name="title" value="{{ $course->title }}">
            <input type="hidden" name="mentor_name" value="{{ $course->mentor_name }}">
            <input type="hidden" name="mentor_company" value="{{ $course->mentor_company }}">
            <input type="hidden" name="category" value="{{ $course->category }}">
            <input type="hidden" name="level" value="{{ $course->level }}">
            <input type="hidden" name="price" value="{{ $course->price }}">
            <input type="hidden" name="color" value="{{ $course->color }}">

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                <input type="text" name="short_description" value="{{ old('short_description', $course->short_description ?? '') }}" placeholder="Ringkasan singkat kursus" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Lengkap</label>
                <textarea name="description" rows="4" placeholder="Deskripsi lengkap kursus" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors resize-none">{{ old('description', $course->description ?? '') }}</textarea>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2.5 px-6 rounded-full text-sm transition-colors shadow-lg shadow-red-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- TABS -->
    <div class="bg-gray-50 p-1.5 rounded-full flex w-full overflow-x-auto border border-gray-200">
        <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-bold whitespace-nowrap transition-all">Curriculum</button>
        <button @click="activeTab = 'videos'" :class="activeTab === 'videos' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Videos</button>
        <button @click="activeTab = 'resources'" :class="activeTab === 'resources' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Resources</button>
        <button @click="activeTab = 'pictures'" :class="activeTab === 'pictures' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Pictures</button>
    </div>

    <!-- CURRICULUM TAB -->
    <div x-show="activeTab === 'curriculum'">
        <!-- ADD CHAPTER FORM -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Bab Baru</h3>
            </div>
            <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Judul Bab <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="Contoh: Pengenalan HTML" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Lesson <span class="text-red-500">*</span></label>
                        <input type="number" name="lessons" min="1" placeholder="5" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Total Durasi <span class="text-red-500">*</span></label>
                        <input type="text" name="duration" placeholder="45 Menit" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat bab (opsional)" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 resize-none"></textarea>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Bab
                    </button>
                </div>
            </form>
        </div>

        <!-- CHAPTERS LIST -->
        @if(count($course->chapters) === 0)
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada bab</h3>
            <p class="text-gray-500 text-sm">Tambahkan bab pertama untuk kursus ini.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($course->chapters->sortBy('order') as $chapter)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <!-- Chapter Header -->
                <div @click="toggleChapter({{ $chapter->id }})" class="px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center font-bold text-lg">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $chapter->title }}</h4>
                            <p class="text-sm text-gray-500">{{ $chapter->lessons }} lessons &bull; {{ $chapter->duration }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                            {{ count($chapter->videos) }} video
                        </span>
                        <svg :class="expandedChapter === {{ $chapter->id }} ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Chapter Expanded Content -->
                <div x-show="expandedChapter === {{ $chapter->id }}" x-cloak class="border-t border-gray-100">
                    <div class="p-6 bg-gray-50/50">
                        <!-- Videos in this chapter -->
                        <div class="mb-4">
                            <h5 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Videos
                            </h5>

                            <!-- Add Video Form -->
                            <form method="POST" action="{{ route('admin.courses.chapters.videos.store', [$course, $chapter]) }}" class="mb-4 bg-white rounded-xl p-4 border border-gray-200">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                    <input type="text" name="title" placeholder="Judul Video" required class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                                    <input type="url" name="video_url" placeholder="URL Video (YouTube/Vimeo)" required class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                    <input type="text" name="duration" placeholder="Durasi (cth: 15:30)" class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                                    <input type="url" name="thumbnail_url" placeholder="Thumbnail URL (opsional)" class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                                </div>
                                <textarea name="description" rows="2" placeholder="Deskripsi (opsional)" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm mb-3 resize-none focus:ring-red-500 focus:border-red-500"></textarea>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded-lg transition-colors">
                                    + Tambah Video
                                </button>
                            </form>

                            <!-- Videos List -->
                            @if(count($chapter->videos) > 0)
                            <div class="space-y-2">
                                @foreach($chapter->videos as $video)
                                <div class="bg-white rounded-lg p-3 border border-gray-200 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-red-100 text-red-600 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $video->title }}</p>
                                            <p class="text-xs text-gray-500">{{ $video->duration ?? 'N/A' }} &bull; {{ Str::limit($video->video_url, 40) }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($video->video_url)
                                        <a href="{{ $video->video_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 p-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        </a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.courses.chapters.videos.destroy', [$course, $chapter, $video]) }}" onsubmit="return confirm('Hapus video ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 p-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <!-- Chapter Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <form method="POST" action="{{ route('admin.courses.chapters.destroy', [$course, $chapter]) }}" onsubmit="return confirm('Hapus bab ini beserta semua videonya?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Hapus Bab
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- VIDEOS TAB (All videos across chapters) -->
    <div x-show="activeTab === 'videos'" x-cloak>
        @php $allVideos = $course->chapters->flatMap->videos @endphp
        @if(count($allVideos) === 0)
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada video</h3>
            <p class="text-gray-500 text-sm">Tambahkan video melalui tab Curriculum.</p>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Semua Video ({{ count($allVideos) }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($course->chapters as $chapter)
                    @foreach($chapter->videos as $video)
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $video->title }}</p>
                                <p class="text-sm text-gray-500">{{ $chapter->title }} &bull; {{ $video->duration ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($video->video_url)
                            <a href="{{ $video->video_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Buka</a>
                            @endif
                            <form method="POST" action="{{ route('admin.courses.chapters.videos.destroy', [$course, $chapter, $video]) }}" onsubmit="return confirm('Hapus video ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- RESOURCES TAB -->
    <div x-show="activeTab === 'resources'" x-cloak>
        <!-- ADD RESOURCE FORM -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Resource</h3>
            </div>
            <form method="POST" action="{{ route('admin.courses.resources.store', $course) }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Judul Resource <span class="text-red-500">*</span></label>
                        <input type="text" name="title" placeholder="Contoh: Source Code Modul 1" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
                        <select name="type" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                            <option value="pdf">PDF</option>
                            <option value="zip">ZIP</option>
                            <option value="video">Video</option>
                            <option value="link">Link</option>
                            <option value="github">GitHub</option>
                            <option value="file">File</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">URL <span class="text-red-500">*</span></label>
                        <input type="url" name="url" placeholder="https://..." required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ukuran File (bytes, opsional)</label>
                        <input type="number" name="file_size" placeholder="1048576" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi resource (opsional)" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 resize-none"></textarea>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Resource
                    </button>
                </div>
            </form>
        </div>

        <!-- RESOURCES LIST -->
        @php $courseResourcesList = $course->courseResources @endphp
        @if($courseResourcesList->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 00-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada resource</h3>
            <p class="text-gray-500 text-sm">Tambahkan resource untuk kursus ini.</p>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Daftar Resource ({{ $courseResourcesList->count() }})</h3></h3>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($courseResourcesList as $resource)
                <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl @if($resource->type === 'pdf') bg-red-100 text-red-600 @elseif($resource->type === 'zip') bg-yellow-100 text-yellow-600 @elseif($resource->type === 'github') bg-gray-800 text-white @else bg-blue-100 text-blue-600 @endif flex items-center justify-center">
                            @if($resource->type === 'pdf')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @elseif($resource->type === 'zip')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            @elseif($resource->type === 'github')
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"></path></svg>
                            @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $resource->title }}</p>
                            <p class="text-sm text-gray-500">{{ strtoupper($resource->type) }} @if($resource->formatted_size) &bull; {{ $resource->formatted_size }} @endif</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($resource->url)
                        <a href="{{ $resource->url }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Buka</a>
                        @endif
                        <form method="POST" action="{{ route('admin.courses.resources.destroy', [$course, $resource]) }}" onsubmit="return confirm('Hapus resource ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- PICTURES TAB -->
    <div x-show="activeTab === 'pictures'" x-cloak>
        <!-- ADD PICTURE FORM -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Gambar</h3>
            </div>
            <form method="POST" action="{{ route('admin.pictures.store', ['course', $course->id]) }}" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">URL Gambar <span class="text-red-500">*</span></label>
                        <input type="url" name="image_url" placeholder="https://contoh.com/gambar.jpg" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl p-3">
                        <p class="text-xs text-gray-500 mt-1">Masukkan URL gambar dari CDN</p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tipe</label>
                        <select name="type" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                            <option value="gallery">Gallery</option>
                            <option value="thumbnail">Thumbnail</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                        <input type="text" name="description" placeholder="Deskripsi gambar (opsional)" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors flex items-center gap-2">
                        + Tambah Gambar
                    </button>
                </div>
            </form>
        </div>

        <!-- PICTURES GRID -->
        @php $pictures = $course->pictures()->orderBy('type')->get() @endphp
        @if(count($pictures) === 0)
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada gambar</h3>
            <p class="text-gray-500 text-sm">Tambahkan gambar untuk kursus ini.</p>
        </div>
        @else
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($pictures as $picture)
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden relative group">
                <div class="aspect-video bg-gray-100">
                    <img src="{{ $picture->url }}" alt="{{ $picture->description ?? 'Course image' }}" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 60%27%3E%3Crect fill=%27%23f3f4f6%27 width=%27100%27 height=%2760%27/%3E%3Ctext x=%2750%27 y=%2735%27 text-anchor=%27middle%27 fill=%27%239ca3af%27 font-family=%27sans-serif%27 font-size=%2712%27%3EGambar tidak ditemukan%3C/text%3E%3C/svg%3E'">
                </div>
                <div class="p-3">
                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-full {{ $picture->type === 'thumbnail' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($picture->type) }}
                    </span>
                    @if($picture->description)
                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $picture->description }}</p>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.pictures.destroy', $picture) }}" onsubmit="return confirm('Hapus gambar ini?')" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection