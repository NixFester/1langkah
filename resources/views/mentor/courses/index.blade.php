@extends('layouts.mentor')

@section('title', __('app.manage_courses'))

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.manage_courses')"
        :description="__('app.manage_courses_desc_mentor')"
        actionRoute="{{ route('mentor.courses.create') }}"
        :actionLabel="__('app.create_new_course')"
    />

    @if($courses->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <x-empty-state
            :message="__('app.no_courses_mentor')"
            icon="document"
            :actionRoute="route('mentor.courses.create')"
            :actionLabel="__('app.create_first_course')"
        />
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.course') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.level') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.participant') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.chapter') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($courses as $course)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($course->thumbnail_url)
                                    <img decoding="async" loading="lazy" alt="" src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-12 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold" style="background-color: {{ $course->color ?? '#3B82F6' }}">
                                        {{ substr($course->title, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900">{{ $course->title }}</p>
                                    <p class="text-sm text-gray-500">{{ $course->category }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700">
                                {{ ucfirst($course->level) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-600">{{ $course->chapters->count() }} {{ __('app.chapter_lowercase') }}</span>
                            <span class="text-gray-400 mx-1">•</span>
                            <span class="text-gray-600">{{ $course->chapters->sum(fn($c) => $c->videos->count()) }} {{ __('app.video') }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('mentor.courses.edit', $course) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    {{ __('app.edit') }}
                                </a>
                                <form method="POST" action="{{ route('mentor.courses.destroy', $course) }}" class="m-0" onsubmit="return confirm('{{ __('app.delete_course_confirm') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                        {{ __('app.delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $courses->links() }}
    @endif
</div>
@endsection
