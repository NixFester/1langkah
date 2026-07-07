@extends('layouts.mentor')

@section('title', 'Absensi Bootcamp')
@section('header_title', 'Absensi: ' . $bootcamp->title)

@section('content')
    <x-flash-messages />

    {{-- Back Button --}}
    <a href="{{ route('mentor.my-courses') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali
    </a>

    {{-- Bootcamp Info --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900">{{ $bootcamp->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $bootcamp->category }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Scan Code Panel --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">📱 Scan Kode Absensi</h3>
            <p class="text-sm text-gray-500 mb-6">
                Masukkan kode 4 karakter yang ditunjukkan peserta untuk mencatat kehadiran.
            </p>

            <form method="POST" action="{{ route('mentor.attendance.scan-code') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Kode Absensi
                    </label>
                    <input type="text" name="short_code" required maxlength="4" minlength="4"
                           placeholder="XXXX"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-2xl font-bold text-center tracking-widest rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 uppercase transition-colors">
                    @error('short_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-colors">
                    Tandai Hadir
                </button>
            </form>
        </div>

        {{-- Generate Codes Panel --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">🎫 Generate Kode Hari Ini</h3>
            <p class="text-sm text-gray-500 mb-6">
                Buat kode absensi untuk semua peserta bootcamp hari ini.
            </p>

            <form method="POST" action="{{ route('mentor.attendance.generate-codes', $bootcamp->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Absensi
                    </label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-colors">
                    Generate Kode untuk Semua Peserta
                </button>
            </form>

            @if(session('short_code'))
                <div class="mt-4 p-4 bg-green-50 rounded-xl border border-green-200">
                    <p class="text-sm text-green-700 font-medium">Kode berhasil di-generate!</p>
                    <p class="text-xs text-green-600 mt-1">Peserta dapat menggunakan kode untuk absensi.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Today's Attendance Records --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mt-6">
        <h3 class="font-bold text-gray-900 mb-4">📋 Rekam Absensi Hari Ini</h3>

        @if($records->isEmpty())
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-icon name="calendar" class="w-8 h-8 text-gray-400" />
                </div>
                <p class="text-gray-500">Belum ada rekam absensi untuk bootcamp ini</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Peserta</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu Scan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($records as $date => $dateRecords)
                            @foreach($dateRecords as $record)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($record->user?->profile_photo)
                                                <img src="{{ $record->user->profile_photo }}" alt="{{ $record->user->name }}"
                                                     class="w-8 h-8 rounded-full object-cover">
                                            @else
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-600 font-bold text-xs">{{ substr($record->user->name ?? 'U', 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium text-gray-900">{{ $record->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                            {{ $record->short_code ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->verified)
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700">
                                                ✓ Hadir
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">
                                                ○ Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $record->scanned_at?->format('H:i:s') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
