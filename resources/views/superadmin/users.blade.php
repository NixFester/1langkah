@extends('layouts.superadmin')

@section('title', __('app.manage_users'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.manage_users')"
        :description="__('app.registered_users_list', ['count' => $users->total()])"
    />

    <x-flash-messages />

    {{-- Filters --}}
    <x-filter-form>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.search') }}</label>
            <input aria-label="{{ __('app.search_name_email') }}" type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('app.search_name_email') }}" class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.role') }}</label>
            <select aria-label="Role" name="role" class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full">
                <option value="">{{ __('app.all_roles') }}</option>
                @foreach($roles as $key => $label)
                    <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </x-filter-form>

    <!-- DATA TABLE -->
    <x-data-table :paginator="$users">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold text-left">{{ __('app.user') }}</th>
                <th class="px-6 py-4 font-bold text-left">{{ __('app.role') }}</th>
                <th class="px-6 py-4 font-bold text-left">{{ __('app.joined') }}</th>
                <th class="px-6 py-4 font-bold text-right">{{ __('app.action') }}</th>
            </tr>
        </thead>

        @forelse($users as $user)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                    <x-user-avatar :user="$user" size="md" />
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap">
                <form method="POST" action="{{ route('superadmin.users.role', $user) }}" class="inline-block m-0">
                    @csrf
                    @method('PATCH')
                    <select aria-label="Role" name="role" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-lg focus:ring-red-500 focus:border-red-500 block w-full min-w-[110px] py-1.5 px-2.5 cursor-pointer font-medium appearance-none">
                        @foreach($roles as $key => $label)
                            <option value="{{ $key }}" {{ $user->role === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-sm text-gray-500 whitespace-nowrap">
                {{ $user->created_at->format('d M Y') }}
            </td>
            <td class="px-4 md:px-6 py-3 md:py-4 text-right whitespace-nowrap">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('superadmin.users.edit', $user) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.edit') }}
                    </a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_user_confirm') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.delete') }}
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center justify-center bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-xs font-bold">{{ __('app.you') }}</span>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="px-6 py-8 md:py-12">
                <x-empty-state :message="__('app.no_user')" icon="users" />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
