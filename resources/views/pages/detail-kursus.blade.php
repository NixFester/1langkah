@extends('layouts.app', ['activePage' => 'kursus'])

@section('title', $course['title'] . ' — 1Langkah')
@section('header_title', 'Detail Kursus')

@section('content')
@php
    $c = $course;
    $benefits = (!empty($c['benefits']) && is_array($c['benefits'])) ? $c['benefits'] : [
        'Sertifikat completion',
        'Akses seumur hidup',
        'Forum diskusi',
        'Download materi',
        'Project portfolio',
    ];
    $curriculum = $c['curriculum'] ?? [];
    // $resources from controller (DB table) - do not overwrite!
    $isEnrolled = $isEnrolled ?? false;
@endphp

<!-- Hero Section -->
<div class="-mx-7 -mt-7 relative bg-slate-900 pt-20 pb-28 px-12 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ $c['thumbnail'] ?? 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80' }}" alt="Hero Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/80 to-transparent"></div>
    </div>
    <div class="relative z-10 w-full mt-6">
        <div class="flex items-center gap-3 mb-6">
            <span class="px-4 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">{{ $c['category'] ?? 'Programming' }}</span>
            <span class="px-4 py-1 bg-white text-gray-700 text-xs font-bold rounded-full">{{ $c['level'] ?? 'Intermediate' }}</span>
            @if(!empty($c['badge']))
            <span class="px-4 py-1 bg-yellow-100 text-orange-600 text-xs font-bold rounded-full">{{ $c['badge'] }}</span>
            @endif
        </div>

        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-5 leading-tight tracking-tight">{{ $c['title'] }}</h1>

        @if(!empty($c['short_description']))
        <p class="text-lg text-gray-300 mb-8 max-w-3xl leading-relaxed">{{ $c['short_description'] }}</p>
        @endif

        <div class="flex items-center gap-6 text-sm text-gray-300">
            <div class="flex items-center gap-1.5">
                <div class="flex text-yellow-400">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= round($c['rating'] ?? 0) ? '' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                <span class="font-medium ml-1 text-gray-400">{{ number_format((float) ($c['rating'] ?? 0), 1) }}</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1 h-1 bg-gray-500 rounded-full mr-1"></span>
                {{ number_format((int) ($c['students'] ?? 0)) }} siswa
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="w-full py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">

        <!-- Left Column -->
        <div class="{{ $isEnrolled ? 'lg:col-span-4' : 'lg:col-span-2' }} space-y-8" x-data="{ activeTab: 'overview', openChapter: null }">

            <!-- Tabs -->
            <div class="bg-gray-50 p-1.5 rounded-full flex w-full overflow-x-auto scrollbar-hide shadow-inner border border-gray-100/50">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-bold whitespace-nowrap transition-all">Overview</button>
                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Curriculum</button>
                <button @click="activeTab = 'photos'" :class="activeTab === 'photos' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Photos</button>
                <button @click="activeTab = 'reviews'" :class="activeTab === 'reviews' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Reviews</button>
                <button @click="activeTab = 'resources'" :class="activeTab === 'resources' ? 'bg-white text-red-600 shadow-sm border border-gray-100' : 'text-gray-500'" class="flex-1 px-6 py-3 rounded-full text-sm font-semibold whitespace-nowrap transition-all">Resources</button>
            </div>

            <!-- Overview Tab -->
            <div x-show="activeTab === 'overview'">
               

                <!-- Benefits -->
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                    <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">Benefits</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                        @foreach($benefits as $benefit)
                        <div class="flex items-start gap-3">
                            <div class="flex items-center justify-center w-5 h-5 rounded-full border border-green-500 text-green-500 flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm text-gray-600 leading-relaxed font-medium">{{ $benefit }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                @if(!empty($c['description']))
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)] mt-6">
                    <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">Deskripsi</h2>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($c['description'])) !!}
                    </div>
                </div>
                @endif
            </div>

            <!-- Curriculum Tab -->
            <div x-show="activeTab === 'curriculum'" x-cloak>
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[22px] font-bold text-gray-900 tracking-tight">Curriculum</h2>
                        @if($isCompleted)
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-700 text-sm font-bold rounded-full flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kursus Selesai
                        </span>
                        @endif
                    </div>

                    @if(!empty($chapters))
                    <div class="space-y-4">
                        @foreach($chapters as $index => $chapter)
                        <div class="border border-gray-100 rounded-xl overflow-hidden" x-data="{ open: openChapter === {{ $index }} }">
                            <!-- Chapter Header -->
                            <div @click="open = !open; openChapter = open ? {{ $index }} : null" class="flex items-center justify-between p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-full {{ ($chapter['is_completed'] ?? false) ? 'bg-emerald-500 text-white' : 'bg-red-100 text-red-600' }} flex items-center justify-center text-sm font-bold">
                                        @if(($chapter['is_completed'] ?? false))
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                        {{ $index + 1 }}
                                        @endif
                                    </span>
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $chapter['title'] }}</span>
                                        <span class="ml-2 text-xs text-gray-500 video-counter" data-total="{{ $chapter['total_videos'] ?? count($chapter['videos'] ?? []) }}">
                                            {{ $chapter['completed_videos'] ?? 0 }}/{{ $chapter['total_videos'] ?? count($chapter['videos'] ?? []) }} videos
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 text-sm text-gray-500">
                                    @if(($chapter['is_completed'] ?? false))
                                    <span class="text-emerald-600 font-medium flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Selesai
                                    </span>
                                    @endif
                                    <span>{{ $chapter['duration'] ?? '0h' }}</span>
                                    <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            <!-- Videos List -->
                            <div x-show="open" x-collapse class="border-t border-gray-100">
                                @if(!empty($chapter['description']))
                                <div class="p-4 bg-gray-50/50 border-b border-gray-100">
                                    <p class="text-sm text-gray-600 leading-relaxed">{{ $chapter['description'] }}</p>
                                </div>
                                @endif
                                @if(!empty($chapter['videos']))
                                @foreach($chapter['videos'] as $video)
                                <div class="flex items-center gap-4 p-4 {{ !$isEnrolled ? 'opacity-60' : '' }} hover:bg-gray-50 transition-colors {{ !$loop->last ? 'border-b border-gray-50' : '' }}
                                    {{ ($video['is_completed'] ?? false) ? 'video-completed' : '' }}"
                                    data-video-id="{{ $video['id'] }}"
                                    data-chapter-id="{{ $chapter['id'] }}"
                                    data-course-id="{{ $course['id'] }}"
                                    data-video-url="{{ $video['video_url'] ?? '' }}"
                                >
                                    @if($isEnrolled)
                                        <!-- Enrolled: clickable video -->
                                        <div class="flex items-center gap-4 flex-1 group video-item {{ ($video['is_completed'] ?? false) ? 'cursor-default' : 'cursor-pointer' }}">
                                            <div class="w-24 h-14 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden relative {{ ($video['is_completed'] ?? false) ? 'bg-emerald-100' : 'bg-gray-200' }}">
                                                @if(!empty($video['thumbnail_url']))
                                                <img src="{{ $video['thumbnail_url'] }}" alt="{{ $video['title'] }}" class="w-full h-full object-cover">
                                                @endif
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    @if(($video['is_completed'] ?? false))
                                                    <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                    @else
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-900 group-hover:text-red-600 transition-colors truncate {{ ($video['is_completed'] ?? false) ? 'line-through text-gray-500' : '' }}">{{ $video['title'] }}</p>
                                                <p class="text-xs text-gray-500">
                                                    @if(($video['is_completed'] ?? false))
                                                    <span class="text-emerald-600">Selesai ditonton</span>
                                                    @else
                                                    {{ $video['duration'] ?? '' }}
                                                    @endif
                                                </p>
                                            </div>
                                            @if(($video['is_completed'] ?? false))
                                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            @else
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                                            @endif
                                        </div>
                                    @else
                                        <!-- Not enrolled: locked video -->
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="w-24 h-14 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0 overflow-hidden relative">
                                                @if(!empty($video['thumbnail_url']))
                                                <img src="{{ $video['thumbnail_url'] }}" alt="{{ $video['title'] }}" class="w-full h-full object-cover blur-sm">
                                                @endif
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-900 truncate">{{ $video['title'] }}</p>
                                                <p class="text-xs text-gray-500">{{ $video['duration'] ?? '' }}</p>
                                            </div>
                                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full flex-shrink-0">Locked</span>
                                        </div>
                                    @endif
                                </div>
                                @endforeach
                                @else
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    Tidak ada video untuk chapter ini.
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if(!$isEnrolled)
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-sm text-amber-800 text-center">
                            <a href="{{ route('pembayaran', ['id' => $c['id']]) }}" class="font-semibold text-red-600 hover:underline">Daftar sekarang</a> untuk mengakses semua video.
                        </p>
                    </div>
                    @endif
                    @else
                    <p class="text-gray-500">Curriculum belum tersedia.</p>
                    @endif
                </div>
            </div>

            <!-- Photos Tab -->
            <div x-show="activeTab === 'photos'" x-cloak>
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                    <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">Photos</h2>
                    @if(!empty($photos))
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($photos as $photo)
                        <a href="{{ $photo['url'] }}" target="_blank" class="block rounded-xl overflow-hidden aspect-video hover:opacity-90 transition-opacity">
                            <img src="{{ $photo['url'] }}" alt="{{ $photo['alt'] ?? $c['title'] }}" class="w-full h-full object-cover">
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <p class="text-gray-500">Tidak ada foto untuk kursus ini.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" x-cloak>
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                    <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">Reviews & Ratings</h2>

                    <!-- Rating Summary + User Rating -->
                    <div class="flex flex-col lg:flex-row gap-6 mb-8 p-6 bg-gray-50 rounded-2xl">
                        <!-- Left: Rating Summary (Server calculated) -->
                        <div class="flex-1 text-center lg:text-left">
                            <div class="text-5xl font-extrabold text-gray-900">{{ number_format((float) ($c['rating'] ?? 0), 1) }}</div>
                            <div class="flex text-yellow-400 mt-2 justify-center lg:justify-start">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= round($c['rating'] ?? 0) ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $reviews->total() }} reviews</p>
                        </div>

                        <!-- Right: User's Own Rating -->
                        @auth
                        <div class="flex-1 border-t lg:border-t-0 lg:border-l border-gray-200 pt-4 lg:pt-0 lg:pl-6">
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-2">Rating Kamu</p>
                                @if($userRating)
                                <div class="flex text-yellow-400 justify-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $userRating->rating ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Terima kasih sudah memberi rating!</p>
                                @else
                                <div class="flex text-gray-300 justify-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Belum memberikan rating</p>
                                @endif
                            </div>
                        </div>
                        @endauth
                    </div>

                    <!-- Reviews List with Pagination -->
                    @if($reviews->count() > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($reviews as $review)
                        <div class="flex gap-4 p-4 bg-gray-50 rounded-xl">
                            <div class="flex-shrink-0">
                                @if($review->user && $review->user->profile_photo)
                                <img src="{{ $review->user->profile_photo }}" alt="{{ $review->user->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</span>
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->review_text)
                                <p class="text-sm text-gray-600">{{ $review->review_text }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                    <div class="flex justify-center gap-2">
                        @if($reviews->onFirstPage())
                        <span class="px-3 py-1.5 rounded-full text-sm text-gray-400 bg-gray-100 cursor-not-allowed">&laquo; Prev</span>
                        @else
                        <a href="{{ $reviews->previousPageUrl() }}" class="px-3 py-1.5 rounded-full text-sm text-gray-600 bg-gray-100 hover:bg-gray-200">&laquo; Prev</a>
                        @endif

                        @foreach($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                            @if($page == $reviews->currentPage())
                            <span class="px-3 py-1.5 rounded-full text-sm text-white bg-red-600">{{ $page }}</span>
                            @else
                            <a href="{{ $url }}" class="px-3 py-1.5 rounded-full text-sm text-gray-600 bg-gray-100 hover:bg-gray-200">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($reviews->hasMorePages())
                        <a href="{{ $reviews->nextPageUrl() }}" class="px-3 py-1.5 rounded-full text-sm text-gray-600 bg-gray-100 hover:bg-gray-200">Next &raquo;</a>
                        @else
                        <span class="px-3 py-1.5 rounded-full text-sm text-gray-400 bg-gray-100 cursor-not-allowed">Next &raquo;</span>
                        @endif
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Belum ada review untuk kursus ini.</p>
                        <p class="text-sm mt-1">Jadilah yang pertama memberikan review!</p>
                    </div>
                    @endif

                    <!-- Rate This Course -->
                    @auth
                        @if($isEnrolled)
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h3 class="font-bold text-gray-900 mb-4">Berikan Rating</h3>
                        <div id="ratingForm">
                            <div class="flex items-center gap-2 mb-4">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">★</button>
                                @endfor
                            </div>
                            <textarea id="reviewText" class="w-full border border-gray-200 rounded-xl p-3 text-sm" rows="3" placeholder="Tulis review kamu (opsional)..."></textarea>
                            <button onclick="submitRating({{ $c['id'] }}, 'course')" class="mt-3 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-full text-sm font-bold transition-colors">
                                {{ $userRating ? 'Update Rating' : 'Submit Rating' }}
                            </button>
                        </div>
                    </div>
                        @else
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
                            <p class="text-sm text-amber-800 mb-3">Kamu harus terdaftar di kursus ini untuk memberikan rating.</p>
                            <a href="{{ route('pembayaran', ['id' => $c['id']]) }}" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-semibold transition-colors">
                                Daftar Sekarang
                            </a>
                        </div>
                    </div>
                        @endif
                    @else
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <p class="text-gray-500 text-sm"><a href="{{ route('login') }}" class="text-red-600 hover:underline">Login</a> untuk memberikan rating.</p>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Resources Tab -->
            <div x-show="activeTab === 'resources'" x-cloak>
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-[0_4px_20px_rgb(0,0,0,0.03)]">
                    <h2 class="text-[22px] font-bold text-gray-900 mb-6 tracking-tight">Resources</h2>

                    @if(auth()->check() && $isEnrolled)
                        @if(count($resources) > 0)
                        <div class="space-y-4">
                            @foreach($resources as $r)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 00-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $r->title }}</p>
                                        <p class="text-xs text-gray-500">{{ strtoupper($r->type ?? 'FILE') }}</p>
                                    </div>
                                </div>
                                <a href="{{ $r->url }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                                    Download
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada resource.</p>
                        </div>
                        @endif
                    @elseif(auth()->check())
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-gray-500 mb-4">Silakan daftar di kursus ini untuk melihat resources.</p>
                            <a href="{{ route('pembayaran', ['id' => $c['id']]) }}" class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-full text-sm font-semibold transition-colors">
                                Daftar Sekarang
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Silakan <a href="{{ route('login') }}" class="text-red-600 hover:underline">login</a> untuk melihat resources.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column (Sticky Sidebar) - Only show if not enrolled -->
        @if(!$isEnrolled)
        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-100 rounded-3xl p-7 shadow-[0_8px_30px_rgb(0,0,0,0.05)] lg:sticky lg:top-24">

                <div class="mb-6">
                    <div class="text-4xl font-extrabold text-gray-900 mb-1 tracking-tight">
                        @if(isset($c['price']) && is_numeric($c['price']))
                            Rp {{ number_format((float) $c['price']) }}
                        @elseif(isset($c['price']) && $c['price'])
                            {{ $c['price'] }}
                        @else
                            Gratis
                        @endif
                    </div>
                    @if(($c['price'] ?? 0) > 0)
                    <div class="text-[15px] font-semibold text-gray-400 line-through">Rp 999.000</div>
                    @endif
                </div>

                <a href="{{ route('pembayaran', ['id' => $c['id']]) }}" class="w-full inline-flex items-center justify-center py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-full transition-colors mb-3">
                    Daftar Sekarang
                </a>
                <button class="w-full py-3.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-full transition-colors mb-8 shadow-sm">
                    Coba Gratis 7 Hari
                </button>

                <!-- Benefits List -->
                <div class="space-y-4 mb-8">
                    @foreach($benefits as $benefit)
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-[14px] font-medium text-gray-600">{{ $benefit }}</span>
                    </div>
                    @endforeach
                </div>

                <!-- Rating in Sidebar -->
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Rating</span>
                        <div class="flex items-center gap-1">
                            <span class="font-bold text-gray-900">{{ number_format((float) ($c['rating'] ?? 0), 1) }}</span>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3 h-3 {{ $i <= round($c['rating'] ?? 0) ? '' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">{{ number_format((int) ($c['students'] ?? 0)) }} siswa</p>
                </div>
            </div>

            <div class="bg-[#FFFDF3] border border-[#FDF0CD] rounded-xl p-4 flex gap-3 shadow-sm mt-6 lg:sticky lg:top-[500px]">
                <span class="text-[18px]">🎉</span>
                <div>
                    <div class="text-[13px] font-bold text-orange-800 mb-0.5">Promo berlaku 2 hari lagi!</div>
                    <div class="text-[12px] font-semibold text-orange-600">Hemat 40% dari harga normal</div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
let selectedRating = {{ $userRating ? $userRating->rating : 0 }};

// Client-side tracking state
var completedVideos = new Set();
@foreach($chapters as $chapterIndex => $chapter)
    @foreach($chapter['videos'] ?? [] as $video)
        @if($video['is_completed'] ?? false)
completedVideos.add({{ $video['id'] }});
        @endif
    @endforeach
@endforeach

// Video click tracking - immediate client-side + background server sync
console.log('Initializing video tracking...');
console.log('Completed videos from server:', Array.from(completedVideos));

document.querySelectorAll('.video-item').forEach(function(item) {
    item.addEventListener('click', function(e) {
        e.preventDefault();

        var container = this.closest('[data-video-id]');
        if (!container) {
            console.error('Container not found');
            return;
        }

        var videoId = parseInt(container.dataset.videoId);
        var chapterId = parseInt(container.dataset.chapterId);
        var courseId = parseInt(container.dataset.courseId);
        var videoUrl = container.dataset.videoUrl;

        console.log('Video clicked:', { videoId, chapterId, courseId, videoUrl });

        // Check if already completed (from client-side tracking)
        if (completedVideos.has(videoId)) {
            console.log('Already tracked, opening video');
            if (videoUrl) {
                window.open(videoUrl, '_blank');
            }
            return;
        }

        // === CLIENT-SIDE: Immediately mark as completed ===
        completedVideos.add(videoId);
        updateVideoUI(container);

        // Update chapter counter and check chapter completion
        updateChapterProgress(container, function(chapterComplete) {
            if (chapterComplete) {
                updateChapterUI(container, true);
            }
        });

        // === SERVER-SIDE: Track progress in background ===
        var csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        console.log('CSRF Token exists:', !!csrfToken);

        var url = '/api/progress/chapter/' + chapterId;
        console.log('POST to:', url);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                video_id: videoId,
                course_id: courseId,
                progress_seconds: 0
            })
        })
        .then(function(response) {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            console.log('Server response:', data);
            if (data.course_completed) {
                alert('🎉 Selamat! Kamu telah menyelesaikan kursus ini!');
                location.reload();
            }
        })
        .catch(function(error) {
            console.error('Server tracking failed:', error);
        });

        // Open the video
        if (videoUrl) {
            window.open(videoUrl, '_blank');
        }
    });
});

// Update single video UI immediately
function updateVideoUI(container) {
    // Mark container
    container.classList.add('video-completed');

    // Update thumbnail
    var thumbnail = container.querySelector('.w-24.h-14');
    if (thumbnail) {
        thumbnail.classList.remove('bg-gray-200');
        thumbnail.classList.add('bg-emerald-100');
        var iconContainer = thumbnail.querySelector('.absolute');
        if (iconContainer) {
            iconContainer.innerHTML = '<div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>';
        }
    }

    // Update title (strikethrough)
    var title = container.querySelector('.font-medium.text-gray-900');
    if (title) {
        title.classList.add('line-through', 'text-gray-500');
        title.classList.remove('group-hover:text-red-600');
    }

    // Update subtitle
    var subtitle = container.querySelector('.text-xs.text-gray-500');
    if (subtitle) {
        subtitle.innerHTML = '<span class="text-emerald-600">Selesai ditonton</span>';
    }

    // Update play icon to checkmark
    var playIcon = container.querySelector('.text-red-500.flex-shrink-0');
    if (playIcon) {
        playIcon.classList.remove('text-red-500');
        playIcon.classList.add('text-emerald-500');
        playIcon.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>';
    }

    // Make non-clickable
    var videoItem = container.querySelector('.video-item');
    if (videoItem) {
        videoItem.classList.remove('cursor-pointer');
        videoItem.classList.add('cursor-default');
    }
}

// Update chapter progress counter and check completion
function updateChapterProgress(videoContainer, callback) {
    var chapter = videoContainer.closest('.border.border-gray-100.rounded-xl');
    if (!chapter) return;

    // Update counter
    var counter = chapter.querySelector('.video-counter');
    if (counter) {
        var total = parseInt(counter.dataset.total);
        var current = parseInt(counter.textContent.split('/')[0]) || 0;
        counter.textContent = (current + 1) + '/' + total;
    }

    // Check if chapter is complete
    var videoItems = chapter.querySelectorAll('.video-item');
    var total = videoItems.length;
    var completed = chapter.querySelectorAll('.video-completed').length;

    if (completed >= total && callback) {
        callback(true);
    }
}

// Update chapter UI when all videos are completed
function updateChapterUI(videoContainer, isCompleted) {
    var chapter = videoContainer.closest('.border.border-gray-100.rounded-xl');
    if (!chapter || !isCompleted) return;

    // Update chapter badge
    var badge = chapter.querySelector('.w-8.h-8.rounded-full');
    if (badge) {
        badge.classList.remove('bg-red-100', 'text-red-600');
        badge.classList.add('bg-emerald-500', 'text-white');
        badge.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    }

    // Add Selesai badge in header
    var headerRight = chapter.querySelector('.flex.items-center.gap-4.text-sm.text-gray-500');
    if (headerRight && !headerRight.querySelector('.chapter-selesai')) {
        var selesaiBadge = document.createElement('span');
        selesaiBadge.className = 'chapter-selesai text-emerald-600 font-medium flex items-center gap-1';
        selesaiBadge.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Selesai';
        headerRight.insertBefore(selesaiBadge, headerRight.firstChild);
    }
}

// Pre-select stars based on existing rating
if (selectedRating > 0) {
    document.querySelectorAll('.star-btn').forEach((btn, index) => {
        if (index < selectedRating) {
            btn.classList.remove('text-gray-300');
            btn.classList.add('text-yellow-400');
        }
    });
}

function setRating(rating) {
    selectedRating = rating;
    document.querySelectorAll('.star-btn').forEach((btn, index) => {
        btn.classList.toggle('text-yellow-400', index < rating);
        btn.classList.toggle('text-gray-300', index >= rating);
    });
}

function submitRating(itemId, type) {
    if (selectedRating === 0) {
        alert('Pilih rating terlebih dahulu');
        return;
    }

    const reviewText = document.getElementById('reviewText')?.value || '';
    const endpoint = type === 'course' ? '/ratings/course' : '/ratings/bootcamp';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!csrfToken) {
        alert('Session expired. Silakan refresh halaman dan login ulang.');
        return;
    }

    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            [type + '_id']: itemId,
            rating: selectedRating,
            review: reviewText
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Rating berhasil submitted!');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(err => {
        console.error('Rating error:', err);
        alert('Terjadi kesalahan. Pastikan kamu sudah login.');
    });
}
</script>
@endpush
