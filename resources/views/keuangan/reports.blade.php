@extends('layouts.keuangan')

@section('title', __('app.revenue_report'))
@section('header_title', __('app.revenue_report'))

@section('content')
    <x-flash-messages />

    {{-- Date Filter --}}
    <x-filter-form
        :showExport="true"
        :exportRoute="route('keuangan.reports.export', ['date_from' => $startDate, 'date_to' => $endDate])"
        :exportLabel="__('app.export_csv')"
    >
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.from_date') }}</label>
            <input aria-label="Date From" type="date" name="date_from" value="{{ $startDate }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.to_date') }}</label>
            <input aria-label="Date To" type="date" name="date_to" value="{{ $endDate }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
    </x-filter-form>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <p class="text-green-100 text-sm mb-1">{{ __('app.total_revenue') }}</p>
            <p class="text-3xl font-bold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-green-100 text-sm mt-2">{{ $totalApproved }} {{ __('app.transactions') }}</p>
        </div>
        <x-stat-card :label="__('app.total_discount')" :value="'Rp ' . number_format($totalDiscount, 0, ',', '.')" color="pink" />
        <x-stat-card :label="__('app.approved_transactions')" :value="$totalApproved" icon="check" color="green" />
    </div>

    {{-- Daily Revenue Table --}}
    <x-card-panel :title="__('app.daily_revenue')" class="mb-6">
        <x-data-table>
            <template #thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.transaction_count') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.total_revenue') }}</th>
                </tr>
            </template>
            @forelse($dailyRevenue as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-800">{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $row->count }}</td>
                    <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-8">
                        <x-empty-state :message="__('app.no_data')" icon="chart" />
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </x-card-panel>

    {{-- Top Courses --}}
    <x-card-panel :title="__('app.top_courses')">
        <x-data-table>
            <template #thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kursus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.transactions') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.total_revenue') }}</th>
                </tr>
            </template>
            @forelse($topCourses as $i => $course)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $course->course_title }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $course->count }}</td>
                    <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($course->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8">
                        <x-empty-state :message="__('app.no_data')" icon="chart" />
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </x-card-panel>
@endsection
