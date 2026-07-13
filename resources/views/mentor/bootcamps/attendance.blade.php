@extends('layouts.mentor')

@section('title', __('app.bootcamp_attendance'))
@section('header_title', __('app.attendance_colon') . $bootcamp->title)

@section('content')
    <x-flash-messages />

    {{-- Back Button --}}
    <a href="{{ route('mentor.bootcamps.edit', $bootcamp) }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ __('app.back_to_bootcamp') }}
    </a>

    {{-- Bootcamp Info --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-900">{{ $bootcamp->title }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('app.type') }}: {{ ucfirst($bootcamp->type) }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Scan Code Panel --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">{{ __('app.scan_attendance_code') }}</h2>
            <p class="text-sm text-gray-500 mb-6">
                {{ __('app.scan_code_instruction') }}
            </p>

            <form method="POST" action="{{ route('mentor.bootcamps.scan-code') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.attendance_code') }}
                    </label>
                    <input aria-label="XXXX" type="text" name="short_code" required maxlength="4" minlength="4"
                           placeholder="XXXX"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-2xl font-bold text-center tracking-widest rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 uppercase transition-colors">
                    @error('short_code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-colors">
                    {{ __('app.mark_present') }}
                </button>
            </form>
        </div>

        {{-- Generate Codes Panel --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">{{ __('app.generate_code_today') }}</h2>
            <p class="text-sm text-gray-500 mb-6">
                {{ __('app.generate_code_instruction') }}
            </p>

            <form method="POST" action="{{ route('mentor.bootcamps.generate-codes', $bootcamp->id) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.attendance_date') }}
                    </label>
                    <input aria-label="Date" type="date" name="date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition-colors">
                    {{ __('app.generate_code_button') }}
                </button>
            </form>

            @if(session('short_code'))
                <div class="mt-4 p-4 bg-green-50 rounded-xl border border-green-200">
                    <p class="text-sm text-green-700 font-medium">{{ __('app.code_generated_success') }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ __('app.code_generated_instruction') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Today's Attendance Records --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mt-6">
        <h2 class="font-bold text-gray-900 mb-4">{{ __('app.today_attendance_records') }}</h2>

        @if($records->isEmpty())
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-gray-500">{{ __('app.no_attendance_records') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.participant') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.code') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.scan_time') }}</th>
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
                                                {{ __('app.present_status') }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700">
                                                {{ __('app.absent_status') }}
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
