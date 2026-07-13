@extends('layouts.app', ['activePage' => 'my-sessions'])

@section('title', __('app.my_sessions_title'))
@section('header_title', __('app.my_sessions_header'))

@section('content')
<div class="w-full px-2 pb-12 space-y-6">
    <x-flash-messages />

    <!-- Active Session -->
    @if($activeSession)
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-3xl p-6 md:p-8">
        <div class="flex items-start justify-between mb-4">
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    {{ $activeSession->isPending() ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ $activeSession->isPending() ? __('app.status_pending') : __('app.status_active') }}
                </span>
                <h2 class="text-xl font-bold text-gray-900 mt-3">{{ __('app.session_with', ['name' => $activeSession->mentor->name]) }}</h2>
                <p class="text-gray-600 mt-1">{{ $activeSession->formatted_date }} • {{ __('app.at_time', ['time' => $activeSession->booked_time]) }}</p>
            </div>
        </div>

        @if($activeSession->isPending())
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-4">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-yellow-800">{{ __('app.mentor_will_contact') }}</p>
                </div>
            </div>
            <form action="{{ route('session.cancel', $activeSession) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-full hover:bg-gray-50 transition-colors text-sm" onclick="return confirm('{{ __('app.cancel_booking_confirm') }}')">
                    {{ __('app.cancel_booking') }}
                </button>
            </form>
        @else
            <div class="bg-white border border-gray-100 rounded-xl p-4 mb-4">
                <p class="text-sm text-gray-600 mb-3">{{ __('app.contact_mentor_start') }}</p>
                <a href="{{ $activeSession->wa_link }}" target="_blank"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-[#25D366] hover:bg-[#20BD5A] text-white font-bold rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    {{ __('app.contact_via_wa') }}
                </a>
            </div>
            <p class="text-xs text-gray-500">{{ __('app.session_completed_when') }}</p>
        @endif
    </div>
    @endif

    <!-- No Active Session -->
    @if(!$activeSession)
    <div class="bg-white border border-gray-100 rounded-3xl p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('app.no_active_session') }}</h2>
        <p class="text-gray-600 mb-6">{{ __('app.book_session_desc') }}</p>
        <a href="{{ route('mentor') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-[#d00000] hover:bg-red-700 text-white font-bold rounded-full transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            {{ __('app.find_mentor') }}
        </a>
    </div>
    @endif

    <!-- Session History -->
    @if($history->count() > 0)
    <div class="bg-white border border-gray-100 rounded-3xl p-6 md:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-5">{{ __('app.session_history') }}</h2>
        <div class="space-y-4">
            @foreach($history as $session)
            <div class="border border-gray-100 rounded-xl p-4 {{ $session->isCompleted() ? '' : 'opacity-60' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $session->mentor->name }}</p>
                            <p class="text-sm text-gray-500">{{ $session->formatted_date }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                        {{ $session->isCompleted() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $session->isCompleted() ? __('app.status_completed') : __('app.status_cancelled') }}
                    </span>
                </div>
                @if($session->isCompleted())
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('profil-mentor', $session->mentor_id) }}" class="px-4 py-2 bg-[#d00000] hover:bg-red-700 text-white text-sm font-medium rounded-full transition-colors">
                        {{ __('app.rebook_session') }}
                    </a>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        @if($history->hasPages())
        <div class="mt-6">
            {{ $history->links() }}
        </div>
        @endif
    </div>
    @endif
</div>
@endsection
