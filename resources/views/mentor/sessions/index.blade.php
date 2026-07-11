@extends('layouts.mentor')

@section('title', __('app.manage_mentoring_sessions_mentor'))
@section('header_title', __('app.manage_mentoring_sessions'))

@section('content')
<div class="w-full space-y-6">
    <x-flash-messages />

    <!-- Alert for Pending Sessions -->
    @if($pendingSessions->count() > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-yellow-900">{{ __('app.new_booking_request') }}</h3>
                <p class="text-sm text-yellow-700">{{ $pendingSessions->count() }} {{ __('app.students_waiting_confirmation') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Pending Sessions -->
    @if($pendingSessions->count() > 0)
    <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-gray-900 mb-5">{{ __('app.waiting_confirmation') }}</h3>
        <div class="space-y-4">
            @foreach($pendingSessions as $session)
            <div class="border border-gray-100 rounded-xl p-4 bg-yellow-50/50">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        @if($session->user?->profile_photo)
                            <img src="{{ $session->user->profile_photo }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-lg">{{ substr($session->user->name ?? 'U', 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900">{{ $session->user->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">{{ $session->user->email ?? '' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded-full">{{ __('app.waiting') }}</span>
                </div>

                <div class="bg-white rounded-lg p-4 mb-4 border border-gray-100">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">{{ __('app.date') }}</p>
                            <p class="font-bold text-gray-900">{{ $session->formatted_date }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">{{ __('app.time') }}</p>
                            <p class="font-bold text-gray-900">{{ $session->booked_time }}</p>
                        </div>
                    </div>
                    @if($session->notes)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-gray-500 text-xs mb-1">{{ __('app.notes_colon') }}</p>
                        <p class="text-sm text-gray-700">{{ $session->notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex gap-3">
                    <form action="{{ route('mentor.sessions.accept', $session) }}" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-colors text-sm">
                            {{ __('app.accept_booking') }}
                        </button>
                    </form>
                    <form action="{{ route('mentor.sessions.reject', $session) }}" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full px-4 py-2.5 bg-red-100 hover:bg-red-200 text-red-700 font-bold rounded-lg transition-colors text-sm" onclick="return confirm('{{ __('app.reject_request_confirm') }}')">
                            {{ __('app.reject') }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Active Sessions -->
    @if($activeSessions->count() > 0)
    <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-gray-900 mb-5">{{ __('app.active_sessions') }}</h3>
        <div class="space-y-4">
            @foreach($activeSessions as $session)
            <div class="border border-gray-100 rounded-xl p-4 bg-green-50/50">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex items-center gap-3">
                        @if($session->user?->profile_photo)
                            <img src="{{ $session->user->profile_photo }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-lg">{{ substr($session->user->name ?? 'U', 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900">{{ $session->user->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-gray-500">{{ $session->formatted_date }} • {{ $session->booked_time }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">{{ __('app.active') }}</span>
                </div>

                <div class="bg-white rounded-lg p-4 mb-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">{{ __('app.whatsapp_link') }}</p>
                            <a href="{{ $session->wa_link }}" target="_blank" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                {{ __('app.contact_student') }}
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('mentor.sessions.complete', $session) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full px-4 py-2.5 bg-[#d00000] hover:bg-red-700 text-white font-bold rounded-lg transition-colors text-sm" onclick="return confirm('{{ __('app.mark_session_complete_confirm') }}')">
                        {{ __('app.mark_complete') }}
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Completed Sessions History -->
    <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8">
        <h3 class="text-lg font-bold text-gray-900 mb-5">{{ __('app.session_history') }}</h3>
        @if($completedSessions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-3 px-2 font-bold text-gray-600">{{ __('app.student') }}</th>
                        <th class="text-left py-3 px-2 font-bold text-gray-600">{{ __('app.date') }}</th>
                        <th class="text-left py-3 px-2 font-bold text-gray-600">{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($completedSessions as $session)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-900">{{ $session->user->name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-gray-600">{{ $session->formatted_date }}</td>
                        <td class="py-3 px-2">
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full">{{ __('app.completed') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($completedSessions->hasPages())
        <div class="mt-4">
            {{ $completedSessions->links() }}
        </div>
        @endif
        @else
        <p class="text-gray-500 text-center py-8">{{ __('app.no_session_history') }}</p>
        @endif
    </div>
</div>
@endsection
