@extends('layouts.keuangan')

@section('title', __('app.enrollments'))
@section('header_title', __('app.enrollments_list'))

@section('content')
    <x-flash-messages />

    <x-data-table :paginator="$enrollments">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.student') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.course') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.payment_amount') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.verification_date') }}</th>
            </tr>
        </thead>
        @forelse($enrollments as $e)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-bold text-sm">{{ substr($e->user->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $e->user->name ?? __('app.unknown') }}</p>
                            <p class="text-xs text-gray-400">{{ $e->user->email ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-800">{{ $e->course_title }}</td>
                <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $e->verified_at?->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-8 md:py-12">
                    <x-empty-state :message="__('app.no_enrollment_data')" icon="users" />
                </td>
            </tr>
        @endforelse
    </x-data-table>
@endsection
