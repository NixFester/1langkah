@extends('layouts.superadmin')

@section('title', __('app.system_statistics'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.system_statistics')"
        :description="__('app.overview_system')"
    />

    {{-- Overall Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 md:gap-6">
        <x-stat-card :label="__('app.total_users')" :value="$stats['total_users']" icon="users" color="red" />
        <x-stat-card :label="__('app.total_courses')" :value="$stats['total_courses']" icon="book" color="blue" />
        <x-stat-card :label="__('app.total_enrollments')" :value="$stats['total_enrollments']" icon="award" color="green" />
        <x-stat-card :label="__('app.total_revenue')" :value="'Rp ' . number_format($stats['total_revenue'], 0, ',', '.')" icon="creditCard" color="amber" />
        <x-stat-card :label="__('app.pending_verify')" :value="$stats['pending_verifications']" icon="shieldCheck" color="orange" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- User Growth --}}
        <x-card-panel :title="__('app.user_growth_12')">
            <x-data-table>
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.month') }}</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.amount') }}</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.growth') }}</th>
                    </tr>
                </thead>
                @forelse($monthlyUsers as $i => $month)
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::create($month->year, $month->month)->format('M Y') }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $month->count }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm">
                            @if($i > 0)
                                @php
                                    $prev = $monthlyUsers[$i-1]->count;
                                    $growth = $prev > 0 ? round(($month->count - $prev) / $prev * 100, 1) : 0;
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $growth >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $growth >= 0 ? '+' : '' }}{{ $growth }}%
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 md:px-6 py-8 text-center text-gray-400"><x-empty-state :message="__('app.no_data')" icon="chart" /></td></tr>
                @endforelse
            </x-data-table>
        </x-card-panel>

        {{-- Revenue per Bulan --}}
        <x-card-panel :title="__('app.revenue_per_month')">
            <x-data-table>
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.month') }}</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.revenue') }}</th>
                    </tr>
                </thead>
                @forelse($monthlyRevenue as $month)
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::create($month->year, $month->month)->format('M Y') }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($month->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-4 md:px-6 py-8 text-center text-gray-400"><x-empty-state :message="__('app.no_data')" icon="chart" /></td></tr>
                @endforelse
            </x-data-table>
        </x-card-panel>
    </div>

</div>
@endsection
