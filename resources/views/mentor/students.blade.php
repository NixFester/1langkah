@extends('layouts.mentor')

@section('title', __('app.my_students'))
@section('header_title', __('app.my_students'))

@section('content')
    <x-flash-messages />

    <x-data-table :paginator="$students">
        <template #thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.student') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.course') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.completed') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.last_activity') }}</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('app.action') }}</th>
            </tr>
        </template>

        @forelse($students as $data)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($data['user']?->profile_photo)
                            <img src="{{ $data['user']->profile_photo }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                {{ substr($data['user']->name ?? 'U', 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-800">{{ $data['user']->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $data['user']->email ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-800">
                    {{ $data['total_courses'] }} {{ __('app.course_lowercase') }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-sm">
                        {{ $data['completed_courses'] }} {{ __('app.completed_lowercase') }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $data['last_activity']?->diffForHumans() ?? __('app.not_active_yet') }}
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('mentor.student-detail', $data['user']) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        {{ __('app.detail') }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-12">
                    <x-empty-state :message="__('app.no_student_data')" icon="users" />
                </td>
            </tr>
        @endforelse
    </x-data-table>
@endsection
