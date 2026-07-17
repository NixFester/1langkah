@extends('layouts.keuangan')

@section('title', __('app.revenue_report'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.revenue_report')"
        :description="__('app.overview_system')"
    />

    <x-flash-messages />

    {{-- Date Filter --}}
    <x-filter-form
        :showExport="true"
        :exportRoute="route('keuangan.reports.export', ['date_from' => $startDate, 'date_to' => $endDate])"
        :exportLabel="__('app.export_csv')"
    >
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.from_date') }}</label>
            <input aria-label="Date From" type="date" name="date_from" value="{{ $startDate }}" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.to_date') }}</label>
            <input aria-label="Date To" type="date" name="date_to" value="{{ $endDate }}" class="border border-gray-300 rounded-lg px-4 py-2 w-full">
        </div>
    </x-filter-form>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="relative z-10 flex items-center justify-between mb-4">
                <h2 class="text-gray-500 font-medium">{{ __('app.total_revenue') }}</h2>
                <div class="w-10 h-10 rounded-xl bg-red-50 text-[#cc0000] flex items-center justify-center">
                    <x-icon name="creditCard" class="w-5 h-5" />
                </div>
            </div>
            <p class="relative z-10 text-3xl sm:text-4xl font-bold mb-1 text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="relative z-10 text-gray-500 text-sm">{{ $totalApproved }} {{ __('app.transactions') }}</p>
        </div>
        <x-stat-card :label="__('app.total_discount')" :value="'Rp ' . number_format($totalDiscount, 0, ',', '.')" icon="promo" color="red" />
        <x-stat-card :label="__('app.approved_transactions')" :value="$totalApproved" icon="check" color="green" />
    </div>

    {{-- Daily Revenue Table --}}
    <x-card-panel :title="__('app.daily_revenue')">
        <x-data-table>
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.date') }}</th>
                    <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.transaction_count') }}</th>
                    <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.total_revenue') }}</th>
                </tr>
            </thead>
            @forelse($dailyRevenue as $row)
                <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-800">{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-800">{{ $row->count }}</td>
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 md:px-6 py-8 text-center">
                        <x-empty-state :message="__('app.no_data')" icon="chart" />
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </x-card-panel>

    {{-- Top Courses --}}
    <x-card-panel :title="__('app.top_courses')">
        <x-data-table>
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-4 md:px-6 py-4 font-bold text-left">#</th>
                    <th class="px-4 md:px-6 py-4 font-bold text-left">Kursus</th>
                    <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.transactions') }}</th>
                    <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.total_revenue') }}</th>
                </tr>
            </thead>
            @forelse($topCourses as $i => $course)
                <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-800">{{ $course->course_title }}</td>
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-800">{{ $course->count }}</td>
                    <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($course->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 md:px-6 py-8 text-center">
                        <x-empty-state :message="__('app.no_data')" icon="chart" />
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </x-card-panel>

</div>
@endsection
