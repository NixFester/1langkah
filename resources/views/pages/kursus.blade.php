@extends('layouts.app', ['activePage' => 'kursus'])

@section('title', 'Kursus — 1Langkah')

@section('content')
<div class="page-title" style="margin-bottom:24px">Kursus</div>

<div class="chips" style="margin-bottom:16px" x-data="{ active: 'Semua' }">
    @foreach($categories as $i => $c)
        <span class="chip" :class="{ 'active': active === '{{ $c }}' }" @click="active = '{{ $c }}'">{{ $c }}</span>
    @endforeach
</div>

<div class="chips" style="margin-bottom:24px" x-data="{ active: 'Semua Level' }">
    @foreach($levels as $i => $l)
        <span class="chip" :class="{ 'active': active === '{{ $l }}' }" @click="active = '{{ $l }}'">{{ $l }}</span>
    @endforeach
</div>

<div class="grid-3">
    @foreach($courses as $c)
        <x-course-card :course="$c" />
    @endforeach
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
