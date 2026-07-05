@extends('layouts.marketing')

@section('title', 'Dashboard Marketing')
@section('header_title', 'Dashboard Marketing')

@section('content')
    <x-flash-messages />

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card label="Promo Aktif" :value="$promoStats['active']" icon="promo" color="pink" />
        <x-stat-card label="Siswa Baru Hari Ini" :value="$studentStats['new_today']" icon="users" color="blue" />
        <x-stat-card label="Enrollments Minggu Ini" :value="$enrollmentStats['this_week']" icon="book" color="green" />
        <x-stat-card label="Total Siswa" :value="$studentStats['total']" icon="users" color="purple" />
    </div>

    {{-- Promo Overview --}}
    <x-card-panel title="Promo Codes Aktif" :actionRoute="route('marketing.promo-codes.create')" actionLabel="Buat Promo Baru" class="mb-8">
        @if($activePromos->isEmpty())
            <x-empty-state
                message="Belum ada promo code aktif"
                icon="promo"
                :actionRoute="route('marketing.promo-codes.create')"
                actionLabel="Buat promo pertama"
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($activePromos as $promo)
                    <div class="p-4 bg-gradient-to-br from-pink-50 to-purple-50 rounded-lg border border-pink-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="px-2 py-1 bg-pink-500 text-white rounded text-xs font-bold">{{ $promo->code }}</span>
                            <span class="text-xs text-gray-500">
                                {{ $promo->remaining_uses !== null ? $promo->remaining_uses . ' tersisa' : 'Unlimited' }}
                            </span>
                        </div>
                        <p class="font-medium text-gray-800 mb-1">{{ $promo->name }}</p>
                        <p class="text-2xl font-bold text-pink-600">{{ $promo->type_label }}</p>
                        @if($promo->max_uses)
                            <div class="mt-2 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-pink-500 rounded-full" style="width: {{ min(100, $promo->usage_percentage) }}%"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </x-card-panel>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Courses --}}
        <x-card-panel title="Kursus Terpopuler">
            @if($topCourses->isEmpty())
                <x-empty-state message="Belum ada data kursus" icon="book" />
            @else
                <div class="space-y-4">
                    @foreach($topCourses as $course)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $course->title }}</p>
                                <p class="text-sm text-gray-500">{{ $course->category }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                                <p class="text-xs text-gray-400">siswa</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Top Promo Codes --}}
        <x-card-panel title="Promo Paling Banyak Digunakan">
            @if($topPromos->isEmpty())
                <x-empty-state message="Belum ada promo yang digunakan" icon="promo" />
            @else
                <div class="space-y-4">
                    @foreach($topPromos as $promo)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $promo->code }}</p>
                                <p class="text-sm text-gray-500">{{ $promo->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-pink-600">{{ $promo->used_count }}</p>
                                <p class="text-xs text-gray-400">kali digunakan</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>
@endsection
