@extends('layouts.app', ['activePage' => 'siap.index'])

@section('title', 'Asesmen SIAP - 1Langkah')
@section('header_title', 'SIAP — Asesmen Potensi Diri')

@section('content')
<div class="mx-auto max-w-[1400px] px-1 pb-10 sm:px-2">
    <x-flash-messages />

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-xs text-slate-600">
        <x-icon name="grid" class="h-3.5 w-3.5" />
        <span>Beranda</span>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">SIAP</span>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">Asesmen</span>
    </div>

    <!-- Hero Banner -->
    <section class="relative flex min-h-[240px] items-center overflow-hidden rounded-[22px] bg-[#EAF3F5] px-6 py-8 md:px-9">
        <div class="relative z-10 max-w-[540px] md:w-[60%]">
            <span class="mb-4 inline-flex items-center gap-2 rounded-full border border-white bg-white/85 px-3 py-1.5 text-xs font-semibold text-[#3F6F76]">
                <x-icon name="sparkles" class="h-3.5 w-3.5" />
                SATU LANGKAH MENGENAL DIRI
            </span>
            <h1 class="font-display text-[26px] font-semibold leading-[1.3] tracking-[-0.02em] text-[#292C33] md:text-[32px]">
                Kenali Dirimu,<br>Temukan <span class="text-[#DE2727]">Potensimu!</span>
            </h1>
            <p class="mt-3 max-w-[460px] text-sm leading-6 text-slate-600">
                Setiap orang memiliki kecerdasan dan potensi yang unik. Yuk, cari tahu cara terbaik untuk belajar, berkembang, dan meraih cita-citamu.
            </p>
        </div>
        <div class="absolute -right-4 bottom-0 hidden w-[42%] md:block">
            <x-siap.learning-art />
        </div>
    </section>

    <!-- Section Header -->
    <div class="mb-5 mt-8 flex flex-wrap items-end justify-between gap-2">
        <div>
            <h2 class="font-display text-xl font-semibold tracking-tight text-[#292C33]">Eksplorasi Potensimu</h2>
            <p class="mt-1 text-sm text-slate-600">Langkah kecil hari ini, untuk masa depan yang lebih berarti.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-full border border-stone-200/70 bg-white px-3.5 py-2 text-xs font-medium text-slate-600">
            <x-icon name="sparkles" class="h-3.5 w-3.5 text-red-600" />
            Kenali diri, bukan kompetisi
        </span>
    </div>

    <!-- Assessment Cards Grid (Psycho Geometric + Multiple Intelligence + Super Finger) -->
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($assessments as $assessment)
            @php
                $completedAttempt = $latestAttempts->get($assessment->id);
                $inProgress = $inProgressAttempts->get($assessment->id);
                $isPsycho = $assessment->slug === 'psycho-geometric';
                $headerBg = $isPsycho ? 'bg-[#FDF0EA]' : 'bg-[#F0EBFA]';
                $pillText = $isPsycho ? 'text-[#B84323]' : 'text-[#7351A5]';
                $categoryPill = $isPsycho ? '5 kecerdasan geometri' : '8 jenis kecerdasan';
            @endphp
            <article class="flex flex-col overflow-hidden rounded-[20px] border border-stone-200 bg-white shadow-sm">
                <div class="relative flex h-[174px] items-center justify-center overflow-hidden {{ $headerBg }}">
                    <span class="absolute left-5 top-4 z-10 rounded-full bg-white px-2.5 py-1 text-xs font-semibold tracking-wide {{ $pillText }}">
                        {{ $assessment->badge }}
                    </span>
                    @if($isPsycho)
                        <div class="mt-5 flex items-center gap-3">
                            @foreach([
                                ['■', '#4783C8'],
                                ['▲', '#DF2020'],
                                ['▭', '#D18637'],
                                ['●', '#439D8A'],
                                ['〰', '#8065CF'],
                            ] as [$sym, $clr])
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-xl font-bold shadow-sm" style="color: {{ $clr }}">{{ $sym }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-8 w-[270px]">
                            <x-siap.learning-art />
                        </div>
                    @endif
                    <span class="absolute bottom-4 right-5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold {{ $pillText }}">
                        {{ $categoryPill }}
                    </span>
                </div>

                <div class="flex flex-1 flex-col p-5 md:p-6">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="font-display text-xl font-semibold tracking-tight text-[#292C33]">{{ $assessment->title }}</h3>
                        <span class="shrink-0 rounded-md bg-[#EDF7EF] px-2 py-1 text-xs font-medium text-[#34734B]">Gratis</span>
                    </div>
                    <p class="mt-2 min-h-12 text-sm leading-6 text-slate-600">{{ $assessment->subtitle }}</p>

                    <div class="my-5 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-600">
                        <span class="flex items-center gap-1.5">
                            <x-icon name="book" class="h-4 w-4" />
                            {{ $assessment->question_count }} pertanyaan
                        </span>
                        <span class="flex items-center gap-1.5">
                            <x-icon name="clock" class="h-4 w-4" />
                            {{ $assessment->time_limit_minutes }} menit
                        </span>
                    </div>

                    <div class="mt-auto space-y-3 border-t border-stone-100 pt-4">
                        <div class="flex items-center gap-2 text-xs text-slate-600">
                            <x-icon name="check" class="h-4 w-4 text-green-700" />
                            <span>Tidak ada jawaban benar atau salah</span>
                        </div>

                        @if($completedAttempt)
                            <div class="flex items-center justify-between gap-2 rounded-xl bg-stone-50 px-3 py-2 text-xs">
                                <span class="font-semibold {{ $completedAttempt->is_full_unlocked ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $completedAttempt->is_full_unlocked ? '✓ Hasil Lengkap Terbuka' : '🔒 Mode Preview' }}
                                </span>
                                <a href="{{ route('siap.results', $completedAttempt) }}" class="font-bold text-[#E50000] hover:underline">
                                    Lihat Hasil &rarr;
                                </a>
                            </div>
                        @endif

                        <a href="{{ route('siap.intro', $assessment) }}"
                            class="flex min-h-12 w-full items-center justify-center gap-3 rounded-xl bg-[#E50000] px-4 text-sm font-semibold text-white transition hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-700">
                            <span>{{ $inProgress ? 'Lanjutkan Asesmen' : ($completedAttempt ? 'Kerjakan Ulang Asesmen' : 'Mulai Asesmen') }}</span>
                            <x-icon name="arrowRight" class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            </article>
        @endforeach

        <!-- Super Finger Card -->
        <article class="flex flex-col overflow-hidden rounded-[20px] border border-stone-200 bg-white shadow-sm">
            <div class="relative bg-[#EAF1F8]">
                <span class="absolute left-5 top-4 z-10 rounded-full bg-white px-2.5 py-1 text-xs font-semibold tracking-wide text-[#4B7199]">
                    NATURE <span class="mx-1 text-blue-300">|</span> GENOTIPE
                </span>
                <x-siap.finger-art />
                <span class="absolute bottom-4 right-5 rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-[#4B7199]">
                    5 core kecerdasan
                </span>
            </div>

            <div class="flex flex-1 flex-col p-5 md:p-6">
                <div class="flex items-start justify-between gap-2">
                    <h3 class="font-display text-xl font-semibold tracking-tight text-[#292C33]">Super Finger</h3>
                    <span class="shrink-0 rounded-md bg-[#FFF2E6] px-2 py-1 text-xs font-medium text-[#A85B1D]">Promo spesial</span>
                </div>
                <p class="mt-2 min-h-12 text-sm leading-6 text-slate-600">
                    Kenali potensi dasar kecerdasan melalui teknologi analisis sidik jari.
                </p>

                <div class="my-5 flex flex-wrap gap-x-5 gap-y-2 text-xs text-slate-600">
                    <span class="flex items-center gap-1.5">
                        <x-icon name="qr" class="h-4 w-4" />
                        Tes sidik jari
                    </span>
                    <span class="flex items-center gap-1.5">
                        <x-icon name="mapPin" class="h-4 w-4" />
                        Di lokasi mitra
                    </span>
                </div>

                <div class="mt-auto space-y-4 border-t border-stone-100 pt-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500 line-through">Rp{{ number_format($pricing['super_finger_normal'], 0, ',', '.') }}</span>
                        <span class="text-lg font-bold text-[#DF2020]">Rp{{ number_format($pricing['super_finger_promo'], 0, ',', '.') }}</span>
                        @if($pricing['super_finger_discount_percent'] > 0)
                            <span class="rounded bg-red-50 px-1.5 py-1 text-xs font-semibold text-red-700">
                                Hemat {{ $pricing['super_finger_discount_percent'] }}%
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('siap.super-finger') }}"
                        class="flex min-h-12 w-full items-center justify-center gap-3 rounded-xl border border-red-600 px-4 text-sm font-semibold text-red-600 transition hover:bg-red-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-700">
                        <span>Pelajari Lebih Lanjut</span>
                        <x-icon name="arrowRight" class="h-4 w-4" />
                    </a>
                </div>
            </div>
        </article>
    </div>

    <!-- Paket Lengkap SIAP Bundle Card -->
    <x-siap.bundle-card :bundle-price="$pricing['bundle_price']" />

    <!-- Why Explore Your Potential -->
    <section class="mt-8">
        <h2 class="font-display text-lg font-semibold text-[#292C33]">Kenapa Kamu Perlu Mengenal Potensimu?</h2>
        <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['book', 'Belajar lebih nyaman', 'Temukan cara belajar yang sesuai dengan dirimu.', 'bg-blue-50 text-blue-700'],
                ['sparkles', 'Kenali keunggulanmu', 'Jelajahi potensi unik yang kamu miliki.', 'bg-amber-50 text-amber-700'],
                ['award', 'Lebih yakin pilih jurusan', 'Punya arah untuk langkah pendidikanmu.', 'bg-purple-50 text-purple-700'],
                ['barChart', 'Bayangkan masa depan', 'Buka wawasan tentang pilihan kariermu.', 'bg-green-50 text-green-700'],
            ] as [$icon, $title, $text, $colorClass])
                <div class="rounded-2xl border border-stone-200/80 bg-white p-4">
                    <span class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl {{ $colorClass }}">
                        <x-icon :name="$icon" class="h-5 w-5" />
                    </span>
                    <h3 class="text-sm font-semibold text-[#292C33]">{{ $title }}</h3>
                    <p class="mt-1.5 text-sm leading-5 text-slate-600">{{ $text }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <footer class="mt-9 flex flex-wrap items-center justify-between gap-3 border-t border-stone-200 pt-5 text-xs text-slate-500">
        <span>SIAP — Sistem Integrasi Asesmen dan Pengembangan</span>
        <span class="flex items-center gap-1.5">
            <x-icon name="shield" class="h-3.5 w-3.5" />
            Ruang aman untuk mengenal dirimu
        </span>
    </footer>
</div>
@endsection
