@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.manage_events')"
        :description="__('app.events_list_desc', ['count' => $events->total()])"
        actionRoute="{{ route('admin.events.new') }}"
        :actionLabel="__('app.add_event')"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$events">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold">{{ __('app.event_title') }}</th>
                <th class="px-6 py-4 font-bold">{{ __('app.type') }}</th>
                <th class="px-6 py-4 font-bold">{{ __('app.start') }}</th>
                <th class="px-6 py-4 font-bold">{{ __('app.status') }}</th>
                <th class="px-6 py-4 font-bold text-right">{{ __('app.action') }}</th>
            </tr>
        </template>

        @forelse($events as $event)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 text-purple-600 overflow-hidden">
                        @if($event->banner_url)
                            <img decoding="async" loading="lazy" alt="" src="{{ str_starts_with($event->banner_url, 'http') ? $event->banner_url : asset($event->banner_url) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <x-icon name="calendar" class="w-6 h-6" />
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ $event->title }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5 truncate">
                            {{ $event->location ?? __('app.no_location_yet') }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <x-stat-badge :type="$event->type" />
            </td>
            <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ $event->start_date->format('d M Y') }}</div>
                <div class="text-xs text-gray-500">{{ $event->start_date->format('H:i') }}</div>
            </td>
            <td class="px-6 py-4">
                <x-stat-badge :status="$event->status" />
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.events.manage', $event) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.manage') }}
                    </a>
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_event_confirm') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-8">
                <x-empty-state :message="__('app.no_event_data')" icon="calendar" />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
