@extends('layouts.marketing')

@section('title', __('app.promo_codes_title'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.promo_codes_title')"
        :description="__('app.overview_system')"
        actionRoute="{{ route('marketing.promo-codes.create') }}"
        :actionLabel="__('app.create_new_promo')"
    >
        <x-slot name="actionIcon">
            <x-icon name="promo" class="w-4 h-4" />
        </x-slot>
    </x-page-header>

    <x-flash-messages />

    {{-- Filters --}}
    <x-filter-form>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }}</label>
            <select aria-label="Status" name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-[#cc0000] focus:border-[#cc0000] w-full">
                <option value="">{{ __('app.all_status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.active_status') }}</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>{{ __('app.expired') }}</option>
                <option value="maxed" {{ request('status') === 'maxed' ? 'selected' : '' }}>{{ __('app.maxed_out') }}</option>
            </select>
        </div>
    </x-filter-form>

    <!-- DATA TABLE -->
    <x-data-table :paginator="$promos">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.code') }}</th>
                <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.name') }}</th>
                <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.type') }}</th>
                <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.used') }}</th>
                <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.status') }}</th>
                <th class="px-4 md:px-6 py-4 font-bold text-left">{{ __('app.action') }}</th>
            </tr>
        </thead>

        @forelse($promos as $promo)
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm">
                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-lg font-bold">{{ $promo->code }}</span>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-800 font-medium">{{ $promo->name }}</td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm text-gray-500">{{ $promo->type_label }}</td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-gray-800">{{ $promo->used_count }}</span>
                        @if($promo->max_uses)
                            <span class="text-gray-400 text-xs">/ {{ $promo->max_uses }}</span>
                            <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full" style="width: {{ min(100, $promo->usage_percentage) }}%"></div>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs">/ ∞</span>
                        @endif
                    </div>
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm">
                    @if($promo->is_active && $promo->isValid())
                        <x-stat-badge status="active" />
                    @elseif($promo->expires_at && $promo->expires_at->lt(now()))
                        <x-stat-badge status="expired" />
                    @else
                        <x-stat-badge status="inactive" />
                    @endif
                </td>
                <td class="px-4 md:px-6 py-3 md:py-4 whitespace-nowrap text-sm">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('marketing.promo-codes.edit', $promo) }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">{{ __('app.edit') }}</a>
                        <form action="{{ route('marketing.promo-codes.toggle', $promo) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">
                                {{ $promo->is_active ? __('app.deactivate') : __('app.activate') }}
                            </button>
                        </form>
                        <form action="{{ route('marketing.promo-codes.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('app.delete_confirm') }}')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium transition-colors">{{ __('app.delete') }}</button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 md:px-6 py-8 md:py-12 text-center">
                    <x-empty-state :message="__('app.no_promo_codes')" icon="folder" />
                </td>
            </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
