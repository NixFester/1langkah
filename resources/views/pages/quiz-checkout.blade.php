@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', 'Checkout ' . $quiz->title . ' — 1Langkah')

@section('content')
<div x-data="quizCheckout()" class="w-full max-w-5xl mx-auto px-2 pb-12 space-y-6">

    <x-flash-messages />

    <!-- Header -->
    <div>
        <a href="{{ $attempt ? route('quiz.result', $attempt) : route('quiz.start', $quiz) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-900 text-sm font-bold mb-3 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            {{ __('app.back') }}
        </a>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Checkout Pembayaran Tes Psikologi</h1>
        <p class="text-sm text-gray-500 mt-1">Buka akses penuh {{ $quiz->questions->count() }} soal, analisis 4 dimensi kepribadian, superpower, rekomendasi karir, dan pengiriman laporan lengkap ke email Google Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Payment Methods & Discount Code -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Payment Methods Card -->
            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-extrabold text-gray-900 mb-4">{{ __('app.choose_payment_method') }}</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- QRIS -->
                    <div @click="paymentMethod = 'qris'" :class="paymentMethod === 'qris' ? 'border-[#cc0000] bg-red-50/40 ring-2 ring-red-500/10' : 'border-gray-200 hover:bg-gray-50'" class="border-2 rounded-2xl p-4 flex items-center gap-3.5 cursor-pointer transition-all">
                        <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-900">QRIS</div>
                            <div class="text-xs text-gray-500">{{ __('app.qris_scan') }}</div>
                        </div>
                    </div>

                    <!-- E-Wallet -->
                    <div @click="paymentMethod = 'ewallet'" :class="paymentMethod === 'ewallet' ? 'border-[#cc0000] bg-red-50/40 ring-2 ring-red-500/10' : 'border-gray-200 hover:bg-gray-50'" class="border-2 rounded-2xl p-4 flex items-center gap-3.5 cursor-pointer transition-all">
                        <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-900">{{ __('app.ewallet') }}</div>
                            <div class="text-xs text-gray-500">GoPay, OVO, DANA, ShopeePay</div>
                        </div>
                    </div>

                    <!-- Virtual Account -->
                    <div @click="paymentMethod = 'va'" :class="paymentMethod === 'va' ? 'border-[#cc0000] bg-red-50/40 ring-2 ring-red-500/10' : 'border-gray-200 hover:bg-gray-50'" class="border-2 rounded-2xl p-4 flex items-center gap-3.5 cursor-pointer transition-all">
                        <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-900">{{ __('app.virtual_account') }}</div>
                            <div class="text-xs text-gray-500">BCA, Mandiri, BNI, BRI</div>
                        </div>
                    </div>

                    <!-- Kartu Debit / Kredit -->
                    <div @click="paymentMethod = 'cc'" :class="paymentMethod === 'cc' ? 'border-[#cc0000] bg-red-50/40 ring-2 ring-red-500/10' : 'border-gray-200 hover:bg-gray-50'" class="border-2 rounded-2xl p-4 flex items-center gap-3.5 cursor-pointer transition-all">
                        <div class="w-11 h-11 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0 text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-900">{{ __('app.credit_debit_card') }}</div>
                            <div class="text-xs text-gray-500">{{ __('app.visa_mastercard') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount / Promo Code Card -->
            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-extrabold text-gray-900 flex items-center gap-2">
                        <span>Kode Diskon / Promo</span>
                        <span class="text-xs font-medium text-gray-400">({{ __('app.optional') }})</span>
                    </h2>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" x-model="promoInput" @keydown.enter.prevent="applyPromoCode()"
                        placeholder="Masukkan kode diskon (contoh: CONTOH)"
                        class="flex-1 bg-gray-50 border border-gray-200 rounded-full px-5 py-3 text-sm font-bold uppercase tracking-wider text-gray-800 focus:bg-white focus:border-[#cc0000] outline-none transition-all">
                    <button type="button" @click="applyPromoCode()" :disabled="loadingPromo"
                        class="px-7 py-3 rounded-full bg-gray-900 hover:bg-black text-white font-bold text-sm transition-colors cursor-pointer disabled:opacity-50">
                        <span x-text="loadingPromo ? 'Memeriksa...' : 'Gunakan Kode'"></span>
                    </button>
                </div>

                <!-- Promo feedback message -->
                <template x-if="promoMessage">
                    <div class="mt-3 px-4 py-2.5 rounded-xl text-xs font-bold flex items-center justify-between"
                        :class="promoValid ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200'">
                        <span x-text="promoMessage"></span>
                        <button x-show="promoValid" type="button" @click="removePromo()" class="text-xs underline ml-2">Hapus</button>
                    </div>
                </template>

                <!-- Quick test promo chips -->
                <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs text-gray-500">
                    <span>Kode diskon tersedia untuk testing:</span>
                    <button type="button" @click="promoInput = 'CONTOH'; applyPromoCode()"
                        class="px-3 py-1 rounded-full bg-red-50 hover:bg-red-100 text-[#cc0000] font-extrabold border border-red-200 transition-colors cursor-pointer">
                        CONTOH (Klik untuk pakai)
                    </button>
                    @foreach($activePromos as $p)
                        @if(strtoupper($p->code) !== 'CONTOH')
                        <button type="button" @click="promoInput = '{{ $p->code }}'; applyPromoCode()"
                            class="px-3 py-1 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition-colors cursor-pointer">
                            {{ $p->code }} ({{ $p->type_label }})
                        </button>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column: Order Summary & Pay Action -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white border border-gray-200 rounded-3xl p-6 sm:p-8 shadow-sm">
                <h2 class="text-lg font-extrabold text-gray-900 mb-5">{{ __('app.order_summary') }}</h2>

                <!-- Item Info -->
                <div class="flex items-start gap-4 pb-5 border-b border-dashed border-gray-200">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-[#cc0000] flex items-center justify-center flex-shrink-0 font-bold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <div>
                        <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-red-100 text-red-700 uppercase">Full Akses Tes Psikologi</span>
                        <h3 class="font-extrabold text-gray-900 text-base mt-1 leading-snug">{{ $quiz->title }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">
                            @if($attempt)
                                Buka Hasil Penuh Attempt #QUIZ-{{ $attempt->id }} & Semua 20 Soal
                            @else
                                Buka Semua {{ $quiz->questions->count() }} Soal & Laporan Kepribadian Penuh
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="py-5 border-b border-dashed border-gray-200 space-y-3 text-sm">
                    <div class="flex justify-between items-center text-gray-600">
                        <span>Harga Full Akses Tes</span>
                        <span class="font-bold text-gray-900">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                    </div>

                    <template x-if="discountAmount > 0">
                        <div class="flex justify-between items-center text-emerald-600 font-bold">
                            <span>Diskon (<span x-text="appliedCode"></span>)</span>
                            <span x-text="formattedDiscount"></span>
                        </div>
                    </template>
                </div>

                <!-- Final Total -->
                <div class="py-5 flex justify-between items-center">
                    <span class="text-base font-extrabold text-gray-900">{{ __('app.total') }} Bayar</span>
                    <span class="text-2xl font-black text-[#cc0000]" x-text="formattedFinal">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                </div>

                <!-- Submit Mock Payment Form -->
                <form action="{{ route('quiz.checkout.process', $quiz) }}" method="POST">
                    @csrf
                    <input type="hidden" name="promo_code" :value="appliedCode">
                    <input type="hidden" name="payment_method" :value="paymentMethod">
                    @if($attempt)
                        <input type="hidden" name="attempt_id" value="{{ $attempt->id }}">
                    @endif

                    <button type="submit" class="w-full py-4 px-6 rounded-full bg-[#cc0000] hover:bg-red-700 text-white font-extrabold text-base transition-all shadow-lg shadow-red-600/25 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Bayar Sekarang (Mock Payment)</span>
                    </button>
                </form>

                <p class="text-xs text-gray-400 text-center mt-3">
                    Mode Testing Mock Payment: Mengklik tombol di atas akan otomatis melunasi pembayaran dan membuka seluruh hasil tes.
                </p>

                <!-- Benefits -->
                <div class="mt-6 pt-5 border-t border-gray-100 space-y-2.5 text-xs text-gray-500">
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">✓</span>
                        <span>Buka seluruh {{ $quiz->questions->count() }} soal tanpa dikunci</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">✓</span>
                        <span>Analisis 4 Dimensi Kepribadian, Superpower, Strengths & Blind Spots</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600 font-bold">✓</span>
                        <span>Rincian seluruh jawaban & pengiriman otomatis ke Email Google Anda</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function quizCheckout() {
    return {
        paymentMethod: 'qris',
        promoInput: '',
        appliedCode: '',
        basePrice: {{ (int) $basePrice }},
        discountAmount: 0,
        finalPrice: {{ (int) $basePrice }},
        formattedDiscount: '- Rp 0',
        formattedFinal: 'Rp {{ number_format($basePrice, 0, ',', '.') }}',
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
            fetch(`{{ route('quiz.checkout.promo', $quiz) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ promo_code: code })
            })
            .then(async res => {
                const data = await res.json();
                this.loadingPromo = false;
                if (res.ok && data.valid) {
                    this.promoValid = true;
                    this.appliedCode = data.code;
                    this.discountAmount = data.discount;
                    this.finalPrice = data.final_price;
                    this.formattedDiscount = data.formatted_discount;
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
            this.discountAmount = 0;
            this.finalPrice = this.basePrice;
            this.formattedDiscount = '- Rp 0';
            this.formattedFinal = 'Rp ' + this.basePrice.toLocaleString('id-ID');
            this.promoValid = false;
            this.promoMessage = errorMsg;
        }
    }
}
</script>
@endpush
