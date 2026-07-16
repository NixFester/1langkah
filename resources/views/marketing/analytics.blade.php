@extends('layouts.marketing')

@section('title', __('app.analytics'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.analytics')"
        :description="__('app.overview_system')"
    />

    {{-- Promo Usage Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <x-stat-card :label="__('app.total_promo_used')" :value="$promoUsage['total_used']" icon="award" color="red" />
        <x-stat-card :label="__('app.total_discount_given')" :value="'Rp ' . number_format($promoUsage['total_discount'], 0, ',', '.')" icon="creditCard" color="blue" />
        <x-stat-card :label="__('app.avg_discount')" :value="'Rp ' . number_format($promoUsage['avg_discount'], 0, ',', '.')" icon="barChart" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue by Course --}}
        <x-card-panel :title="__('app.revenue_per_course')">
            <x-data-table>
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.course') }}</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.transactions') }}</th>
                        <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.revenue') }}</th>
                    </tr>
                </thead>
                @forelse($revenueByCourse as $course)
                    <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-800">{{ $course->course_title }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-600">{{ $course->count }}</td>
                        <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($course->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 md:px-6 py-8 text-center"><x-empty-state :message="__('app.no_data')" icon="chart" /></td></tr>
                @endforelse
            </x-data-table>
        </x-card-panel>

        {{-- User Trend --}}
        <x-card-panel :title="__('app.new_user_trend_30')">
            @if($userTrend->isEmpty())
                <x-empty-state :message="__('app.no_data')" icon="users" />
            @else
                <div class="space-y-3">
                    @foreach($userTrend as $day)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                            <span class="text-[13px] font-bold text-gray-700">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                            <div class="flex items-center gap-3">
                                <div class="w-32 md:w-48 h-3 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-500 rounded-full" style="width: {{ ($day->count / max($userTrend->max('count'), 1)) * 100 }}%"></div>
                                </div>
                                <span class="text-[13px] font-bold text-gray-900 w-8 text-right">{{ $day->count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>

</div>
@endsection
