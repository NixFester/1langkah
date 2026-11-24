@props(['bundlePrice' => 500000, 'assessmentId' => null, 'attemptId' => null])

@php
    $benefits = [
        'Tes Multiple Intelligence Test, Psycho Geometric Test, dan Super Finger',
        'Sertifikat asesmen dan merchandise resmi 1Langkah',
        'Private mentoring 1 sesi (2 jam)',
        'Mapping arah karier dan akademisi',
        'Voucher potongan harga untuk kursus, pelatihan, dan pengembangan lainnya di 1Langkah',
    ];
    $checkoutParams = array_filter([
        'product' => 'bundle_siap',
        'assessment' => $assessmentId,
        'attempt' => $attemptId,
    ]);
@endphp

<section class="mt-7 overflow-hidden rounded-3xl border border-red-200 bg-[#FFF3F0] p-6 md:p-8">
    <div class="grid gap-7 xl:grid-cols-[1fr_1.25fr]">
        <div class="min-w-0">
            <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-xs font-semibold tracking-wide text-red-700">
                <x-icon name="sparkles" class="h-4 w-4" />
                PAKET LENGKAP SIAP
            </span>
            <h2 class="mt-4 font-display text-2xl font-semibold text-[#292C33]">Paket Lengkap SIAP</h2>
            <p class="mt-3 text-sm leading-7 text-slate-700">
                Dua pendekatan asesmen, satu langkah lebih terarah. Kenali potensimu sekaligus rencanakan pengembangannya bersama mentor.
            </p>
            <p class="mb-5 mt-5 font-display text-3xl font-semibold text-red-700">
                Rp{{ number_format($bundlePrice, 0, ',', '.') }}
                <span class="ml-1 font-sans text-sm font-normal text-slate-600">/ paket</span>
            </p>
            <a href="{{ route('siap.checkout', $checkoutParams) }}"
                class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-4">
                Pilih Paket Lengkap – Rp{{ number_format($bundlePrice, 0, ',', '.') }}
            </a>
        </div>
        <div class="min-w-0 rounded-2xl border border-red-100 bg-white p-5 md:p-6">
            <h3 class="mb-4 text-base font-semibold text-[#292C33]">Semua benefit dalam satu paket</h3>
            <ul class="space-y-3.5">
                @foreach($benefits as $benefit)
                    <li class="flex items-start gap-3 text-sm leading-6 text-slate-700">
                        <x-icon name="check" class="mt-0.5 h-5 w-5 text-red-600" />
                        <span>{{ $benefit }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="mt-5 border-t border-stone-100 pt-4 text-xs leading-5 text-slate-600">
                Termasuk akses hasil lengkap asesmen SIAP dan kode redeem Super Finger. Pengiriman merchandise, jadwal mentoring, mapping, dan voucher dikoordinasikan oleh tim 1Langkah.
            </p>
        </div>
    </div>
</section>
