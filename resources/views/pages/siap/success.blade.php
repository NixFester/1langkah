@extends('layouts.app', ['activePage' => 'siap.index'])

@section('title', 'Pembayaran Berhasil — SIAP 1Langkah')
@section('header_title', 'Pembayaran Berhasil')

@php
    $bundleBenefits = [
        'Tes fenotipe (Psycho Geometric & Multiple Intelligence) dan genotipe (Super Finger)',
        'Sertifikat asesmen dan merchandise resmi 1Langkah',
        'Private mentoring 1 sesi (2 jam)',
        'Mapping arah karier dan akademisi',
        'Voucher potongan harga untuk kursus, pelatihan, dan pengembangan lainnya di 1Langkah',
    ];
@endphp

@section('content')
<div x-data="{ copied: false, showGuide: false }" class="mx-auto max-w-2xl px-1 pb-12 sm:px-2">
    <x-flash-messages />

    <div class="rounded-3xl border border-stone-200 bg-white p-7 text-center shadow-sm md:p-12">
        <span class="mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-green-700">
            <x-icon name="check" class="h-8 w-8" />
        </span>

        <h1 class="font-display text-2xl font-semibold text-[#292C33] md:text-3xl">Pembayaran Berhasil!</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600">
            @if($order->isBundle())
                Pembelian <strong>Paket Lengkap SIAP</strong> berhasil! Akses hasil lengkap asesmen fenotipe telah terbuka. Gunakan kode di bawah untuk tes genotipe Super Finger di mitra resmi 1Langkah.
            @elseif($order->product_type === \App\Models\SiapOrder::PRODUCT_SUPER_FINGER)
                Terima kasih! Pembelian <strong>Super Finger Assessment</strong> berhasil. Tunjukkan kode di bawah kepada petugas mitra resmi 1Langkah saat melakukan asesmen sidik jari.
            @else
                Pembayaran <strong>{{ $order->product_name }}</strong> berhasil! Hasil lengkap &amp; Sertifikat Asesmen SIAP kamu kini sudah terbuka.
            @endif
        </p>

        @if($order->isBundle())
            <div class="mt-6 rounded-2xl bg-red-50 p-5 text-left">
                <h2 class="font-semibold text-[#292C33]">Benefit Paket Lengkap SIAP · Rp{{ number_format($order->final_price, 0, ',', '.') }}</h2>
                <ul class="mt-4 space-y-3">
                    @foreach($bundleBenefits as $benefit)
                        <li class="flex items-start gap-2 text-sm leading-6 text-slate-700">
                            <x-icon name="check" class="mt-1 h-4 w-4 text-red-700" />
                            <span>{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
                <p class="my-4 text-xs leading-6 text-slate-600">
                    Tim 1Langkah akan menghubungi WhatsApp/Email Anda untuk koordinasi pengiriman merchandise, penjadwalan mentoring 2 jam, mapping karier-akademisi, serta voucher diskon kursus.
                </p>
                @if($hasCompletedAnyFenotipe)
                    <a href="{{ route('siap.results') }}"
                        class="inline-flex min-h-11 items-center rounded-xl bg-[#E50000] px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                        Lihat Hasil Lengkap
                    </a>
                @elseif($firstAssessment)
                    <a href="{{ route('siap.intro', $firstAssessment) }}"
                        class="inline-flex min-h-11 items-center rounded-xl bg-[#E50000] px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                        Mulai Tes Fenotipe Sekarang
                    </a>
                @endif
            </div>
        @endif

        @if($order->redeem_code)
            <div class="my-7 rounded-2xl border border-dashed border-blue-300 bg-blue-50 p-6">
                <p class="text-xs font-semibold tracking-widest text-blue-800">KODE REDEEM SUPER FINGER KAMU</p>
                <p class="my-4 font-display text-3xl font-semibold text-[#292C33]" id="redeem-code-text">{{ $order->redeem_code }}</p>
                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $order->redeem_status === 'used' ? 'bg-stone-200 text-stone-700' : 'bg-green-100 text-green-800' }}">
                    {{ $order->redeem_status === 'used' ? 'SUDAH DIGUNAKAN' : 'BELUM DIGUNAKAN' }}
                </span>
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                <button type="button"
                    @click="navigator.clipboard?.writeText('{{ $order->redeem_code }}').then(() => copied = true)"
                    class="min-h-12 cursor-pointer rounded-xl bg-[#E50000] px-5 py-3 text-sm font-semibold text-white hover:bg-red-700">
                    <span x-text="copied ? '✓ Kode Tersalin' : 'Salin Kode Redeem'"></span>
                </button>
                <button type="button" @click="showGuide = !showGuide"
                    class="min-h-12 cursor-pointer rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                    Lihat Panduan Tes
                </button>
            </div>

            <div x-show="showGuide" style="display: none;" class="mt-5 rounded-xl bg-stone-50 p-4 text-left text-sm leading-7 text-slate-700">
                1. Hubungi mitra resmi 1Langkah untuk jadwal tes.<br>
                2. Datang bersama orang tua/wali dan tunjukkan kode <strong>{{ $order->redeem_code }}</strong>.<br>
                3. Ikuti panduan pemindaian sidik jari dari petugas.<br>
                Kode hanya dapat digunakan satu kali.
            </div>
        @endif

        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('siap.certificates') }}"
                class="inline-flex min-h-12 items-center rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                Lihat Sertifikat &amp; Riwayat
            </a>
            <a href="{{ route('siap.index') }}"
                class="inline-flex min-h-12 items-center rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                Kembali ke Asesmen
            </a>
        </div>
    </div>
</div>
@endsection
