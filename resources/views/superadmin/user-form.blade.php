@extends('layouts.superadmin')

@section('title', __('app.edit_user_colon') . $user->name)
@section('header_title', __('app.edit_user'))

@section('content')
    <x-back-button route="{{ route('superadmin.users') }}" />

    <x-flash-messages />

    <div class="max-w-2xl mx-auto">
        <x-card-panel>
            <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.name_star') }}</label>
                        <input aria-label="Name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.email_star') }}</label>
                        <input aria-label="Email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.role_star') }}</label>
                        <select aria-label="Role" name="role" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ $user->role === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.photo_url') }}</label>
                        <input aria-label="Profile Photo" type="url" name="profile_photo" value="{{ old('profile_photo', $user->profile_photo) }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.bio') }}</label>
                        <textarea aria-label="Bio" name="bio" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6 pt-6 border-t border-gray-100">
                    <a href="{{ route('superadmin.users') }}" class="px-6 py-2 text-gray-600 hover:text-gray-800">{{ __('app.cancel') }}</a>
                    <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">
                        {{ __('app.save_changes') }}
                    </button>
                </div>
            </form>
        </x-card-panel>
    </div>
@endsection
