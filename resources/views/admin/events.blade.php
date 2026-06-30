@extends('layouts.app')

@section('title', 'Manage Events')

@section('content')
<div class="px-6 py-8 sm:px-10 w-full space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Event</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar agenda dan acara ({{ $events->total() }}) yang ada di platform.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.events.new') }}" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-full text-sm transition-colors shadow-lg shadow-red-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Event
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- DATA TABLE -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Judul Event</th>
                        <th class="px-6 py-4 font-bold">Tipe</th>
                        <th class="px-6 py-4 font-bold">Mulai</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($events as $event)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 text-purple-600">
                                    <x-icon name="calendar" class="w-6 h-6" />
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
                            @php
                                $typeColor = match($event->type) {
                                    'online' => 'bg-blue-50 text-blue-700',
                                    'offline' => 'bg-purple-50 text-purple-700',
                                    'hybrid' => 'bg-green-50 text-green-700',
                                    default => 'bg-gray-50 text-gray-700'
                                };
                            @endphp
                            <span class="{{ $typeColor }} text-[11px] font-bold px-2.5 py-1 rounded-md capitalize">{{ $event->type }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $event->start_date->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $event->start_date->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColor = match($event->status) {
                                    'upcoming' => 'bg-yellow-50 text-yellow-700',
                                    'ongoing' => 'bg-blue-50 text-blue-700',
                                    'completed' => 'bg-green-50 text-green-700',
                                    'cancelled' => 'bg-red-50 text-red-700',
                                    default => 'bg-gray-50 text-gray-700'
                                };
                            @endphp
                            <span class="{{ $statusColor }} text-[11px] font-bold px-2.5 py-1 rounded-md capitalize">{{ $event->status }}</span>
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
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($events->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $events->links() }}
        </div>
        @endif
    </div>

</div>
@endsection