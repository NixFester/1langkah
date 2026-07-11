@extends('layouts.superadmin')

@section('title', __('app.system_statistics'))
@section('header_title', __('app.system_statistics'))

@section('content')
    {{-- Overall Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <x-stat-card :label="__('app.total_users')" :value="$stats['total_users']" color="purple" />
        <x-stat-card :label="__('app.total_courses')" :value="$stats['total_courses']" color="blue" />
        <x-stat-card :label="__('app.total_enrollments')" :value="$stats['total_enrollments']" color="green" />
        <x-stat-card :label="__('app.total_revenue')" :value="'Rp ' . number_format($stats['total_revenue'], 0, ',', '.')" color="amber" />
        <x-stat-card :label="__('app.pending_verify')" :value="$stats['pending_verifications']" color="red" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- User Growth --}}
        <x-card-panel :title="__('app.user_growth_12')">
            <x-data-table>
                <template #thead>
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="pb-3">{{ __('app.month') }}</th>
                        <th class="pb-3">{{ __('app.amount') }}</th>
                        <th class="pb-3">{{ __('app.growth') }}</th>
                    </tr>
                </template>
                @forelse($monthlyUsers as $i => $month)
                    <tr>
                        <td class="py-2 text-gray-800">{{ \Carbon\Carbon::create($month->year, $month->month)->format('M Y') }}</td>
                        <td class="py-2 font-medium text-gray-800">{{ $month->count }}</td>
                        <td class="py-2">
                            @if($i > 0)
                                @php
                                    $prev = $monthlyUsers[$i-1]->count;
                                    $growth = $prev > 0 ? round(($month->count - $prev) / $prev * 100, 1) : 0;
                                @endphp
                                <span class="{{ $growth >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $growth >= 0 ? '+' : '' }}{{ $growth }}%
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-4 text-center text-gray-400">{{ __('app.no_data') }}</td></tr>
                @endforelse
            </x-data-table>
        </x-card-panel>

        {{-- Revenue per Bulan --}}
        <x-card-panel :title="__('app.revenue_per_month')">
            <x-data-table>
                <template #thead>
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="pb-3">{{ __('app.month') }}</th>
                        <th class="pb-3">{{ __('app.revenue') }}</th>
                    </tr>
                </template>
                @forelse($monthlyRevenue as $month)
                    <tr>
                        <td class="py-2 text-gray-800">{{ \Carbon\Carbon::create($month->year, $month->month)->format('M Y') }}</td>
                        <td class="py-2 font-bold text-green-600">Rp {{ number_format($month->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="py-4 text-center text-gray-400">{{ __('app.no_data') }}</td></tr>
                @endforelse
            </x-data-table>
        </x-card-panel>
    </div>
@endsection
