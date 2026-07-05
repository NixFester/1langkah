@extends('layouts.superadmin')

@section('title', 'Audit Log')

@section('header_title', 'Audit Log')

@section('content')
    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Total Log</p>
            <p class="text-2xl font-bold text-purple-600">{{ $summary['total_logs'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Hari Ini</p>
            <p class="text-2xl font-bold text-blue-600">{{ $summary['today_logs'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Minggu Ini</p>
            <p class="text-2xl font-bold text-green-600">{{ $summary['this_week'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-sm text-gray-500">Login Hari Ini</p>
            <p class="text-2xl font-bold text-amber-600">{{ $summary['logins_today'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
                <select name="action" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Semua</option>
                    <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                    <option value="verified" {{ request('action') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="role_changed" {{ request('action') === 'role_changed' ? 'selected' : '' }}>Role Changed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Filter</button>
                <a href="{{ route('superadmin.audit-logs') }}" class="ml-2 px-4 py-2 text-gray-600 hover:text-gray-800">Reset</a>
            </div>
        </form>
    </div>

    {{-- Log Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-800">{{ $log->user?->name ?? 'System' }}</p>
                            <p class="text-xs text-gray-400">{{ $log->user?->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ match($log->action) {
                                    'created' => 'bg-green-100 text-green-700',
                                    'updated' => 'bg-blue-100 text-blue-700',
                                    'deleted' => 'bg-red-100 text-red-700',
                                    'verified' => 'bg-purple-100 text-purple-700',
                                    'role_changed' => 'bg-amber-100 text-amber-700',
                                    default => 'bg-gray-100 text-gray-700'
                                } }}">
                                {{ $log->action_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $log->description ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($log->model_type)
                                <span class="text-xs text-gray-500">{{ class_basename($log->model_type) }}
                                    @if($log->model_id) #{{ $log->model_id }} @endif
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada log</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->withQueryString()->links() }}
    </div>
@endsection
