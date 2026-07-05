@extends('layouts.keuangan')

@section('title', 'Detail Verifikasi')
@section('header_title', 'Detail Verifikasi Pembayaran')

@section('content')
    <x-back-button route="{{ route('keuangan.verifications') }}" theme="amber" />

    <x-flash-messages />

    <div class="max-w-3xl mx-auto">
        {{-- Payment Info --}}
        <x-card-panel title="Informasi Pembayaran" class="mb-6">
            @php $badge = $verification->status_badge; @endphp
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-800">Informasi Pembayaran</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $badge['class'] }}">
                    {{ $badge['label'] }}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nama Siswa</p>
                    <p class="font-medium text-gray-800">{{ $verification->user->name ?? 'Unknown' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Email</p>
                    <p class="font-medium text-gray-800">{{ $verification->user->email ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kursus</p>
                    <p class="font-medium text-gray-800">{{ $verification->course_title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tipe</p>
                    <p class="font-medium text-gray-800">{{ ucfirst($verification->course_type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah</p>
                    <p class="font-bold text-xl text-green-600">Rp {{ number_format($verification->amount, 0, ',', '.') }}</p>
                </div>
                @if($verification->promo_code)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Promo Code</p>
                    <p class="font-medium text-pink-600">{{ $verification->promo_code }}</p>
                    @if($verification->discount_amount)
                        <p class="text-sm text-gray-500">Diskon: Rp {{ number_format($verification->discount_amount, 0, ',', '.') }}</p>
                    @endif
                </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 mb-1">Metode Bayar</p>
                    <p class="font-medium text-gray-800">{{ $verification->payment_method ?? 'Transfer Bank' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Submit</p>
                    <p class="font-medium text-gray-800">{{ $verification->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </x-card-panel>

        {{-- Payment Proof --}}
        <x-card-panel title="Bukti Pembayaran" class="mb-6">
            <img src="{{ $verification->proof_image }}" alt="Bukti Bayar" class="max-w-md rounded-lg border border-gray-200">
        </x-card-panel>

        {{-- Verification Actions --}}
        @if($verification->isPending())
            <x-card-panel title="Verifikasi Pembayaran">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Approve Form --}}
                    <form action="{{ route('keuangan.verifications.approve', $verification) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                            <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Catatan verifikasi..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Setujui Pembayaran
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <form action="{{ route('keuangan.verifications.reject', $verification) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2" placeholder="Jelaskan alasan penolakan..." required></textarea>
                        </div>
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>
            </x-card-panel>
        @else
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex items-center gap-3 mb-4">
                    @if($verification->isApproved())
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="font-bold text-green-800">Pembayaran Disetujui</h3>
                    @else
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="font-bold text-red-800">Pembayaran Ditolak</h3>
                    @endif
                </div>
                <p class="text-sm text-gray-600">
                    <strong>Diverifikasi oleh:</strong> {{ $verification->verifier->name ?? 'Unknown' }}<br>
                    <strong>Waktu:</strong> {{ $verification->verified_at?->format('d/m/Y H:i') }}<br>
                    @if($verification->verification_notes)
                        <strong>Catatan:</strong> {{ $verification->verification_notes }}<br>
                    @endif
                    @if($verification->rejection_reason)
                        <strong>Alasan:</strong> {{ $verification->rejection_reason }}
                    @endif
                </p>
            </div>
        @endif
    </div>
@endsection
