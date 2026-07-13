@extends('layouts.mentor')

@section('title', __('app.edit_event'))
@section('header_title', __('app.edit_event'))

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

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6">{{ __('app.edit_event_colon') }} {{ $event->title }}</h2>

        <form method="POST" action="{{ route('mentor.events.update', $event) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.event_title_label') }} <span class="text-red-500">*</span>
                    </label>
                    <input aria-label="Title" type="text" name="title" value="{{ old('title', $event->title) }}" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.type') }} Event <span class="text-red-500">*</span>
                    </label>
                    <select aria-label="Type" name="type" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 cursor-pointer transition-colors">
                        <option value="online" {{ $event->type === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ $event->type === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="hybrid" {{ $event->type === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.status') }} <span class="text-red-500">*</span>
                    </label>
                    <select aria-label="Status" name="status" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 cursor-pointer transition-colors">
                        <option value="draft" {{ $event->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ $event->status === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ $event->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Max Participants --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.max_participants') }}
                    </label>
                    <input aria-label="Max Participants" type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('max_participants')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.start_date') }} <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <input aria-label="Start Date" type="date" name="start_date" value="{{ old('start_date', $event->start_date?->format('Y-m-d')) }}" required
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                        <input aria-label="Start Time" type="time" name="start_time" value="{{ old('start_time', $event->start_date?->format('H:i')) }}" required
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    </div>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.end_date') }}
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <input aria-label="End Date" type="date" name="end_date" value="{{ old('end_date', $event->end_date?->format('Y-m-d')) }}"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                        <input aria-label="End Time" type="time" name="end_time" value="{{ old('end_time', $event->end_date?->format('H:i')) }}"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ __('app.optional_start_date_hint') }}</p>
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.location') }}</label>
                    <input aria-label="Location" type="text" name="location" value="{{ old('location', $event->location) }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Meeting URL --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.meeting_link') }}
                    </label>
                    <input aria-label="Meeting Url" type="url" name="meeting_url" value="{{ old('meeting_url', $event->meeting_url) }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('meeting_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Short Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.short_description') }}
                    </label>
                    <input aria-label="Short Description" type="text" name="short_description" value="{{ old('short_description', $event->short_description) }}" maxlength="300"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('short_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.full_description') }}
                    </label>
                    <textarea aria-label="Description" name="description" rows="5"
                              class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors resize-y">{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Banner URL --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        {{ __('app.banner_url') }}
                    </label>
                    <input aria-label="Banner Url" type="url" name="banner_url" value="{{ old('banner_url', $event->banner_url) }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('banner_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-4 border-t border-gray-100 flex justify-between">
                <a href="{{ route('mentor.events.registrations', $event) }}"
                   class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-full text-sm transition-colors">
                    {{ __('app.view_participants_count', ['count' => $event->registrations->count()]) }}
                </a>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors">
                        {{ __('app.save_changes') }}
                    </button>
            </div>
        </form>
    </div>
@endsection
