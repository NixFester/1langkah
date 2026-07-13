@extends('layouts.superadmin')

@section('title', __('app.audit_log'))
@section('header_title', __('app.audit_log'))

@section('content')
    <x-flash-messages />

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-stat-card :label="__('app.total_log')" :value="$summary['total_logs']" color="purple" />
        <x-stat-card :label="__('app.today')" :value="$summary['today_logs']" color="blue" />
        <x-stat-card :label="__('app.this_week')" :value="$summary['this_week']" color="green" />
        <x-stat-card :label="__('app.logins_today')" :value="$summary['logins_today']" color="amber" />
    </div>

    {{-- Filters --}}
    <x-filter-form>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.action') }}</label>
            <select aria-label="Action" name="action" class="border border-gray-300 rounded-lg px-4 py-2">
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
            <input aria-label="Date From" type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.to_date') }}</label>
            <input aria-label="Date To" type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
    </x-filter-form>

    {{-- Log Table --}}
    <x-card-panel>
        <x-data-table :paginator="$logs">
            <template #thead>
                <tr class="bg-gray-50">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.time') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.user') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.action') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.description') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.detail') }}</th>
                </tr>
            </template>

            @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-800">{{ $log->user?->name ?? __('app.system') }}</p>
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
                            <span class="text-xs text-gray-500">
                                {{ class_basename($log->model_type) }}
                                @if($log->model_id) #{{ $log->model_id }} @endif
                            </span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12">
                        <x-empty-state :message="__('app.no_log')" icon="chart" />
                    </td>
                </tr>
            @endforelse
        </x-data-table>
    </x-card-panel>
@endsection
