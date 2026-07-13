@extends('layouts.superadmin')

@section('title', __('app.manage_users'))
@section('header_title', __('app.manage_users'))

@section('content')
    <x-flash-messages />

    {{-- Filters --}}
    <x-filter-form>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.search') }}</label>
            <input aria-label="{{ __('app.search_name_email') }}" type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_name_email') }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.role') }}</label>
            <select aria-label="Role" name="role" class="border border-gray-300 rounded-lg px-4 py-2">
                <option value="">{{ __('app.all_roles') }}</option>
                @foreach($roles as $key => $label)
                    <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </x-filter-form>

    {{-- Table --}}
    <x-data-table :paginator="$users">
        <template #thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.user') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.role') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.joined') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.action') }}</th>
            </tr>
        </template>

        @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <x-user-avatar :user="$user" size="md" />
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-sm text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('superadmin.users.role', $user) }}" method="POST" class="inline">
                        @csrf
                        <select aria-label="Role" name="role" onchange="this.form.submit()" class="text-sm border border-gray-300 rounded px-2 py-1">
                            @foreach($roles as $key => $label)
                                <option value="{{ $key }}" {{ $user->role === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-700 text-sm">{{ __('app.edit') }}</a>
                        @if($user->id !== auth()->id())
                            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('app.delete_user_confirm') }}')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">{{ __('app.delete') }}</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12">
                    <x-empty-state :message="__('app.no_user')" icon="users" />
                </td>
            </tr>
        @endforelse
    </x-data-table>
@endsection
