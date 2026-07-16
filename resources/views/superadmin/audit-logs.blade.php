@extends('layouts.superadmin')

@section('title', __('app.audit_log'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.audit_log')"
        :description="__('app.recent_activity')"
    />

    <x-flash-messages />

    {{-- Summary Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <x-stat-card :label="__('app.total_log')" :value="$summary['total_logs']" icon="document" color="red" />
        <x-stat-card :label="__('app.today')" :value="$summary['today_logs']" icon="clock" color="blue" />
        <x-stat-card :label="__('app.this_week')" :value="$summary['this_week']" icon="calendar" color="green" />
        <x-stat-card :label="__('app.logins_today')" :value="$summary['logins_today']" icon="users" color="amber" />
    </div>

    {{-- Filters --}}
    <x-filter-form>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.action') }}</label>
            <select aria-label="Action" name="action" class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full">
                <option value="">{{ __('app.all') }}</option>
                <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>{{ __('app.created') }}</option>
                <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>{{ __('app.updated') }}</option>
                <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>{{ __('app.deleted') }}</option>
                <option value="verified" {{ request('action') === 'verified' ? 'selected' : '' }}>{{ __('app.verified') }}</option>
                <option value="role_changed" {{ request('action') === 'role_changed' ? 'selected' : '' }}>{{ __('app.role_changed') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.from_date') }}</label>
            <input aria-label="Date From" type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.to_date') }}</label>
            <input aria-label="Date To" type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full">
        </div>
    </x-filter-form>

    {{-- Log Table --}}
    <x-data-table :paginator="$logs">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold text-left">{{ __('app.time') }}</th>
                <th class="px-6 py-4 font-bold text-left">{{ __('app.user') }}</th>
                <th class="px-6 py-4 font-bold text-left">{{ __('app.action') }}</th>
                <th class="px-6 py-4 font-bold text-left">{{ __('app.description') }}</th>
                <th class="px-6 py-4 font-bold text-left">{{ __('app.detail') }}</th>
            </tr>
        </thead>

        @forelse($logs as $log)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-500 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <p class="text-sm font-bold text-gray-900">{{ $log->user?->name ?? __('app.system') }}</p>
                    <p class="text-xs text-gray-500">{{ $log->user?->email ?? '' }}</p>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold
                        {{ match($log->action) {
                            'created' => 'bg-green-50 text-green-700',
                            'updated' => 'bg-blue-50 text-blue-700',
                            'deleted' => 'bg-red-50 text-red-700',
                            'verified' => 'bg-pink-50 text-pink-700',
                            'role_changed' => 'bg-amber-50 text-amber-700',
                            default => 'bg-gray-50 text-gray-700'
                        } }}">
                        {{ $log->action_label }}
                    </span>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-800">{{ $log->description ?? '-' }}</td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                    @if($log->model_type)
                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-medium">
                            {{ class_basename($log->model_type) }}
                            @if($log->model_id) <span class="ml-1 text-gray-400">#{{ $log->model_id }}</span> @endif
                        </span>
                    @else
                        <span class="text-gray-300">-</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 md:py-12 text-center">
                    <x-empty-state :message="__('app.no_log')" icon="chart" />
                </td>
            </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
