@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi Berakhir')

@section('message', 'Maaf, sesi Anda telah berakhir karena terlalu lama tidak ada aktivitas. Silakan segarkan (refresh) halaman ini dan coba lagi.')

@section('icon')
<svg class="w-8 h-8 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection
