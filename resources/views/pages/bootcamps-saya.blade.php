@php
    $activePage = 'bootcamps-saya';
@endphp

@extends('layouts.app')

@section('title', __('app.my_bootcamps') . ' — 1Langkah')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 -mt-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ __('app.my_bootcamps') }}</h1>
            <p class="text-sm text-gray-500">{{ count($myBootcamps) }} {{ __('app.active_bootcamps_count') }}</p>
        </div>
        <a href="{{ route('online-bootcamp') }}" class="bg-red-600 hover:bg-red-700 text-white rounded-full px-5 py-3 text-sm font-bold transition-colors flex items-center gap-2">
            <span>{{ __('app.find_bootcamp') }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6" x-data="{
            bootcamps: {{ json_encode($myBootcamps) }},
            currentPage: 1,
            perPage: 12,
            get totalPages() {
                return Math.ceil(this.bootcamps.length / this.perPage) || 1;
            },
            get paginatedBootcamps() {
                const start = (this.currentPage - 1) * this.perPage;
                return this.bootcamps.slice(start, start + this.perPage);
            },
            changePage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            get pageNumbers() {
                let pages = [];
                for (let i = 1; i <= this.totalPages; i++) {
                    if (i === 1 || i === this.totalPages || Math.abs(i - this.currentPage) <= 1) {
                        if (pages.length > 0 && i - pages[pages.length - 1] > 1) {
                            pages.push('...');
                        }
                        pages.push(i);
                    }
                }
                return pages;
            }
        }">
            <template x-for="bootcamp in paginatedBootcamps" :key="bootcamp.id">
                <a :href="bootcamp.type === 'offline' ? '/bootcamp/offline/' + bootcamp.id : '/bootcamp/online/' + bootcamp.id" class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow block mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-full sm:w-28 h-28 rounded-3xl bg-gray-100 overflow-hidden flex-shrink-0">
                            <template x-if="bootcamp.thumbnail">
                                <img decoding="async" loading="lazy" :src="bootcamp.thumbnail" :alt="bootcamp.title" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!bootcamp.thumbnail">
                                <div class="w-full h-full bg-red-200"></div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-bold text-gray-900" x-text="bootcamp.title"></h2>
                                    <p class="text-sm text-gray-500 mt-1" x-text="bootcamp.mentor"></p>
                                </div>
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-semibold"
                                      :class="bootcamp.type === 'offline' ? 'bg-orange-50 text-orange-700' : 'bg-purple-50 text-purple-700'"
                                      x-text="bootcamp.type === 'offline' ? 'Offline' : 'Online'">
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-500">
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900" x-text="bootcamp.sessions || '-'"></div>
                                    <div>{{ __('app.sessions') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900" x-text="(bootcamp.progress || 0) + '%'"></div>
                                    <div>{{ __('app.progress') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900" x-text="Number(bootcamp.rating || 0).toFixed(1)"></div>
                                    <div>{{ __('app.rating') }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4">
                                    <div class="font-semibold text-gray-900" x-text="bootcamp.enrolled_at ? new Date(bootcamp.enrolled_at).toLocaleDateString('id-ID', {day: '2-digit', month: 'short', year: 'numeric'}) : '-'"></div>
                                    <div>{{ __('app.enrolled_at') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </template>

            <div x-show="bootcamps.length === 0" style="display: none;">
                <x-empty-state
                    :message="__('app.no_enrolled_bootcamp')"
                    icon="users"
                    :actionRoute="route('online-bootcamp')"
                    :actionLabel="__('app.find_bootcamp')"
                />
            </div>

            <!-- Pagination -->
            <div x-show="totalPages > 1" class="flex justify-center mt-8 pb-4" style="display: none;">
                <nav class="flex items-center gap-1 sm:gap-2">
                    <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" 
                        class="p-2 sm:px-3 sm:py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <template x-for="(page, index) in pageNumbers" :key="index">
                        <div>
                            <button x-show="page !== '...'" @click="changePage(page)" 
                                :class="currentPage === page ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg border flex items-center justify-center text-sm font-medium transition-colors"
                                x-text="page">
                            </button>
                            <span x-show="page === '...'" class="px-1 sm:px-2 text-gray-400">...</span>
                        </div>
                    </template>
                    <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" 
                        class="p-2 sm:px-3 sm:py-2 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </nav>
            </div>
        </div>

        <div class="space-y-6">
            <x-card-panel :title="__('app.bootcamp_stats')">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="rounded-3xl bg-red-50 p-4">
                        <div class="text-3xl font-bold text-red-600">{{ $userStats['bootcamps_enrolled'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.enrolled_bootcamps') }}</div>
                    </div>
                    <div class="rounded-3xl bg-green-50 p-4">
                        <div class="text-3xl font-bold text-green-600">{{ $userStats['bootcamps_completed'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.completed_bootcamps') }}</div>
                    </div>
                    <div class="rounded-3xl bg-purple-50 p-4">
                        <div class="text-3xl font-bold text-purple-600">{{ $userStats['xp'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.total_xp') }}</div>
                    </div>
                    <div class="rounded-3xl bg-yellow-50 p-4">
                        <div class="text-3xl font-bold text-yellow-700">{{ $userStats['certificates'] ?? 0 }}</div>
                        <div class="mt-1">{{ __('app.certificates') }}</div>
                    </div>
                </div>
            </x-card-panel>
            <x-card-panel :title="__('app.bootcamp_guide')">
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex gap-3"><span class="text-red-600">•</span> {{ __('app.guide_attendance') }}</li>
                    <li class="flex gap-3"><span class="text-red-600">•</span> {{ __('app.guide_material') }}</li>
                    <li class="flex gap-3"><span class="text-red-600">•</span> {{ __('app.guide_mentor') }}</li>
                </ul>
            </x-card-panel>
        </div>
    </div>
</div>
@endsection
