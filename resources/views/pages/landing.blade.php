@extends('layouts.guest')

@section('title', '1Langkah — AI-Powered Learning Experience Platform')

@section('body')
<!-- Navbar -->
<nav class="fixed top-0 left-0 right-0 z-50 px-6 lg:px-12 py-3 md:py-4 bg-[#0a0a0a]/80 backdrop-blur-md border-b border-white/5 flex items-center justify-between">
    <!-- Logo -->
    <a href="{{ route('landing') }}" class="flex items-center" aria-label="Beranda 1Langkah">
        <svg aria-hidden="true" width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clip-path="url(#clip0_306_8219)">
                <path d="M22.3789 27.1026H16.3237V7.52808H22.3789V27.1026Z" fill="#D10000"/>
                <g filter="url(#filter0_d_306_8219)">
                    <path d="M22.3746 7.57027C22.374 7.57183 22.3735 7.57359 22.373 7.57546C22.3719 7.57922 22.3705 7.58367 22.369 7.58868C22.366 7.59876 22.3622 7.61144 22.3576 7.62646C22.3484 7.65648 22.3362 7.69629 22.3206 7.7453C22.2894 7.84337 22.2451 7.9786 22.1872 8.14667C22.0716 8.48256 21.9009 8.95166 21.6697 9.51938C21.2088 10.651 20.4993 12.1959 19.4946 13.8706C17.5141 17.1718 14.2201 21.2532 9.16017 23.4218L6.7749 17.8562C10.1921 16.3917 12.6499 13.5095 14.302 10.7556C15.1136 9.40283 15.6905 8.14735 16.0619 7.23532C16.2468 6.78124 16.3787 6.41755 16.4618 6.17631C16.5032 6.05587 16.5322 5.96636 16.5496 5.91188C16.5582 5.8847 16.5639 5.86625 16.5667 5.8571L16.5681 5.85254C16.568 5.85317 16.5678 5.85399 16.5675 5.8549C16.5674 5.85534 16.5668 5.8568 16.5667 5.8571C16.5665 5.85753 16.5667 5.85723 16.9297 5.96429L22.0125 7.45993C22.3708 7.56565 22.3753 7.56754 22.3752 7.56806C22.375 7.56844 22.3748 7.56949 22.3746 7.57027Z" fill="#E50000"/>
                </g>
                <path d="M29.6583 7.91772V23.195H36.7659V26.9803H25.8457V7.91772H29.6583Z" fill="white"/>
                <path d="M48.8093 15.2705H51.4236V27.0076H48.5371V25.8638C47.4477 26.7898 46.0317 27.3343 44.5067 27.3343C41.0482 27.3343 38.2705 24.5567 38.2705 21.1254C38.2705 17.6669 41.0482 14.9165 44.5067 14.9165C46.0317 14.9165 47.4477 15.4611 48.5371 16.387L48.8093 15.2705ZM47.0937 23.3857C47.6929 22.7594 47.9924 21.9696 47.9924 21.1254C47.9924 20.2812 47.6929 19.4643 47.0937 18.8652C46.5219 18.266 45.7593 17.9392 44.9424 17.9392C44.1255 17.9392 43.3629 18.266 42.7638 18.8652C42.1919 19.4643 41.8651 20.2812 41.8651 21.1254C41.8651 21.9696 42.1919 22.7594 42.7638 23.3857C43.3629 23.9848 44.1255 24.3116 44.9424 24.3116C45.7593 24.3116 46.5219 23.9848 47.0937 23.3857Z" fill="white"/>
                <path d="M55.2833 15.2704L55.828 16.3052C56.7539 15.4883 57.9793 14.9436 59.1775 14.9436C62.3365 14.9436 64.9235 17.7213 64.9235 21.1798V27.0075H61.6012V21.1798C61.6012 19.4914 60.4029 17.9936 58.7691 17.9936C57.1351 17.9936 55.9369 19.4914 55.9369 21.1798V27.0075H52.5056V15.2704H55.2833Z" fill="white"/>
                <path d="M78.5433 16.4688L76.5554 17.1223C77.3996 18.021 77.917 19.2192 77.917 20.5263C77.917 23.4674 75.33 25.8366 72.1165 25.8366C71.6808 25.8366 71.2723 25.7821 70.8639 25.7005C70.6188 26.1362 70.3737 26.6263 70.1831 27.1166C70.8095 27.0348 71.4902 26.9804 72.2527 26.9804C74.4313 26.9804 76.038 27.3344 77.1817 28.0425C78.38 28.805 79.0336 29.976 79.0336 31.3376C79.0336 32.7536 78.4344 33.8702 77.2635 34.6326C76.1469 35.3407 74.513 35.6947 72.2527 35.6947C69.9924 35.6947 68.3585 35.3407 67.242 34.6326C66.071 33.8702 65.4719 32.7536 65.4719 31.3376C65.4719 30.7929 65.5808 30.2755 65.7987 29.7853C66.3433 27.9608 67.596 25.9456 68.4947 24.6657C67.2148 23.6853 66.3706 22.1875 66.3706 20.5263C66.3706 17.5853 68.9576 15.216 72.1165 15.216C72.3889 15.216 72.6612 15.2433 72.9335 15.2705L78.5433 14.2085V16.4688ZM72.1165 18.2661C70.7277 18.2661 69.6657 19.3553 69.6657 20.5263C69.6657 21.8607 70.8911 22.7866 72.1165 22.7866C73.5327 22.7866 74.5947 21.6973 74.5947 20.5263C74.5947 19.2464 73.4781 18.2661 72.1165 18.2661ZM72.2527 32.5903C74.5947 32.5903 75.6839 32.2089 75.6839 31.3376C75.6839 30.5205 74.2952 30.0849 72.2527 30.0849C70.1013 30.0849 68.8214 30.6023 68.8214 31.3376C68.8214 32.2089 70.0742 32.5903 72.2527 32.5903Z" fill="white"/>
                <path d="M92.57 27.0076H88.104L84.1827 22.242L83.3929 23.0589V27.0076H79.5803V6.74683H83.3929V19.0558L86.8786 15.2705H91.535L86.7697 20.1724L92.57 27.0076Z" fill="white"/>
                <path d="M102.028 15.2705H104.643V27.0076H101.756V25.8638C100.667 26.7898 99.2509 27.3343 97.7254 27.3343C94.2667 27.3343 91.4893 24.5567 91.4893 21.1254C91.4893 17.6669 94.2667 14.9165 97.7254 14.9165C99.2509 14.9165 100.667 15.4611 101.756 16.387L102.028 15.2705ZM100.313 23.3857C100.911 22.7594 101.211 21.9696 101.211 21.1254C101.211 20.2812 100.911 19.4643 100.313 18.8652C99.7405 18.266 98.9782 17.9392 98.161 17.9392C97.3447 17.9392 96.5815 18.266 95.983 18.8652C95.4106 19.4643 95.0839 20.2812 95.0839 21.1254C95.0839 21.9696 95.4106 22.7594 95.983 23.3857C96.5815 23.9848 97.3447 24.3116 98.161 24.3116C98.9782 24.3116 99.7405 23.9848 100.313 23.3857Z" fill="white"/>
                <path d="M109.346 6.74683V16.0058C110.354 15.325 111.552 14.9165 112.86 14.9165C116.291 14.9165 119.068 17.7214 119.068 21.1527V27.0076H115.473V21.1527C115.473 20.3085 115.147 19.5188 114.575 18.8924C114.003 18.2933 113.241 17.9666 112.423 17.9666C111.607 17.9666 110.817 18.2933 110.245 18.8924C109.673 19.5188 109.346 20.3085 109.346 21.1527V27.0076H105.725V6.74683H109.346Z" fill="white"/>
            </g>
            <defs>
                <filter id="filter0_d_306_8219" x="0.599103" y="-0.00106061" width="28.5963" height="30.5653" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="0.3222" dy="0.6444"/>
                    <feGaussianBlur stdDeviation="3.249"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_306_8219"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_306_8219" result="shape"/>
                </filter>
                <clipPath id="clip0_306_8219">
                    <rect width="120" height="36" fill="white"/>
                </clipPath>
            </defs>
        </svg>
    </a>
    
    <!-- Links -->
    <div class="hidden md:flex items-center gap-4 xl:gap-8 text-[14px] xl:text-[15px] font-medium text-gray-300">
        <a href="{{ route('kursus') }}" class="hover:text-white transition-colors">{{ __('app.nav_courses') }}</a>
        <a href="{{ route('online-bootcamp') }}" class="hover:text-white transition-colors">{{ __('app.nav_bootcamp') }}</a>
        <a href="{{ route('mentor') }}" class="hover:text-white transition-colors">{{ __('app.nav_mentor') }}</a>
        <a href="{{ url('/') }}" class="hover:text-white transition-colors">{{ __('app.nav_enterprise') }}</a>
        <a href="{{ route('about') }}" class="hover:text-white transition-colors">{{ __('app.nav_about') }}</a>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
        <!-- Language Switcher -->
        <a href="{{ route('lang.switch', app()->getLocale() == 'id' ? 'en' : 'id') }}" class="flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white/5 border border-white/10 hover:bg-white/10 text-[11px] sm:text-[12px] font-bold text-white transition-colors" title="Switch Language" aria-label="{{ strtoupper(app()->getLocale()) == 'EN' ? 'EN' : 'ID' }} - Switch Language">
            {{ strtoupper(app()->getLocale()) == 'EN' ? 'EN' : 'ID' }}
        </a>
        
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 lg:px-6 py-2 sm:py-2.5 text-[13px] lg:text-[14px] font-medium text-[#d1d5db] border border-white/10 rounded-full hover:bg-white/5 transition-colors">
            {{ __('app.login') }}
        </a>
        <a href="{{ route('signup') }}" class="inline-flex items-center justify-center px-4 lg:px-6 py-2 sm:py-2.5 text-[13px] lg:text-[14px] font-semibold text-white bg-gradient-to-b from-[#D10000] to-[#8B0000] rounded-full shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_0_20px_rgba(209,0,0,0.4)] whitespace-nowrap hover:from-[#b30000] hover:to-[#6b0000] hover:shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_0_25px_rgba(209,0,0,0.6)] transition-all">
            <span class="lg:hidden">{{ __('app.register') }}</span>
            <span class="hidden lg:inline">{{ __('app.register_free') }}</span>
        </a>
    </div>
</nav>

<!-- Hero & Partners Full Screen Wrapper -->
<main id="main-content">
<div class="min-h-screen w-full flex flex-col overflow-x-hidden bg-[#050304]" style="min-height: 100svh; min-height: 100dvh;">
    
    <!-- Hero -->
    <section class="relative flex-1 w-full flex flex-col justify-center px-6 md:px-12 pb-4 lg:pb-6 z-10" style="padding-top: 70px;">
    
    <!-- Red gradient glow in top left corner -->
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-[#FF0000]/20 rounded-full blur-[120px] pointer-events-none z-0"></div>
    
    <div class="max-w-[1400px] mx-auto w-full relative z-10 flex flex-col flex-1 justify-center min-h-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-4 xl:gap-8 items-center">
            
            <!-- Left side: Text Content -->
            <div class="relative z-10 flex flex-col items-center lg:items-start text-center lg:text-left justify-center">
                
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 lg:py-1.5 rounded-full border border-[#D10000]/30 bg-[#D10000]/10 mb-2.5 lg:mb-4">
                    <svg aria-hidden="true" class="w-3 h-3 lg:w-3.5 lg:h-3.5 text-[#FF7070]" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_306_7921)"><path d="M4.96847 7.74988C4.92383 7.57684 4.83364 7.41893 4.70728 7.29257C4.58092 7.16621 4.42301 7.07602 4.24997 7.03138L1.18247 6.24038C1.13014 6.22553 1.08407 6.19401 1.05128 6.1506C1.01848 6.1072 1.00073 6.05428 1.00073 5.99988C1.00073 5.94548 1.01848 5.89256 1.05128 5.84916C1.08407 5.80575 1.13014 5.77423 1.18247 5.75938L4.24997 4.96788C4.42294 4.92328 4.58082 4.83317 4.70717 4.7069C4.83353 4.58063 4.92375 4.42282 4.96847 4.24988L5.75947 1.18238C5.77417 1.12984 5.80566 1.08355 5.84913 1.05058C5.8926 1.0176 5.94566 0.999756 6.00022 0.999756C6.05478 0.999756 6.10784 1.0176 6.15131 1.05058C6.19478 1.08355 6.22627 1.12984 6.24097 1.18238L7.03147 4.24988C7.07611 4.42292 7.1663 4.58083 7.29266 4.70719C7.41902 4.83355 7.57693 4.92374 7.74997 4.96838L10.8175 5.75888C10.8702 5.77343 10.9167 5.80488 10.9499 5.84842C10.983 5.89195 11.001 5.94516 11.001 5.99988C11.001 6.0546 10.983 6.10781 10.9499 6.15134C10.9167 6.19488 10.8702 6.22633 10.8175 6.24088L7.74997 7.03138C7.57693 7.07602 7.41902 7.16621 7.29266 7.29257C7.1663 7.41893 7.07611 7.57684 7.03147 7.74988L6.24047 10.8174C6.22577 10.8699 6.19428 10.9162 6.15081 10.9492C6.10734 10.9822 6.05428 11 5.99972 11C5.94516 11 5.8921 10.9822 5.84863 10.9492C5.80516 10.9162 5.77367 10.8699 5.75897 10.8174L4.96847 7.74988Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 1.5V3.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 2.5H9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 8.5V9.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.5 9H1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_306_7921"><rect width="12" height="12" fill="white"/></clipPath></defs></svg>
                    <span class="text-[9px] sm:text-[10px] lg:text-xs font-bold tracking-[0.15em] text-[#FF7070] uppercase">AI-POWERED LEARNING EXPERIENCE PLATFORM</span>
                </div>

                <!-- Title -->
                <h1 class="font-['Inter'] text-[34px] sm:text-4xl md:text-5xl lg:text-[50px] xl:text-[64px] font-black leading-[1] sm:leading-[1] tracking-[-0.06em] text-white mb-2 lg:mb-3">
                    {{ __('app.landing_title_1') }}<br>
                    <span class="text-transparent bg-clip-text inline-block" style="background-image: linear-gradient(98deg, #FF6B6B 0%, #D10000 35%, #FF4500 65%, #FFB347 100%);">
                        {{ __('app.landing_title_2') }}<br>
                        {{ __('app.landing_title_3') }}
                    </span><br>
                    {{ __('app.landing_title_4') }}
                </h1>

                <!-- Subtitle -->
                <p class="text-[12.5px] sm:text-[13.5px] lg:text-[15px] xl:text-[16px] text-[#9ca3af] mb-3 lg:mb-4 max-w-[500px] leading-relaxed">
                    {{ __('app.landing_desc') }}
                </p>

                <div class="flex flex-row flex-wrap justify-center lg:justify-start items-stretch sm:items-center gap-2 sm:gap-4 lg:gap-4 mb-3 lg:mb-4 w-full sm:w-auto">
                    <a href="{{ route('signup') }}" class="flex-1 sm:w-auto justify-center inline-flex items-center gap-1.5 px-2 sm:px-5 py-2.5 lg:px-7 lg:py-4 xl:px-8 xl:py-4 bg-gradient-to-b from-[#e60000] to-[#880000] text-white font-bold text-[12px] sm:text-[13.5px] lg:text-[14px] xl:text-[16px] rounded-[12px] lg:rounded-[18px] hover:from-[#ff0000] hover:to-[#990000] transition-all shadow-[0_4px_14px_rgba(209,0,0,0.4)]">
                        <span class="truncate">{{ __('app.start_learning') }}</span> 
                        <svg aria-hidden="true" class="w-3.5 h-3.5 lg:w-4 lg:h-4 stroke-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </a>
                    <a href="{{ route('kursus') }}" class="flex-1 sm:w-auto justify-center inline-flex items-center px-2 sm:px-5 py-2.5 lg:px-7 lg:py-4 xl:px-8 xl:py-4 border border-white/20 bg-transparent text-white font-bold text-[12px] sm:text-[13.5px] lg:text-[14px] xl:text-[16px] rounded-[12px] lg:rounded-[18px] hover:bg-white/5 transition-colors">
                        <span class="truncate">{{ __('app.explore_courses') }}</span>
                    </a>
                </div>

                <!-- Watch Demo -->
                <button class="group flex items-center justify-center lg:justify-start gap-3 text-[#d1d5db] hover:text-white transition-colors">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-white/5 group-hover:border-white/30 group-hover:bg-white/10 transition-all">
                        <svg aria-hidden="true" class="w-3.5 h-3.5 ml-1 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="font-medium text-[15px]">{{ __('app.watch_demo') }}</span>
                </button>


            </div>

            <!-- Right side: Image -->
            <div class="relative z-10 hidden lg:flex items-center justify-center w-full min-h-0">
                <div class="relative w-full max-w-[600px] xl:max-w-[700px] mx-auto origin-center lg:mt-2 xl:mt-4 lg:-mb-12 xl:-mb-16 rounded-3xl overflow-hidden">
                    <img loading="lazy" fetchpriority="high" decoding="async" src="{{ asset('images/landing-hero.png') }}" alt="1Langkah Platform" class="w-full h-auto object-contain mix-blend-lighten opacity-90 relative z-10">
                    
                    <!-- Overlays to blend edges into the #050304 background -->
                    <div class="absolute inset-y-0 left-0 w-1/3 bg-gradient-to-r from-[#050304] to-transparent z-20 pointer-events-none"></div>
                    <div class="absolute inset-x-0 bottom-0 h-1/4 bg-gradient-to-t from-[#050304] to-transparent z-20 pointer-events-none"></div>
                    <div class="absolute inset-x-0 top-0 h-1/6 bg-gradient-to-b from-[#050304] to-transparent z-20 pointer-events-none"></div>
                    <div class="absolute inset-y-0 right-0 w-1/6 bg-gradient-to-l from-[#050304] to-transparent z-20 pointer-events-none"></div>
                </div>
            </div>
        </div>

        <!-- Bottom Stats Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-4 xl:gap-8 items-center w-full relative z-20 pt-2 lg:pt-4">
            
            <!-- Stats (Left Column) -->
            <div class="flex items-start justify-between sm:justify-center lg:justify-start gap-2 sm:gap-6 lg:gap-8 xl:gap-14 w-full">
                <div class="text-center lg:text-left flex-1 sm:flex-none">
                    <div class="text-[15px] sm:text-[18px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0 lg:mb-1 tracking-tight">2000+</div>
                    <div class="text-[9px] sm:text-[10.5px] lg:text-[12px] xl:text-[13px] font-medium text-gray-400 leading-[1.2] mt-0.5 sm:mt-0">{{ __('app.stats_students') }}</div>
                </div>
                <div class="text-center lg:text-left flex-1 sm:flex-none">
                    <div class="text-[15px] sm:text-[18px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0 lg:mb-1 tracking-tight">10+</div>
                    <div class="text-[9px] sm:text-[10.5px] lg:text-[12px] xl:text-[13px] font-medium text-gray-400 leading-[1.2] mt-0.5 sm:mt-0">{{ __('app.stats_courses') }}</div>
                </div>
                <div class="text-center lg:text-left flex-1 sm:flex-none">
                    <div class="text-[15px] sm:text-[18px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0 lg:mb-1 tracking-tight">95%</div>
                    <div class="text-[9px] sm:text-[10.5px] lg:text-[12px] xl:text-[13px] font-medium text-gray-400 leading-[1.2] mt-0.5 sm:mt-0">{{ __('app.stats_completion') }}</div>
                </div>
            </div>

            <!-- Ratings (Right Column) - Removed as per design -->
        </div>
    </div>
    </section>
    
    <!-- Partners Section -->
    <div class="w-full bg-black/20 border-t border-white/5 py-8 mt-auto relative z-20 overflow-hidden">
        <style>
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .animate-marquee {
                display: flex;
                width: max-content;
                animation: marquee 15s linear infinite;
            }
            @media (min-width: 1230px) {
                .animate-marquee {
                    width: 100%;
                    animation: none;
                    flex-wrap: nowrap;
                    justify-content: space-between;
                }
                .duplicate-partners {
                    display: none !important;
                }
                .marquee-gap {
                    gap: 0 !important;
                }
            }
        </style>
        <div class="max-w-[1400px] mx-auto px-6 md:px-12">
            <div class="flex overflow-hidden">
                <div class="animate-marquee marquee-gap gap-10 md:gap-14 items-center w-full">
                    @php
                        $partners = ['BMP.svg', 'ICA.svg', 'INTENATIONAL CREATIVES EXCHANGE.svg', 'NCR.svg', 'negerikami.svg', 'pakindo.svg', 'tradeindonesia.svg', 'tuturbangsa.svg'];
                    @endphp
                    <!-- Original Set -->
                    @foreach($partners as $partner)
                        <img src="{{ asset('assets/partners/' . $partner) }}" alt="Partner" class="w-20 sm:w-24 md:w-28 lg:w-32 h-10 md:h-12 lg:h-16 object-contain grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                    @endforeach
                    
                    <!-- Duplicate Set for Mobile & Tablet Seamless Scrolling -->
                    @foreach($partners as $partner)
                        <img src="{{ asset('assets/partners/' . $partner) }}" alt="Partner" class="duplicate-partners w-20 sm:w-24 md:w-28 h-10 md:h-12 object-contain grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features (Section 2) -->
<section id="features" class="min-h-screen flex flex-col justify-center py-16 lg:py-24 bg-white relative w-full overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10 w-full">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-100 bg-red-50 mb-6">
                <svg aria-hidden="true" class="w-3.5 h-3.5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="text-[11px] font-bold tracking-[0.15em] text-[#D10000] uppercase">{{ __('app.complete_platform') }}</span>
            </div>
            
            <h2 class="font-['Inter'] text-[26px] sm:text-3xl md:text-[44px] font-black text-[#0f172a] tracking-tight md:tracking-[-0.05em] leading-[1.15] md:leading-[1.1] mb-4">
                {{ __('app.platform_title_1') }}<br>
                <span class="text-transparent bg-clip-text inline-block" style="background-image: linear-gradient(95deg, #D10000 0%, #FF4500 100%);">{{ __('app.platform_title_2') }}</span>
            </h2>
            
            <p class="text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('app.platform_desc') }}
            </p>
        </div>

        <!-- 3 Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-10 lg:mb-12">
            <!-- Card 1 -->
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform p-3">
                    <img loading="lazy" decoding="async" width="64" height="64" src="{{ asset('assets/icons/AI-Learning-Assistant.svg') }}" alt="AI Learning Assistant" class="w-full h-full object-contain">
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-3">AI Learning Assistant</h2>
                <p class="text-[15px] text-gray-600 leading-relaxed mb-8 flex-1">{{ __('app.feature_1_desc') }}</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#D10000] group-hover:gap-2 transition-all">
                    {{ __('app.learn_more') }} 
                    <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform p-3">
                    <img loading="lazy" decoding="async" width="64" height="64" src="{{ asset('assets/icons/Personalized-Learning-Path.svg') }}" alt="Personalized Learning Path" class="w-full h-full object-contain">
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-3">Personalized Learning Path</h2>
                <p class="text-[15px] text-gray-600 leading-relaxed mb-8 flex-1">{{ __('app.feature_2_desc') }}</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-700 group-hover:gap-2 transition-all">
                    {{ __('app.learn_more') }} 
                    <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform p-3">
                    <img loading="lazy" decoding="async" width="64" height="64" src="{{ asset('assets/icons/Verified-Skill-Passport.svg') }}" alt="Verified Skill Passport" class="w-full h-full object-contain">
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-3">Verified Skill Passport</h2>
                <p class="text-[15px] text-gray-600 leading-relaxed mb-8 flex-1">{{ __('app.feature_3_desc') }}</p>
                <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-purple-700 group-hover:gap-2 transition-all">
                    {{ __('app.learn_more') }} 
                    <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- 8 Small Feature Pills Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Pill 1 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Interactive-Courses.svg') }}" alt="Interactive Courses" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_interactive_courses') }} </span>
            </div>
            <!-- Pill 2 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Certificates.svg') }}" alt="Certificates" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_certificates') }} </span>
            </div>
            <!-- Pill 3 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Portfolio-Builder.svg') }}" alt="Portfolio Builder" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_portfolio_builder') }} </span>
            </div>
            <!-- Pill 4 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Project-Marketplace.svg') }}" alt="Project Marketplace" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_project_marketplace') }} </span>
            </div>
            <!-- Pill 5 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Mentor-Marketplace.svg') }}" alt="Mentor Marketplace" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_mentor_marketplace') }} </span>
            </div>
            <!-- Pill 6 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Career-Center.svg') }}" alt="Career Center" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_career_center') }} </span>
            </div>
            <!-- Pill 7 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Community.svg') }}" alt="Community" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_community') }} </span>
            </div>
            <!-- Pill 8 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 p-2">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Learning-Analytics.svg') }}" alt="Learning Analytics" class="w-full h-full object-contain">
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800"> {{ __('app.feat_learning_analytics') }} </span>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses (Section 3) -->
<section id="popular-courses" class="py-16 md:py-24 bg-slate-50 relative w-full overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100/50 border border-emerald-200 mb-4">
                    <svg aria-hidden="true" class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-[11px] font-bold tracking-[0.15em] text-emerald-700 uppercase">{{ __('app.popular_courses_badge') }}</span>
                </div>
                <h2 class="text-[26px] sm:text-3xl md:text-[42px] font-extrabold text-[#0f172a] tracking-tight leading-tight">{{ __('app.start_learning_now') }}</h2>
            </div>
            <a href="{{ route('kursus') }}" class="inline-flex items-center gap-1.5 text-[15px] font-bold text-[#D10000] hover:text-[#b30000] transition-colors md:pb-2">
                {{ __('app.see_all_courses') }} 
                <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- Card 1 -->
            <div class="bg-white rounded-[20px] border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all group flex flex-col cursor-pointer">
                <!-- Image Wrapper -->
                <div class="relative h-[140px] md:h-[210px] w-full overflow-hidden bg-gray-100">
                    <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=600&auto=format&fit=crop&fm=webp" alt="Coding" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Top Left Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-white text-orange-700 text-[11px] font-bold rounded-full shadow-sm"> {{ __('app.course_bestseller') }} </span>
                        <span class="px-3 py-1 bg-white text-slate-600 text-[11px] font-bold rounded-full shadow-sm"> {{ __('app.course_intermediate') }} </span>
                    </div>
                    <!-- Bookmark -->
                    <button aria-label="{{ __('app.save') }}" class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-700 shadow-sm hover:text-orange-800 transition-colors">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/bookmark.svg') }}" class="w-4 h-4" alt="" aria-hidden="true">
                    </button>
                    <!-- Progress Bar (inside image bottom) -->
                    <div class="absolute bottom-0 left-0 h-2 bg-emerald-500 z-10" style="width: 68%;"></div>
                </div>
                <!-- Card Body -->
                <div class="p-3.5 md:p-6 flex flex-col flex-1">
                    <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 md:py-1 rounded-md text-[10px] md:text-[11px] font-bold text-red-700 bg-red-50 w-fit mb-3 md:mb-4"> {{ __('app.course_programming') }} </span>
                    <h2 class="text-[14.5px] md:text-[19px] font-bold text-slate-900 leading-snug mb-1 group-hover:text-[#D10000] transition-colors line-clamp-2"><a href="{{ route('kursus') }}">Full-Stack Web Development Bootcamp</a></h2>
                    <p class="text-[11.5px] md:text-[13px] text-slate-600 mb-3 md:mb-4">Rudi Yesaya · Google</p>
                    
                    <div class="flex items-center gap-2 mb-4 md:mb-6 mt-auto">
                        <div class="flex text-amber-400">
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">4.9</span>
                        <span class="text-[13px] text-slate-500">(12,840)</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="pt-4 md:pt-5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-600 text-[13px] font-medium">
                            <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            48h
                        </div>
                        <div class="text-[13px] font-bold text-emerald-700">{{ __('app.progress_done', ['percent' => 68]) }}</div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-[20px] border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all group flex flex-col cursor-pointer">
                <!-- Image Wrapper -->
                <div class="relative h-[140px] md:h-[210px] w-full overflow-hidden bg-gray-100">
                    <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=600&auto=format&fit=crop&fm=webp" alt="Design" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Top Left Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-white text-red-700 text-[11px] font-bold rounded-full shadow-sm"> {{ __('app.course_new') }} </span>
                        <span class="px-3 py-1 bg-white text-slate-600 text-[11px] font-bold rounded-full shadow-sm"> {{ __('app.course_beginner') }} </span>
                    </div>
                    <!-- Bookmark -->
                    <button aria-label="{{ __('app.save') }}" class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-700 shadow-sm hover:text-orange-800 transition-colors">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/bookmark.svg') }}" class="w-4 h-4" alt="" aria-hidden="true">
                    </button>
                </div>
                <!-- Card Body -->
                <div class="p-3.5 md:p-6 flex flex-col flex-1">
                    <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 md:py-1 rounded-md text-[10px] md:text-[11px] font-bold text-red-700 bg-red-50 w-fit mb-3 md:mb-4"> {{ __('app.course_design') }} </span>
                    <h2 class="text-[14.5px] md:text-[19px] font-bold text-slate-900 leading-snug mb-1 group-hover:text-[#D10000] transition-colors line-clamp-2"><a href="{{ route('kursus') }}">UI/UX Design Mastery</a></h2>
                    <p class="text-[11.5px] md:text-[13px] text-slate-600 mb-3 md:mb-4">Sari Dewi · Tokopedia</p>
                    
                    <div class="flex items-center gap-2 mb-4 md:mb-6 mt-auto">
                        <div class="flex text-amber-400">
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">4.8</span>
                        <span class="text-[13px] text-slate-500">(9,210)</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="pt-4 md:pt-5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-600 text-[13px] font-medium">
                            <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            32h
                        </div>
                        <div class="text-[17px] font-bold text-slate-900">Rp 499.000</div>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-[20px] border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all group flex flex-col cursor-pointer">
                <!-- Image Wrapper -->
                <div class="relative h-[140px] md:h-[210px] w-full overflow-hidden bg-gray-100">
                    <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=600&auto=format&fit=crop&fm=webp" alt="AI Network" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Top Left Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-white text-red-700 text-[11px] font-bold rounded-full shadow-sm"> {{ __('app.course_hot') }} </span>
                        <span class="px-3 py-1 bg-white text-slate-600 text-[11px] font-bold rounded-full shadow-sm"> {{ __('app.course_advanced') }} </span>
                    </div>
                    <!-- Bookmark -->
                    <button aria-label="{{ __('app.save') }}" class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-700 shadow-sm hover:text-orange-800 transition-colors">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/bookmark.svg') }}" class="w-4 h-4" alt="" aria-hidden="true">
                    </button>
                    <!-- Progress Bar (inside image bottom) -->
                    <div class="absolute bottom-0 left-0 h-2 bg-emerald-500 z-10" style="width: 23%;"></div>
                </div>
                <!-- Card Body -->
                <div class="p-3.5 md:p-6 flex flex-col flex-1">
                    <span class="inline-flex items-center px-2 md:px-2.5 py-0.5 md:py-1 rounded-md text-[10px] md:text-[11px] font-bold text-red-700 bg-red-50 w-fit mb-3 md:mb-4"> {{ __('app.course_ai') }} </span>
                    <h2 class="text-[14.5px] md:text-[19px] font-bold text-slate-900 leading-snug mb-1 group-hover:text-[#D10000] transition-colors line-clamp-2"><a href="{{ route('kursus') }}">AI & Machine Learning Fundamentals</a></h2>
                    <p class="text-[11.5px] md:text-[13px] text-slate-600 mb-3 md:mb-4">Andi Wijaya · Gojek</p>
                    
                    <div class="flex items-center gap-2 mb-4 md:mb-6 mt-auto">
                        <div class="flex text-amber-400">
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg aria-hidden="true" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">4.9</span>
                        <span class="text-[13px] text-slate-500">(15,300)</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="pt-4 md:pt-5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-600 text-[13px] font-medium">
                            <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            60h
                        </div>
                        <div class="text-[13px] font-bold text-emerald-700">{{ __('app.progress_done', ['percent' => 23]) }}</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Bootcamp (Section 4) -->
<section id="bootcamp" class="py-16 md:py-24 bg-[#070707] relative w-full overflow-hidden border-t border-white/5">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Left Column: Content -->
            <div class="w-full lg:w-[55%]">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#2e1065]/50 border border-[#4c1d95]/50 mb-6">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/online&offlinebootcamp.svg') }}" class="w-4 h-4" alt="Bootcamp">
                    <span class="text-[11px] font-bold tracking-[0.15em] text-[#d8b4fe] uppercase">{{ __('app.bootcamp_badge') }}</span>
                </div>
                
                <!-- Title -->
                <h2 class="text-[26px] sm:text-3xl md:text-[46px] font-extrabold text-white tracking-tight leading-[1.2] md:leading-[1.15] mb-5 md:mb-6">
                    {{ __('app.bootcamp_title_1') }}<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#a855f7] to-[#7c3aed]">{{ __('app.bootcamp_title_2') }}</span>
                </h2>
                
                <!-- Description -->
                <p class="text-[17px] text-gray-400 leading-relaxed max-w-[480px] mb-10">
                    {{ __('app.bootcamp_desc_short') }}
                </p>
                
                <!-- 2x2 Feature Grid -->
                <div class="grid grid-cols-2 gap-4 max-w-[480px] mb-10">
                    <!-- Box 1 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h2 class="text-[15px] font-bold text-white mb-1"> {{ __('app.bootcamp_online') }} </h2>
                        <p class="text-[13px] text-gray-400">{{ __('app.bootcamp_online_desc') }}</p>
                    </div>
                    <!-- Box 2 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h2 class="text-[15px] font-bold text-white mb-1"> {{ __('app.bootcamp_offline') }} </h2>
                        <p class="text-[13px] text-gray-400">{{ __('app.bootcamp_offline_desc') }}</p>
                    </div>
                    <!-- Box 3 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h2 class="text-[15px] font-bold text-white mb-1"> {{ __('app.bootcamp_recording') }} </h2>
                        <p class="text-[13px] text-gray-400">{{ __('app.bootcamp_recording_desc') }}</p>
                    </div>
                    <!-- Box 4 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h2 class="text-[15px] font-bold text-white mb-1"> {{ __('app.bootcamp_certificate') }} </h2>
                        <p class="text-[13px] text-gray-400">{{ __('app.bootcamp_certificate_desc') }}</p>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <a href="{{ route('online-bootcamp') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-gradient-to-r from-[#7c3aed] to-[#6d28d9] hover:from-[#6d28d9] hover:to-[#5b21b6] rounded-xl text-white font-bold text-[15px] shadow-[0_0_30px_rgba(124,58,237,0.3)] hover:shadow-[0_0_40px_rgba(124,58,237,0.5)] transition-all">
                    {{ __('app.see_bootcamp_schedule') }}
                    <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
            
            <!-- Right Column: Image Card -->
            <div class="hidden lg:block w-full lg:w-[45%]">
                <div class="relative w-full h-[400px] md:h-[480px] rounded-[2rem] overflow-hidden shadow-2xl">
                    <!-- Base Image -->
                    <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1000&auto=format&fit=crop&fm=webp" alt="Bootcamp Class" class="w-full h-full object-cover">
                    <!-- Purple Tint Overlay -->
                    <div class="absolute inset-0 bg-[#4c1d95]/40 mix-blend-multiply"></div>
                    <!-- Soft gradient to bottom -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <!-- Glassmorphism Floating Bar -->
                    <div class="absolute bottom-5 left-5 right-5 bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-white mb-1.5 leading-snug">{{ __('app.leadership_management') }}</h2>
                            <div class="flex items-center gap-1.5 text-[13px] text-gray-300">
                                <svg aria-hidden="true" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>{{ __('app.participants_count', ['count' => 18]) }} · {{ __('app.starts_on', ['date' => '11 Agu 2025']) }}</span>
                            </div>
                        </div>
                        <div class="text-[15px] font-bold text-[#d8b4fe] whitespace-nowrap">
                            {{ __('app.price_6_5_jt') }}
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Mentor Section (Section 5) -->
<section id="mentors" class="py-16 md:py-24 bg-white relative w-full">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-100 bg-red-50 mb-6">
                <img loading="lazy" decoding="async" src="{{ asset('assets/icons/Mentor-Marketplace.svg') }}" class="w-3.5 h-3.5" alt="Mentor Marketplace">
                <span class="text-[11px] font-bold tracking-[0.15em] text-[#D10000] uppercase">{{ __('app.mentor_marketplace_badge') }}</span>
            </div>
            
            <!-- Title -->
            <h2 class="text-[26px] sm:text-3xl md:text-[44px] font-black text-slate-900 tracking-tight md:tracking-tighter leading-[1.15] md:leading-[1.2] mb-5 md:mb-5 [-webkit-text-stroke:1px_#0f172a]">
                {{ __('app.mentor_title') }}
            </h2>
            
            <!-- Description -->
            <p class="text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
                {{ __('app.mentor_desc') }}
            </p>
        </div>

        <!-- 4 Mentor Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <!-- Status Dot -->
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                
                <!-- Avatar -->
                <img loading="lazy" decoding="async" width="60" height="60" src="https://i.pravatar.cc/150?u=rudi" alt="Rudi Yesaya, Google Senior Dev" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                
                <!-- Info -->
                <h2 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Rudi Yesaya</h2>
                <p class="text-[13px] text-slate-600 mb-1">Senior Software Engineer</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Google</p>
                
                <!-- Rating -->
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.9</span>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-500">{{ __('app.price_per_session', ['price' => 'Rp 150.000']) }}</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        {{ __('app.book_mentor') }}
                    </button>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                <img loading="lazy" decoding="async" width="60" height="60" src="https://i.pravatar.cc/150?u=sari" alt="Sari Dewi, Lead Product Designer" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Sari Dewi</h2>
                <p class="text-[13px] text-slate-600 mb-1">Lead Product Designer</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Tokopedia</p>
                
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.8</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-500">{{ __('app.price_per_session', ['price' => 'Rp 120.000']) }}</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        {{ __('app.book_mentor') }}
                    </button>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-slate-300 ring-4 ring-slate-100"></div>
                <img loading="lazy" decoding="async" width="60" height="60" src="https://i.pravatar.cc/150?u=andi" alt="Andi Wijaya, ML Engineer" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Andi Wijaya</h2>
                <p class="text-[13px] text-slate-600 mb-1">ML Engineer</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Gojek</p>
                
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.9</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-500">{{ __('app.price_per_session', ['price' => 'Rp 200.000']) }}</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        {{ __('app.book_mentor') }}
                    </button>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                <img loading="lazy" decoding="async" width="60" height="60" src="https://i.pravatar.cc/150?u=rina" alt="Rina Kusuma, Head of Marketing" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Rina Kusuma</h2>
                <p class="text-[13px] text-slate-600 mb-1">Head of Marketing</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Shopee</p>
                
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.7</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-500">{{ __('app.price_per_session', ['price' => 'Rp 100.000']) }}</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        {{ __('app.book_mentor') }}
                    </button>
                </div>
            </div>

        </div>

        <!-- CTA Button -->
        <div class="mt-14 text-center">
            <a href="{{ route('mentor') }}" class="inline-flex items-center gap-2 px-9 py-4 bg-[#b90000] hover:bg-[#990000] text-white font-bold text-[15px] rounded-full shadow-[0_12px_35px_rgba(185,0,0,0.35)] hover:shadow-[0_15px_45px_rgba(185,0,0,0.5)] transition-all transform hover:-translate-y-1">
                {{ __('app.see_all_mentors') }}
                <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
        </div>
        
    </div>
</section>

<!-- Enterprise Section (Section 6) -->
<section id="enterprise" class="py-16 md:py-24 bg-[#070707] relative w-full overflow-hidden border-t border-white/5">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Left Column: Image with Floating Cards -->
            <div class="hidden lg:block relative w-full lg:w-[45%] h-[350px] sm:h-[450px] md:h-[540px] rounded-[2rem] overflow-hidden shadow-2xl">
                <!-- Base Image -->
                <img loading="lazy" decoding="async" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop&fm=webp" alt="Enterprise Training" class="w-full h-full object-cover">
                
                <!-- Red Tint Overlay -->
                <div class="absolute inset-0 bg-[#8b0000]/50 mix-blend-multiply"></div>
                <!-- Dark Gradient for depth -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#070707]/90 via-[#070707]/40 to-transparent"></div>
                
                <!-- Floating Card 1: Hiring Partners (Top Left) -->
                <div class="absolute top-6 left-6 md:top-8 md:left-8 bg-[#0a0a0a]/80 backdrop-blur-md rounded-2xl p-4 md:p-5 shadow-2xl border border-white/5 min-w-[130px] md:min-w-[140px]">
                    <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">300+</div>
                    <div class="text-[12px] md:text-[13px] text-gray-400 font-medium"> {{ __('app.hiring_partners') }} </div>
                </div>
                
                <!-- Floating Card 2: Completion Rate (Bottom Right) -->
                <div class="absolute bottom-6 right-6 md:bottom-8 md:right-8 bg-[#0a0a0a]/80 backdrop-blur-md rounded-2xl p-4 md:p-5 shadow-2xl border border-white/5 min-w-[130px] md:min-w-[140px]">
                    <div class="text-2xl md:text-3xl font-extrabold text-white mb-1">95%</div>
                    <div class="text-[12px] md:text-[13px] text-gray-400 font-medium"> {{ __('app.completion_rate') }} </div>
                </div>
            </div>
            
            <!-- Right Column: Content -->
            <div class="w-full lg:w-[55%]">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#2d0a0a] border border-[#5c1a1a] mb-5 lg:mb-6">
                    <img loading="lazy" decoding="async" src="{{ asset('assets/icons/enterprise&government.svg') }}" class="w-3.5 h-3.5 lg:w-4 lg:h-4" alt="Enterprise">
                    <span class="text-[10px] lg:text-[11px] font-bold tracking-[0.15em] text-[#f87171] uppercase">{{ __('app.enterprise_badge') }}</span>
                </div>
                
                <!-- Title -->
                <h2 class="font-['Inter'] text-[26px] sm:text-3xl md:text-[46px] font-black text-white tracking-tight md:tracking-[-0.05em] leading-[1.15] md:leading-[1.1] mb-4 lg:mb-5">
                    {{ __('app.enterprise_title_1') }}<br>
                    <span class="text-transparent bg-clip-text inline-block" style="background-image: linear-gradient(95deg, #D10000 0%, #FF4500 100%);">{{ __('app.enterprise_title_2') }}</span>
                </h2>
                
                <!-- Description -->
                <p class="text-[15px] sm:text-[16px] lg:text-[17px] text-gray-400 leading-relaxed mb-8 lg:mb-10 max-w-[500px]">
                    {{ __('app.enterprise_desc') }}
                </p>
                
                <!-- Features List -->
                <div class="flex flex-col gap-3.5 lg:gap-4 mb-10 lg:mb-12">
                    <div class="flex items-center gap-3.5">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/iconfeatureslist.svg') }}" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" alt="Check">
                        <span class="text-[14px] lg:text-[15px] text-gray-300 font-medium">{{ __('app.feat_dashboard') }}</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/iconfeatureslist.svg') }}" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" alt="Check">
                        <span class="text-[14px] lg:text-[15px] text-gray-300 font-medium">{{ __('app.feat_curriculum') }}</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/iconfeatureslist.svg') }}" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" alt="Check">
                        <span class="text-[14px] lg:text-[15px] text-gray-300 font-medium">{{ __('app.feat_certification') }}</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/iconfeatureslist.svg') }}" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" alt="Check">
                        <span class="text-[14px] lg:text-[15px] text-gray-300 font-medium">{{ __('app.feat_integration') }}</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <img loading="lazy" decoding="async" src="{{ asset('assets/icons/iconfeatureslist.svg') }}" class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0" alt="Check">
                        <span class="text-[14px] lg:text-[15px] text-gray-300 font-medium">{{ __('app.feat_account_manager') }}</span>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 lg:gap-4 w-full sm:w-auto">
                    <button class="w-full sm:w-auto px-8 py-3.5 bg-[#cc0000] hover:bg-[#aa0000] text-white font-bold rounded-xl text-[14px] lg:text-[15px] shadow-[0_0_30px_rgba(204,0,0,0.3)] hover:shadow-[0_0_40px_rgba(204,0,0,0.4)] transition-all">
                        {{ __('app.contact_sales') }}
                    </button>
                    <button class="w-full sm:w-auto px-8 py-3.5 bg-transparent hover:bg-white/5 border border-white/10 text-white font-bold rounded-xl text-[14px] lg:text-[15px] transition-all">
                        {{ __('app.watch_demo') }}
                    </button>
                </div>
                
            </div>
            
        </div>
    </div>
</section>

<!-- Testimonials Section (Section 7) -->
<section id="testimonials" class="py-16 md:py-24 bg-[#fafafa] relative w-full">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-amber-200 bg-amber-50 mb-6">
                <svg aria-hidden="true" class="w-3.5 h-3.5 text-amber-700" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span class="text-[11px] font-bold tracking-[0.15em] text-amber-700 uppercase">{{ __('app.real_stories_badge') }}</span>
            </div>
            
            <!-- Title -->
            <h2 class="font-['Inter'] text-[26px] sm:text-3xl md:text-[44px] font-black text-slate-900 tracking-tight leading-[1.15] md:leading-[1.1] mb-5">
                {{ __('app.real_stories_title') }}
            </h2>
        </div>

        <!-- 3 Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl md:rounded-3xl p-4 md:p-8 border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                <div aria-hidden="true" class="text-pink-600/50 text-4xl md:text-6xl font-serif leading-none mb-1 md:mb-2">"</div>
                <p class="text-[13px] md:text-[15px] text-gray-600 leading-relaxed flex-1 mb-6 md:mb-8">
                    {{ __('app.testi_1_desc') }}
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img loading="lazy" decoding="async" width="48" height="48" src="https://i.pravatar.cc/150?u=aisyah" alt="Aisyah Putri" class="w-10 md:w-12 h-10 md:h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <div class="text-[13px] md:text-[15px] font-bold text-slate-900 leading-snug">Aisyah Putri</div>
                            <div class="text-[11px] md:text-[12px] text-gray-600">Frontend Developer · Tokopedia</div>
                        </div>
                    </div>
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-2xl md:rounded-3xl p-4 md:p-8 border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                <div aria-hidden="true" class="text-pink-600/50 text-4xl md:text-6xl font-serif leading-none mb-1 md:mb-2">"</div>
                <p class="text-[13px] md:text-[15px] text-gray-600 leading-relaxed flex-1 mb-6 md:mb-8">
                    {{ __('app.testi_2_desc') }}
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img loading="lazy" decoding="async" width="48" height="48" src="https://i.pravatar.cc/150?u=dimas" alt="Dimas Prasetyo" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <div class="text-[15px] font-bold text-slate-900 leading-snug">Dimas Prasetyo</div>
                            <div class="text-[12px] text-gray-600">Data Scientist · Gojek</div>
                        </div>
                    </div>
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-2xl md:rounded-3xl p-4 md:p-8 border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                <div aria-hidden="true" class="text-pink-600/50 text-4xl md:text-6xl font-serif leading-none mb-1 md:mb-2">"</div>
                <p class="text-[13px] md:text-[15px] text-gray-600 leading-relaxed flex-1 mb-6 md:mb-8">
                    {{ __('app.testi_3_desc') }}
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img loading="lazy" decoding="async" width="48" height="48" src="https://i.pravatar.cc/150?u=nadya" alt="Nadya Ramadhani" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <div class="text-[15px] font-bold text-slate-900 leading-snug">Nadya Ramadhani</div>
                            <div class="text-[12px] text-gray-600">UI/UX Designer · Shopee</div>
                        </div>
                    </div>
                    <div class="flex text-amber-400">
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg aria-hidden="true" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Section (Section 8) -->
<section id="cta" class="py-32 bg-[#0a0a0a] relative w-full overflow-hidden border-t border-white/5 flex flex-col items-center justify-center text-center">
    <!-- Subtle Red Glow Background -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#FF0000]/20 blur-[120px] rounded-full pointer-events-none z-0"></div>
    
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10 flex flex-col items-center">
        
        <!-- Subtitle -->
        <div class="text-red-500 text-[11px] font-bold tracking-[0.2em] uppercase mb-5">
            {{ __('app.start_now_free') }}
        </div>
        
        <!-- Title -->
        <h2 class="font-['Inter'] text-[30px] sm:text-4xl md:text-[60px] font-black text-white tracking-tight md:tracking-[-0.05em] leading-[1.15] md:leading-[1] mb-4 md:mb-6">
            {{ __('app.cta_title_1') }}<br>
            <span class="text-transparent bg-clip-text inline-block" style="background-image: linear-gradient(95deg, #D10000 0%, #FF4500 100%);">{{ __('app.cta_title_2') }}</span>
        </h2>
        
        <!-- Description -->
        <p class="text-[17px] text-gray-400 leading-relaxed max-w-[540px] mx-auto mb-12">
            {{ __('app.cta_desc') }}
        </p>
        
        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 mb-16">
            <a href="{{ route('signup') }}" class="inline-flex items-center justify-center gap-2 px-9 py-4 bg-[#cc0000] hover:bg-[#aa0000] text-white font-bold rounded-2xl text-[16px] shadow-[0_0_40px_rgba(204,0,0,0.5)] hover:shadow-[0_0_50px_rgba(204,0,0,0.6)] transition-all">
                {{ __('app.register_free_now') }}
                <svg aria-hidden="true" class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-9 py-4 bg-[#111111] hover:bg-[#1a1a1a] border border-white/5 text-gray-300 hover:text-white font-bold rounded-2xl text-[16px] transition-all">
                {{ __('app.already_have_account') }}
            </a>
        </div>
        
        <!-- Features / Checkmarks -->
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4">
            <div class="flex items-center gap-2 text-[13px] text-gray-400 font-medium">
                <svg aria-hidden="true" class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('app.free_forever') }}
            </div>

            <div class="flex items-center gap-2 text-[13px] text-gray-400 font-medium">
                <svg aria-hidden="true" class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ __('app.cancel_anytime') }}
            </div>
            <div class="flex items-center gap-2 text-[13px] text-gray-400 font-medium">
                <svg aria-hidden="true" class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                300+ hiring partner
            </div>
        </div>
        
    </div>
</section>
</main>

<!-- Footer Section -->
<footer class="bg-[#070707] border-t border-white/5 pt-20 pb-10">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12">
        
        <!-- Top Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8 mb-20">
            
            <!-- Brand Column -->
            <div class="md:col-span-5 lg:col-span-4 flex flex-col items-center md:items-start text-center md:text-left">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center mb-4" aria-label="Beranda 1Langkah">
                    <svg aria-hidden="true" width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_306_8219_footer)">
                            <path d="M22.3789 27.1026H16.3237V7.52808H22.3789V27.1026Z" fill="#D10000"/>
                            <g filter="url(#filter0_d_306_8219_footer)">
                                <path d="M22.3746 7.57027C22.374 7.57183 22.3735 7.57359 22.373 7.57546C22.3719 7.57922 22.3705 7.58367 22.369 7.58868C22.366 7.59876 22.3622 7.61144 22.3576 7.62646C22.3484 7.65648 22.3362 7.69629 22.3206 7.7453C22.2894 7.84337 22.2451 7.9786 22.1872 8.14667C22.0716 8.48256 21.9009 8.95166 21.6697 9.51938C21.2088 10.651 20.4993 12.1959 19.4946 13.8706C17.5141 17.1718 14.2201 21.2532 9.16017 23.4218L6.7749 17.8562C10.1921 16.3917 12.6499 13.5095 14.302 10.7556C15.1136 9.40283 15.6905 8.14735 16.0619 7.23532C16.2468 6.78124 16.3787 6.41755 16.4618 6.17631C16.5032 6.05587 16.5322 5.96636 16.5496 5.91188C16.5582 5.8847 16.5639 5.86625 16.5667 5.8571L16.5681 5.85254C16.568 5.85317 16.5678 5.85399 16.5675 5.8549C16.5674 5.85534 16.5668 5.8568 16.5667 5.8571C16.5665 5.85753 16.5667 5.85723 16.9297 5.96429L22.0125 7.45993C22.3708 7.56565 22.3753 7.56754 22.3752 7.56806C22.375 7.56844 22.3748 7.56949 22.3746 7.57027Z" fill="#E50000"/>
                            </g>
                            <path d="M29.6583 7.91772V23.195H36.7659V26.9803H25.8457V7.91772H29.6583Z" fill="white"/>
                            <path d="M48.8093 15.2705H51.4236V27.0076H48.5371V25.8638C47.4477 26.7898 46.0317 27.3343 44.5067 27.3343C41.0482 27.3343 38.2705 24.5567 38.2705 21.1254C38.2705 17.6669 41.0482 14.9165 44.5067 14.9165C46.0317 14.9165 47.4477 15.4611 48.5371 16.387L48.8093 15.2705ZM47.0937 23.3857C47.6929 22.7594 47.9924 21.9696 47.9924 21.1254C47.9924 20.2812 47.6929 19.4643 47.0937 18.8652C46.5219 18.266 45.7593 17.9392 44.9424 17.9392C44.1255 17.9392 43.3629 18.266 42.7638 18.8652C42.1919 19.4643 41.8651 20.2812 41.8651 21.1254C41.8651 21.9696 42.1919 22.7594 42.7638 23.3857C43.3629 23.9848 44.1255 24.3116 44.9424 24.3116C45.7593 24.3116 46.5219 23.9848 47.0937 23.3857Z" fill="white"/>
                            <path d="M55.2833 15.2704L55.828 16.3052C56.7539 15.4883 57.9793 14.9436 59.1775 14.9436C62.3365 14.9436 64.9235 17.7213 64.9235 21.1798V27.0075H61.6012V21.1798C61.6012 19.4914 60.4029 17.9936 58.7691 17.9936C57.1351 17.9936 55.9369 19.4914 55.9369 21.1798V27.0075H52.5056V15.2704H55.2833Z" fill="white"/>
                            <path d="M78.5433 16.4688L76.5554 17.1223C77.3996 18.021 77.917 19.2192 77.917 20.5263C77.917 23.4674 75.33 25.8366 72.1165 25.8366C71.6808 25.8366 71.2723 25.7821 70.8639 25.7005C70.6188 26.1362 70.3737 26.6263 70.1831 27.1166C70.8095 27.0348 71.4902 26.9804 72.2527 26.9804C74.4313 26.9804 76.038 27.3344 77.1817 28.0425C78.38 28.805 79.0336 29.976 79.0336 31.3376C79.0336 32.7536 78.4344 33.8702 77.2635 34.6326C76.1469 35.3407 74.513 35.6947 72.2527 35.6947C69.9924 35.6947 68.3585 35.3407 67.242 34.6326C66.071 33.8702 65.4719 32.7536 65.4719 31.3376C65.4719 30.7929 65.5808 30.2755 65.7987 29.7853C66.3433 27.9608 67.596 25.9456 68.4947 24.6657C67.2148 23.6853 66.3706 22.1875 66.3706 20.5263C66.3706 17.5853 68.9576 15.216 72.1165 15.216C72.3889 15.216 72.6612 15.2433 72.9335 15.2705L78.5433 14.2085V16.4688ZM72.1165 18.2661C70.7277 18.2661 69.6657 19.3553 69.6657 20.5263C69.6657 21.8607 70.8911 22.7866 72.1165 22.7866C73.5327 22.7866 74.5947 21.6973 74.5947 20.5263C74.5947 19.2464 73.4781 18.2661 72.1165 18.2661ZM72.2527 32.5903C74.5947 32.5903 75.6839 32.2089 75.6839 31.3376C75.6839 30.5205 74.2952 30.0849 72.2527 30.0849C70.1013 30.0849 68.8214 30.6023 68.8214 31.3376C68.8214 32.2089 70.0742 32.5903 72.2527 32.5903Z" fill="white"/>
                            <path d="M92.57 27.0076H88.104L84.1827 22.242L83.3929 23.0589V27.0076H79.5803V6.74683H83.3929V19.0558L86.8786 15.2705H91.535L86.7697 20.1724L92.57 27.0076Z" fill="white"/>
                            <path d="M102.028 15.2705H104.643V27.0076H101.756V25.8638C100.667 26.7898 99.2509 27.3343 97.7254 27.3343C94.2667 27.3343 91.4893 24.5567 91.4893 21.1254C91.4893 17.6669 94.2667 14.9165 97.7254 14.9165C99.2509 14.9165 100.667 15.4611 101.756 16.387L102.028 15.2705ZM100.313 23.3857C100.911 22.7594 101.211 21.9696 101.211 21.1254C101.211 20.2812 100.911 19.4643 100.313 18.8652C99.7405 18.266 98.9782 17.9392 98.161 17.9392C97.3447 17.9392 96.5815 18.266 95.983 18.8652C95.4106 19.4643 95.0839 20.2812 95.0839 21.1254C95.0839 21.9696 95.4106 22.7594 95.983 23.3857C96.5815 23.9848 97.3447 24.3116 98.161 24.3116C98.9782 24.3116 99.7405 23.9848 100.313 23.3857Z" fill="white"/>
                            <path d="M109.346 6.74683V16.0058C110.354 15.325 111.552 14.9165 112.86 14.9165C116.291 14.9165 119.068 17.7214 119.068 21.1527V27.0076H115.473V21.1527C115.473 20.3085 115.147 19.5188 114.575 18.8924C114.003 18.2933 113.241 17.9666 112.423 17.9666C111.607 17.9666 110.817 18.2933 110.245 18.8924C109.673 19.5188 109.346 20.3085 109.346 21.1527V27.0076H105.725V6.74683H109.346Z" fill="white"/>
                        </g>
                        <defs>
                            <filter id="filter0_d_306_8219_footer" x="0.599103" y="-0.00106061" width="28.5963" height="30.5653" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                <feOffset dx="0.3222" dy="0.6444"/>
                                <feGaussianBlur stdDeviation="3.249"/>
                                <feComposite in2="hardAlpha" operator="out"/>
                                <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_306_8219"/>
                                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_306_8219" result="shape"/>
                            </filter>
                            <clipPath id="clip0_306_8219_footer">
                                <rect width="120" height="36" fill="white"/>
                            </clipPath>
                        </defs>
                    </svg>
                </a>
                


                <!-- Google Maps Embed -->
                <div class="mb-4 w-full overflow-hidden rounded-2xl border border-white/10 shadow-lg relative group bg-[#111111] p-1">
                    <a href="https://www.google.com/maps/dir//International+creatives+exchange,+Gedung+AlBarkat,+Jl.+RS.+Fatmawati+Raya+No.28AA+lt.2,+RT.1%2FRW.5,+Cipete+Sel.,+Kec.+Cilandak,+Kota+Jakarta+Selatan,+Daerah+Khusus+Ibukota+Jakarta+12410/@-6.2841065,106.8020795,12z/data=!4m8!4m7!1m0!1m5!1m1!1s0x2e69f10072703139:0x6b650f6695b19fc6!2m2!1d106.7976132!2d-6.2698547?entry=ttu&g_ep=EgoyMDI2MDcyMi4wIKXMDSoASAFQAw%3D%3D" target="_blank" aria-label="Open in Google Maps" class="absolute inset-1 z-10 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-xl">
                        <span class="px-5 py-2.5 bg-white/10 backdrop-blur-md rounded-full text-white text-[11px] font-bold border border-white/20 flex items-center gap-2 shadow-xl">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Buka di Google Maps
                        </span>
                    </a>
                    <div class="w-full h-[90px] rounded-xl overflow-hidden relative">
                        <iframe 
                            src="https://maps.google.com/maps?q=-6.2698547,106.7976132&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                            width="100%" 
                            height="100%" 
                            style="border:0; filter: invert(90%) hue-rotate(180deg) brightness(85%) contrast(85%);" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

                <!-- Contact & Address for Verification -->
                <div class="mt-auto flex flex-col gap-1.5 text-[13px] text-gray-400 text-center md:text-left w-full">
                    <div class="flex items-start justify-center md:justify-start gap-2">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>International Creatives Exchange, Gedung AlBarkat, Jl. RS. Fatmawati Raya No.28AA lt.2, RT.1/RW.5, Cipete Sel., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12410</span>
                    </div>
                    <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>+62 896-3725-9300</span>
                    </div>
                    <div class="flex items-center justify-center md:justify-start gap-2 mt-1">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>halo@1langkah.com</span>
                    </div>
                </div>
            </div>
            
            <!-- Link Columns -->
            <div class="md:col-span-7 lg:col-span-8 grid grid-cols-3 gap-2 sm:gap-4 md:gap-8 w-full">
                
                <!-- Column 1 -->
                <div class="flex flex-col items-start text-left">
                    <h2 class="text-[11px] md:text-[13px] font-bold tracking-[0.1em] md:tracking-[0.15em] text-gray-400 uppercase mb-5 md:mb-8"> {{ __('app.footer_platform') }} </h2>
                    <ul class="flex flex-col items-start gap-5 md:gap-7">
                        <li><a href="{{ route('kursus') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_courses') }}</a></li>
                        <li><a href="{{ route('online-bootcamp') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_bootcamp_online') }}</a></li>
                        <li><a href="{{ route('offline-bootcamp') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_bootcamp_offline') }}</a></li>
                        <li><a href="{{ route('mentor') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_mentor') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_job_board') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_community') }}</a></li>
                    </ul>
                </div>
                
                <!-- Column 2 -->
                <div class="flex flex-col items-center text-center">
                    <h2 class="text-[11px] md:text-[13px] font-bold tracking-[0.1em] md:tracking-[0.15em] text-gray-400 uppercase mb-5 md:mb-8"> {{ __('app.footer_company') }} </h2>
                    <ul class="flex flex-col items-center gap-5 md:gap-7">
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_about') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_career') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_blog') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_press') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_partner') }}</a></li>
                    </ul>
                </div>
                
                <!-- Column 3 -->
                <div class="flex flex-col items-end text-right">
                    <h2 class="text-[11px] md:text-[13px] font-bold tracking-[0.1em] md:tracking-[0.15em] text-gray-400 uppercase mb-5 md:mb-8"> {{ __('app.footer_support') }} </h2>
                    <ul class="flex flex-col items-end gap-5 md:gap-7">
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_help') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_privacy') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_terms') }}</a></li>
                        <li><a href="{{ url('/') }}" class="text-[13px] md:text-[15px] text-gray-400 hover:text-white transition-colors">{{ __('app.nav_status') }}</a></li>
                    </ul>
                </div>
                
            </div>
            
        </div>
        
        <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-center">
            <div class="text-[13px] text-gray-400 md:text-left">
                &copy; 2026 1Langkah Technologies. All rights reserved. | Developed by <a href="https://elc.my.id" target="_blank" class="text-blue-400 underline hover:text-blue-300">ELCoding.id</a>
            </div>
            <div class="text-[13px] text-gray-400 md:text-right">
                {!! __('app.footer_made_in') !!}
            </div>
        </div>
        
    </div>
</footer>
@endsection
