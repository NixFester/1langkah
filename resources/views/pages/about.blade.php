@extends('layouts.guest')

@section('title', __('app.nav_about') . ' — 1Langkah')

@section('body')
<!-- Navbar -->
<nav class="fixed top-0 left-0 right-0 z-50 px-6 lg:px-12 py-3 md:py-4 bg-[#050304]/80 backdrop-blur-md border-b border-white/5 flex items-center justify-between">
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
        <a href="{{ route('about') }}" class="text-white transition-colors font-bold">{{ __('app.nav_about') }}</a>
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
</nav><!-- Main Content -->
<main class="w-full flex flex-col overflow-x-hidden bg-[#050304] text-white">
    
    <!-- 1. HERO SECTION -->
    <section class="relative min-h-screen min-h-[100svh] w-full flex flex-col justify-center px-6 md:px-12 py-16 md:py-24 z-10 overflow-hidden">
        
        <!-- Random Red Gradients / Orbs -->
        <div class="absolute top-[10%] left-[10%] w-[400px] h-[400px] bg-[#D10000]/25 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[10%] right-[10%] w-[500px] h-[500px] bg-[#FF4500]/20 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute top-[40%] right-[30%] w-[350px] h-[350px] bg-[#8B0000]/30 rounded-full blur-[110px] pointer-events-none"></div>
        <div class="absolute bottom-[30%] left-[30%] w-[250px] h-[250px] bg-[#D10000]/20 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="max-w-[1400px] mx-auto w-full text-center relative z-10">
            <!-- Breadcrumb Badge -->
            <div class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-full border border-white/10 bg-white/5 backdrop-blur-md mb-8 shadow-2xl">
                <span class="w-2 h-2 rounded-full bg-[#D10000] animate-pulse"></span>
                <span class="text-[12px] font-bold tracking-[0.2em] text-white uppercase">{{ __('app.about_hero_badge') }}</span>
            </div>
            
            <h1 class="text-[34px] sm:text-4xl md:text-7xl lg:text-[84px] font-black mb-6 md:mb-8 tracking-tighter leading-[1.05]">
                {{ __('app.about_hero_title1') }}<br/>
                <span class="text-transparent bg-clip-text relative inline-block" style="background-image: linear-gradient(98deg, #FF6B6B 0%, #D10000 35%, #FF4500 65%, #FFB347 100%);">
                    {{ __('app.about_hero_title2') }}
                    <div class="absolute -bottom-2 left-0 w-full h-[4px] bg-gradient-to-r from-transparent via-[#D10000] to-transparent opacity-50 rounded-full"></div>
                </span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-400 leading-relaxed max-w-3xl mx-auto mb-12 font-medium">
                {{ __('app.about_hero_desc') }}
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto">
                <a href="{{ route('signup') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-[15px] font-bold text-white bg-gradient-to-b from-[#D10000] to-[#8B0000] rounded-full shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_0_20px_rgba(209,0,0,0.4)] hover:scale-105 transition-transform duration-300">
                    {{ __('app.about_btn_start') }}
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="{{ route('kursus') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-[15px] font-bold text-white border border-white/10 bg-white/5 rounded-full hover:bg-white/10 transition-colors duration-300">
                    {{ __('app.about_btn_explore') }}
                </a>
            </div>
        </div>
    </section>

    <!-- 2. CERITA KAMI (Our Story) -->
    <section class="py-16 md:py-24 relative z-10 border-t border-slate-200 bg-white">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <!-- Image / Graphic placeholder -->
                <div class="relative rounded-[2.5rem] overflow-hidden border border-slate-200 group">
                    <div class="absolute inset-0 bg-gradient-to-tr from-black/20 to-transparent z-10"></div>
                    <img decoding="async" loading="lazy" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Tim 1Langkah Berkolaborasi" class="w-full h-full object-cover aspect-square md:aspect-[4/3] group-hover:scale-105 transition-transform duration-700 ease-in-out">
                    
                    <div class="absolute bottom-8 left-8 right-8 z-20">
                        <div class="bg-white/90 backdrop-blur-md border border-white/20 rounded-2xl p-6 shadow-xl">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-[#D10000] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <div class="text-slate-900 font-bold text-lg">{{ app()->getLocale() == 'en' ? 'Born from Restlessness' : 'Lahir dari Keresahan' }}</div>
                                    <p class="text-sm text-slate-600">{{ app()->getLocale() == 'en' ? 'We saw millions of talents struggling to break into the industry.' : 'Kami melihat jutaan talenta yang kesulitan menembus batas industri.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Text Content -->
                <div>
                    <h2 class="text-[12px] font-bold tracking-[0.2em] text-red-400 uppercase mb-4">{{ __('app.about_story_badge') }}</h2>
                    <h2 class="text-[28px] sm:text-3xl md:text-5xl font-black mb-6 tracking-tight text-slate-900 leading-tight">{!! __('app.about_story_title') !!}</h2>
                    
                    <div class="space-y-6 text-slate-600 text-lg leading-relaxed">
                        <p>
                            {!! Str::inlineMarkdown(__('app.about_story_p1')) !!}
                        </p>
                        <p>
                            {!! Str::inlineMarkdown(__('app.about_story_p2')) !!}
                        </p>
                        
                        <div class="pt-6 border-t border-slate-200 flex items-center gap-8">
                            <div>
                                <div class="text-3xl font-black text-slate-900 mb-1">2023</div>
                                <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">{{ __('app.about_story_year_title') }}</div>
                            </div>
                            <div>
                                <div class="text-3xl font-black text-red-400 mb-1">Jakarta</div>
                                <div class="text-sm font-bold text-slate-500 uppercase tracking-widest">{{ __('app.about_story_hq_title') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. KEUNGGULAN (Why Us Grid) -->
    <section class="py-16 md:py-32 bg-[#050304] relative overflow-hidden border-t border-white/5">
        <!-- Subtle Red Glow Background -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#FF0000]/10 blur-[120px] rounded-full pointer-events-none z-0"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#D10000]/10 blur-[100px] rounded-full pointer-events-none z-0"></div>

        <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-[12px] font-bold tracking-[0.2em] text-red-500 uppercase mb-4">{{ __('app.about_why_badge') }}</h2>
                <h2 class="text-[28px] sm:text-3xl md:text-5xl font-black mb-6 tracking-tight leading-tight">{{ __('app.about_why_title') }}</h2>
                <p class="text-gray-400 text-lg">{!! Str::inlineMarkdown(__('app.about_why_desc')) !!}</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-[#0a0a0a] p-8 rounded-3xl border border-white/10 hover:border-[#D10000]/50 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#D10000]/20 group-hover:border-[#D10000]/30 transition-colors">
                        <svg class="w-7 h-7 text-white group-hover:text-[#D10000] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-3">{{ __('app.about_why_1_title') }}</h2>
                    <p class="text-gray-400 leading-relaxed text-sm">{!! Str::inlineMarkdown(__('app.about_why_1_desc')) !!}</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-[#0a0a0a] p-8 rounded-3xl border border-white/10 hover:border-[#D10000]/50 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#D10000]/20 group-hover:border-[#D10000]/30 transition-colors">
                        <svg class="w-7 h-7 text-white group-hover:text-[#D10000] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-3">{{ __('app.about_why_2_title') }}</h2>
                    <p class="text-gray-400 leading-relaxed text-sm">{!! Str::inlineMarkdown(__('app.about_why_2_desc')) !!}</p>
                </div>
                
                <!-- Card 3 -->
                <div class="bg-[#0a0a0a] p-8 rounded-3xl border border-white/10 hover:border-[#D10000]/50 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-[#D10000]/20 group-hover:border-[#D10000]/30 transition-colors">
                        <svg class="w-7 h-7 text-white group-hover:text-[#D10000] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-3">{{ __('app.about_why_3_title') }}</h2>
                    <p class="text-gray-400 leading-relaxed text-sm">{!! Str::inlineMarkdown(__('app.about_why_3_desc')) !!}</p>
                </div>
                
                <!-- Card 4 (Full Width Span) -->
                <div class="lg:col-span-3 bg-gradient-to-br from-[#1a1a1a] to-[#050304] p-10 md:p-12 rounded-[2.5rem] border border-white/10 flex flex-col md:flex-row items-center justify-between gap-8 mt-4 relative overflow-hidden">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-[#D10000]/10 blur-[80px] rounded-full pointer-events-none"></div>
                    <div class="relative z-10 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-500/30 bg-red-500/10 mb-4">
                            <span class="text-[10px] font-bold tracking-[0.15em] text-red-400 uppercase">{{ __('app.about_enterprise_badge') }}</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black text-white mb-4">{{ __('app.about_enterprise_title') }}</h2>
                        <p class="text-gray-400 text-lg">{!! Str::inlineMarkdown(__('app.about_enterprise_desc')) !!}</p>
                    </div>
                    <a href="{{ url('/') }}" class="relative z-10 whitespace-nowrap inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-white/10 border border-white/20 rounded-full hover:bg-white/20 transition-all">
                        {{ __('app.about_enterprise_btn') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. LEADERSHIP & IMPACT -->
    <section class="py-16 md:py-24 relative z-10 border-t border-slate-200 bg-white overflow-hidden">
        <!-- Subtle Grid Pattern overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSIjZTJlOGYwIiBzdHJva2Utb3BhY2l0eT0iMC44IiBmaWxsPSJub25lIj48cGF0aCBkPSJNMCA2MGg2ME02MCAwZi02MCIvPjwvZz48L3N2Zz4=')] opacity-50 pointer-events-none"></div>
        
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
            <!-- Leadership Part -->
            <div class="mx-auto max-w-7xl pb-20 mt-16">
                <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                    
                    <!-- Content -->
                    <div class="flex flex-col justify-center h-full text-center lg:text-left">
                        <div class="text-base/7 text-gray-700 max-w-xl mx-auto lg:mx-0">
                            <p class="text-[12px] font-bold tracking-[0.2em] text-red-600 uppercase">{{ __('app.about_leadership_badge') }}</p>
                            <h2 class="mt-3 text-3xl md:text-4xl font-black tracking-tight text-slate-900 sm:text-5xl">{{ __('app.about_leadership_name') }}</h2>
                            <p class="text-sm font-bold text-gray-500 tracking-widest mt-2">{{ __('app.about_leadership_role') }}</p>
                            <!-- Quote Section -->
                            <div class="mt-8">
                                <blockquote class="text-xl md:text-2xl font-bold leading-relaxed text-slate-900 italic border-l-4 border-red-600 pl-6 text-left">
                                    {{ __('app.about_leadership_quote') }}
                                </blockquote>
                            </div>
                            
                            <!-- Description -->
                            <div class="mt-8 text-lg leading-relaxed text-gray-600 text-left">
                                {!! Str::inlineMarkdown(__('app.about_leadership_desc')) !!}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Image -->
                    <div class="w-full flex justify-center lg:justify-end">
                        <div class="w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl ring-1 ring-gray-900/10 bg-gray-100">
                            <img class="w-full h-auto object-cover" src="https://attaulkarim.id/wp-content/uploads/2025/06/Atta_Ul_Karim_Potrait.jpg" alt="{{ __('app.about_leadership_name') }}">
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 5. CTA SECTION -->
    <section class="py-16 md:py-32 bg-[#0a0a0a] relative overflow-hidden border-t border-white/5">
        <!-- Subtle Red Glow Background -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#FF0000]/20 blur-[120px] rounded-full pointer-events-none z-0"></div>

        <div class="max-w-[1400px] mx-auto px-6 md:px-12 text-center relative z-10 flex flex-col items-center">
            <h2 class="text-[28px] sm:text-3xl md:text-6xl font-black mb-6 tracking-tight text-white leading-tight">{{ __('app.about_cta_title') }}</h2>
            <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto">{{ __('app.about_cta_desc') }}</p>
            <a href="{{ route('signup') }}" class="inline-flex items-center justify-center px-10 py-5 text-[16px] font-bold text-white bg-gradient-to-b from-[#D10000] to-[#8B0000] rounded-full shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_0_20px_rgba(209,0,0,0.6)] hover:scale-105 transition-transform duration-300">
                {{ __('app.about_cta_btn') }}
            </a>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-[#050304] py-12 border-t border-white/10 mt-auto relative z-10">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 text-center">
        <div class="text-gray-400 text-[14px] font-medium">
            &copy; 2026 1Langkah Technologies. All rights reserved. | Developed by <a href="https://elc.my.id" target="_blank" class="text-blue-400 underline hover:text-blue-300">ELCoding.id</a>
            <span class="mx-3 text-white/20">|</span> 
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Privacy Policy</a> 
            <span class="mx-3 text-white/20">|</span> 
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Terms of Service</a>
        </div>
    </div>
</footer>
@endsection
