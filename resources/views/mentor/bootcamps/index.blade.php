@extends('layouts.mentor')

@section('title', 'Kelola Bootcamp')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Bootcamp</h1>
            <p class="text-sm text-gray-500">Buat dan kelola bootcamp kamu</p>
        </div>
        <a href="{{ route('mentor.bootcamps.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Bootcamp Baru
        </a>
    </div>

    @if($bootcamps->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Bootcamp</h3>
        <p class="text-gray-500 mb-6">Mulai buat bootcamp pertamamu</p>
        <a href="{{ route('mentor.bootcamps.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
            Buat Bootcamp Baru
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($bootcamps as $bootcamp)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-32 bg-gradient-to-br" style="background-color: {{ $bootcamp->color ?? '#3B82F6' }}"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $bootcamp->type === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($bootcamp->type) }}
                    </span>
                    <span class="text-sm font-medium text-gray-600">{{ $bootcamp->price }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $bootcamp->title }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $bootcamp->start_date }}</p>
                <p class="text-sm text-gray-600 mb-4">{{ $bootcamp->enrollments_count ?? 0 }} peserta</p>

                <div class="flex items-center gap-2 pt-3 border-t">
                    <a href="{{ route('mentor.bootcamps.edit', $bootcamp) }}" class="flex-1 text-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-sm font-medium">
                        Edit
                    </a>
                    @if($bootcamp->type === 'offline')
                    <a href="{{ route('mentor.bootcamps.attendance', $bootcamp) }}" class="flex-1 text-center px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-sm font-medium">
                        Absensi
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $bootcamps->links() }}
    @endif
</div>
@endsection
