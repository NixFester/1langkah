@extends('layouts.mentor')

@section('title', __('app.manage_bootcamps_mentor'))

@section('content')
<div class="space-y-6">
    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.manage_bootcamps_mentor')"
        :description="__('app.manage_bootcamps_desc_mentor')"
        actionRoute="{{ route('mentor.bootcamps.create') }}"
        :actionLabel="__('app.create_new_bootcamp')"
    />

    @if($bootcamps->isEmpty())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <x-empty-state
            :message="__('app.no_bootcamps_yet')"
            icon="document"
            :actionRoute="route('mentor.bootcamps.create')"
            :actionLabel="__('app.create_first_bootcamp')"
        />
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($bootcamps as $bootcamp)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-32 bg-gradient-to-br" style="background-color: {{ $bootcamp->color ?? '#3B82F6' }}"></div>
            <div class="p-5">
                <div class="flex items-start justify-between mb-3">
                    <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $bootcamp->type === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                        {{ ucfirst($bootcamp->type) }}
                    </span>
                    <span class="text-sm font-medium text-gray-600">{{ $bootcamp->price }}</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $bootcamp->title }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $bootcamp->start_date }}</p>
                <p class="text-sm text-gray-600 mb-4">{{ $bootcamp->enrollments_count ?? 0 }} {{ __('app.participant') }}</p>

                <div class="flex items-center gap-2 pt-3 border-t">
                    <a href="{{ route('mentor.bootcamps.edit', $bootcamp) }}" class="flex-1 inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.edit') }}
                    </a>
                    @if($bootcamp->type === 'offline')
                    <a href="{{ route('mentor.bootcamps.attendance', $bootcamp) }}" class="flex-1 inline-flex items-center justify-center bg-green-50 text-green-600 hover:bg-green-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        {{ __('app.bootcamp_attendance') }}
                    </a>
                    @endif
                    <form method="POST" action="{{ route('mentor.bootcamps.destroy', $bootcamp) }}" class="flex-1 m-0" onsubmit="return confirm('{{ __('app.delete_bootcamp_confirm') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            {{ __('app.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $bootcamps->links() }}
    @endif
</div>
@endsection
