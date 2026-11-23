@extends('layouts.app')

@section('title', 'Manage Global Quiz - Admin')

@php
    $initialTab = request('tab', (request()->anyFilled(['search', 'course_id', 'type']) ? 'quizzes' : 'config'));
@endphp

@section('content')
<div x-data="{ activeTab: '{{ $initialTab }}' }" class="w-full px-2 pb-12 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manage Global Quiz</h1>
            <p class="text-sm text-gray-500 mt-1">Konfigurasi Tes Psikologi Global, daftar kode diskon checkout (termasuk <code class="font-bold text-red-600">CONTOH</code>), buat quiz baru, dan rekap hasil peserta.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.quizzes.all-results') }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Hasil & Export ({{ $totalAttempts ?? 0 }})</span>
            </a>
            <a href="{{ route('admin.quizzes.all-results.export-csv') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <span>Export CSV</span>
            </a>
            <a href="{{ route('admin.quizzes.all-results.export-pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 bg-gray-900 hover:bg-black text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <span>Export PDF</span>
            </a>
            <a href="{{ route('admin.quizzes.create') }}" class="inline-flex items-center gap-2 bg-[#cc0000] hover:bg-red-700 text-white px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <span>+ Buat Quiz</span>
            </a>
        </div>
    </div>

    <!-- MAIN NAVIGATION TABS (Default: Current Configuration) -->
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-3">
        <button type="button" @click="activeTab = 'config'"
            :class="activeTab === 'config' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all cursor-pointer">
            ⚙️ Konfigurasi Global Quiz (Default)
        </button>

        <button type="button" @click="activeTab = 'promos'"
            :class="activeTab === 'promos' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <span>🎟️ Daftar Kode Diskon</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full bg-red-100 text-red-700 font-extrabold">{{ isset($promoCodes) ? $promoCodes->count() : 0 }}</span>
        </button>

        <button type="button" @click="activeTab = 'quizzes'"
            :class="activeTab === 'quizzes' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <span>📋 Daftar Semua Quiz & Buat Quiz</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full bg-gray-100 text-gray-700 font-extrabold">{{ $quizzes->total() }}</span>
        </button>

        <a href="{{ route('admin.quizzes.all-results') }}" class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 transition-all flex items-center gap-2">
            <span>📊 Hasil Peserta & Export PDF/CSV</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-50 text-emerald-700 font-extrabold">{{ $totalAttempts ?? 0 }}</span>
        </a>
    </div>

    <x-flash-messages />

    <!-- ========================================================================= -->
    <!-- TAB 1 (DEFAULT): CURRENT GLOBAL QUIZ CONFIGURATION & QUICK OVERVIEW        -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'config'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left: Current Global Quiz Configuration Form -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-gray-100">
                    <div>
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-red-100 text-red-700 uppercase">Current Configuration</span>
                        <h2 class="text-xl font-extrabold text-gray-900 mt-1">Konfigurasi Tes Psikologi Global Saat Ini</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Atur harga buka hasil penuh (Rp 30.000), jumlah soal gratis di mode preview, dan batas waktu pengerjaan.</p>
                    </div>
                    @if($globalQuiz)
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.quizzes.questions', $globalQuiz) }}" class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-extrabold transition-colors">
                            Kelola Soal ({{ $globalQuiz->questions_count ?? 0 }})
                        </a>
                        <a href="{{ route('quiz.checkout', $globalQuiz) }}" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-xl text-xs font-extrabold transition-colors">
                            Preview Checkout &rarr;
                        </a>
                    </div>
                    @endif
                </div>

                <form action="{{ route('admin.quizzes.global-config.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @if($globalQuiz)
                        <input type="hidden" name="quiz_id" value="{{ $globalQuiz->id }}">
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Judul Tes Psikologi Global <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $globalQuiz->title ?? 'Tes Psikologi & Pemetaan 4 Kepribadian Global 1Langkah') }}" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Tes</label>
                        <textarea name="description" rows="3"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:bg-white focus:border-red-500 outline-none">{{ old('description', $globalQuiz->description ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Harga Full Akses (Rp)</label>
                            <input type="number" name="unlock_price" value="{{ old('unlock_price', $globalQuiz->unlock_price ?? 30000) }}" min="0" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-extrabold text-red-600 focus:bg-white focus:border-red-500 outline-none">
                            <p class="text-[11px] text-gray-400 mt-1">Default: 30000 (Rp 30.000)</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Soal Gratis (Preview)</label>
                            <input type="number" name="free_question_limit" value="{{ old('free_question_limit', $globalQuiz->free_question_limit ?? 10) }}" min="1" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                            <p class="text-[11px] text-gray-400 mt-1">Misal: 10 dari {{ $globalQuiz->questions_count ?? 20 }} soal</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Batas Waktu (Menit)</label>
                            <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', $globalQuiz->time_limit_minutes ?? 1) }}" min="1" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                            <p class="text-[11px] text-gray-400 mt-1">Testing: 1 mnt | Prod: 15 mnt</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Passing Score (%)</label>
                            <input type="number" name="passing_score" value="{{ old('passing_score', $globalQuiz->passing_score ?? 70) }}" min="0" max="100" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                        </div>
                    </div>

                    <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-t border-gray-100">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $globalQuiz->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="ml-2 text-sm font-bold text-gray-800">Aktifkan Tes Global untuk Seluruh Siswa</span>
                        </label>

                        <button type="submit" class="px-7 py-3 bg-[#cc0000] hover:bg-red-700 text-white font-extrabold rounded-full text-sm transition-all shadow-md cursor-pointer">
                            Simpan Konfigurasi Global Quiz
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Quick Discount Codes Summary & Quick Actions -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Active Discount Codes Card -->
                <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">Kode Diskon Checkout</h3>
                            <p class="text-xs text-gray-500">Dapat dipakai siswa saat checkout tes</p>
                        </div>
                        <button type="button" @click="activeTab = 'promos'" class="text-xs font-extrabold text-[#cc0000] hover:underline cursor-pointer">
                            Kelola Semua &rarr;
                        </button>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($promoCodes->take(4) as $promo)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 bg-red-100 text-[#cc0000] rounded-md text-xs font-extrabold">{{ $promo->code }}</span>
                                    <span class="text-xs font-bold text-gray-800">{{ $promo->type_label }}</span>
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">{{ $promo->name }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $promo->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400">Belum ada kode diskon.</p>
                        @endforelse
                    </div>

                    <button type="button" @click="activeTab = 'promos'" class="mt-4 w-full py-2.5 px-4 bg-gray-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-colors cursor-pointer">
                        + Tambah / Kelola Kode Diskon
                    </button>
                </div>

                <!-- Quick Links Card -->
                <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-3">
                    <h3 class="text-base font-extrabold text-gray-900">Aksi Cepat Global Quiz</h3>
                    @if($globalQuiz)
                    <a href="{{ route('admin.quizzes.results', $globalQuiz) }}" class="w-full py-2.5 px-4 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-xl flex items-center justify-between transition-colors">
                        <span>Lihat Hasil Peserta ({{ $globalQuiz->attempts_count ?? 0 }})</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('admin.quizzes.questions', $globalQuiz) }}" class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-800 text-xs font-extrabold rounded-xl flex items-center justify-between transition-colors">
                        <span>Edit Daftar 20 Soal Psikologi</span>
                        <span>&rarr;</span>
                    </a>
                    @endif
                    <a href="{{ route('admin.quizzes.create') }}" class="w-full py-2.5 px-4 bg-red-50 hover:bg-red-100 text-[#cc0000] text-xs font-extrabold rounded-xl flex items-center justify-between transition-colors">
                        <span>+ Buat Quiz Baru</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: DISCOUNT CODES LIST & CREATE FORM (Includes "CONTOH")              -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'promos'" style="display: none;" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Add New Discount Code Form -->
            <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Tambah Kode Diskon</h2>
                <p class="text-xs text-gray-500 mb-5">Buat kode kupon potongan harga untuk halaman checkout pembayaran quiz.</p>

                <form action="{{ route('admin.quizzes.promos.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Kode Diskon <span class="text-red-500">*</span></label>
                        <input type="text" name="code" placeholder="Misal: CONTOH / DISKON50" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-extrabold uppercase tracking-wider text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nama Promo <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Misal: Diskon Testing Quiz 50%" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Tipe Potongan</label>
                            <select name="type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm text-gray-900 focus:border-red-500 outline-none">
                                <option value="percentage">Persentase (%)</option>
                                <option value="fixed_amount">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nilai <span class="text-red-500">*</span></label>
                            <input type="number" name="value" placeholder="50 atau 15000" min="1" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Batas Pemakaian (Opsional)</label>
                        <input type="number" name="max_uses" placeholder="Kosongkan jika tanpa batas" min="1"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#cc0000] hover:bg-red-700 text-white font-extrabold rounded-full text-sm transition-all shadow-sm cursor-pointer">
                        + Simpan Kode Diskon
                    </button>
                </form>
            </div>

            <!-- Discount Codes Table -->
            <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">Daftar Kode Diskon Aktif</h2>
                        <p class="text-xs text-gray-500">Kode <code class="font-bold text-red-600">CONTOH</code> sudah disiapkan otomatis untuk keperluan testing checkout.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3.5 font-bold">Kode</th>
                                <th class="px-5 py-3.5 font-bold">Nama Diskon</th>
                                <th class="px-5 py-3.5 font-bold">Potongan</th>
                                <th class="px-5 py-3.5 font-bold">Digunakan</th>
                                <th class="px-5 py-3.5 font-bold">Status</th>
                                <th class="px-5 py-3.5 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse($promoCodes as $promo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-red-100 text-[#cc0000] rounded-lg font-extrabold text-xs tracking-wider">{{ $promo->code }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-medium text-gray-900">{{ $promo->name }}</td>
                                <td class="px-5 py-3.5 font-extrabold text-gray-900 whitespace-nowrap">{{ $promo->type_label }}</td>
                                <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                    {{ $promo->used_count }} / {{ $promo->max_uses ?? '∞' }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $promo->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="{{ route('admin.quizzes.promos.toggle', $promo) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                                {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.quizzes.promos.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kode diskon {{ $promo->code }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada kode diskon.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: ALL QUIZZES LIST & CREATE QUIZ                                     -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'quizzes'" style="display: none;" class="space-y-6">

        <!-- SEARCH & FILTER BAR -->
        <form method="GET" action="{{ route('admin.quizzes') }}" class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <input type="hidden" name="tab" value="quizzes">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_quiz_placeholder') }}" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
            </div>
            <select name="course_id" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
                <option value="">{{ __('app.all_courses') }}</option>
                @foreach($courses ?? [] as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                @endforeach
            </select>
            <select name="type" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
                <option value="">{{ __('app.all_types') }}</option>
                <option value="pre_test" {{ request('type') === 'pre_test' ? 'selected' : '' }}>Pre-Test</option>
                <option value="post_test" {{ request('type') === 'post_test' ? 'selected' : '' }}>Post-Test</option>
                <option value="chapter_quiz" {{ request('type') === 'chapter_quiz' ? 'selected' : '' }}>Chapter Quiz</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl text-sm font-bold transition-colors">
                    {{ __('app.filter') }}
                </button>
                <a href="{{ route('admin.quizzes.create') }}" class="px-5 py-2.5 bg-[#cc0000] hover:bg-red-700 text-white rounded-xl text-sm font-bold transition-colors whitespace-nowrap">
                    + Buat Quiz
                </a>
            </div>
        </form>

        <!-- DATA TABLE -->
        <x-data-table :paginator="$quizzes">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.quiz') }}</th>
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Cakupan / Kursus</th>
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">Harga & Preview</th>
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.questions') }}</th>
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.participants') }}</th>
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold whitespace-nowrap">{{ __('app.status') }}</th>
                    <th class="px-4 md:px-6 py-3 md:py-4 font-bold text-right whitespace-nowrap">{{ __('app.action') }}</th>
                </tr>
            </thead>

            @forelse($quizzes as $quiz)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <a href="{{ route('admin.quizzes.results', $quiz) }}" class="font-bold text-gray-900 hover:text-red-600 transition-colors">{{ $quiz->title }}</a>
                    <div class="text-xs text-gray-400 mt-0.5">Timer: {{ $quiz->time_limit_minutes ?? '-' }} mnt • Passing: {{ $quiz->passing_score }}%</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    @if($quiz->is_global)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-red-100 text-red-800">
                            🌐 Tes Global (Semua Siswa)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $quiz->course->title ?? 'N/A' }}
                        </span>
                    @endif
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 text-xs text-gray-700 whitespace-nowrap">
                    <div class="font-bold text-gray-900">Rp {{ number_format($quiz->unlock_price ?: 30000, 0, ',', '.') }}</div>
                    <div class="text-gray-400">Preview: {{ $quiz->free_question_limit ?: 10 }} soal</div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-600 whitespace-nowrap">
                    {{ $quiz->questions_count }} {{ __('app.questions') }}
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <a href="{{ route('admin.quizzes.results', $quiz) }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors">
                        <span>{{ $quiz->attempts_count ?? 0 }} {{ __('app.participants') }}</span>
                        <span>&rarr;</span>
                    </a>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <x-stat-badge :status="$quiz->is_active ? 'active' : 'inactive'" />
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                    <div class="flex items-center justify-end gap-1.5">
                        <a href="{{ route('admin.quizzes.results', $quiz) }}" class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            <span>{{ __('app.quiz_results') }}</span>
                        </a>
                        <a href="{{ route('admin.quizzes.results.export-csv', $quiz) }}" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            CSV
                        </a>
                        <a href="{{ route('admin.quizzes.results.export-pdf', $quiz) }}" target="_blank" class="inline-flex items-center justify-center bg-gray-100 text-gray-700 hover:bg-gray-200 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            PDF
                        </a>
                        <a href="{{ route('admin.quizzes.questions', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.questions') }}
                        </a>
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.edit') }}
                        </a>
                        <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_quiz_confirm') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                {{ __('app.delete') }}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8">
                    <x-empty-state
                        :message="__('app.no_quizzes')"
                        icon="document"
                        :actionRoute="route('admin.quizzes.create')"
                        :actionLabel="__('app.create_first_quiz')"
                    />
                </td>
            </tr>
            @endforelse
        </x-data-table>

    </div>

</div>
@endsection
