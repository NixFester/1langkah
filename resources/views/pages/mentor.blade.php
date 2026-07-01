@extends('layouts.app', ['activePage' => 'mentor'])

@section('title', 'Mentor Marketplace — 1Langkah')
@section('header_title', 'Mentor')

@section('content')
<div class="w-full px-2 pb-8">
    <!-- Header -->
    <div class="mb-10 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Mentor Marketplace</h1>
        <p class="text-gray-500 text-base">500+ mentor dari perusahaan top Indonesia & global</p>
    </div>

    <!-- Mentor Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($mentors as $m)
            <x-mentor-card :mentor="$m" />
        @endforeach
    </div>
</div>
@endsection
