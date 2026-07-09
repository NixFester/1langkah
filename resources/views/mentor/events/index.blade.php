@extends('layouts.mentor')

@section('title', 'Event Saya')

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER -->
    <x-page-header
        title="Event Saya"
        description="Kelola event yang kamu buat"
        actionRoute="{{ route('mentor.events.create') }}"
        actionLabel="Buat Event Baru"
    />

    @if($events->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <x-empty-state
            message="Belum ada event. Mulai buat event pertamamu."
            icon="document"
            :actionRoute="route('mentor.events.create')"
            actionLabel="Buat event pertama"
        />
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
                <div class="mb-4">
                    <span class="text-sm text-gray-600">{{ $event->registrations_count ?? 0 }} peserta</span>
                </div>
                <div class="flex items-center gap-2 pt-3 border-t">
                    <a href="{{ route('mentor.events.edit', $event) }}" class="flex-1 inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        Edit
                    </a>
                    <a href="{{ route('mentor.events.registrations', $event) }}" class="flex-1 inline-flex items-center justify-center bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        Peserta
                    </a>
                    <form method="POST" action="{{ route('mentor.events.destroy', $event) }}" class="flex-1 m-0" onsubmit="return confirm('Hapus event ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $events->links() }}
    @endif
</div>
@endsection
