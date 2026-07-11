@extends('layouts.app')

@section('title', 'Manage Bootcamps')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.manage_bootcamps')"
        :description="__('app.bootcamps_list_desc', ['count' => $bootcamps->total()])"
        actionRoute="{{ route('admin.bootcamps.new') }}"
        :actionLabel="__('app.add_bootcamp')"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$bootcamps">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold">{{ __('app.bootcamp') }}</th>
                <th class="px-6 py-4 font-bold">{{ __('app.type') }}</th>
                <th class="px-6 py-4 font-bold">{{ __('app.start') }}</th>
                <th class="px-6 py-4 font-bold">{{ __('app.price') }}</th>
                <th class="px-6 py-4 font-bold text-right">{{ __('app.action') }}</th>
            </tr>
        </template>

        @forelse($bootcamps as $bootcamp)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0 text-orange-600">
                        <x-icon name="award" class="w-6 h-6" />
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ $bootcamp->title }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                            <x-icon name="users" class="w-3 h-3 text-gray-400" />
                            {{ __('app.mentor') }}: {{ $bootcamp->mentor_name }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <x-stat-badge :type="$bootcamp->type" />
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900">{{ $bootcamp->start_date }}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm font-bold text-gray-900">
                    {{ $bootcamp->formatted_price }}
                </div>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.bootcamps.manage', $bootcamp) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.manage') }}
                    </a>
                    <form method="POST" action="{{ route('admin.bootcamps.destroy', $bootcamp) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_bootcamp_confirm') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-8">
                <x-empty-state :message="__('app.no_bootcamp_data')" icon="calendar" />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
