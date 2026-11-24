@extends('layouts.app', ['activePage' => 'siap.index'])

@section('title', 'Super Finger — SIAP 1Langkah')
@section('header_title', 'Super Finger (Nature | Genotipe)')

@section('content')
<div class="mx-auto max-w-[1400px] px-1 pb-12 sm:px-2">
    <x-flash-messages />

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-xs text-slate-600">
        <a href="{{ route('siap.index') }}" class="inline-flex items-center gap-1.5 hover:text-[#E50000]">
            <x-icon name="grid" class="h-3.5 w-3.5" />
            <span>SIAP</span>
        </a>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">Super Finger</span>
    </div>

    <!-- Hero -->
    <section class="grid items-center gap-6 rounded-3xl bg-[#EAF1F8] p-7 md:grid-cols-2 md:p-10">
        <div>
            <span class="text-sm font-semibold text-blue-800">NATURE · GENOTIPE</span>
            <h1 class="my-4 font-display text-3xl font-semibold leading-tight text-[#292C33]">
                Setiap Sidik Jari Punya Cerita.<br>Kenali Potensi Hebatmu!
            </h1>
            <p class="text-sm leading-7 text-slate-600">
                Super Finger adalah bagian dari SIAP 1Langkah. Eksplorasi kecenderungan potensi dasar melalui analisis sidik jari, sebagai salah satu referensi untuk mengenali diri.
            </p>
        </div>
        <div class="py-6 md:scale-110">
            <x-siap.finger-art />
        </div>
    </section>

    <!-- Nature vs Nurture (5 Core cards omitted per Figma revision) -->
    <h2 class="mb-4 mt-8 font-display text-xl font-semibold text-[#292C33]">Dua pendekatan, saling melengkapi</h2>
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-stone-200/80 bg-white p-6">
            <h3 class="font-semibold text-[#292C33]">Nature (Genotipe)</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Eksplorasi kecenderungan potensi dasar melalui asesmen sidik jari di lokasi mitra resmi 1Langkah.
            </p>
        </div>
        <div class="rounded-2xl border border-stone-200/80 bg-white p-6">
            <h3 class="font-semibold text-[#292C33]">Nurture (Fenotipe)</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">
                Kenali kecerdasan dan pola perilaku yang berkembang melalui pengalaman, lingkungan, pola asuh, dan pergaulan lewat Multiple Intelligence Test &amp; Psycho Geometric Test.
            </p>
        </div>
    </div>

    <!-- Pricing CTA Card -->
    <div class="mt-7 flex flex-wrap items-center justify-between gap-6 rounded-2xl border border-red-200 bg-white p-7 shadow-sm">
        <div>
            <span class="text-xs font-bold text-red-600">PROMO SPESIAL</span>
            <h3 class="my-2 text-3xl font-bold text-red-600">
                Rp{{ number_format($pricing['super_finger_promo'], 0, ',', '.') }}
                <span class="text-sm font-normal text-slate-500 line-through">Rp{{ number_format($pricing['super_finger_normal'], 0, ',', '.') }}</span>
            </h3>
            <p class="text-sm text-slate-600">
                Kode redeem diterbitkan otomatis setelah pembayaran · Tes di lokasi mitra resmi 1Langkah
            </p>
        </div>
        <a href="{{ route('siap.checkout', 'super_finger') }}"
            class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-red-700">
            Beli Sekarang – Rp{{ number_format($pricing['super_finger_promo'], 0, ',', '.') }}
        </a>
    </div>

    <!-- Paket Lengkap SIAP Bundle -->
    <x-siap.bundle-card :bundle-price="$pricing['bundle_price']" />
</div>
@endsection
