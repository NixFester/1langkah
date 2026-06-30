@extends('layouts.app')

@section('title', 'Kelola Kursus')

@section('content')
<div class="px-6 py-8 sm:px-10 w-full max-w-5xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $course->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola detail kursus, informasi mentor, dan bab pembelajaran.</p>
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
        <form method="POST" action="{{ route('admin.courses.update', $course) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Title -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Kursus <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $course->title) }}" placeholder="Masukkan judul kursus" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ old('category', $course->category) }}" placeholder="Contoh: Programming" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Mentor Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Mentor <span class="text-red-500">*</span></label>
                    <input type="text" name="mentor_name" value="{{ old('mentor_name', $course->mentor_name) }}" placeholder="Nama mentor" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Mentor Company -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Perusahaan Mentor <span class="text-red-500">*</span></label>
                    <input type="text" name="mentor_company" value="{{ old('mentor_company', $course->mentor_company) }}" placeholder="Perusahaan mentor" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Level -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Level <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="level" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                            <option value="">-- Pilih Level --</option>
                            <option value="Beginner" {{ old('level', $course->level) === 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('level', $course->level) === 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('level', $course->level) === 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                    <input type="text" name="price" value="{{ old('price', $course->price) }}" placeholder="Contoh: 799000 (tanpa Rp/titik)" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Warna (Opsional)</label>
                    <input type="text" name="color" value="{{ old('color', $course->color) }}" placeholder="Kode hex, cth: #667eea" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- CHAPTERS SECTION: TWO COLUMNS (Add form left, List right) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- ADD CHAPTER -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-1 h-fit">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Bab Baru</h3>
            </div>
            <form method="POST" action="{{ route('admin.courses.chapters.store', $course) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Bab <span class="text-red-500">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Pengenalan HTML" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Lesson <span class="text-red-500">*</span></label>
                    <input type="number" name="lessons" min="1" placeholder="Contoh: 5" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Total Durasi <span class="text-red-500">*</span></label>
                    <input type="text" name="duration" placeholder="Contoh: 45 Menit" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
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
                        <x-icon name="book" class="w-8 h-8" />
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada bab untuk kursus ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">Judul Bab</th>
                                <th class="px-6 py-4 font-bold text-center">Lessons</th>
                                <th class="px-6 py-4 font-bold text-right">Durasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($course->chapters as $index => $chapter)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                        <div class="text-sm font-bold text-gray-900">{{ $chapter->title }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $chapter->lessons }} Lesson</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="text-sm text-gray-500">{{ $chapter->duration }}</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
