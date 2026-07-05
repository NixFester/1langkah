@extends('layouts.marketing')

@section('title', 'Analytics')
@section('header_title', 'Analytics')

@section('content')
    {{-- Promo Usage Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-stat-card label="Total Promo Digunakan" :value="$promoUsage['total_used']" icon="promo" color="pink" />
        <x-stat-card label="Total Diskon Diberikan" :value="'Rp ' . number_format($promoUsage['total_discount'], 0, ',', '.')" color="red" />
        <x-stat-card label="Rata-rata Diskon" :value="'Rp ' . number_format($promoUsage['avg_discount'], 0, ',', '.')" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue by Course --}}
        <x-card-panel title="Revenue per Kursus">
            <x-data-table>
                <template #thead>
                    <tr>
                        <th class="pb-3 text-left text-xs text-gray-500 uppercase">Kursus</th>
                        <th class="pb-3 text-left text-xs text-gray-500 uppercase">Transaksi</th>
                        <th class="pb-3 text-left text-xs text-gray-500 uppercase">Revenue</th>
                    </tr>
                </template>
                @forelse($revenueByCourse as $course)
                    <tr>
                        <td class="py-3 text-gray-800">{{ $course->course_title }}</td>
                        <td class="py-3 text-gray-600">{{ $course->count }}</td>
                        <td class="py-3 font-bold text-green-600">Rp {{ number_format($course->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-4"><x-empty-state message="Tidak ada data" icon="chart" /></td></tr>
                @endforelse
            </x-data-table>
        </x-card-panel>

        {{-- User Trend --}}
        <x-card-panel title="Trend User Baru (30 Hari)">
            @if($userTrend->isEmpty())
                <x-empty-state message="Tidak ada data" icon="users" />
            @else
                <div class="space-y-2">
                    @foreach($userTrend as $day)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                            <div class="flex items-center gap-2">
                                <div class="w-32 h-4 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($day->count / max($userTrend->max('count'), 1)) * 100 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-800 w-8">{{ $day->count }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>
@endsection
