@extends('layouts.mentor')

@section('title', __('app.edit_bootcamp'))

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('mentor.bootcamps.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            {{ __('app.back') }}
        </a>
    </div>

    <x-flash-messages />

    <div class="space-y-6">
        {{-- Bootcamp Info Form --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">{{ __('app.edit_bootcamp_colon') }} {{ $bootcamp->title }}</h1>
                @if($bootcamp->type === 'offline')
                <a href="{{ route('mentor.bootcamps.attendance', $bootcamp) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Scanner Absensi
                </a>
                @endif
            </div>

            <form method="POST" action="{{ route('mentor.bootcamps.update', $bootcamp) }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.title') }} *</label>
                        <input type="text" name="title" required value="{{ old('title', $bootcamp->title) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.type') }} *</label>
                        <select name="type" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type', $bootcamp->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.price') }} *</label>
                        <input type="text" name="price" required value="{{ old('price', $bootcamp->price) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.start_date') }} *</label>
                        <input type="text" name="start_date" required value="{{ old('start_date', $bootcamp->start_date) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.participant_count') }}</label>
                        <input type="number" name="participants" value="{{ old('participants', $bootcamp->participants) }}" min="0"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.location') }}</label>
                        <input type="text" name="location" value="{{ old('location', $bootcamp->location) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.short_description') }}</label>
                        <input type="text" name="short_description" value="{{ old('short_description', $bootcamp->short_description) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.description') }}</label>
                        <textarea name="description" rows="4"
                                  class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $bootcamp->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.color') }}</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color" value="{{ old('color', $bootcamp->color ?? '#3B82F6') }}"
                                   class="w-12 h-10 border border-gray-200 rounded-lg cursor-pointer">
                            <input type="text" name="color_text" value="{{ old('color', $bootcamp->color ?? '#3B82F6') }}"
                                   class="flex-1 border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.session_info') }}</label>
                        <input type="text" name="sessions_info" value="{{ old('sessions_info', $bootcamp->sessions_info) }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        {{ __('app.save_changes') }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Sessions Management --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-900 mb-4">{{ __('app.bootcamp_sessions') }}</h3>

            {{-- Add Session Form --}}
            <form method="POST" action="{{ route('mentor.bootcamps.sessions.store', $bootcamp) }}" class="mb-6 p-4 bg-gray-50 rounded-lg">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="date" required placeholder="{{ __('app.date') }}" class="border border-gray-200 rounded-lg px-4 py-2">
                    <input type="text" name="topic" required placeholder="{{ __('app.session_topic') }}" class="border border-gray-200 rounded-lg px-4 py-2">
                    <input type="text" name="time" required placeholder="{{ __('app.time') }}" class="border border-gray-200 rounded-lg px-4 py-2">
                    <input type="url" name="meeting_url" placeholder="{{ __('app.meeting_url') }}" class="border border-gray-200 rounded-lg px-4 py-2">
                </div>
                <input type="text" name="description" placeholder="{{ __('app.description_optional') }}" class="mt-3 w-full border border-gray-200 rounded-lg px-4 py-2">
                <button type="submit" class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                    {{ __('app.add_session') }}
                </button>
            </form>

            @forelse($bootcamp->sessions->sortBy('order') as $session)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-medium text-gray-700">{{ $session->date }}</span>
                        <span class="text-sm text-gray-500">{{ $session->time }}</span>
                    </div>
                    <p class="font-medium text-gray-900">{{ $session->topic }}</p>
                    @if($session->meeting_url)
                        <a href="{{ $session->meeting_url }}" target="_blank" class="text-sm text-blue-600 hover:underline">{{ $session->meeting_url }}</a>
                    @endif
                </div>
                <form method="POST" action="{{ route('mentor.bootcamps.sessions.destroy', [$bootcamp, $session]) }}" class="inline ml-4" onsubmit="return confirm('{{ __('app.delete_session_confirm') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">{{ __('app.delete') }}</button>
                </form>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">{{ __('app.no_session_data') }}</p>
            @endforelse
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white rounded-xl border border-red-200 p-6">
            <h3 class="font-semibold text-red-600 mb-4">{{ __('app.danger_zone') }}</h3>
            <p class="text-sm text-gray-600 mb-4">{{ __('app.delete_bootcamp_warning') }}</p>
            <form method="POST" action="{{ route('mentor.bootcamps.destroy', $bootcamp) }}" onsubmit="return confirm('{{ __('app.delete_bootcamp_confirm') }}');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium">
                    {{ __('app.delete') }} Bootcamp
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
