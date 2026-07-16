@extends('layouts.keuangan')

@section('title', __('app.finance_dashboard'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.finance_dashboard')"
        :description="__('app.overview_system')"
    />

    <x-flash-messages />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <x-stat-card :label="__('app.awaiting_verification')" :value="$stats['pending']" icon="clock" color="amber" />
        <x-stat-card :label="__('app.approved_today')" :value="$stats['approved_today']" icon="check" color="green" />
        <x-stat-card :label="__('app.rejected_today')" :value="$stats['rejected_today']" icon="x" color="red" />
        <x-stat-card :label="__('app.total_users')" :value="$stats['total_users']" icon="users" color="blue" />
    </div>

    {{-- Revenue Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex items-center justify-between mb-4">
                <h2 class="text-green-100 font-medium">{{ __('app.today_revenue') }}</h2>
                <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="relative z-10 text-3xl sm:text-4xl font-bold mb-1">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
            <p class="relative z-10 text-green-100 text-sm">{{ $stats['approved_today'] }} {{ __('app.successful_transactions') }}</p>
        </div>

        <div class="bg-[#cc0000] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-red-500/50 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex items-center justify-between mb-4">
                <h2 class="text-red-100 font-medium">{{ __('app.month_revenue') }}</h2>
                <svg class="w-8 h-8 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <p class="relative z-10 text-3xl sm:text-4xl font-bold mb-1">Rp {{ number_format($monthRevenue, 0, ',', '.') }}</p>
            <p class="relative z-10 text-red-100 text-sm">{{ __('app.total_month_revenue') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pending Payments --}}
        <x-card-panel :title="__('app.pending_payments')" :actionRoute="route('keuangan.verifications')" :actionLabel="__('app.view_all')">
            @if($recentPending->isEmpty())
                <x-empty-state :message="__('app.no_pending_payments')" icon="success" />
            @else
                <div class="space-y-3">
                    @foreach($recentPending as $payment)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-amber-600 font-bold">{{ substr($payment->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[13px] font-bold text-gray-900 truncate">{{ $payment->user->name ?? __('app.unknown') }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ $payment->course_title }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[12px] font-bold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                <p class="text-[11px] text-gray-400">{{ $payment->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Recent Verified --}}
        <x-card-panel :title="__('app.recent_payments')">
            @if($recentVerified->isEmpty())
                <x-empty-state :message="__('app.no_verified_payments_yet')" icon="payment" />
            @else
                <div class="space-y-3">
                    @foreach($recentVerified as $payment)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $payment->isApproved() ? 'bg-green-50' : 'bg-red-50' }}">
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
                                <div class="min-w-0">
                                    <p class="text-[13px] font-bold text-gray-900 truncate">{{ $payment->user->name ?? __('app.unknown') }}</p>
                                    <p class="text-[11px] text-gray-500 truncate">{{ $payment->course_title }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[12px] font-bold {{ $payment->isApproved() ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $payment->isApproved() ? __('app.approved') : __('app.rejected') }}
                                </p>
                                <p class="text-[11px] text-gray-400">{{ $payment->verified_at?->diffForHumans() ?? '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>

</div>
@endsection
