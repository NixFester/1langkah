@extends('layouts.app', ['activePage' => 'admin.siap.index'])

@section('title', 'Kelola Soal ' . $assessment->title . ' - Admin')
@section('header_title', 'Kelola Soal SIAP')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@php
    $categoriesBySlug = $assessment->categories->keyBy('slug');
@endphp

@section('content')
<div x-data="{ showCreate: false, editingId: null, searchQuery: '', categoryFilter: '' }"
     class="w-full max-w-5xl mx-auto px-1 sm:px-2 pb-12 space-y-6">

    <!-- Header -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.siap.index', ['assessment_id' => $assessment->id]) }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-500 hover:text-[#cc0000] transition">
                <span>&larr; Kembali ke Manage SIAP</span>
            </a>
            <div class="flex flex-wrap items-center gap-2.5 mt-2">
                <h1 class="font-display text-2xl font-extrabold text-gray-900">{{ $assessment->title }}</h1>
                <span class="px-2.5 py-1 rounded-full bg-red-50 border border-red-100 text-xs font-bold text-[#cc0000]">
                    {{ $assessment->questions->count() }} Soal
                </span>
                <span class="px-2.5 py-1 rounded-full bg-gray-100 text-xs font-semibold text-gray-700">
                    {{ $assessment->isForcedChoice() ? 'Pilihan A–E (5 Bentuk)' : 'Skala Likert 1–5 (8 Kecerdasan)' }}
                </span>
            </div>
        </div>

        <button type="button" @click="showCreate = !showCreate"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#cc0000] hover:bg-red-700 text-white text-sm font-bold shadow-sm transition cursor-pointer">
            <span x-text="showCreate ? 'Tutup Form' : '+ Tambah Pertanyaan'"></span>
        </button>
    </div>

    <x-flash-messages />

    <!-- Collapsible Add Question Form -->
    <div x-show="showCreate" x-cloak x-transition class="bg-white rounded-3xl border-2 border-red-100 p-6 shadow-sm">
        <h2 class="text-base font-extrabold text-gray-900 mb-4">Tambah Pertanyaan Baru</h2>
        <form action="{{ route('admin.siap.questions.store', $assessment) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Teks Pertanyaan</label>
                <textarea name="question_text" rows="2" required placeholder="Tulis pernyataan atau situasi..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:border-[#cc0000] outline-none"></textarea>
            </div>

            @if($assessment->isForcedChoice())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach([
                        'option_a' => '■ A (Square / Kotak)',
                        'option_b' => '▲ B (Triangle / Segitiga)',
                        'option_c' => '▭ C (Rectangle / Persegi Panjang)',
                        'option_d' => '● D (Circle / Lingkaran)',
                        'option_e' => '〰 E (Squiggle / Garis Bergelombang)',
                    ] as $field => $label)
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">{{ $label }}</label>
                            <input type="text" name="{{ $field }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm focus:bg-white focus:border-[#cc0000] outline-none">
                        </div>
                    @endforeach
                </div>
            @else
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1.5">Kategori Kecerdasan</label>
                    <select name="siap_category_id" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium">
                        @foreach($assessment->categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->label }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex justify-end gap-2.5 pt-2">
                <button type="button" @click="showCreate = false" class="px-5 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-700 cursor-pointer">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#cc0000] hover:bg-red-700 text-white text-sm font-bold cursor-pointer">Simpan Pertanyaan</button>
            </div>
        </form>
    </div>

    <!-- Instant Search & Filter Bar -->
    <div class="bg-white rounded-2xl border border-gray-200 p-3.5 shadow-sm flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                <x-icon name="search" class="w-4 h-4" />
            </span>
            <input type="text" x-model="searchQuery" placeholder="Cari pertanyaan..."
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#cc0000] outline-none">
        </div>
        @if(!$assessment->isForcedChoice())
            <select x-model="categoryFilter" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-700">
                <option value="">Semua 8 Kecerdasan</option>
                @foreach($assessment->categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        @endif
    </div>

    <!-- Scannable Read-Only Question Cards with Click-to-Edit -->
    <div class="space-y-3">
        @foreach($assessment->questions as $idx => $question)
            @php
                $qTextLower = strtolower($question->question_text);
                $catIdStr = (string) ($question->siap_category_id ?? '');
            @endphp
            <div x-show="(!searchQuery || @js($qTextLower).includes(searchQuery.toLowerCase())) && (!categoryFilter || categoryFilter === '{{ $catIdStr }}')"
                 class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm transition hover:border-gray-300">

                <!-- Read-Only Header -->
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3.5 min-w-0 flex-1">
                        <span class="w-9 h-9 rounded-xl bg-red-50 text-[#cc0000] font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                            {{ str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900 leading-snug">{{ $question->question_text }}</h3>

                            @if($assessment->isForcedChoice())
                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach($question->options ?? [] as $opt)
                                        @php $cat = $categoriesBySlug->get($opt['category_slug'] ?? ''); @endphp
                                        <div class="flex items-start gap-2 rounded-xl bg-gray-50 border border-gray-100 px-3 py-2 text-xs text-gray-700">
                                            <span class="font-extrabold flex-shrink-0" style="color: {{ $cat?->color ?? '#4783C8' }}">
                                                {{ $cat?->symbol ?? '' }} {{ $opt['key'] }}
                                            </span>
                                            <span class="leading-snug">{{ $opt['text'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($question->category)
                                <span class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold"
                                      style="background-color: {{ $question->category->color }}18; color: {{ $question->category->color }}">
                                    {{ $question->category->name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button type="button"
                            @click="editingId = (editingId === {{ $question->id }} ? null : {{ $question->id }})"
                            class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold cursor-pointer">
                            <span x-text="editingId === {{ $question->id }} ? 'Tutup' : 'Edit'"></span>
                        </button>
                        <form action="{{ route('admin.siap.questions.delete', [$assessment, $question]) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- On-Demand Inline Edit Form -->
                <div x-show="editingId === {{ $question->id }}" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                    @if($assessment->isForcedChoice())
                        <form action="{{ route('admin.siap.questions.update', [$assessment, $question]) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="question_text" value="{{ $question->question_text }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold focus:bg-white focus:border-[#cc0000] outline-none">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                @php $opts = collect($question->options ?? [])->keyBy('key'); @endphp
                                @foreach(['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d', 'E' => 'option_e'] as $k => $fname)
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 text-center text-xs font-extrabold text-gray-500">{{ $k }}</span>
                                        <input type="text" name="{{ $fname }}" value="{{ $opts->get($k)['text'] ?? '' }}" required
                                            class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:bg-white focus:border-[#cc0000] outline-none">
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="editingId = null" class="px-4 py-2 rounded-xl border border-gray-200 text-xs font-bold text-gray-700 cursor-pointer">Batal</button>
                                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold cursor-pointer">Simpan Perubahan</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('admin.siap.questions.update', [$assessment, $question]) }}" method="POST" class="flex flex-wrap items-center gap-3">
                            @csrf
                            @method('PUT')
                            <input type="text" name="question_text" value="{{ $question->question_text }}" required
                                class="flex-1 min-w-[240px] bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-[#cc0000] outline-none">
                            <select name="siap_category_id" required class="bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-xs font-bold">
                                @foreach($assessment->categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $question->siap_category_id === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-xs font-bold cursor-pointer">Simpan</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
