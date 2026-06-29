@extends('layouts.app', ['activePage' => 'kursus'])

@section('title', 'Kursus — 1Langkah')
@section('header_title', 'Kursus')

@section('content')
<div class="px-2">
    <!-- Header -->
    <div class="flex items-start justify-between mb-8 -mt-2">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Kursus</h1>
            <p class="text-gray-500 text-base">800+ kursus praktis dari instruktur terbaik</p>
        </div>
        <div class="flex items-center gap-2 mt-1">
            <button class="w-10 h-10 flex items-center justify-center rounded-full bg-red-50 text-red-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </button>
            <button class="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
            </button>
        </div>
    </div>

    <!-- Categories / Filters -->
    <div class="flex items-center gap-3 mb-8 overflow-x-auto pb-2 scrollbar-hide" x-data="{ activeCat: 'All' }">
        <button @click="activeCat = 'All'" 
                :class="activeCat === 'All' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors">
            All
        </button>
        
        @foreach(['Programming', 'Design', 'AI', 'Marketing', 'Data', 'Leadership', 'Business'] as $c)
        <button @click="activeCat = '{{ $c }}'" 
                :class="activeCat === '{{ $c }}' ? 'bg-red-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'"
                class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors">
            {{ $c }}
        </button>
        @endforeach

        <button class="px-5 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            Filter
        </button>
    </div>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($courses as $c)
            <x-course-card :course="$c" />
        @endforeach
    </div>
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
