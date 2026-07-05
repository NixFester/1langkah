@extends('layouts.keuangan')

@section('title', 'Dashboard Keuangan')
@section('header_title', 'Dashboard Keuangan')

@section('content')
    <x-flash-messages />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card label="Menunggu Verifikasi" :value="$stats['pending']" icon="clock" color="amber" />
        <x-stat-card label="Disetujui Hari Ini" :value="$stats['approved_today']" icon="check" color="green" />
        <x-stat-card label="Ditolak Hari Ini" :value="$stats['rejected_today']" icon="x" color="red" />
        <x-stat-card label="Total User" :value="$stats['total_users']" icon="users" color="blue" />
    </div>

    {{-- Revenue Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-green-100 font-medium">Pendapatan Hari Ini</h3>
                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold mb-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="text-green-100 text-sm">{{ $stats['approved_today'] }} transaksi berhasil</p>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-blue-100 font-medium">Pendapatan Bulan Ini</h3>
                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold mb-1">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
            <p class="text-blue-100 text-sm">Total pendapatan bulan ini</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pending Payments --}}
        <x-card-panel title="Pembayaran Menunggu" :actionRoute="route('keuangan.verifications')" actionLabel="Lihat Semua">
            @if($recentPending->isEmpty())
                <x-empty-state message="Tidak ada pembayaran menunggu" icon="success" />
            @else
                <div class="space-y-4">
                    @foreach($recentPending as $payment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                    <span class="text-amber-600 font-bold">{{ substr($payment->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $payment->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->course_title }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400">{{ $payment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Recent Verified --}}
        <x-card-panel title="Pembayaran Terakhir">
            @if($recentVerified->isEmpty())
                <x-empty-state message="Belum ada pembayaran diverifikasi" icon="payment" />
            @else
                <div class="space-y-4">
                    @foreach($recentVerified as $payment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $payment->isApproved() ? 'bg-green-100' : 'bg-red-100' }}">
                                    @if($payment->isApproved())
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $payment->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->course_title }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold {{ $payment->isApproved() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $payment->isApproved() ? 'Disetujui' : 'Ditolak' }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $payment->verified_at?->diffForHumans() ?? '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>
@endsection
