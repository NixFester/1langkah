@extends('layouts.marketing')

@section('title', __('app.marketing_dashboard'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="__('app.marketing_dashboard')"
        :description="__('app.overview_system')"
    />

    <x-flash-messages />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <x-stat-card :label="__('app.active_promo')" :value="$promoStats['active']" icon="promo" color="red" />
        <x-stat-card :label="__('app.new_students_today')" :value="$studentStats['new_today']" icon="users" color="blue" />
        <x-stat-card :label="__('app.enrollments_this_week')" :value="$enrollmentStats['this_week']" icon="book" color="green" />
        <x-stat-card :label="__('app.total_students')" :value="$studentStats['total']" icon="users" color="amber" />
    </div>

    {{-- Promo Overview --}}
    <x-card-panel :title="__('app.active_promo_codes')" :actionRoute="route('marketing.promo-codes.create')" :actionLabel="__('app.create_new_promo')">
        @if($activePromos->isEmpty())
            <x-empty-state
                :message="__('app.no_active_promo_codes')"
                icon="promo"
                :actionRoute="route('marketing.promo-codes.create')"
                :actionLabel="__('app.create_first_promo')"
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activePromos as $promo)
                    <div class="p-4 bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl border border-red-100 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 bg-red-600 text-white rounded text-xs font-bold">{{ $promo->code }}</span>
                            <span class="text-xs text-gray-500 font-medium">
                                {{ $promo->remaining_uses !== null ? $promo->remaining_uses .  ' ' . __('app.remaining') : __('app.unlimited') }}
                            </span>
                        </div>
                        <p class="font-medium text-gray-800 mb-1">{{ $promo->name }}</p>
                        <p class="text-2xl font-bold text-red-600">{{ $promo->type_label }}</p>
                        @if($promo->max_uses)
                            <div class="mt-2 h-2 bg-white/50 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full" style="width: {{ min(100, $promo->usage_percentage) }}%"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-card-panel>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Courses --}}
        <x-card-panel :title="__('app.top_courses_marketing')">
            @if($topCourses->isEmpty())
                <x-empty-state :message="__('app.no_course_data')" icon="book" />
            @else
                <div class="space-y-3">
                    @foreach($topCourses as $course)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 gap-3">
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 truncate">{{ $course->title }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ $course->category }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[12px] font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                                <p class="text-[11px] text-gray-400">{{ __('app.student_lowercase') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Top Promo Codes --}}
        <x-card-panel :title="__('app.most_used_promos')">
            @if($topPromos->isEmpty())
                <x-empty-state :message="__('app.no_used_promos')" icon="promo" />
            @else
                <div class="space-y-3">
                    @foreach($topPromos as $promo)
                        <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100 gap-3">
                            <div class="min-w-0">
                                <p class="text-[13px] font-bold text-gray-900 truncate">{{ $promo->code }}</p>
                                <p class="text-[11px] text-gray-500 truncate">{{ $promo->name }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-[12px] font-bold text-red-600">{{ $promo->used_count }}</p>
                                <p class="text-[11px] text-gray-400">{{ __('app.times_used') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>

</div>
@endsection
