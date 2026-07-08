@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        title="Kelola Event"
        :description="'Daftar agenda dan acara (' . $events->total() . ') yang ada di platform.'"
        actionRoute="{{ route('admin.events.new') }}"
        actionLabel="Tambah Event"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$events">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold">Judul Event</th>
                <th class="px-6 py-4 font-bold">Tipe</th>
                <th class="px-6 py-4 font-bold">Mulai</th>
                <th class="px-6 py-4 font-bold">Status</th>
                <th class="px-6 py-4 font-bold text-right">Aksi</th>
            </tr>
        </template>

        @forelse($events as $event)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 text-purple-600 overflow-hidden">
                        @if($event->banner_url)
                            <img src="{{ str_starts_with($event->banner_url, 'http') ? $event->banner_url : asset($event->banner_url) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                        @else
                            <x-icon name="calendar" class="w-6 h-6" />
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ $event->title }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5 truncate">
                            {{ $event->location ?? 'Belum ada lokasi' }}
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
                        Kelola
                    </a>
                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="m-0" onsubmit="return confirm('Hapus event ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-8">
                <x-empty-state message="Belum ada data event." icon="calendar" />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
