@extends('layouts.app', ['activePage' => 'admin.siap.index'])

@section('title', 'Manage SIAP - Admin')
@section('header_title', 'Manage SIAP')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@php
    $initialTab = request('tab', (
        request()->anyFilled(['search', 'assessment_filter', 'payment_status']) ? 'results'
        : (request()->filled('order_search') || request()->filled('orders_page') ? 'orders' : 'config')
    ));
@endphp

@section('content')
<div x-data="siapAdminDashboard(@js($initialTab), {{ (int) ($pricing['super_finger_normal'] ?? 350000) }}, {{ (int) ($pricing['super_finger_promo'] ?? 99000) }})"
     class="w-full px-1 sm:px-2 pb-12 space-y-6">

    <!-- HERO HEADER -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#cc0000] via-[#b91c1c] to-[#7f1d1d] p-6 md:p-8 text-white shadow-xl">
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm">
                    <x-icon name="sparkles" class="w-3.5 h-3.5" />
                    <span>SIAP — Sistem Integrasi Asesmen dan Pengembangan</span>
                </div>
                <h1 class="font-display text-2xl sm:text-3xl font-extrabold tracking-tight">Manage SIAP</h1>
                <p class="text-xs sm:text-sm text-red-100 leading-relaxed">
                    Pusat kendali asesmen Psycho Geometric, Multiple Intelligence, Super Finger, Paket Lengkap SIAP, validasi kode redeem mitra, &amp; fulfillment.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('siap.index') }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 px-4 py-2.5 text-xs sm:text-sm font-bold text-white backdrop-blur-sm transition">
                    <x-icon name="globe" class="w-4 h-4" />
                    <span>Preview Halaman Siswa</span>
                </a>
                <a href="{{ route('admin.siap.results.export-csv') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm transition">
                    <span>Export CSV</span>
                </a>
                <a href="{{ route('admin.siap.results.export-pdf') }}" target="_blank"
                   class="inline-flex items-center gap-2 rounded-xl bg-white text-[#cc0000] hover:bg-red-50 px-4 py-2.5 text-xs sm:text-sm font-bold shadow-sm transition">
                    <span>Export PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 4 KPI STAT CARDS -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        <x-stat-card
            :value="$stats['total_attempts']"
            label="Peserta Asesmen Selesai"
            :change="$stats['unlocked_attempts'] . ' Full Akses Terbuka'"
            icon="users"
            color="blue"
        />
        <x-stat-card
            :value="'Rp ' . number_format($stats['total_revenue'], 0, ',', '.')"
            label="Total Pendapatan SIAP"
            icon="barChart"
            color="green"
        />
        <x-stat-card
            :value="$stats['unused_redeems']"
            label="Kode Redeem Aktif"
            :change="$stats['used_redeems'] . ' Sudah Digunakan'"
            icon="qr"
            color="purple"
        />
        <x-stat-card
            :value="$stats['pending_bundles']"
            label="Bundle Perlu Fulfillment"
            :change="$stats['pending_bundles'] > 0 ? 'Menunggu tindak lanjut admin' : 'Semua bundle selesai'"
            icon="award"
            color="orange"
        />
    </div>

    <!-- SEGMENTED PILL TAB NAVIGATION -->
    <div class="bg-white rounded-2xl border border-gray-200/80 p-1.5 shadow-sm flex flex-wrap gap-1.5">
        <button type="button" @click="activeTab = 'config'"
            :class="activeTab === 'config' ? 'bg-[#cc0000] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
            <x-icon name="settings" class="w-4 h-4" />
            <span>Konfigurasi &amp; Harga</span>
        </button>

        <button type="button" @click="activeTab = 'results'"
            :class="activeTab === 'results' ? 'bg-[#cc0000] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
            <x-icon name="chart" class="w-4 h-4" />
            <span>Hasil Peserta</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full font-extrabold"
                  :class="activeTab === 'results' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800'">
                {{ $attempts->total() }}
            </span>
        </button>

        <button type="button" @click="activeTab = 'orders'"
            :class="activeTab === 'orders' ? 'bg-[#cc0000] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
            <x-icon name="creditCard" class="w-4 h-4" />
            <span>Redeem &amp; Pesanan</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full font-extrabold"
                  :class="activeTab === 'orders' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-800'">
                {{ $orders->total() }}
            </span>
        </button>

        <button type="button" @click="activeTab = 'promos'"
            :class="activeTab === 'promos' ? 'bg-[#cc0000] text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900'"
            class="flex-1 min-w-[160px] inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
            <x-icon name="award" class="w-4 h-4" />
            <span>Kode Diskon</span>
            <span class="px-2 py-0.5 text-[11px] rounded-full font-extrabold"
                  :class="activeTab === 'promos' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-700'">
                {{ $promoCodes->count() }}
            </span>
        </button>
    </div>

    <x-flash-messages />

    <!-- ========================================================================= -->
    <!-- TAB 1: KONFIGURASI & HARGA                                                -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'config'" x-cloak x-transition.opacity.duration.150ms class="space-y-6">

        <!-- Visual Assessment Selector Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($assessments as $item)
                @php
                    $isSelected = $currentAssessment?->id === $item->id;
                    $isPsycho = $item->slug === 'psycho-geometric';
                @endphp
                <div class="rounded-2xl border-2 p-5 transition-all bg-white shadow-sm flex flex-col justify-between gap-4 {{ $isSelected ? 'border-[#cc0000] bg-[#FFF8F8] shadow-md shadow-red-100/50' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="flex items-start justify-between gap-3">
                        <a href="{{ route('admin.siap.index', ['assessment_id' => $item->id]) }}" class="flex items-start gap-3.5 min-w-0 flex-1 group">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 {{ $isPsycho ? 'bg-orange-50 text-orange-600' : 'bg-purple-50 text-purple-600' }}">
                                <x-icon :name="$isPsycho ? 'target' : 'sparkles'" class="w-5 h-5" />
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-bold text-gray-900 group-hover:text-[#cc0000] transition-colors truncate">{{ $item->title }}</h3>
                                    @if($isSelected)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-[#cc0000] text-white uppercase">Sedang Diedit</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $item->questions_count }} Soal · Unlock Rp{{ number_format($item->unlock_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </a>

                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold flex-shrink-0 {{ $item->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-2 pt-3 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.siap.questions', $item) }}"
                               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition">
                                <span>Kelola {{ $item->questions_count }} Soal</span>
                                <span>&rarr;</span>
                            </a>
                            <a href="{{ route('siap.intro', $item) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold transition">
                                <span>Preview</span>
                            </a>
                        </div>
                        @if(!$isSelected)
                            <a href="{{ route('admin.siap.index', ['assessment_id' => $item->id]) }}"
                               class="text-xs font-bold text-[#cc0000] hover:underline">
                                Pilih untuk Edit
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <!-- Left Column (7 cols): Assessment Configuration Form -->
            <div class="lg:col-span-7 bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
                <div class="border-b border-gray-100 pb-4 mb-6 flex items-center justify-between gap-3">
                    <div>
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-[#cc0000]">Pengaturan Asesmen Terpilih</span>
                        <h2 class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $currentAssessment?->title ?? 'Asesmen' }}</h2>
                    </div>
                    @if($currentAssessment)
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-600">
                            {{ $currentAssessment->isForcedChoice() ? 'Pilihan A–E' : 'Skala Likert 1–5' }}
                        </span>
                    @endif
                </div>

                @if($currentAssessment)
                    <form action="{{ route('admin.siap.config.update') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="assessment_id" value="{{ $currentAssessment->id }}">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Judul Asesmen</label>
                            <input type="text" name="title" value="{{ old('title', $currentAssessment->title) }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-[#cc0000] focus:ring-4 focus:ring-red-500/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Subjudul Singkat</label>
                            <input type="text" name="subtitle" value="{{ old('subtitle', $currentAssessment->subtitle) }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:bg-white focus:border-[#cc0000] focus:ring-4 focus:ring-red-500/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Deskripsi Lengkap</label>
                            <textarea name="description" rows="3"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:bg-white focus:border-[#cc0000] focus:ring-4 focus:ring-red-500/10 outline-none transition">{{ old('description', $currentAssessment->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Harga Normal</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                                    <input type="number" name="normal_price" min="0" value="{{ old('normal_price', $currentAssessment->normal_price) }}" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-[#cc0000] outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Harga Unlock</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-red-400">Rp</span>
                                    <input type="number" name="unlock_price" min="0" value="{{ old('unlock_price', $currentAssessment->unlock_price) }}" required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-3 py-3 text-sm font-extrabold text-[#cc0000] focus:bg-white focus:border-[#cc0000] outline-none">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100">
                            <label class="inline-flex items-center gap-3 cursor-pointer select-none">
                                <input type="checkbox" name="is_active" value="1" {{ $currentAssessment->is_active ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-[#cc0000] focus:ring-[#cc0000]">
                                <span class="text-sm font-bold text-gray-800">Tampilkan &amp; aktifkan untuk siswa</span>
                            </label>

                            <div class="flex flex-wrap items-center gap-2.5">
                                <a href="{{ route('admin.siap.questions', $currentAssessment) }}"
                                    class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-xs sm:text-sm font-bold text-gray-800 transition">
                                    Kelola Soal ({{ $currentAssessment->questions_count }})
                                </a>
                                <button type="submit"
                                    class="px-6 py-2.5 rounded-xl bg-[#cc0000] hover:bg-red-700 text-white text-xs sm:text-sm font-bold shadow-sm transition cursor-pointer">
                                    Simpan Konfigurasi
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>

            <!-- Right Column (5 cols): Super Finger & Bundle Pricing + Quick Shortcuts -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-7 shadow-sm">
                    <div class="flex items-start justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-lg font-extrabold text-gray-900">Harga Super Finger &amp; Paket Lengkap</h2>
                            <p class="text-xs text-gray-500 mt-0.5">Mengatur harga yang tampil di Hub &amp; Checkout SIAP.</p>
                        </div>
                        <span x-show="sfDiscountPct > 0"
                              class="rounded-full bg-red-50 border border-red-100 px-2.5 py-1 text-xs font-extrabold text-[#cc0000]"
                              x-text="`Hemat ${sfDiscountPct}%`"></span>
                    </div>

                    <form action="{{ route('admin.siap.pricing.update') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Super Finger — Harga Normal</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">Rp</span>
                                <input type="number" name="siap_super_finger_normal_price" x-model.number="sfNormal" min="0" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-bold text-gray-900 focus:bg-white focus:border-[#cc0000] outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Super Finger — Harga Promo</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-red-400">Rp</span>
                                <input type="number" name="siap_super_finger_price" x-model.number="sfPromo" min="0" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-extrabold text-[#cc0000] focus:bg-white focus:border-[#cc0000] outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Paket Lengkap SIAP (Bundle)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-red-400">Rp</span>
                                <input type="number" name="siap_bundle_price" value="{{ $pricing['bundle_price'] }}" min="0" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-10 pr-4 py-3 text-sm font-extrabold text-[#cc0000] focus:bg-white focus:border-[#cc0000] outline-none">
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-bold transition cursor-pointer">
                            Simpan Harga Produk
                        </button>
                    </form>
                </div>

                <!-- Quick Student Flow Shortcuts -->
                <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-2.5">
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-gray-500 mb-3">Pintasan Halaman Siswa</h3>
                    <a href="{{ route('siap.index') }}" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-xs font-bold text-gray-800 transition">
                        <span>Hub Asesmen SIAP</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('siap.super-finger') }}" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-blue-50 hover:bg-blue-100 text-xs font-bold text-blue-800 transition">
                        <span>Halaman Produk Super Finger</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="{{ route('siap.checkout', 'bundle_siap') }}" target="_blank" class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-xs font-bold text-[#cc0000] transition">
                        <span>Checkout Paket Lengkap SIAP</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 2: HASIL PESERTA                                                      -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'results'" x-cloak x-transition.opacity.duration.150ms class="space-y-4">
        <form method="GET" action="{{ route('admin.siap.index') }}" class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm flex flex-col md:flex-row items-stretch md:items-center gap-3">
            <input type="hidden" name="tab" value="results">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                    <x-icon name="search" class="w-4 h-4" />
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peserta, email Google, nomor WA, atau ID..."
                    class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#cc0000] outline-none">
            </div>
            <select name="assessment_filter" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-700">
                <option value="">Semua Asesmen</option>
                @foreach($assessments as $item)
                    <option value="{{ $item->id }}" {{ request('assessment_filter') == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-sm font-medium text-gray-700">
                <option value="">Semua Status Akses</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Full Akses (Lunas)</option>
                <option value="preview" {{ request('payment_status') === 'preview' ? 'selected' : '' }}>Mode Preview</option>
            </select>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-bold cursor-pointer">Filter</button>
                @if(request()->anyFilled(['search', 'assessment_filter', 'payment_status']))
                    <a href="{{ route('admin.siap.index', ['tab' => 'results']) }}" class="px-4 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold">Reset</a>
                @endif
            </div>
        </form>

        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[920px] text-left text-sm">
                    <thead class="bg-gray-50/80 border-b border-gray-200 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-4">ID</th>
                            <th class="px-5 py-4">Peserta &amp; Kontak</th>
                            <th class="px-5 py-4">Asesmen</th>
                            <th class="px-5 py-4">Profil Dominan</th>
                            <th class="px-5 py-4">Status Akses</th>
                            <th class="px-5 py-4">Selesai</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($attempts as $attempt)
                            @php
                                $topPct = $attempt->scores[0]['percentage'] ?? 0;
                                $pName = $attempt->participant_name ?? $attempt->user?->name ?? 'Peserta';
                            @endphp
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-5 py-4 font-mono text-xs font-bold text-gray-600">SIAP-{{ $attempt->id }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-red-50 text-[#cc0000] font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($pName, 0, 2)) }}
                                        </span>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $pName }}</div>
                                            <div class="text-xs text-gray-500">{{ $attempt->google_email ?? $attempt->user?->email }} · {{ $attempt->whatsapp }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-semibold">
                                        {{ $attempt->assessment?->title }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-gray-900">{{ $attempt->dominant_profile['label'] ?? '-' }}</div>
                                    <div class="mt-1 flex items-center gap-2">
                                        <div class="w-20 h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-[#cc0000]" style="width: {{ min(100, $topPct) }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-500">{{ $topPct }}%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $attempt->is_full_unlocked ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $attempt->is_full_unlocked ? 'FULL AKSES' : 'PREVIEW' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $attempt->completed_at?->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('siap.results', $attempt) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-3.5 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-[#cc0000] text-xs font-bold transition">
                                        <span>Buka Laporan</span>
                                        <span>&nearr;</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-empty-state message="Belum ada peserta yang menyelesaikan asesmen SIAP." icon="chart" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $attempts->links() }}
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 3: REDEEM & PESANAN                                                   -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'orders'" x-cloak x-transition.opacity.duration.150ms class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Left (4 cols): Super Finger Single-Use Redeem Validator -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white rounded-3xl border-2 border-red-100 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <span class="w-10 h-10 rounded-xl bg-red-50 text-[#cc0000] flex items-center justify-center">
                        <x-icon name="qr" class="w-5 h-5" />
                    </span>
                    <div>
                        <h2 class="text-base font-extrabold text-gray-900">Validasi Kode Super Finger</h2>
                        <p class="text-xs text-gray-500">1 kode hanya berlaku 1 kali pemakaian.</p>
                    </div>
                </div>

                <form action="{{ route('admin.siap.redeem') }}" method="POST" class="space-y-3 mt-4">
                    @csrf
                    <input type="text" name="redeem_code" required placeholder="SF-XXXX-XXXX"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-base font-mono font-extrabold uppercase tracking-widest text-center text-gray-900 focus:bg-white focus:border-[#cc0000] outline-none">
                    <button type="submit" class="w-full py-3 rounded-xl bg-[#cc0000] hover:bg-red-700 text-white text-sm font-bold shadow-sm transition cursor-pointer">
                        Validasi &amp; Gunakan Kode
                    </button>
                </form>

                <div class="grid grid-cols-2 gap-2.5 mt-5 pt-4 border-t border-gray-100 text-center">
                    <div class="rounded-xl bg-emerald-50 p-3">
                        <div class="text-lg font-extrabold text-emerald-700">{{ $stats['unused_redeems'] }}</div>
                        <div class="text-[11px] font-semibold text-emerald-800">Belum Dipakai</div>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <div class="text-lg font-extrabold text-gray-700">{{ $stats['used_redeems'] }}</div>
                        <div class="text-[11px] font-semibold text-gray-500">Sudah Dipakai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right (8 cols): Orders & Collapsible Bundle Fulfillment Checklist -->
        <div class="lg:col-span-8 space-y-4">
            <form method="GET" action="{{ route('admin.siap.index') }}" class="bg-white rounded-2xl border border-gray-200 p-3.5 shadow-sm flex flex-wrap gap-2.5">
                <input type="hidden" name="tab" value="orders">
                <div class="relative flex-1 min-w-[200px]">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <x-icon name="search" class="w-4 h-4" />
                    </span>
                    <input type="text" name="order_search" value="{{ request('order_search') }}" placeholder="Cari kode redeem SF-..., nama peserta, atau email..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:border-[#cc0000] outline-none">
                </div>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-gray-900 hover:bg-black text-white text-sm font-bold cursor-pointer">Cari</button>
                @if(request()->filled('order_search'))
                    <a href="{{ route('admin.siap.index', ['tab' => 'orders']) }}" class="px-4 py-2.5 rounded-xl bg-gray-100 text-gray-700 text-sm font-bold">Reset</a>
                @endif
            </form>

            <div class="space-y-3">
                @forelse($orders as $order)
                    @php
                        $notes = $order->fulfillment_notes ?? [];
                        $doneCount = collect(['merchandise', 'mentoring', 'career_mapping', 'voucher'])
                            ->filter(fn ($k) => ($notes[$k] ?? 'pending') === 'done')
                            ->count();
                    @endphp
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="space-y-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-mono text-xs font-bold text-gray-400">#{{ $order->id }}</span>
                                    <span class="font-bold text-gray-900">{{ $order->product_name }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $order->isPaid() ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ strtoupper($order->status) }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500">
                                    <strong class="text-gray-700">{{ $order->participant_name }}</strong> · {{ $order->email }} · WA: {{ $order->parent_whatsapp }}
                                </div>
                                <div class="text-xs font-semibold text-gray-600">
                                    Rp{{ number_format($order->final_price, 0, ',', '.') }} · {{ $order->payment_method }} · {{ $order->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5">
                                @if($order->redeem_code)
                                    <div class="flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5">
                                        <span class="font-mono text-xs font-extrabold text-blue-900">{{ $order->redeem_code }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->redeem_status === 'used' ? 'bg-gray-200 text-gray-700' : 'bg-green-100 text-green-800' }}">
                                            {{ $order->redeem_status === 'used' ? 'USED' : 'UNUSED' }}
                                        </span>
                                    </div>
                                @endif

                                @if($order->isBundle() && $order->isPaid())
                                    <button type="button" @click="open = !open"
                                        class="inline-flex items-center gap-2 rounded-xl border px-3.5 py-2 text-xs font-bold transition cursor-pointer {{ $doneCount === 4 ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-amber-200 bg-amber-50 text-amber-800 hover:bg-amber-100' }}">
                                        <span>Fulfillment {{ $doneCount }}/4</span>
                                        <svg class="w-3.5 h-3.5 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($order->isBundle() && $order->isPaid())
                            <form x-show="open" x-cloak action="{{ route('admin.siap.orders.fulfillment', $order) }}" method="POST"
                                  class="mt-4 pt-4 border-t border-gray-100 space-y-3">
                                @csrf
                                @method('PATCH')
                                <div class="text-xs font-extrabold uppercase tracking-wider text-gray-500">Checklist Fulfillment Manual</div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                                    @foreach([
                                        'merchandise' => 'Sertifikat & Merchandise',
                                        'mentoring' => 'Mentoring 2 Jam',
                                        'career_mapping' => 'Mapping Karier',
                                        'voucher' => 'Voucher Kursus',
                                    ] as $key => $label)
                                        <div>
                                            <label class="block font-semibold text-gray-700 mb-1">{{ $label }}</label>
                                            <select name="{{ $key }}" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-2.5 py-2 font-medium">
                                                <option value="pending" {{ ($notes[$key] ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="done" {{ ($notes[$key] ?? '') === 'done' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <input type="text" name="notes" value="{{ $notes['notes'] ?? '' }}" placeholder="Catatan pengiriman / jadwal mentoring / kode voucher..."
                                        class="flex-1 min-w-[200px] bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-xs">
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-black text-white text-xs font-bold cursor-pointer">
                                        Simpan Checklist
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-3xl border border-gray-200">
                        <x-empty-state message="Belum ada transaksi SIAP." icon="payment" />
                    </div>
                @endforelse
            </div>
            {{ $orders->links() }}
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- TAB 4: KODE DISKON                                                        -->
    <!-- ========================================================================= -->
    <div x-show="activeTab === 'promos'" x-cloak x-transition.opacity.duration.150ms class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-4 bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-extrabold text-gray-900">Tambah Kode Diskon</h2>
            <p class="text-xs text-gray-500 mt-0.5 mb-5">Berlaku di halaman checkout SIAP.</p>

            <form action="{{ route('admin.siap.promos.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Kode Promo</label>
                    <input type="text" name="code" required placeholder="CONTOH50"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono font-bold uppercase tracking-wider focus:bg-white focus:border-[#cc0000] outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nama Promo</label>
                    <input type="text" name="name" required placeholder="Diskon Spesial SIAP"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-[#cc0000] outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Tipe</label>
                        <select name="type" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                            <option value="percentage">Persentase (%)</option>
                            <option value="fixed_amount">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Nilai</label>
                        <input type="number" name="value" min="1" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-bold focus:bg-white focus:border-[#cc0000] outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1.5">Maks. Pemakaian (opsional)</label>
                    <input type="number" name="max_uses" min="1" placeholder="Tanpa batas"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:border-[#cc0000] outline-none">
                </div>
                <button type="submit" class="w-full py-3 rounded-xl bg-[#cc0000] hover:bg-red-700 text-white text-sm font-bold shadow-sm transition cursor-pointer">
                    + Simpan Kode Diskon
                </button>
            </form>
        </div>

        <div class="lg:col-span-8 bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50/80 border-b border-gray-200 text-[11px] font-extrabold uppercase tracking-wider text-gray-500">
                        <tr>
                            <th class="px-5 py-4">Kode</th>
                            <th class="px-5 py-4">Nama Promo</th>
                            <th class="px-5 py-4">Diskon</th>
                            <th class="px-5 py-4">Terpakai</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($promoCodes as $promo)
                            <tr class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-lg bg-red-50 border border-red-100 font-mono text-xs font-extrabold text-[#cc0000]">
                                        {{ $promo->code }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $promo->name }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $promo->type_label }}</td>
                                <td class="px-5 py-4 text-xs text-gray-500 font-semibold">
                                    {{ $promo->used_count }}{{ $promo->max_uses ? ' / ' . $promo->max_uses : ' / ∞' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $promo->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <form action="{{ route('admin.siap.promos.toggle', $promo) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold cursor-pointer">
                                                {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.siap.promos.destroy', $promo) }}" method="POST" onsubmit="return confirm('Hapus kode promo ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 text-xs font-bold cursor-pointer">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <x-empty-state message="Belum ada kode diskon." icon="inbox" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function siapAdminDashboard(initialTab, sfNormal, sfPromo) {
    return {
        activeTab: initialTab || 'config',
        sfNormal: sfNormal || 0,
        sfPromo: sfPromo || 0,
        get sfDiscountPct() {
            if (!this.sfNormal || this.sfNormal <= this.sfPromo) return 0;
            return Math.round(((this.sfNormal - this.sfPromo) / this.sfNormal) * 100);
        }
    };
}
</script>
@endpush
