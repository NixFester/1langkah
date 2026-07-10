@extends('layouts.app', ['activePage' => 'event'])

@section('title', __('app.event') . ' — 1Langkah')
@section('header_title', __('app.event'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header Section -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="font-extrabold text-gray-900 tracking-tight" style="font-size: 28px;">{{ __('app.event') }}</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">{{ __('app.event_subtitle') }}</p>
        </div>
    </div>

    <!-- Events Grid -->
    @if(!empty($events))
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($events as $event)
            <x-event-card :event="$event" />
        @endforeach
    </div>
    @else
        <x-empty-state
            :title="__('app.no_events_title')"
            :message="__('app.no_events_desc')"
        >
            <x-slot name="icon">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </x-slot>
        </x-empty-state>
    @endif

</div>
@endsection
