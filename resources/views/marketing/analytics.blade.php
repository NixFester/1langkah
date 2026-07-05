@extends('layouts.marketing')

@section('title', 'Analytics')

@section('header_title', 'Analytics')

@section('content')
    {{-- Promo Usage Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Total Promo Digunakan</p>
            <p class="text-3xl font-bold text-pink-600">{{ $promoUsage['total_used'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Total Diskon Diberikan</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($promoUsage['total_discount'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500 mb-1">Rata-rata Diskon</p>
            <p class="text-3xl font-bold text-amber-600">Rp {{ number_format($promoUsage['avg_discount'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Revenue by Course --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Revenue per Kursus</h3>
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="pb-3">Kursus</th>
                        <th class="pb-3">Transaksi</th>
                        <th class="pb-3">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($revenueByCourse as $course)
                        <tr>
                            <td class="py-3 text-gray-800">{{ $course->course_title }}</td>
                            <td class="py-3 text-gray-600">{{ $course->count }}</td>
                            <td class="py-3 font-bold text-green-600">Rp {{ number_format($course->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="py-4 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- User Trend --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Trend User Baru (30 Hari)</h3>
            <div class="space-y-2">
                @forelse($userTrend as $day)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-32 h-4 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($day->count / max($userTrend->max('count'), 1)) * 100 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-800 w-8">{{ $day->count }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-400 py-4">Tidak ada data</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
