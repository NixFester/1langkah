@extends('errors.layout')

@section('code', '429')
@section('title', 'Terlalu Banyak Permintaan')

@section('message', 'Maaf, Anda telah melakukan terlalu banyak permintaan ke server dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba lagi.')

@section('icon')
<svg class="w-8 h-8 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
</svg>
@endsection
