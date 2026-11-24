@extends('layouts.app', ['activePage' => 'siap.certificates'])

@section('title', 'Sertifikat Asesmen — SIAP 1Langkah')
@section('header_title', 'Sertifikat Asesmen SIAP')

@section('content')
<div class="mx-auto max-w-[1400px] space-y-8 px-1 pb-12 sm:px-2">
    <x-flash-messages />

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-600">
        <a href="{{ route('siap.index') }}" class="inline-flex items-center gap-1.5 hover:text-[#E50000]">
            <x-icon name="grid" class="h-3.5 w-3.5" />
            <span>SIAP</span>
        </a>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">Sertifikat Asesmen</span>
    </div>

    <!-- Certificates Section -->
    @if($certificates->isEmpty())
        <section class="mx-auto max-w-2xl rounded-3xl border border-stone-200 bg-white p-8 text-center md:p-12">
            <span class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-50 text-blue-700">
                <x-icon name="award" class="h-10 w-10" />
            </span>
            <h1 class="font-display text-2xl font-semibold text-[#292C33]">Setiap langkah layak diapresiasi</h1>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Belum ada sertifikat asesmen yang diterbitkan. Selesaikan asesmen dan buka Hasil Lengkap untuk menerbitkan Sertifikat Asesmen SIAP kamu.
            </p>
            <div class="mt-6">
                <a href="{{ route('siap.index') }}"
                    class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Eksplorasi Asesmen
                </a>
            </div>
        </section>
    @else
        <section>
            <div class="mb-5">
                <h1 class="font-display text-2xl font-semibold text-[#292C33]">Sertifikat Asesmen Kamu</h1>
                <p class="mt-1 text-sm text-slate-600">Sertifikat resmi hasil asesmen fenotipe SIAP 1Langkah.</p>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                @foreach($certificates as $cert)
                    @php
                        $attempt = $cert->certifiable;
                    @endphp
                    <div class="flex flex-col justify-between rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
                        <div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-[#E50000]">
                                    <x-icon name="award" class="h-6 w-6" />
                                </span>
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                    Terverifikasi
                                </span>
                            </div>
                            <h2 class="mt-4 font-display text-lg font-semibold text-[#292C33]">{{ $cert->title }}</h2>
                            @if($cert->description)
                                <p class="mt-1.5 text-sm text-slate-600">{{ $cert->description }}</p>
                            @endif
                            <div class="mt-4 space-y-1 text-xs text-slate-500">
                                <div>Nomor Sertifikat: <strong class="text-slate-700">{{ $cert->certificate_number }}</strong></div>
                                <div>Kode Verifikasi: <strong class="text-slate-700">{{ $cert->verification_code }}</strong></div>
                                <div>Diterbitkan: <strong class="text-slate-700">{{ $cert->issued_date?->translatedFormat('d F Y') }}</strong></div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-2.5 border-t border-stone-100 pt-4">
                            <a href="{{ route('siap.certificates.print', $cert) }}" target="_blank"
                                class="inline-flex min-h-11 flex-1 items-center justify-center gap-2 rounded-xl bg-[#E50000] px-4 text-sm font-semibold text-white hover:bg-red-700">
                                Cetak / Simpan PDF
                            </a>
                            @if($attempt)
                                <a href="{{ route('siap.results', $attempt) }}"
                                    class="inline-flex min-h-11 items-center justify-center rounded-xl border border-stone-200 bg-white px-4 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                                    Lihat Hasil
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Purchase & Super Finger Redeem History -->
    <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <h2 class="font-display text-xl font-semibold text-[#292C33]">Riwayat Pembelian &amp; Kode Redeem SIAP</h2>
        <p class="mt-1 text-sm text-slate-600">Daftar transaksi asesmen, Super Finger, dan Paket Lengkap SIAP kamu.</p>

        @if($orders->isEmpty())
            <p class="mt-5 text-sm text-slate-500">Belum ada riwayat transaksi SIAP.</p>
        @else
            <div class="mt-5 space-y-3">
                @foreach($orders as $order)
                    <div class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-stone-200 p-4">
                        <div>
                            <div class="font-semibold text-[#292C33]">{{ $order->product_name }}</div>
                            <div class="mt-1 text-xs text-slate-500">
                                {{ $order->created_at->format('d M Y, H:i') }} ·
                                Rp{{ number_format($order->final_price, 0, ',', '.') }} ·
                                <span class="font-semibold {{ $order->isPaid() ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2.5">
                            @if($order->redeem_code)
                                <span class="rounded-xl border border-blue-200 bg-blue-50 px-3 py-1.5 font-mono text-sm font-bold text-blue-900">
                                    {{ $order->redeem_code }}
                                </span>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $order->redeem_status === 'used' ? 'bg-stone-100 text-stone-700' : 'bg-green-100 text-green-800' }}">
                                    {{ $order->redeem_status === 'used' ? 'SUDAH DIGUNAKAN' : 'BELUM DIGUNAKAN' }}
                                </span>
                            @endif
                            @if($order->isPaid())
                                <a href="{{ route('siap.order.success', $order) }}" class="text-xs font-semibold text-[#E50000] hover:underline">
                                    Detail &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
