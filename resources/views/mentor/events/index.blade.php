@extends('layouts.mentor')

@section('title', 'Event Saya')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Event Saya</h1>
            <p class="text-sm text-gray-500">Kelola event yang kamu buat</p>
        </div>
        <a href="{{ route('mentor.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Event Baru
        </a>
    </div>

    @if($events->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Event</h3>
        <p class="text-gray-500 mb-6">Mulai buat event pertamamu</p>
        <a href="{{ route('mentor.events.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
            Buat Event Baru
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($events as $event)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-32 bg-gradient-to-br" style="background-color: {{ $event->color ?? '#3B82F6' }}"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-green-100 text-green-700">
                        {{ ucfirst($event->status) }}
                    </span>
                    <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                        {{ ucfirst($event->type) }}
                    </span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $event->title }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $event->start_date->format('d M Y, H:i') }}</p>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $event->registrations_count ?? 0 }} peserta</span>
                    <div class="flex gap-2">
                        <a href="{{ route('mentor.events.edit', $event) }}" class="text-gray-500 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <a href="{{ route('mentor.events.registrations', $event) }}" class="text-gray-500 hover:text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $events->links() }}
    @endif
</div>
@endsection
