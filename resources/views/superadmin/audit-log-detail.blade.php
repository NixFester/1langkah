@extends('layouts.superadmin')

@section('title', __('app.audit_log_detail'))
@section('header_title', __('app.audit_log_detail'))

@section('content')
    <x-back-button route="{{ route('superadmin.audit-logs') }}" />

    <x-flash-messages />

    <div class="max-w-3xl mx-auto">
        <x-card-panel :title="__('app.log_detail')">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-bold text-gray-800">{{ __('app.log_detail') }}</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium
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
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('app.user') }}</p>
                    <p class="font-medium text-gray-800">{{ $log->user?->name ?? __('app.system') }}</p>
                    <p class="text-sm text-gray-400">{{ $log->user?->email ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('app.time') }}</p>
                    <p class="font-medium text-gray-800">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                </div>
                @if($log->model_type)
                    <div>
                        <p class="text-sm text-gray-500 mb-1">{{ __('app.model') }}</p>
                        <p class="font-medium text-gray-800">{{ class_basename($log->model_type) }}</p>
                        @if($log->model_id)
                            <p class="text-sm text-gray-400">{{ __('app.id_colon') }} {{ $log->model_id }}</p>
                        @endif
                    </div>
                @endif
                <div>
                    <p class="text-sm text-gray-500 mb-1">{{ __('app.ip_address') }}</p>
                    <p class="font-medium text-gray-800">{{ $log->ip_address ?? '-' }}</p>
                </div>
            </div>

            @if($log->description)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">{{ __('app.description') }}</p>
                    <p class="text-gray-800">{{ $log->description }}</p>
                </div>
            @endif

            @if($log->old_values)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-2">{{ __('app.old_data') }}</p>
                    <pre class="bg-gray-50 p-4 rounded-lg text-sm overflow-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            @if($log->new_values)
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <p class="text-sm text-gray-500 mb-2">{{ __('app.new_data') }}</p>
                    <pre class="bg-gray-50 p-4 rounded-lg text-sm overflow-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </x-card-panel>
    </div>
@endsection
