@extends('errors.layout')

@section('code', '405')
@section('title', 'Metode Tidak Diizinkan')

@section('message', 'Maaf, metode HTTP (seperti GET atau POST) yang Anda gunakan tidak didukung untuk halaman atau aksi ini.')

@section('icon')
<svg class="w-8 h-8 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
</svg>
@endsection
