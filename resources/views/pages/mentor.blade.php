@extends('layouts.app', ['activePage' => 'mentor'])

@section('title', 'Mentor Marketplace — 1Langkah')

@section('content')
<div style="margin-bottom:24px">
    <div class="page-title">Mentor Marketplace</div>
    <p style="font-size:14px;color:var(--text-muted);margin-top:4px">500+ mentor berpengalaman siap membimbing kamu</p>
</div>

<div class="chips" style="margin-bottom:24px" x-data="{ active: 'Semua' }">
    @foreach($categories as $c)
        <span class="chip" :class="{ 'active': active === '{{ $c }}' }" @click="active = '{{ $c }}'">{{ $c }}</span>
    @endforeach
</div>

<div class="grid-3">
    @foreach($mentors as $m)
        <x-mentor-card :mentor="$m" />
    @endforeach
</div>

@push('scripts')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection
