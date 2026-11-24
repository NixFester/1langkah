@extends('layouts.app', ['activePage' => 'siap.index'])

@section('title', 'Checkout ' . $productName . ' — SIAP 1Langkah')
@section('header_title', 'Checkout Pembayaran SIAP')

@php
    $bundleBenefits = [
        'Tes Multiple Intelligence Test, Psycho Geometric Test, dan Super Finger',
        'Sertifikat asesmen dan merchandise resmi 1Langkah',
        'Private mentoring 1 sesi (2 jam)',
        'Mapping arah karier dan akademisi',
        'Voucher potongan harga untuk kursus, pelatihan, dan pengembangan lainnya di 1Langkah',
    ];
    $isBundle = $productType === \App\Models\SiapOrder::PRODUCT_BUNDLE;
    $isSuperFinger = $productType === \App\Models\SiapOrder::PRODUCT_SUPER_FINGER;
    $promoApplyUrl = route('siap.checkout.promo', [
        'product' => $productType,
        'assessment' => $assessment?->id,
        'attempt' => $attempt?->id,
    ]);
@endphp

@section('content')
<div x-data="siapCheckout()" class="mx-auto max-w-5xl px-1 pb-12 sm:px-2">
    <x-flash-messages />

    <!-- Breadcrumb -->
    <div class="mb-5 flex items-center gap-2 text-xs text-slate-600">
        <a href="{{ route('siap.index') }}" class="inline-flex items-center gap-1.5 hover:text-[#E50000]">
            <x-icon name="grid" class="h-3.5 w-3.5" />
            <span>SIAP</span>
        </a>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">Checkout</span>
    </div>

    <div>
        <h1 class="font-display text-2xl font-semibold leading-snug tracking-tight text-[#292C33] md:text-3xl">
            Satu Langkah Lebih Dekat
        </h1>
        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $productSubtitle }}</p>
    </div>

    @if($errors->any())
        <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siap.checkout.process', $productType) }}" method="POST"
        class="mt-7 grid items-start gap-6 md:grid-cols-[1.3fr_1fr]">
        @csrf
        <input type="hidden" name="promo_code" :value="appliedCode">
        @if($assessment)
            <input type="hidden" name="assessment_id" value="{{ $assessment->id }}">
        @endif
        @if($attempt)
            <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
        @endif

        <!-- Left Column: Participant Data, Xendit Notice & Promo Code -->
        <div class="space-y-6">
            <div class="space-y-5 rounded-2xl border border-stone-200 bg-white p-6">
                <h2 class="font-display text-lg font-semibold text-[#292C33]">Data Peserta</h2>

                <label class="block text-sm font-medium text-[#292C33]">
                    Nama peserta <span class="text-red-500">*</span>
                    <input type="text" name="participant_name" required
                        value="{{ old('participant_name', $lastAttemptInfo?->participant_name ?? $user?->name) }}"
                        class="mt-2 min-h-12 w-full rounded-xl border border-stone-300 px-4 text-sm focus:border-red-600 focus:outline-none">
                </label>

                <label class="block text-sm font-medium text-[#292C33]">
                    Email <span class="text-red-500">*</span>
                    <input type="email" name="email" required
                        value="{{ old('email', $lastAttemptInfo?->google_email ?? $user?->email) }}"
                        placeholder="nama@gmail.com"
                        class="mt-2 min-h-12 w-full rounded-xl border border-stone-300 px-4 text-sm focus:border-red-600 focus:outline-none">
                </label>

                <label class="block text-sm font-medium text-[#292C33]">
                    WhatsApp orang tua / wali (atau peserta) <span class="text-red-500">*</span>
                    <input type="tel" name="parent_whatsapp" required
                        value="{{ old('parent_whatsapp', $lastAttemptInfo?->whatsapp ?? $user?->phone) }}"
                        placeholder="08xxxxxxxxxx"
                        class="mt-2 min-h-12 w-full rounded-xl border border-stone-300 px-4 text-sm focus:border-red-600 focus:outline-none">
                </label>

                <label class="flex min-h-11 items-start gap-3 text-sm leading-6 text-slate-700">
                    <input required type="checkbox" name="parental_consent" value="1" {{ old('parental_consent') ? 'checked' : '' }}
                        class="mt-1.5 h-4 w-4 accent-red-600">
                    <span>Saya menyetujui pembelian asesmen ini dan memiliki izin orang tua/wali jika berusia di bawah umur.</span>
                </label>
            </div>

            <!-- Xendit Payment Notice Card -->
            <div class="rounded-2xl border border-stone-200 bg-white p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-red-50 text-[#E50000]">
                        <x-icon name="shieldCheck" class="h-6 w-6" />
                    </div>
                    <div>
                        <h2 class="font-display text-base font-semibold text-[#292C33]">Pembayaran via Xendit</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-600">
                            Anda akan diarahkan ke halaman checkout Xendit untuk memilih metode pembayaran yang tersedia (QRIS, Virtual Account, E-Wallet, atau Kartu Kredit/Debit) dan menyelesaikan transaksi secara aman.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Promo Code Card -->
            <div class="rounded-2xl border border-stone-200 bg-white p-6">
                <h2 class="font-display text-base font-semibold text-[#292C33]">
                    Kode Diskon / Promo <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                </h2>
                <div class="mt-3 flex flex-col gap-2.5 sm:flex-row">
                    <input type="text" x-model="promoInput" @keydown.enter.prevent="applyPromoCode()"
                        placeholder="Masukkan kode voucher / promo"
                        class="min-h-12 flex-1 rounded-xl border border-stone-300 bg-stone-50 px-4 text-sm font-bold uppercase tracking-wider text-stone-900 focus:border-red-600 focus:bg-white focus:outline-none">
                    <button type="button" @click="applyPromoCode()" :disabled="loadingPromo"
                        class="min-h-12 cursor-pointer rounded-xl bg-stone-900 px-5 text-sm font-semibold text-white hover:bg-black disabled:opacity-50">
                        <span x-text="loadingPromo ? 'Memeriksa…' : 'Gunakan Kode'"></span>
                    </button>
                </div>

                <template x-if="promoMessage">
                    <div class="mt-3 flex items-center justify-between rounded-xl px-4 py-2.5 text-xs font-semibold"
                        :class="promoValid ? 'border border-emerald-200 bg-emerald-50 text-emerald-700' : 'border border-red-200 bg-red-50 text-red-700'">
                        <span x-text="promoMessage"></span>
                        <button x-show="promoValid" type="button" @click="removePromo()" class="ml-2 cursor-pointer underline">Hapus</button>
                    </div>
                </template>

                @if($activePromos->isNotEmpty())
                    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-stone-100 pt-3 text-xs text-slate-500">
                        <span>Kode tersedia:</span>
                        @foreach($activePromos as $p)
                            <button type="button" @click="promoInput = '{{ $p->code }}'; applyPromoCode()"
                                class="cursor-pointer rounded-full border border-red-200 bg-red-50 px-3 py-1 font-bold text-[#E50000] hover:bg-red-100">
                                {{ $p->code }} ({{ $p->type_label }})
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="font-display text-lg font-semibold text-[#292C33]">Ringkasan Pembelian</h2>

            @if($isBundle)
                <ul class="mt-4 space-y-3 rounded-xl bg-red-50 p-4">
                    @foreach($bundleBenefits as $benefit)
                        <li class="flex items-start gap-2 text-sm leading-6 text-slate-700">
                            <x-icon name="check" class="mt-1 h-4 w-4 text-red-700" />
                            <span>{{ $benefit }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            <p class="my-5 font-semibold text-[#292C33]">{{ $productName }}</p>

            <div class="space-y-3 text-sm">
                <p class="flex justify-between text-slate-600">
                    <span>Harga normal</span>
                    <span>Rp{{ number_format($normalPrice, 0, ',', '.') }}</span>
                </p>

                @if($normalPrice > $promoPrice)
                    <p class="flex justify-between text-green-700">
                        <span>Diskon spesial</span>
                        <span>−Rp{{ number_format($normalPrice - $promoPrice, 0, ',', '.') }}</span>
                    </p>
                @endif

                <template x-if="promoDiscount > 0">
                    <p class="flex justify-between font-semibold text-emerald-700">
                        <span>Kode Promo (<span x-text="appliedCode"></span>)</span>
                        <span x-text="formattedPromoDiscount"></span>
                    </p>
                </template>

                <p class="flex justify-between border-t border-stone-200 pt-4 text-lg font-bold text-[#292C33]">
                    <span>Total</span>
                    <span class="text-[#E50000]" x-text="formattedFinal">Rp{{ number_format($promoPrice, 0, ',', '.') }}</span>
                </p>
            </div>

            <button type="submit"
                class="mt-6 min-h-12 w-full cursor-pointer rounded-xl bg-[#E50000] px-4 text-sm font-semibold text-white transition hover:bg-red-700">
                Lanjutkan Pembayaran
            </button>

            <p class="mt-4 text-center text-xs text-slate-500">
                @if(config('payment.driver') === 'xendit')
                    Pembayaran aman diproses melalui Xendit. Anda akan diarahkan ke halaman checkout Xendit.
                @else
                    Mode Mock Payment aktif · pembayaran akan langsung dikonfirmasi.
                @endif
            </p>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function siapCheckout() {
    const basePrice = {{ (int) $promoPrice }};
    return {
        promoInput: '',
        appliedCode: '',
        basePrice,
        promoDiscount: 0,
        finalPrice: basePrice,
        formattedPromoDiscount: '- Rp0',
        formattedFinal: 'Rp' + basePrice.toLocaleString('id-ID'),
        promoMessage: '',
        promoValid: false,
        loadingPromo: false,

        applyPromoCode() {
            const code = this.promoInput.trim();
            if (!code) {
                this.promoValid = false;
                this.promoMessage = 'Silakan masukkan kode diskon terlebih dahulu.';
                return;
            }

            this.loadingPromo = true;
            fetch(@json($promoApplyUrl), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ promo_code: code }),
            })
            .then(async res => {
                const data = await res.json();
                this.loadingPromo = false;
                if (res.ok && data.valid) {
                    this.promoValid = true;
                    this.appliedCode = data.code;
                    this.promoDiscount = data.discount;
                    this.finalPrice = data.final_price;
                    this.formattedPromoDiscount = data.formatted_discount;
                    this.formattedFinal = data.formatted_final;
                    this.promoMessage = data.message;
                } else {
                    this.removePromo(data.message || 'Kode diskon tidak valid.');
                }
            })
            .catch(() => {
                this.loadingPromo = false;
                this.removePromo('Gagal memeriksa kode diskon.');
            });
        },

        removePromo(errorMsg = '') {
            this.appliedCode = '';
            this.promoDiscount = 0;
            this.finalPrice = this.basePrice;
            this.formattedPromoDiscount = '- Rp0';
            this.formattedFinal = 'Rp' + this.basePrice.toLocaleString('id-ID');
            this.promoValid = false;
            this.promoMessage = errorMsg;
        }
    };
}
</script>
@endpush
