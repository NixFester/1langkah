@extends('errors.layout')

@section('code', '400')
@section('title', 'Permintaan Tidak Valid')

@section('message', 'Maaf, server tidak dapat memproses permintaan Anda karena sintaks yang tidak valid (Bad Request).')

@section('icon')
<svg class="w-8 h-8 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection
