@extends('layouts.mentor')

@section('title', 'Scanner Tiket Event')
@section('header_title', 'Scanner Tiket: ' . $event->title)

@section('content')
    <x-flash-messages />

    {{-- Back Button --}}
    <a href="{{ route('mentor.events.edit', $event) }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Event
    </a>

    {{-- Event Info --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900">{{ $event->title }}</h2>
        <div class="flex items-center gap-4 mt-2">
            <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $event->type === 'offline' ? 'bg-green-100 text-green-700' : ($event->type === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                {{ ucfirst($event->type) }}
            </span>
            <span class="text-sm text-gray-500">{{ $event->start_date->format('d M Y, H:i') }}</span>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <p class="text-sm text-gray-500 mb-1">Total Pendaftaran</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_registrations'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <p class="text-sm text-gray-500 mb-1">Hadir</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['attended_count'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <p class="text-sm text-gray-500 mb-1">Belum Hadir</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_count'] }}</p>
        </div>
    </div>

    {{-- Ticket Scanner --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">🎫 Scan Tiket Peserta</h3>
        <p class="text-sm text-gray-500 mb-6">
            Masukkan kode tiket atau email/nama peserta untuk mencatat kehadiran dan memberikan XP.
        </p>

        <form method="POST" action="{{ route('mentor.events.scan-ticket', $event) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">
                    Kode Tiket / Email / Nama
                </label>
                <input type="text" name="ticket_code" required
                       placeholder="Masukkan kode tiket, email, atau nama peserta"
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-lg rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-4 transition-colors">
                @error('ticket_code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-xl text-sm transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
                Scan & Beri XP
            </button>
        </form>
    </div>

    {{-- Recent Attendances --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="font-bold text-gray-900 mb-4">📋 Peserta Yang Sudah Hadir</h3>

        @if($recentAttendances->isEmpty())
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <p class="text-gray-500">Belum ada peserta yang hadir</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tiket</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu Scan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($recentAttendances as $attendance)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($attendance->user?->profile_photo)
                                            <img src="{{ $attendance->user->profile_photo }}" alt="{{ $attendance->user->name }}"
                                                 class="w-8 h-8 rounded-full object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-bold text-xs">{{ substr($attendance->user->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <span class="text-sm font-medium text-gray-900">{{ $attendance->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $attendance->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($attendance->ticket_code)
                                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                            {{ $attendance->ticket_code }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $attendance->attended_at?->format('d M Y, H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
