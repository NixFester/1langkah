@extends('layouts.mentor')

@section('title', __('app.event_registration'))
@section('header_title', __('app.event_registration_colon') . $event->title)

@section('content')
    <x-flash-messages />

    {{-- Back Button --}}
    <a href="{{ route('mentor.events') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        {{ __('app.back_to_my_events') }}
    </a>

    {{-- Event Info --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $event->title }}</h2>
                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1">
                        <x-icon name="calendar" class="w-4 h-4" />
                        {{ $event->start_date->format('d M Y, H:i') }}
                    </span>
                    @if($event->location)
                        <span class="flex items-center gap-1">
                            <x-icon name="location" class="w-4 h-4" />
                            {{ $event->location }}
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('mentor.events.edit', $event) }}"
               class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold py-2 px-4 rounded-lg text-sm transition-colors">
                {{ __('app.edit_event') }}
            </a>
        </div>
    </div>

    {{-- Registrations --}}
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">{{ __('app.participant_list') }}</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $registrations->count() }} {{ __('app.registered_participants') }}</p>
        </div>

        @if($registrations->isEmpty())
            <div class="p-6 md:p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-icon name="users" class="w-8 h-8 text-gray-400" />
                </div>
                <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('app.no_participants_yet') }}</h2>
                <p class="text-gray-500">{{ __('app.no_event_participants') }}</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.participant') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.registered') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.attendance') }}</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">{{ __('app.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($registrations as $registration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($registration->user?->profile_photo)
                                            <img decoding="async" loading="lazy" alt="" src="{{ $registration->user->profile_photo }}" alt="{{ $registration->user->name }}"
                                                 class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-bold">{{ substr($registration->user->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $registration->user->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-500">{{ $registration->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full
                                        @if($registration->status === 'attended') bg-green-100 text-green-700
                                        @elseif($registration->status === 'cancelled') bg-red-100 text-red-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($registration->status ?? 'registered') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $registration->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($registration->attended_at)
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            <span class="text-sm text-gray-700">
                                                {{ $registration->attended_at->format('d M Y, H:i') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">{{ __('app.not_attended_yet') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if(!$registration->attended_at)
                                        <form method="POST" action="{{ route('mentor.events.registrations.attended', [$event, $registration]) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('{{ __('app.mark_attended_confirm', ['name' => $registration->user->name ?? 'peserta']) }}')"
                                                    class="bg-green-50 hover:bg-green-100 text-green-600 font-bold py-2 px-4 rounded-lg text-xs transition-colors">
                                                ✓ {{ __('app.mark_attended') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-sm text-green-600 font-medium">✓ {{ __('app.attended') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
