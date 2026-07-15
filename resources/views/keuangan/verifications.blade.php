@extends('layouts.keuangan')

@section('title', __('app.payment_verification'))
@section('header_title', __('app.payment_verification'))

@section('content')
    <x-flash-messages />

    {{-- Filters --}}
    <x-filter-form :showExport="false">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.status') }}</label>
            <select aria-label="Status" name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                <option value="">{{ __('app.all') }}</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('app.pending') }}</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('app.approved') }}</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('app.rejected') }}</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.from_date') }}</label>
            <input aria-label="Date From" type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('app.to_date') }}</label>
            <input aria-label="Date To" type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-4 py-2">
        </div>
    </x-filter-form>

    {{-- Table --}}
    <x-data-table :paginator="$verifications">
        <template #thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.student') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.course') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.amount') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.status') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.date') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.action') }}</th>
            </tr>
        </template>

        @forelse($verifications as $v)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                            <span class="text-amber-600 font-bold text-sm">{{ substr($v->user->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $v->user->name ?? __('app.unknown') }}</p>
                            <p class="text-xs text-gray-400">{{ $v->user->email ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-800">{{ $v->course_title }}</td>
                <td class="px-6 py-4 font-bold text-gray-800">Rp {{ number_format($v->amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4">
                    <x-stat-badge :status="$v->status" />
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('keuangan.verifications.show', $v) }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm">
                        {{ __('app.detail') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-6 py-8 md:py-12">
                    <x-empty-state :message="__('app.no_payment_verification_data')" icon="payment" />
                </td>
            </tr>
        @endforelse
    </x-data-table>
@endsection
