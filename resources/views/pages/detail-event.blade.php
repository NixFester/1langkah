@extends('layouts.app', ['activePage' => 'event'])

@section('title', $event['title'] . ' — 1Langkah')
@section('header_title', __('app.detail_event'))

@php
$eventType = $event['type'] ?? 'online';
$status = $event['status'] ?? 'upcoming';
$ticketCode = null;
if (auth()->check() && $isRegistered) {
    $ticketCode = \App\Models\EventRegistration::where('user_id', auth()->id())
        ->where('event_id', $event['id'])
        ->value('ticket_code');
}
@endphp

@section('content')
<div x-data="{ showConfirm: false, copyLink: function() { navigator.clipboard.writeText(window.location.href).then(function() { var t = document.createElement('div'); t.className = 'fixed bottom-4 right-4 bg-gray-900 text-white px-6 py-3 rounded-full text-sm font-medium shadow-lg z-50'; t.textContent = '{{ __('app.copy_link_success') }}'; document.body.appendChild(t); setTimeout(function() { t.remove(); }, 3000); }).catch(function() { alert('{{ __('app.copy_link_fail') }}'); }); } }">
    <!-- Modal -->
    <div x-show="showConfirm" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" @click="showConfirm = false" @keydown.escape.window="showConfirm = false">
        <div x-show="showConfirm" x-transition class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl" @click.stop>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('app.registration_confirmation') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('app.sure_to_register') }} <strong>{{ $event['title'] }}</strong>?</p>
                <div class="flex gap-3">
                    <button @click="showConfirm = false" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-full transition-colors">{{ __('app.cancel') }}</button>
                    <form method="POST" action="{{ route('event.register', $event['id']) }}" class="flex-1">@csrf<button type="submit" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors">{{ __('app.yes_register') }}</button></form>
                </div>
            </div>
        </div>
    </div>

<!-- Hero Section -->
<div class="-mx-7 -mt-7 relative bg-slate-900 pt-20 pb-28 px-12 overflow-hidden">
    <div class="absolute inset-0 z-0">
        @if(!empty($event['banner_url']))
        <img src="{{ $event['banner_url'] }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover opacity-30">
        @else
        <div class="w-full h-full" style="background: linear-gradient(135deg, {{ $event['color'] ?? '#cc0000' }} 0%, {{ $event['color'] ?? '#cc0000' }}66 100%);"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
    </div>
    <div class="relative z-10 w-full mt-6">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-4 py-1 bg-white text-gray-700 text-xs font-bold rounded-full capitalize">{{ str_replace('_', ' ', $eventType) }}</span>
            @if($status !== 'completed')
            <span class="px-4 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full capitalize">{{ str_replace('_', ' ', $status) }}</span>
            @endif
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-5 leading-tight tracking-tight">{{ $event['title'] }}</h1>

        @if(!empty($event['short_description']))
        <p class="text-lg text-gray-300 mb-8 max-w-3xl leading-relaxed">{{ $event['short_description'] }}</p>
        @endif

        <!-- Event Meta Info -->
        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-300">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">{{ $event['date_display'] ?? 'TBA' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-medium">{{ $event['start_time'] ?? '' }} {{ $event['end_time'] ? ' - ' . $event['end_time'] : '' }}</span>
            </div>
            @if(!empty($event['location']))
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="font-medium">{{ $event['location'] }}</span>
            </div>
            @endif
            @if(!empty($event['meeting_url']))
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                <a href="{{ $event['meeting_url'] }}" target="_blank" class="font-medium hover:text-white transition-colors">Zoom / Google Meet</a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="w-full py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

        <!-- Left Column: Description -->
        <div class="lg:col-span-2 space-y-8">
            <!-- About Event -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0 4px_20px_rgb(0,0,0,0.03)]">
                <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">{{ __('app.about_event') }}</h2>

                @if(!empty($event['description']))
                <div class="prose prose-sm max-w-none text-gray-600">
                    {!! nl2br(e($event['description'])) !!}
                </div>
                @else
                <p class="text-gray-500">{{ __('app.no_description_yet') }}</p>
                @endif
            </div>

            <!-- Event Timeline / Info -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0 4px_20px_rgb(0,0,0,0.03)]">
                <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">{{ __('app.event_info') }}</h2>

                <div class="space-y-6">
                    <!-- Date & Time -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: {{ $event['color'] ?? '#cc0000' }}20;">
                            <svg class="w-6 h-6" style="color: {{ $event['color'] ?? '#cc0000' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ __('app.date_time') }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ $event['date_display'] ?? 'TBA' }}<br>
                                {{ $event['start_time'] ?? '' }} {{ $event['end_time'] ? ' - ' . $event['end_time'] : '' }}
                            </p>
                        </div>
                    </div>

                    <!-- Location / Meeting -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: {{ $event['color'] ?? '#cc0000' }}20;">
                            @if($eventType === 'online')
                            <svg class="w-6 h-6" style="color: {{ $event['color'] ?? '#cc0000' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            @else
                            <svg class="w-6 h-6" style="color: {{ $event['color'] ?? '#cc0000' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ $eventType === 'online' ? __('app.meeting_link') : __('app.location') }}</h3>
                            <p class="text-sm text-gray-500">
                                @if(!empty($event['location']))
                                    {{ $event['location'] }}
                                @elseif(!empty($event['meeting_url']))
                                    <a href="{{ $event['meeting_url'] }}" target="_blank" class="text-red-600 hover:underline">{{ $event['meeting_url'] }}</a>
                                @else
                                    {{ __('app.to_be_announced') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Timezone -->
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: {{ $event['color'] ?? '#cc0000' }}20;">
                            <svg class="w-6 h-6" style="color: {{ $event['color'] ?? '#cc0000' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ __('app.timezone') }}</h3>
                            <p class="text-sm text-gray-500">{{ $event['timezone'] ?? 'Asia/Jakarta (WIB)' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Registration -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-100 rounded-3xl p-7 shadow-[0 8px_30px_rgb(0,0,0,0.05)] lg:sticky lg:top-24">

                <!-- Event Status -->
                @if($status === 'completed')
                <div class="bg-gray-100 rounded-xl p-4 mb-6 text-center">
                    <p class="font-bold text-gray-600">{{ __('app.event_completed') }}</p>
                </div>
                @elseif($status === 'cancelled')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-center">
                    <p class="font-bold text-red-600">{{ __('app.event_cancelled') }}</p>
                </div>
                @endif

                <!-- Participants Info -->
                @if(isset($event['max_participants']))
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-gray-600">{{ __('app.participants') }}</span>
                        <span class="font-bold text-gray-900">{{ $event['registered_count'] ?? 0 }} / {{ $event['max_participants'] }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        @php
                            $percentage = $event['max_participants'] > 0
                                ? min(100, round(($event['registered_count'] ?? 0) / $event['max_participants'] * 100))
                                : 0;
                        @endphp
                        <div class="h-2 rounded-full transition-all" style="width: {{ $percentage }}%; background-color: {{ $event['color'] ?? '#cc0000' }};"></div>
                    </div>
                    @if($percentage >= 90)
                    <p class="text-xs text-red-600 mt-2 font-medium">{{ __('app.almost_full') }}</p>
                    @endif
                </div>
                @endif

                <!-- Ticket for Registered Users -->
                @if($isRegistered && $ticketCode)
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-red-700">{{ __('app.event_ticket') }}</p>
                            <h3 class="text-lg font-bold text-gray-900 mt-1">{{ __('app.show_ticket') }}</h3>
                            <p class="text-sm text-gray-600 mt-2">{{ __('app.ticket_scan_desc') }}</p>
                        </div>
                        <div class="rounded-2xl bg-white p-3 border border-red-100 shadow-sm">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($ticketCode) }}" alt="Ticket QR" class="w-32 h-32 object-contain">
                        </div>
                    </div>
                    <div class="mt-4 rounded-xl border border-red-100 bg-white/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">{{ __('app.ticket_code') }}</p>
                        <p class="mt-2 font-mono text-lg sm:text-2xl font-bold tracking-widest sm:tracking-[0.35em] text-gray-900 break-all">{{ $ticketCode }}</p>
                    </div>
                </div>
                @endif

                <!-- Register Button -->
                @auth
                    @if($isRegistered)
                    <a href="{{ route('dashboard') }}" class="w-full block py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-full transition-colors shadow-lg text-center mb-3">
                        {{ __('app.already_registered') }}
                    </a>
                    @elseif($status === 'upcoming')
                    <button @click="showConfirm = true" class="w-full py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors shadow-lg shadow-red-200 mb-3">
                        {{ __('app.register_event') }}
                    </button>
                    @else
                    <button disabled class="w-full py-3.5 bg-gray-300 text-gray-500 font-bold rounded-full cursor-not-allowed mb-3">
                        {{ __('app.registration_closed') }}
                    </button>
                    @endif
                @else
                <a href="{{ route('login') }}" class="w-full block py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors shadow-lg shadow-red-200 mb-3 text-center">
                    {{ __('app.login_to_register') }}
                </a>
                @endauth

                <button @click="copyLink()" class="w-full py-3.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-full transition-colors mb-8 shadow-sm">
                    {{ __('app.share_event') }}
                </button>

                <!-- Quick Info -->
                <div class="space-y-4 mb-6">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $event['day_name'] ?? '' }}, {{ $event['date_display'] ?? '' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $event['start_time'] ?? '' }}</span>
                    </div>
                </div>

                <!-- Hosted by 1Langkah -->
                <div class="border-t border-gray-100 pt-6">
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('app.hosted_by') }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-red-600 flex items-center justify-center text-white font-bold text-lg">
                            1L
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">1Langkah</p>
                            <p class="text-xs text-gray-500">{{ __('app.learning_platform') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection