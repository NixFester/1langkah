@extends('layouts.app', ['activePage' => 'online-bootcamp'])

@section('title', $bootcamp['title'] . ' — 1Langkah')
@section('header_title', __('app.online_bootcamp'))

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Session cards script loaded');

    // Event delegation for session cards
    document.querySelectorAll('.session-card').forEach(function(card) {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var sessionId = this.dataset.sessionId;
            var bootcampId = this.dataset.bootcampId;
            var meetingUrl = this.dataset.meetingUrl;
            var password = this.dataset.password;

            console.log('Session clicked:', sessionId, meetingUrl);

            // Only process if there's a meeting URL
            if (!meetingUrl) {
                console.log('No meeting URL');
                return;
            }

            // Show password modal if needed
            if (password) {
                var passwordModal = document.createElement('div');
                passwordModal.id = 'password-modal';
                passwordModal.className = 'fixed inset-0 bg-black/50 flex items-center justify-center z-50';
                passwordModal.innerHTML = '<div class="bg-white rounded-2xl p-6 max-w-sm mx-4 shadow-2xl">' +
                    '<h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('app.password_meeting') }}</h2>' +
                    '<p class="text-sm text-gray-500 mb-4">{{ __('app.password_instruction') }}</p>' +
                    '<div class="bg-gray-100 rounded-lg p-4 text-center mb-4">' +
                    '<code class="text-2xl font-bold text-gray-900 tracking-wider">' + password + '</code></div>' +
                    '<div class="flex gap-3">' +
                    '<button class="close-modal flex-1 px-4 py-2 border border-gray-200 rounded-full text-gray-700 font-medium hover:bg-gray-50 transition-colors">{{ __('app.close') }}</button>' +
                    '<button class="join-btn flex-1 px-4 py-2 bg-[#d00000] text-white rounded-full font-medium hover:bg-red-700 transition-colors">{{ __('app.join_meeting') }}</button>' +
                    '</div></div>';
                document.body.appendChild(passwordModal);

                // Close button handler
                passwordModal.querySelector('.close-modal').addEventListener('click', function() {
                    passwordModal.remove();
                });

                // Join button handler - track attendance then open URL
                passwordModal.querySelector('.join-btn').addEventListener('click', function() {
                    // Track the session attendance via API
                    fetch('/api/session-progress', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            session_id: sessionId,
                            bootcamp_id: bootcampId
                        })
                    })
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        console.log('Attendance tracked:', data);
                        // Open meeting URL
                        window.open(meetingUrl, '_blank');
                        // Close modal and refresh to show attended status
                        passwordModal.remove();
                        setTimeout(function() { location.reload(); }, 300);
                    })
                    .catch(function(error) {
                        console.error('Error tracking session:', error);
                        // Still open meeting URL even if tracking fails
                        window.open(meetingUrl, '_blank');
                        passwordModal.remove();
                    });
                });

                // Close on backdrop click
                passwordModal.addEventListener('click', function(ev) {
                    if (ev.target === passwordModal) {
                        passwordModal.remove();
                    }
                });
            } else {
                // No password - track attendance and open URL directly
                fetch('/api/session-progress', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        session_id: sessionId,
                        bootcamp_id: bootcampId
                    })
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    console.log('Attendance tracked:', data);
                    // Open meeting URL
                    window.open(meetingUrl, '_blank');
                    // Refresh to show attended status
                    setTimeout(function() { location.reload(); }, 300);
                })
                .catch(function(error) {
                    console.error('Error tracking session:', error);
                    // Still open meeting URL even if tracking fails
                    window.open(meetingUrl, '_blank');
                });
            }

            return false;
        });
    });
});
</script>
@endpush

@section('content')
@inject('catalog', 'App\Services\CatalogService')
@php
    $b = $bootcamp;
    $allBootcamps = $catalog->bootcamps()['online'];
@endphp

<div class="w-full px-2 pb-8">
    <!-- Header (Same as Online Bootcamp) -->
    <div class="mb-8 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ __('app.online_bootcamp') }}</h1>
        <p class="text-gray-500 text-base">{{ __('app.online_bootcamp_desc') }}</p>
    </div>

    <!-- Alert / Info Banner -->
    <x-alert-banner
        type="info"
        :title="__('app.online_feature_1')"
        :message="__('app.online_feature_1_desc')"
        :stats="[
            ['value' => '7–10', 'label' => __('app.meetings')],
            ['value' => '2 Jam', 'label' => __('app.per_session')],
            ['value' => '30 Hari', 'label' => __('app.recording_access')]
        ]"
    >
        <x-slot name="icon">
            <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
        </x-slot>
    </x-alert-banner>

    <!-- Master-Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Master List -->
        <div class="lg:col-span-4 flex flex-col gap-5">
            @foreach($allBootcamps as $item)
                @php
                    $isActive = $item['id'] == $b['id'];
                    // Get actual enrolled count from database
                    $itemEnrolledCount = \App\Models\Enrollment::where('purchasable_type', \App\Models\Bootcamp::class)
                        ->where('purchasable_id', $item['id'])
                        ->count();
                @endphp
                
                <a href="{{ route('detail-online-bootcamp', ['id' => $item['id']]) }}" class="block bg-white rounded-2xl p-5 border {{ $isActive ? 'border-red-600 shadow-[0_0_0_1px_#e11d48,0_4px_12px_rgb(0,0,0,0.05)]' : 'border-gray-200 shadow-sm hover:border-gray-300' }} transition-all">
                    <!-- Top Badges -->
                    <div class="flex gap-2 mb-3">
                        <span class="px-2.5 py-0.5 {{ $isActive ? 'bg-red-600 text-white' : 'bg-red-50 text-red-600' }} text-[11px] font-bold rounded-full">{{ $loop->first ? __('app.most_wanted') : ($loop->iteration == 2 ? __('app.new') : __('app.premium')) }}</span>
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-500 text-[11px] font-semibold rounded-full">{{ __('app.intermediate') }}</span>
                    </div>
                    
                    <h2 class="text-[15px] font-bold text-gray-900 leading-snug mb-1">{{ $item['title'] }}</h2>
                    <p class="text-[12px] text-gray-500 mb-4">{{ $item['mentor'] }}</p>
                    
                    <!-- Enrolled Count -->
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-[11px] font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.196A3 3 0 007 18v-2m.232-.172a3 3 0 014.232 2.196A3 3 0 0013.536 16M7 8a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                            {{ $itemEnrolledCount }} {{ __('app.students') }} enrolled
                        </div>
                    </div>
                    
                    <div class="w-full h-px bg-gray-100 mb-3"></div>
                    
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 mb-0.5">{{ __('app.start') }} {{ $item['startDate'] }}</div>
                            <div class="text-[13px] font-bold text-gray-900">{{ $item['sessions'] }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[11px] font-medium text-gray-400 mb-0.5">{{ __('app.price') }}</div>
                            <div class="text-[15px] font-extrabold text-[#e11d48]">{{ $item['formatted_price'] ?? __('app.free') }}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Right Column: Detail Pane -->
        <div class="lg:col-span-8">
            <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm lg:sticky lg:top-24">
                
                <!-- Hero Image -->
                <div class="relative w-full h-[280px] bg-gray-900">
                    @if(!empty($b['thumbnail']))
                        <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" class="w-full h-full object-cover opacity-60">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <!-- Close button -->
                    <a href="{{ route('online-bootcamp') }}" class="absolute top-4 right-4 w-8 h-8 bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                    
                    <!-- Title Overlay -->
                    <div class="absolute bottom-6 left-8 right-8">
                        <h1 class="text-2xl font-extrabold text-white mb-1.5">{{ $b['title'] }}</h1>
                        <p class="text-gray-300 text-[13px] font-medium">{{ $b['mentor'] }}</p>
                    </div>
                </div>
                
                <!-- Content Body -->
                <div class="p-8">
                    <!-- Description -->
                    <p class="text-[14px] text-gray-600 leading-relaxed mb-6 font-medium">{{ __('app.online_desc_text') }}</p>
                    
                    <!-- Meta info row -->
                    <div class="flex flex-wrap items-center gap-6 text-[12px] font-bold text-gray-500 mb-8">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ __('app.start') }} {{ $b['startDate'] }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            {{ count($sessions) }} {{ __('app.live_meetings') }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $enrolledCount }} {{ __('app.students') }}
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pb-8 border-b border-gray-100">
                        @if(!empty($isEnrolled))
                            <a href="{{ route('bootcamps-saya') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 sm:py-3 px-6 rounded-xl sm:rounded-full text-center transition-colors shadow-sm text-[13.5px] sm:text-sm">
                                {{ __('app.enrolled_see_bootcamps') }}
                            </a>
                        @else
                            <a href="{{ route('pembayaran', ['id' => $b['id']]) }}" class="flex-1 bg-[#d00000] hover:bg-red-700 text-white font-bold py-3.5 sm:py-3 px-6 rounded-xl sm:rounded-full text-center transition-colors shadow-sm text-[13.5px] sm:text-sm whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ __('app.enroll_bootcamp') }}{{ $b['formatted_price'] ?? __('app.free') }}
                            </a>
                        @endif
                        <button class="w-full sm:w-auto px-8 py-3.5 sm:py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl sm:rounded-full hover:bg-gray-50 transition-colors shadow-sm text-[13.5px] sm:text-sm">
                            {{ __('app.save') }}
                        </button>
                    </div>
                    
                    <!-- Jadwal Section -->
                    <div class="pt-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <h2 class="text-[17px] font-bold text-gray-900">{{ __('app.live_meeting_schedule') }}</h2>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold rounded-full">{{ count($sessions) }} {{ __('app.sessions') }}</span>
                        </div>
                        
                        <div class="space-y-3 relative">
                            <!-- vertical line connecting timeline -->
                            <div class="absolute top-8 bottom-8 left-[18px] w-px bg-gray-100 z-0 pointer-events-none"></div>

                            @foreach($sessions as $i => $s)
                            @php
                                // Check if user has attended this session
                                $hasAttended = isset($s['has_attended']) && $s['has_attended'];
                                // Check if session has a meeting URL
                                $hasMeetingUrl = !empty($s['meeting_url']);
                                // Can join if enrolled and has meeting URL
                                $canJoin = $isEnrolled && $hasMeetingUrl;
                                $sessionId = $s['id'] ?? ($i + 1);
                            @endphp
                            <div class="relative z-10 {{ !$loop->last ? 'pb-3' : '' }}">
                                <!-- Session Card -->
                                <div class="session-card flex items-start gap-4 p-4 rounded-2xl border border-gray-100 bg-white hover:border-gray-200 hover:shadow-sm transition-all {{ $canJoin && !$hasAttended ? 'cursor-pointer' : '' }}"
                                    data-session-id="{{ $sessionId }}"
                                    data-bootcamp-id="{{ $bootcamp['id'] }}"
                                    data-meeting-url="{{ $s['meeting_url'] ?? '' }}"
                                    data-password="{{ $s['password'] ?? '' }}"
                                >
                                    <!-- Number Badge -->
                                    <div class="w-9 h-9 rounded-full {{ $hasAttended ? 'bg-emerald-500' : 'bg-[#d00000]' }} text-white flex items-center justify-center font-bold text-sm flex-shrink-0 shadow-sm">
                                        @if($hasAttended)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            {{ $i + 1 }}
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-2">
                                            <div class="flex-1">
                                                <h2 class="text-[14px] font-bold text-gray-900 mb-1.5">{{ $s['topic'] }}</h2>
                                                <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[12px] text-gray-500">
                                                    <span class="flex items-center gap-1 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        {{ $s['date'] }}
                                                    </span>
                                                    <span class="flex items-center gap-1 bg-gray-50 px-2 py-0.5 rounded-md border border-gray-100">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        {{ $s['time'] }} WIB
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Join Button -->
                                            @if($canJoin && !$hasAttended)
                                            <button class="w-full sm:w-auto justify-center px-4 py-2 sm:py-1.5 bg-[#d00000] hover:bg-red-700 text-white text-xs font-bold rounded-lg sm:rounded-full transition-colors flex items-center gap-1.5 flex-shrink-0 mt-1 sm:mt-0">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                {{ __('app.join') }}
                                            </button>
                                            @elseif($hasAttended)
                                            <span class="w-full sm:w-auto justify-center px-3 py-2 sm:py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg sm:rounded-full flex items-center gap-1 flex-shrink-0 mt-1 sm:mt-0">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                {{ __('app.done') }}
                                            </span>
                                            @elseif(!$isEnrolled)
                                            <span class="w-full sm:w-auto text-center px-3 py-2 sm:py-1 bg-gray-100 text-gray-500 text-xs font-medium rounded-lg sm:rounded-full flex-shrink-0 mt-1 sm:mt-0">
                                                {{ __('app.login_to_join') }}
                                            </span>
                                            @endif
                                        </div>

                                        <!-- Password Info -->
                                        @if($hasAttended)
                                        <div class="mt-2 inline-flex items-center gap-1.5 text-[11px] text-emerald-600">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ __('app.session_attended') }}
                                        </div>
                                        @elseif($canJoin && !empty($s['password']))
                                        <div class="mt-2 text-[11px] text-gray-400">
                                            Password: <code class="bg-gray-100 px-1.5 py-0.5 rounded font-mono">{{ $s['password'] }}</code>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Alert Box -->
                        <div class="mt-6 bg-[#FFFDF3] border border-[#FDF0CD] rounded-xl p-4 flex gap-3 shadow-sm items-start">
                            <svg class="w-5 h-5 text-orange-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-[12px] font-medium text-orange-800 leading-relaxed">
                                {{ __('app.zoom_info') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
