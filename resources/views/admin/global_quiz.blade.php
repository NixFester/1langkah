@extends('layouts.app')

@section('title', 'Manage Global Quiz - Admin')

@php
    $initialTab = request('tab', (request()->anyFilled(['search', 'payment_status']) ? 'results' : 'config'));
@endphp

@section('content')
<div x-data="{ activeTab: '{{ $initialTab }}', showCreateModal: false }" class="w-full px-2 pb-12 space-y-6">

    <!-- PAGE HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-red-100 text-[#cc0000] uppercase tracking-wider mb-1">
                Tes Psikologi & Kepribadian Global
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Manage Global Quiz</h1>
            <p class="text-sm text-gray-500 mt-0.5">Konfigurasi Tes Psikologi Global (terpisah dari kuis kursus), daftar kode diskon (<code class="font-bold text-red-600">CONTOH</code>), buat quiz baru, & rekap hasil peserta.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.global-quiz.results.export-csv') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span>Export CSV</span>
            </a>
            <a href="{{ route('admin.global-quiz.results.export-pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 bg-gray-900 hover:bg-black text-white px-4 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Export PDF</span>
            </a>
            <button type="button" @click="activeTab = 'create'" class="inline-flex items-center gap-2 bg-[#cc0000] hover:bg-red-700 text-white px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all shadow-sm cursor-pointer">
                <span>+ Buat Quiz</span>
            </button>
        </div>
    </div>

    <!-- NAVIGATION TABS (Default: Current Configuration) -->
    <div class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-3">
        <button type="button" @click="activeTab = 'config'"
            :class="activeTab === 'config' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all cursor-pointer">
            ⚙️ Current Configuration
        </button>

        <button type="button" @click="activeTab = 'promos'"
            :class="activeTab === 'promos' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <span>🎟️ List of Discount Codes</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full bg-red-100 text-red-700 font-extrabold">{{ $promoCodes->count() }}</span>
        </button>

        <button type="button" @click="activeTab = 'create'"
            :class="activeTab === 'create' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all cursor-pointer">
            ➕ Buat Quiz
        </button>

        <button type="button" @click="activeTab = 'results'"
            :class="activeTab === 'results' ? 'bg-[#cc0000] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50'"
            class="px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer">
            <span>📊 Hasil Peserta & Export</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full bg-emerald-100 text-emerald-800 font-extrabold">{{ $attempts->total() }}</span>
        </button>
    </div>

    <x-flash-messages />

    <!-- ========================================================================= -->
    <!-- TAB 1 (DEFAULT): CURRENT CONFIGURATION + DISCOUNT CODES OVERVIEW          -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'config'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Left Column (8 cols): Current Global Quiz Configuration -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 mb-6 border-b border-gray-100">
                        <div>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-red-100 text-red-700 uppercase">Current Configuration</span>
                            <h2 class="text-xl font-extrabold text-gray-900 mt-1">Konfigurasi Global Quiz Saat Ini</h2>
                            <p class="text-xs sm:text-sm text-gray-500">Pengaturan utama Tes Psikologi Global untuk seluruh siswa.</p>
                        </div>

                        @if($globalQuizzes->count() > 1)
                        <div>
                            <form method="GET" action="{{ route('admin.global-quiz') }}">
                                <select name="quiz_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-bold text-gray-800">
                                    @foreach($globalQuizzes as $gq)
                                        <option value="{{ $gq->id }}" {{ $currentGlobalQuiz?->id === $gq->id ? 'selected' : '' }}>
                                            {{ $gq->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        @endif
                    </div>

                    @if($currentGlobalQuiz)
                    <form action="{{ route('admin.global-quiz.config.update') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="quiz_id" value="{{ $currentGlobalQuiz->id }}">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Judul Global Quiz <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $currentGlobalQuiz->title) }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi Tes</label>
                            <textarea name="description" rows="3"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:bg-white focus:border-red-500 outline-none">{{ old('description', $currentGlobalQuiz->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Harga Full Akses (Rp)</label>
                                <input type="number" name="unlock_price" value="{{ old('unlock_price', $currentGlobalQuiz->unlock_price ?? 30000) }}" min="0" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-extrabold text-red-600 focus:bg-white focus:border-red-500 outline-none">
                                <p class="text-[11px] text-gray-400 mt-1">Default: Rp 30.000</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Batas Soal Preview</label>
                                <input type="number" name="free_question_limit" value="{{ old('free_question_limit', $currentGlobalQuiz->free_question_limit ?? 10) }}" min="1" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                                <p class="text-[11px] text-gray-400 mt-1">Dari total {{ $currentGlobalQuiz->questions_count }} soal</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Batas Waktu (Menit)</label>
                                <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', $currentGlobalQuiz->time_limit_minutes ?? 1) }}" min="1" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                                <p class="text-[11px] text-gray-400 mt-1">Testing: 1 mnt | Normal: 15 mnt</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Passing Score (%)</label>
                                <input type="number" name="passing_score" value="{{ old('passing_score', $currentGlobalQuiz->passing_score ?? 70) }}" min="0" max="100" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $currentGlobalQuiz->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                <span class="ml-2 text-sm font-bold text-gray-800">Aktifkan Global Quiz Ini untuk Seluruh Siswa</span>
                            </label>

                            <div class="flex flex-wrap items-center gap-2.5">
                                <a href="{{ route('admin.quizzes.questions', $currentGlobalQuiz) }}" class="px-5 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-extrabold rounded-full text-xs sm:text-sm transition-colors">
                                    Kelola {{ $currentGlobalQuiz->questions_count }} Soal Psikologi
                                </a>
                                <button type="submit" class="px-6 py-2.5 bg-[#cc0000] hover:bg-red-700 text-white font-extrabold rounded-full text-xs sm:text-sm transition-all shadow-md cursor-pointer">
                                    Simpan Konfigurasi
                                </button>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-sm mb-4">Belum ada Global Quiz yang dibuat.</p>
                        <button type="button" @click="activeTab = 'create'" class="px-6 py-2.5 bg-[#cc0000] text-white font-bold rounded-full text-sm">
                            + Buat Global Quiz Sekarang
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column (4 cols): List of Discount Codes & Quick Test Links -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Discount Codes Summary Card -->
                <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-900">List of Discount Codes</h3>
                            <p class="text-xs text-gray-500">Kode diskon untuk halaman Checkout Quiz</p>
                        </div>
                        <button type="button" @click="activeTab = 'promos'" class="text-xs font-extrabold text-[#cc0000] hover:underline cursor-pointer">
                            Kelola Semua &rarr;
                        </button>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($promoCodes as $promo)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="px-2.5 py-0.5 bg-red-100 text-[#cc0000] rounded-md text-xs font-extrabold tracking-wider">{{ $promo->code }}</span>
                                    <span class="text-xs font-extrabold text-gray-900">{{ $promo->type_label }}</span>
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
                        + Tambah / Edit Kode Diskon
                    </button>
                </div>

                <!-- Quick Links to Test Student Flow -->
                @if($currentGlobalQuiz)
                <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-3">
                    <h3 class="text-base font-extrabold text-gray-900">Pintasan Testing & Soal</h3>
                    <a href="{{ route('quiz.checkout', $currentGlobalQuiz) }}" target="_blank" class="w-full py-2.5 px-4 bg-red-50 hover:bg-red-100 text-[#cc0000] text-xs font-extrabold rounded-xl flex items-center justify-between transition-colors">
                        <span>💳 Lihat Halaman Checkout Quiz</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('quiz.start', $currentGlobalQuiz) }}" target="_blank" class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-extrabold rounded-xl flex items-center justify-between transition-colors">
                        <span>📝 Lihat Halaman Tes Siswa</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('admin.quizzes.questions', $currentGlobalQuiz) }}" class="w-full py-2.5 px-4 bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-extrabold rounded-xl flex items-center justify-between transition-colors">
                        <span>❓ Kelola Daftar Soal ({{ $currentGlobalQuiz->questions_count }})</span>
                        <span>&rarr;</span>
                    </a>
                </div>
                @endif
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: LIST OF DISCOUNT CODES (Includes "CONTOH")                         -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'promos'" style="display: none;" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Add Discount Code Form -->
            <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
                <h2 class="text-lg font-extrabold text-gray-900 mb-1">Tambah Kode Diskon</h2>
                <p class="text-xs text-gray-500 mb-5">Buat kode kupon untuk halaman checkout pembayaran Global Quiz.</p>

                <form action="{{ route('admin.global-quiz.promos.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Kode Diskon <span class="text-red-500">*</span></label>
                        <input type="text" name="code" placeholder="Contoh: CONTOH / DISKON50" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-extrabold uppercase tracking-wider text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nama Promo <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Diskon Testing Quiz 50%" required
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
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-lg font-extrabold text-gray-900">List of Discount Codes</h2>
                    <p class="text-xs text-gray-500">Kode diskon <code class="font-bold text-red-600">CONTOH</code> tersedia untuk pengujian potongan harga di halaman checkout.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-5 py-3.5 font-bold">Kode</th>
                                <th class="px-5 py-3.5 font-bold">Nama Diskon</th>
                                <th class="px-5 py-3.5 font-bold">Potongan</th>
                                <th class="px-5 py-3.5 font-bold">Dipakai</th>
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
                                        <form action="{{ route('admin.global-quiz.promos.toggle', $promo) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors cursor-pointer">
                                                {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.global-quiz.promos.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kode diskon {{ $promo->code }}?')">
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
    <!-- TAB 3: BUAT GLOBAL QUIZ BARU                                              -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'create'" style="display: none;" class="max-w-3xl">
        <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-xl font-extrabold text-gray-900 mb-1">Buat Global Quiz Baru</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-6">Global Quiz ini berdiri sendiri untuk seluruh siswa dan tidak terikat pada kursus tertentu.</p>

            <form action="{{ route('admin.global-quiz.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Judul Global Quiz <span class="text-red-500">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Tes Psikologi & Minat Bakat Global" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan tujuan tes psikologi global ini..."
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:bg-white focus:border-red-500 outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Harga Full Akses (Rp)</label>
                        <input type="number" name="unlock_price" value="30000" min="0" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-extrabold text-red-600 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Batas Soal Preview</label>
                        <input type="number" name="free_question_limit" value="10" min="1" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Batas Waktu (Menit)</label>
                        <input type="number" name="time_limit_minutes" value="15" min="1" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Passing Score (%)</label>
                        <input type="number" name="passing_score" value="70" min="0" max="100" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-red-500 outline-none">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm font-bold text-gray-800">Langsung Aktifkan</span>
                    </label>

                    <button type="submit" class="px-7 py-3 bg-[#cc0000] hover:bg-red-700 text-white font-extrabold rounded-full text-sm transition-all shadow-md cursor-pointer">
                        + Buat Global Quiz & Tambah Soal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: HASIL PESERTA GLOBAL QUIZ & EXPORT PDF/CSV                         -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'results'" style="display: none;" class="space-y-6">

        <form method="GET" action="{{ route('admin.global-quiz') }}" class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <input type="hidden" name="tab" value="results">
            <div class="relative flex-1">
                <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_participant_placeholder') }}" class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-red-500 outline-none transition-all">
            </div>
            <select name="payment_status" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm text-gray-700 focus:border-red-500 outline-none">
                <option value="">Semua Status Bayar</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas (Full Akses)</option>
                <option value="preview" {{ request('payment_status') === 'preview' ? 'selected' : '' }}>Preview (Belum Bayar)</option>
            </select>
            <button type="submit" class="px-5 py-2.5 bg-gray-900 hover:bg-black text-white rounded-xl text-sm font-bold transition-colors">
                Cari & Filter
            </button>
        </form>

        <x-data-table :paginator="$attempts">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-bold whitespace-nowrap">ID</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">Nama & Email Google</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">TTL</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">Alamat</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">No. WA</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">Kepribadian & Skor</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">Status Bayar</th>
                    <th class="px-4 py-3 font-bold whitespace-nowrap">Tanggal</th>
                </tr>
            </thead>

            @forelse($attempts as $attempt)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 text-xs font-mono text-gray-700 whitespace-nowrap">
                    <div class="font-bold">QUIZ-{{ $attempt->id }}</div>
                    <div class="text-[11px] text-gray-400">UID: {{ $attempt->user_id }}</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="font-bold text-gray-900 text-sm">{{ $attempt->participant_name ?? $attempt->user?->name ?? '-' }}</div>
                    <div class="text-xs text-blue-600 font-medium">{{ $attempt->google_email ?? $attempt->user?->email ?? '' }}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ $attempt->formatted_ttl }}
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs">
                    <div class="line-clamp-2" title="{{ $attempt->address }}">{{ $attempt->address ?? '-' }}</div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-700 whitespace-nowrap">
                    {{ $attempt->whatsapp ?? '-' }}
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="font-bold text-gray-900 text-sm">{{ $attempt->personality_profile['archetype'] ?? '-' }}</div>
                    <div class="text-xs text-gray-500">Skor: {{ number_format((float) $attempt->score, 0) }}% ({{ is_array($attempt->answers) ? count($attempt->answers) : $attempt->correct_answers }}/{{ $attempt->total_questions }} soal)</div>
                </td>
                <td class="px-4 py-3 whitespace-nowrap">
                    @if($attempt->is_paid)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            ✓ LUNAS (Rp {{ number_format($attempt->payment_amount ?: 30000, 0, ',', '.') }})
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            🔒 PREVIEW
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                    {{ $attempt->completed_at?->format('d/m/Y H:i') ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                    Belum ada hasil peserta Global Quiz.
                </td>
            </tr>
            @endforelse
        </x-data-table>

    </div>

</div>
@endsection
