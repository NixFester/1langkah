@extends('layouts.superadmin')

@section('title', 'Statistik Sistem')

@section('header_title', 'Statistik Sistem')

@section('content')
    {{-- Overall Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total User</p>
            <p class="text-3xl font-bold text-purple-600">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Kursus</p>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_courses'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Enrollments</p>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_enrollments'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-xl font-bold text-amber-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Pending Verify</p>
            <p class="text-3xl font-bold text-red-600">{{ $stats['pending_verifications'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- User Growth --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">User Growth (12 Bulan)</h3>
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="pb-3">Bulan</th>
                        <th class="pb-3">Jumlah</th>
                        <th class="pb-3">Growth</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($monthlyUsers as $i => $month)
                        <tr>
                            <td class="py-2 text-gray-800">{{ \Carbon\Carbon::create($month->year, $month->month)->format('M Y') }}</td>
                            <td class="py-2 font-medium text-gray-800">{{ $month->count }}</td>
                            <td class="py-2">
                                @if($i > 0)
                                    @php
                                        $prev = $monthlyUsers[$i-1]->count;
                                        $growth = $prev > 0 ? round((($month->count - $prev) / $prev) * 100, 1) : 0;
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
                        <tr><td colspan="3" class="py-4 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Revenue --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-4">Revenue per Bulan</h3>
            <table class="w-full">
                <thead>
                    <tr class="text-left text-xs text-gray-500 uppercase">
                        <th class="pb-3">Bulan</th>
                        <th class="pb-3">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($monthlyRevenue as $month)
                        <tr>
                            <td class="py-2 text-gray-800">{{ \Carbon\Carbon::create($month->year, $month->month)->format('M Y') }}</td>
                            <td class="py-2 font-bold text-green-600">Rp {{ number_format($month->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="py-4 text-center text-gray-400">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
