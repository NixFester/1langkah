@extends('layouts.guest')
@section('title')
    @yield('code') - @yield('title')
@endsection

@section('body')
<div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Optional background glow for dark mode -->
    <div class="hidden dark:block absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[#F61500] opacity-[0.03] rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="w-full max-w-md bg-white dark:bg-[#161615] shadow-[0px_4px_24px_rgba(0,0,0,0.02)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-xl p-8 md:p-12 text-center border border-[#e3e3e0] dark:border-[#3E3E3A] relative z-10">
        
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#fff2f2] dark:bg-[#1D0002] mb-6 border border-[#F53003]/20 dark:border-[#F61500]/20">
            @yield('icon', '<svg class="w-8 h-8 text-[#F53003] dark:text-[#F61500]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>')
        </div>
        
        <h1 class="text-7xl font-black text-[#1b1b18] dark:text-[#fdfdfc] mb-2 tracking-tighter">@yield('code')</h1>
        <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-[#fdfdfc] mb-4">@yield('title')</h2>
        
        <p class="text-[#706f6c] dark:text-[#a1a09a] text-sm mb-8 leading-relaxed">
            @yield('message')
        </p>
        
        <a href="{{ url('/') }}" class="inline-block w-full dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-[#161615] hover:border-[#161615] px-5 py-2.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm font-medium leading-normal transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
