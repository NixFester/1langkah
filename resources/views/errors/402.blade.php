@extends('errors.layout')

@section('code', '402')
@section('title', 'Pembayaran Diperlukan')

@section('message', 'Maaf, akses ke halaman ini memerlukan pembayaran. Silakan selesaikan proses tagihan Anda untuk melanjutkan.')

@section('icon')
<svg class="w-8 h-8 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
</svg>
@endsection
