@extends('layouts.guest')

@section('title', '1Langkah — AI-Powered Learning Experience Platform')

@section('body')
<!-- Navbar -->
<nav class="fixed top-0 left-0 right-0 z-50 px-6 md:px-12 py-4 bg-[#0a0a0a]/80 backdrop-blur-md border-b border-white/5 flex items-center justify-between">
    <!-- Logo -->
    <a href="{{ route('landing') }}" class="flex items-center">
        <svg width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
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
    <div class="hidden md:flex items-center gap-8 text-[15px] font-medium text-[#6b7280]">
        <a href="{{ route('kursus') }}" class="hover:text-white transition-colors">Kursus</a>
        <a href="{{ route('online-bootcamp') }}" class="hover:text-white transition-colors">Bootcamp</a>
        <a href="{{ route('mentor') }}" class="hover:text-white transition-colors">Mentor</a>
        <a href="#" class="hover:text-white transition-colors">Enterprise</a>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4">
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-[14px] font-medium text-[#d1d5db] border border-white/10 rounded-full hover:bg-white/5 transition-colors">
            Masuk
        </a>
        <a href="{{ route('signup') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-[14px] font-semibold text-white bg-gradient-to-b from-[#D10000] to-[#8B0000] rounded-full shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_0_20px_rgba(209,0,0,0.4)] hover:from-[#b30000] hover:to-[#6b0000] hover:shadow-[inset_0_1px_0_rgba(255,255,255,0.25),0_0_25px_rgba(209,0,0,0.6)] transition-all">
            Daftar Gratis
        </a>
    </div>
</nav>

<!-- Hero & Partners Full Screen Wrapper -->
<div class="h-[100svh] min-h-[800px] xl:min-h-[850px] w-full flex flex-col overflow-x-hidden bg-[#050304]">
    
    <!-- Hero -->
    <section class="relative flex-1 w-full flex flex-col justify-center px-6 md:px-12 pt-[90px] lg:pt-[100px] pb-8 z-10">
    <!-- Red gradient glow in background -->
    <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-[#D10000]/15 rounded-full blur-[100px] -translate-y-1/2 pointer-events-none"></div>

    <div class="max-w-[1400px] mx-auto w-full relative z-10 flex flex-col flex-1 justify-center min-h-0">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-4 xl:gap-8 items-end">
            
            <!-- Left side: Text Content -->
            <div class="relative z-10 flex flex-col items-start justify-center">
                
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-[#D10000]/30 bg-[#D10000]/10 mb-3 lg:mb-4">
                    <svg class="w-3.5 h-3.5 text-[#ef4444]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0L13.8 8.2L22 10L13.8 11.8L12 20L10.2 11.8L2 10L10.2 8.2L12 0Z"/></svg>
                    <span class="text-[10px] sm:text-xs font-bold tracking-[0.15em] text-[#ef4444] uppercase">AI-POWERED LEARNING EXPERIENCE PLATFORM</span>
                </div>

                <!-- Title -->
                <h1 class="text-5xl sm:text-6xl lg:text-[54px] xl:text-[70px] font-extrabold leading-[1.05] tracking-tight text-white mb-3 lg:mb-4">
                    Satu Langkah<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">Menuju Masa</span><br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500">Depan</span><br>
                    Lebih Baik.
                </h1>

                <!-- Subtitle -->
                <p class="text-[15px] lg:text-[16px] xl:text-lg text-[#9ca3af] mb-4 lg:mb-5 xl:mb-6 max-w-[500px] leading-relaxed">
                    Kuasai skill praktis, bangun pengalaman nyata dari proyek perusahaan, raih sertifikat terverifikasi, dan percepat karir kamu bersama AI terdepan.
                </p>

                <!-- Buttons Row -->
                <div class="flex flex-wrap items-center gap-3 lg:gap-4 xl:gap-5 mb-4 lg:mb-6">
                    <a href="{{ route('signup') }}" class="inline-flex items-center gap-2 px-6 py-3 lg:px-7 lg:py-3.5 xl:px-8 xl:py-4 bg-[#D10000] text-white font-bold text-[14px] xl:text-[16px] rounded-full hover:bg-[#b30000] transition-all shadow-[0_0_30px_rgba(209,0,0,0.5)]">
                        Mulai Belajar Gratis 
                        <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </a>
                    <a href="{{ route('kursus') }}" class="inline-flex items-center justify-center px-6 py-3 lg:px-7 lg:py-3.5 xl:px-8 xl:py-4 border border-white/20 bg-transparent text-white font-bold text-[14px] xl:text-[16px] rounded-full hover:bg-white/5 transition-colors">
                        Jelajahi Kursus
                    </a>
                </div>

                <!-- Watch Demo -->
                <button class="group flex items-center gap-3 text-[#d1d5db] hover:text-white transition-colors">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white/10 bg-white/5 group-hover:border-white/30 group-hover:bg-white/10 transition-all">
                        <svg class="w-3.5 h-3.5 ml-1 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <span class="font-medium text-[15px]">Watch Demo</span>
                </button>


            </div>

            <!-- Right side: Illustrative Dashboard Mockup -->
            <div class="relative z-10 hidden lg:flex items-center justify-center w-full min-h-0">
                <div class="relative w-full max-w-[500px] xl:max-w-[650px] mx-auto origin-center lg:mt-4 xl:mt-8 -mb-16 lg:-mb-24 xl:-mb-28">
                    <!-- Floating Pills -->
                    <div class="absolute left-[2%] top-[25%] px-4 py-1.5 lg:px-5 lg:py-2 bg-[#171313]/90 backdrop-blur-sm border border-white/10 rounded-full text-white text-[11px] lg:text-[13px] font-semibold z-30 shadow-2xl animate-[float_7s_ease-in-out_infinite]">React</div>
                    <div class="absolute left-[5%] top-[45%] px-4 py-1.5 lg:px-5 lg:py-2 bg-[#171313]/90 backdrop-blur-sm border border-white/10 rounded-full text-white text-[11px] lg:text-[13px] font-semibold z-30 shadow-2xl animate-[float_8s_ease-in-out_infinite_reverse]">Python</div>

                    <!-- Main Dashboard Image -->
                    <img src="{{ asset('images/hero/hero-main.png') }}" alt="1Langkah Platform" class="w-full relative z-10">
                    
                    <!-- Sertifikat (Top Right) -->
                    <img src="{{ asset('images/hero/hero-cert.png') }}" alt="Sertifikat" class="absolute right-[0%] top-[8%] w-[244px] z-20 animate-[float_6s_ease-in-out_infinite]">
                    
                    <!-- Rudi Yesaya (Bottom Left) -->
                    <img src="{{ asset('images/hero/hero-mentor.png') }}" alt="Mentor" class="absolute left-[2%] bottom-[20%] w-[232px] z-20 animate-[float_8s_ease-in-out_infinite_reverse]">
                    
                    <!-- Skill Passport (Bottom Right) -->
                    <img src="{{ asset('images/hero/hero-skills.png') }}" alt="Skill Passport" class="absolute right-[2%] bottom-[12%] w-[222px] z-20 animate-[float_7s_ease-in-out_infinite_1s]">
                </div>

                </div>
            </div>

        <!-- Bottom Stats Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-4 xl:gap-8 items-center w-full relative z-20 pt-6 lg:pt-8">
            
            <!-- Stats (Left Column) -->
            <div class="flex items-center justify-center lg:justify-start gap-4 lg:gap-8 xl:gap-14 whitespace-nowrap w-full">
                <div class="text-center lg:text-left">
                    <div class="text-[20px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0.5 lg:mb-1 tracking-tight">100K+</div>
                    <div class="text-[11px] lg:text-[12px] xl:text-[13px] font-medium text-[#6b7280]">Pelajar Aktif</div>
                </div>
                <div class="text-center lg:text-left">
                    <div class="text-[20px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0.5 lg:mb-1 tracking-tight">800+</div>
                    <div class="text-[11px] lg:text-[12px] xl:text-[13px] font-medium text-[#6b7280]">Kursus Premium</div>
                </div>
                <div class="text-center lg:text-left">
                    <div class="text-[20px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0.5 lg:mb-1 tracking-tight">500+</div>
                    <div class="text-[11px] lg:text-[12px] xl:text-[13px] font-medium text-[#6b7280]">Mentor Berpengalaman</div>
                </div>
                <div class="text-center lg:text-left">
                    <div class="text-[20px] lg:text-[22px] xl:text-[26px] font-bold text-white mb-0.5 lg:mb-1 tracking-tight">95%</div>
                    <div class="text-[11px] lg:text-[12px] xl:text-[13px] font-medium text-[#6b7280]">Course Completion</div>
                </div>
            </div>

            <!-- Ratings (Right Column) -->
            <div class="flex items-center justify-center gap-4 whitespace-nowrap w-full">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-[#070707]" src="https://ui-avatars.com/api/?name=A&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-[#070707]" src="https://ui-avatars.com/api/?name=B&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-[#070707]" src="https://ui-avatars.com/api/?name=C&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-[#070707]" src="https://ui-avatars.com/api/?name=D&background=random" alt="User">
                    <div class="w-10 h-10 rounded-full border-2 border-[#070707] bg-[#dc2626] flex items-center justify-center text-[10px] font-bold text-white z-10">+99K</div>
                </div>
                <div class="text-left">
                    <div class="flex items-center gap-1 text-[#eab308] mb-0.5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-white font-bold ml-1.5 text-sm">4.9</span>
                    </div>
                    <div class="text-[#6b7280] text-[11px] font-medium">dari 100,000+ pelajar aktif di seluruh Indonesia</div>
                </div>

            </div>
        </div>
    </div>
    </section>
    
    <!-- Partners -->
    <section class="py-3 lg:py-4 px-6 md:px-12 bg-transparent border-t border-white/5 relative z-20">
        <div class="max-w-[1400px] mx-auto text-center">
            <p class="text-[9px] lg:text-[10px] font-bold tracking-[0.2em] text-[#6b7280] uppercase mb-2 lg:mb-3">DIPERCAYA OLEH 300+ PERUSAHAAN & INSTITUSI TERKEMUKA</p>
        <div class="flex flex-wrap justify-center items-center gap-4 lg:gap-6 xl:gap-12 opacity-60">
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">GOJEK</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">SHOPEE</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">TRAVELOKA</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">GRAB</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">BUKALAPAK</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">PERTAMINA</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">BCA</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">TELKOM</span>
            <span class="text-sm lg:text-base xl:text-lg font-bold text-[#9ca3af]">BLIBLI</span>
        </div>
    </section>
</div>

<!-- Features (Section 2) -->
<section id="features" class="min-h-screen flex flex-col justify-center py-16 lg:py-24 bg-white relative w-full overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10 w-full">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-100 bg-red-50 mb-6">
                <svg class="w-3.5 h-3.5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span class="text-[11px] font-bold tracking-[0.15em] text-[#D10000] uppercase">PLATFORM LENGKAP</span>
            </div>
            
            <h2 class="text-4xl md:text-[44px] font-extrabold text-[#0f172a] tracking-tight leading-[1.2] mb-4">
                Bukan sekadar platform kursus.<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF3333] to-[#cc0000]">Ini ekosistem karir kamu.</span>
            </h2>
            
            <p class="text-lg text-gray-500 leading-relaxed max-w-2xl mx-auto">
                Dari skill pertama hingga pekerjaan impian — semuanya dalam satu platform<br class="hidden md:block"> yang dirancang bersama AI.
            </p>
        </div>

        <!-- 3 Feature Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 mb-10 lg:mb-12">
            <!-- Card 1 -->
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-red-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h.01M10 10h.01M19 16H5a2 2 0 01-2-2V8a2 2 0 012-2h14a2 2 0 012 2v6a2 2 0 01-2 2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6V4c0-1.1.9-2 2-2h4c1.1 0 2 .9 2 2v2"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">AI Learning Assistant</h3>
                <p class="text-[15px] text-gray-500 leading-relaxed mb-8 flex-1">Tutor AI 24/7 yang siap menjelaskan topik sulit, meringkas materi, dan membuat quiz personal hanya dalam hitungan detik.</p>
                <a href="#" class="inline-flex items-center gap-1.5 text-sm font-bold text-[#D10000] group-hover:gap-2 transition-all">
                    Pelajari lebih lanjut 
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Personalized Learning Path</h3>
                <p class="text-[15px] text-gray-500 leading-relaxed mb-8 flex-1">Jalur belajar yang dibentuk oleh AI berdasarkan tujuan karir, skill gaps, dan kecepatan belajarmu — bukan jalur generik.</p>
                <a href="#" class="inline-flex items-center gap-1.5 text-sm font-bold text-emerald-500 group-hover:gap-2 transition-all">
                    Pelajari lebih lanjut 
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all flex flex-col items-start group cursor-pointer">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Verified Skill Passport</h3>
                <p class="text-[15px] text-gray-500 leading-relaxed mb-8 flex-1">Profil skill digital terverifikasi dengan QR code yang bisa langsung dibagikan ke rekruter dan diakui 300+ hiring partner.</p>
                <a href="#" class="inline-flex items-center gap-1.5 text-sm font-bold text-purple-500 group-hover:gap-2 transition-all">
                    Pelajari lebih lanjut 
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- 8 Small Feature Pills Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Pill 1 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Interactive Courses</span>
            </div>
            <!-- Pill 2 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Certificates</span>
            </div>
            <!-- Pill 3 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Portfolio Builder</span>
            </div>
            <!-- Pill 4 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Project Marketplace</span>
            </div>
            <!-- Pill 5 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Mentor Marketplace</span>
            </div>
            <!-- Pill 6 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Career Center</span>
            </div>
            <!-- Pill 7 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Community</span>
            </div>
            <!-- Pill 8 -->
            <div class="bg-slate-50 border border-slate-100/80 rounded-[20px] p-4 flex items-center gap-4 hover:shadow-md transition-shadow cursor-pointer">
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="text-[13px] md:text-sm font-bold text-gray-800">Learning Analytics</span>
            </div>
        </div>
    </div>
</section>

<!-- Popular Courses (Section 3) -->
<section id="popular-courses" class="py-24 bg-slate-50 relative w-full overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-100/50 border border-emerald-200 mb-4">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="text-[11px] font-bold tracking-[0.15em] text-emerald-700 uppercase">KURSUS TERPOPULER</span>
                </div>
                <h2 class="text-4xl md:text-[42px] font-extrabold text-[#0f172a] tracking-tight">Mulai belajar sekarang</h2>
            </div>
            <a href="{{ route('kursus') }}" class="inline-flex items-center gap-1.5 text-[15px] font-bold text-[#D10000] hover:text-[#b30000] transition-colors md:pb-2">
                Lihat 800+ kursus 
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Card 1 -->
            <a href="#" class="bg-white rounded-[20px] border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all group flex flex-col cursor-pointer">
                <!-- Image Wrapper -->
                <div class="relative h-[210px] w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=600&auto=format&fit=crop" alt="Coding" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Top Left Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-white text-orange-500 text-[11px] font-bold rounded-full shadow-sm">Bestseller</span>
                        <span class="px-3 py-1 bg-white text-slate-600 text-[11px] font-bold rounded-full shadow-sm">Intermediate</span>
                    </div>
                    <!-- Bookmark -->
                    <button class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-400 shadow-sm hover:text-orange-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    </button>
                    <!-- Progress Bar (inside image bottom) -->
                    <div class="absolute bottom-0 left-0 h-2 bg-emerald-500 z-10" style="width: 68%;"></div>
                </div>
                <!-- Card Body -->
                <div class="p-6 flex flex-col flex-1">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold text-red-500 bg-red-50 w-fit mb-4">Programming</span>
                    <h3 class="text-[19px] font-bold text-slate-900 leading-snug mb-1 group-hover:text-[#D10000] transition-colors line-clamp-2">Full-Stack Web Development Bootcamp</h3>
                    <p class="text-[13px] text-slate-500 mb-4">Rudi Yesaya · Google</p>
                    
                    <div class="flex items-center gap-2 mb-6 mt-auto">
                        <div class="flex text-amber-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">4.9</span>
                        <span class="text-[13px] text-slate-400">(12,840)</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="pt-5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-500 text-[13px] font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            48h
                        </div>
                        <div class="text-[13px] font-bold text-emerald-600">68% done</div>
                    </div>
                </div>
            </a>

            <!-- Card 2 -->
            <a href="#" class="bg-white rounded-[20px] border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all group flex flex-col cursor-pointer">
                <!-- Image Wrapper -->
                <div class="relative h-[210px] w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?q=80&w=600&auto=format&fit=crop" alt="Design" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Top Left Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-white text-red-500 text-[11px] font-bold rounded-full shadow-sm">New</span>
                        <span class="px-3 py-1 bg-white text-slate-600 text-[11px] font-bold rounded-full shadow-sm">Beginner</span>
                    </div>
                    <!-- Bookmark -->
                    <button class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-400 shadow-sm hover:text-orange-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    </button>
                </div>
                <!-- Card Body -->
                <div class="p-6 flex flex-col flex-1">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold text-red-500 bg-red-50 w-fit mb-4">Design</span>
                    <h3 class="text-[19px] font-bold text-slate-900 leading-snug mb-1 group-hover:text-[#D10000] transition-colors line-clamp-2">UI/UX Design Mastery</h3>
                    <p class="text-[13px] text-slate-500 mb-4">Sari Dewi · Tokopedia</p>
                    
                    <div class="flex items-center gap-2 mb-6 mt-auto">
                        <div class="flex text-amber-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">4.8</span>
                        <span class="text-[13px] text-slate-400">(9,210)</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="pt-5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-500 text-[13px] font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            32h
                        </div>
                        <div class="text-[17px] font-bold text-slate-900">Rp 499.000</div>
                    </div>
                </div>
            </a>

            <!-- Card 3 -->
            <a href="#" class="bg-white rounded-[20px] border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all group flex flex-col cursor-pointer">
                <!-- Image Wrapper -->
                <div class="relative h-[210px] w-full overflow-hidden bg-gray-100">
                    <img src="https://images.unsplash.com/photo-1677442136019-21780ecad995?q=80&w=600&auto=format&fit=crop" alt="AI Network" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <!-- Top Left Badges -->
                    <div class="absolute top-4 left-4 flex gap-2">
                        <span class="px-3 py-1 bg-white text-red-500 text-[11px] font-bold rounded-full shadow-sm">Hot</span>
                        <span class="px-3 py-1 bg-white text-slate-600 text-[11px] font-bold rounded-full shadow-sm">Advanced</span>
                    </div>
                    <!-- Bookmark -->
                    <button class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center text-orange-400 shadow-sm hover:text-orange-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    </button>
                    <!-- Progress Bar (inside image bottom) -->
                    <div class="absolute bottom-0 left-0 h-2 bg-emerald-500 z-10" style="width: 23%;"></div>
                </div>
                <!-- Card Body -->
                <div class="p-6 flex flex-col flex-1">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold text-red-500 bg-red-50 w-fit mb-4">AI</span>
                    <h3 class="text-[19px] font-bold text-slate-900 leading-snug mb-1 group-hover:text-[#D10000] transition-colors line-clamp-2">AI & Machine Learning Fundamentals</h3>
                    <p class="text-[13px] text-slate-500 mb-4">Andi Wijaya · Gojek</p>
                    
                    <div class="flex items-center gap-2 mb-6 mt-auto">
                        <div class="flex text-amber-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700">4.9</span>
                        <span class="text-[13px] text-slate-400">(15,300)</span>
                    </div>
                    
                    <!-- Footer -->
                    <div class="pt-5 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-500 text-[13px] font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            60h
                        </div>
                        <div class="text-[13px] font-bold text-emerald-600">23% done</div>
                    </div>
                </div>
            </a>

        </div>
    </div>
</section>

<!-- Bootcamp (Section 4) -->
<section id="bootcamp" class="py-24 bg-[#070707] relative w-full overflow-hidden border-t border-white/5">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <div class="flex flex-col lg:flex-row items-center gap-16">
            
            <!-- Left Column: Content -->
            <div class="w-full lg:w-[55%]">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#2e1065]/50 border border-[#4c1d95]/50 mb-6">
                    <svg class="w-3.5 h-3.5 text-[#d8b4fe]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <span class="text-[11px] font-bold tracking-[0.15em] text-[#d8b4fe] uppercase">ONLINE & OFFLINE BOOTCAMP</span>
                </div>
                
                <!-- Title -->
                <h2 class="text-4xl md:text-[46px] font-extrabold text-white tracking-tight leading-[1.15] mb-6">
                    Belajar intensif dengan<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#a855f7] to-[#7c3aed]">instruktur terbaik.</span>
                </h2>
                
                <!-- Description -->
                <p class="text-[17px] text-gray-400 leading-relaxed max-w-[480px] mb-10">
                    7–10 sesi tatap muka LIVE via Zoom atau hadir langsung di kampus 1Langkah. Kurikulum intensif, cohort kecil, hasil nyata.
                </p>
                
                <!-- 2x2 Feature Grid -->
                <div class="grid grid-cols-2 gap-4 max-w-[480px] mb-10">
                    <!-- Box 1 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h4 class="text-[15px] font-bold text-white mb-1">Online Bootcamp</h4>
                        <p class="text-[13px] text-gray-500">Via Zoom · 7-10 sesi LIVE</p>
                    </div>
                    <!-- Box 2 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h4 class="text-[15px] font-bold text-white mb-1">Offline Bootcamp</h4>
                        <p class="text-[13px] text-gray-500">Tatap muka di 3 kota</p>
                    </div>
                    <!-- Box 3 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h4 class="text-[15px] font-bold text-white mb-1">Rekaman 30 hari</h4>
                        <p class="text-[13px] text-gray-500">Akses setelah sesi</p>
                    </div>
                    <!-- Box 4 -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-[14px] p-4">
                        <h4 class="text-[15px] font-bold text-white mb-1">Sertifikat</h4>
                        <p class="text-[13px] text-gray-500">Terverifikasi</p>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <a href="{{ route('online-bootcamp') }}" class="inline-flex items-center gap-2 px-7 py-3.5 bg-gradient-to-r from-[#7c3aed] to-[#6d28d9] hover:from-[#6d28d9] hover:to-[#5b21b6] rounded-xl text-white font-bold text-[15px] shadow-[0_0_30px_rgba(124,58,237,0.3)] hover:shadow-[0_0_40px_rgba(124,58,237,0.5)] transition-all">
                    Lihat Jadwal Bootcamp
                    <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                </a>
            </div>
            
            <!-- Right Column: Image Card -->
            <div class="w-full lg:w-[45%]">
                <div class="relative w-full h-[400px] md:h-[480px] rounded-[2rem] overflow-hidden shadow-2xl">
                    <!-- Base Image -->
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=1000&auto=format&fit=crop" alt="Bootcamp Class" class="w-full h-full object-cover">
                    <!-- Purple Tint Overlay -->
                    <div class="absolute inset-0 bg-[#4c1d95]/40 mix-blend-multiply"></div>
                    <!-- Soft gradient to bottom -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <!-- Glassmorphism Floating Bar -->
                    <div class="absolute bottom-5 left-5 right-5 bg-black/40 backdrop-blur-md border border-white/10 rounded-2xl p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-white mb-1.5 leading-snug">Leadership & Management Excellence</h3>
                            <div class="flex items-center gap-1.5 text-[13px] text-gray-300">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <span>18 peserta · Mulai 11 Agu 2025</span>
                            </div>
                        </div>
                        <div class="text-[15px] font-bold text-[#d8b4fe] whitespace-nowrap">
                            Rp 6.5jt
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Mentor Section (Section 5) -->
<section id="mentors" class="py-24 bg-white relative w-full">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-red-100 bg-red-50 mb-6">
                <svg class="w-3.5 h-3.5 text-[#D10000]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                <span class="text-[11px] font-bold tracking-[0.15em] text-[#D10000] uppercase">MENTOR MARKETPLACE</span>
            </div>
            
            <!-- Title -->
            <h2 class="text-4xl md:text-[44px] font-extrabold text-slate-900 tracking-tight leading-[1.2] mb-5">
                Bimbingan 1-on-1 dari para ahli.
            </h2>
            
            <!-- Description -->
            <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto">
                500+ mentor berpengalaman dari Google, Gojek, Tokopedia, dan<br class="hidden md:block"> ratusan perusahaan top siap memandu karir kamu.
            </p>
        </div>

        <!-- 4 Mentor Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <!-- Status Dot -->
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                
                <!-- Avatar -->
                <img src="https://i.pravatar.cc/150?u=rudi" alt="Rudi" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                
                <!-- Info -->
                <h3 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Rudi Yesaya</h3>
                <p class="text-[13px] text-slate-500 mb-1">Senior Software Engineer</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Google</p>
                
                <!-- Rating -->
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.9</span>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-400">Rp 150.000/sesi</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        Book
                    </button>
                </div>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                <img src="https://i.pravatar.cc/150?u=sari" alt="Sari" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Sari Dewi</h3>
                <p class="text-[13px] text-slate-500 mb-1">Lead Product Designer</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Tokopedia</p>
                
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.8</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-400">Rp 120.000/sesi</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        Book
                    </button>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-slate-300 ring-4 ring-slate-100"></div>
                <img src="https://i.pravatar.cc/150?u=andi" alt="Andi" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Andi Wijaya</h3>
                <p class="text-[13px] text-slate-500 mb-1">ML Engineer</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Gojek</p>
                
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.9</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-400">Rp 200.000/sesi</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        Book
                    </button>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow relative flex flex-col cursor-pointer group">
                <div class="absolute top-6 right-6 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-emerald-50"></div>
                <img src="https://i.pravatar.cc/150?u=rina" alt="Rina" class="w-[60px] h-[60px] rounded-[18px] object-cover mb-5 shadow-sm">
                <h3 class="text-lg font-bold text-slate-900 mb-0.5 group-hover:text-[#D10000] transition-colors">Rina Kusuma</h3>
                <p class="text-[13px] text-slate-500 mb-1">Head of Marketing</p>
                <p class="text-[13px] font-bold text-[#D10000] mb-5">Shopee</p>
                
                <div class="flex items-center gap-2 mb-6 mt-auto">
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5 text-amber-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    <span class="text-[13px] font-bold text-slate-800">4.7</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-medium text-slate-400">Rp 100.000/sesi</span>
                    <button class="px-5 py-2 bg-[#cc0000] hover:bg-[#aa0000] text-white text-[13px] font-bold rounded-full transition-colors shadow-sm">
                        Book
                    </button>
                </div>
            </div>

        </div>

        <!-- CTA Button -->
        <div class="mt-14 text-center">
            <a href="{{ route('mentor') }}" class="inline-flex items-center gap-2 px-9 py-4 bg-[#b90000] hover:bg-[#990000] text-white font-bold text-[15px] rounded-full shadow-[0_12px_35px_rgba(185,0,0,0.35)] hover:shadow-[0_15px_45px_rgba(185,0,0,0.5)] transition-all transform hover:-translate-y-1">
                Lihat 500+ Mentor
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
        </div>
        
    </div>
</section>

<!-- Enterprise Section (Section 6) -->
<section id="enterprise" class="py-24 bg-[#070707] relative w-full overflow-hidden border-t border-white/5">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            
            <!-- Left Column: Image with Floating Cards -->
            <div class="relative w-full h-[450px] md:h-[540px] rounded-[2rem] overflow-hidden shadow-2xl">
                <!-- Base Image -->
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop" alt="Enterprise Training" class="w-full h-full object-cover">
                
                <!-- Red Tint Overlay -->
                <div class="absolute inset-0 bg-[#8b0000]/50 mix-blend-multiply"></div>
                <!-- Dark Gradient for depth -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#070707]/90 via-[#070707]/40 to-transparent"></div>
                
                <!-- Floating Card 1: Hiring Partners (Top Left) -->
                <div class="absolute top-8 left-8 bg-[#0a0a0a]/80 backdrop-blur-md rounded-2xl p-5 shadow-2xl border border-white/5 min-w-[140px]">
                    <div class="text-3xl font-extrabold text-white mb-1">300+</div>
                    <div class="text-[13px] text-gray-400 font-medium">Hiring Partners</div>
                </div>
                
                <!-- Floating Card 2: Completion Rate (Bottom Right) -->
                <div class="absolute bottom-8 right-8 bg-[#0a0a0a]/80 backdrop-blur-md rounded-2xl p-5 shadow-2xl border border-white/5 min-w-[140px]">
                    <div class="text-3xl font-extrabold text-white mb-1">95%</div>
                    <div class="text-[13px] text-gray-400 font-medium">Completion Rate</div>
                </div>
            </div>
            
            <!-- Right Column: Content -->
            <div>
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#2d0a0a] border border-[#5c1a1a] mb-6">
                    <svg class="w-3.5 h-3.5 text-[#f87171]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <span class="text-[11px] font-bold tracking-[0.15em] text-[#f87171] uppercase">ENTERPRISE & GOVERNMENT</span>
                </div>
                
                <!-- Title -->
                <h2 class="text-4xl md:text-[46px] font-extrabold text-white tracking-tight leading-[1.1] mb-5">
                    Solusi pelatihan<br>
                    <span class="text-[#ff3b30]">skala enterprise.</span>
                </h2>
                
                <!-- Description -->
                <p class="text-[17px] text-gray-400 leading-relaxed mb-10 max-w-[500px]">
                    Tingkatkan kompetensi tim kamu dengan program pelatihan yang dipersonalisasi — dari startup hingga korporasi dan institusi pemerintah.
                </p>
                
                <!-- Features List -->
                <div class="flex flex-col gap-4 mb-12">
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-[#ff3b30] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[15px] text-gray-300 font-medium">Dashboard analytics karyawan & tim</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-[#ff3b30] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[15px] text-gray-300 font-medium">Kurikulum custom sesuai kebutuhan bisnis</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-[#ff3b30] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[15px] text-gray-300 font-medium">Sertifikasi massal yang terverifikasi</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-[#ff3b30] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[15px] text-gray-300 font-medium">Integrasi dengan HR system perusahaan</span>
                    </div>
                    <div class="flex items-center gap-3.5">
                        <svg class="w-5 h-5 text-[#ff3b30] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-[15px] text-gray-300 font-medium">Dedicated account manager</span>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-wrap items-center gap-4">
                    <button class="px-8 py-3.5 bg-[#cc0000] hover:bg-[#aa0000] text-white font-bold rounded-xl text-[15px] shadow-[0_0_30px_rgba(204,0,0,0.3)] hover:shadow-[0_0_40px_rgba(204,0,0,0.4)] transition-all">
                        Hubungi Sales
                    </button>
                    <button class="px-8 py-3.5 bg-transparent hover:bg-white/5 border border-white/10 text-white font-bold rounded-xl text-[15px] transition-all">
                        Lihat Demo
                    </button>
                </div>
                
            </div>
            
        </div>
    </div>
</section>

<!-- Testimonials Section (Section 7) -->
<section id="testimonials" class="py-24 bg-[#fafafa] relative w-full">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-amber-200 bg-amber-50 mb-6">
                <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                <span class="text-[11px] font-bold tracking-[0.15em] text-amber-500 uppercase">CERITA NYATA PELAJAR</span>
            </div>
            
            <!-- Title -->
            <h2 class="text-4xl md:text-[44px] font-extrabold text-slate-900 tracking-tight leading-[1.2] mb-5">
                Mereka sudah membuktikannya.
            </h2>
        </div>

        <!-- 3 Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1 -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                <div class="text-pink-200 text-6xl font-serif leading-none mb-2">"</div>
                <p class="text-[15px] text-gray-500 leading-relaxed flex-1 mb-8">
                    Dalam 6 bulan belajar di 1Langkah, saya berhasil pindah karir dari accounting ke frontend dev. AI tutornya benar-benar membantu saya memahami konsep coding yang rumit.
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/150?u=aisyah" alt="Aisyah" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <div class="text-[15px] font-bold text-slate-900 leading-snug">Aisyah Putri</div>
                            <div class="text-[12px] text-gray-400">Frontend Developer · Tokopedia</div>
                        </div>
                    </div>
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                <div class="text-pink-200 text-6xl font-serif leading-none mb-2">"</div>
                <p class="text-[15px] text-gray-500 leading-relaxed flex-1 mb-8">
                    Kualitas kursus Data Science-nya setara dengan bootcamp mahal, tapi dengan harga yang jauh lebih terjangkau. Sertifikatnya langsung diakui saat saya interview.
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/150?u=dimas" alt="Dimas" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <div class="text-[15px] font-bold text-slate-900 leading-snug">Dimas Prasetyo</div>
                            <div class="text-[12px] text-gray-400">Data Scientist · Gojek</div>
                        </div>
                    </div>
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col hover:shadow-md transition-shadow">
                <div class="text-pink-200 text-6xl font-serif leading-none mb-2">"</div>
                <p class="text-[15px] text-gray-500 leading-relaxed flex-1 mb-8">
                    Mentor marketplace-nya luar biasa. Bisa sesi 1-on-1 langsung dengan senior designer dari perusahaan top. Portfolio saya makin kuat dan akhirnya dapat offer impian.
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="https://i.pravatar.cc/150?u=nadya" alt="Nadya" class="w-12 h-12 rounded-full object-cover bg-gray-100">
                        <div>
                            <div class="text-[15px] font-bold text-slate-900 leading-snug">Nadya Ramadhani</div>
                            <div class="text-[12px] text-gray-400">UI/UX Designer · Shopee</div>
                        </div>
                    </div>
                    <div class="flex text-amber-400">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA Section (Section 8) -->
<section id="cta" class="py-32 bg-[#0a0a0a] relative w-full overflow-hidden border-t border-white/5 flex flex-col items-center justify-center text-center">
    <!-- Subtle Red Glow Background -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[400px] bg-[#cc0000]/10 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="max-w-[1400px] mx-auto px-6 md:px-12 relative z-10 flex flex-col items-center">
        
        <!-- Subtitle -->
        <div class="text-[#ff3b30] text-[11px] font-bold tracking-[0.2em] uppercase mb-5">
            MULAI SEKARANG · GRATIS SELAMANYA
        </div>
        
        <!-- Title -->
        <h2 class="text-5xl md:text-[60px] font-extrabold text-white tracking-tight leading-[1.1] mb-6">
            Wujudkan karir impianmu<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF3333] to-[#cc0000]">mulai hari ini.</span>
        </h2>
        
        <!-- Description -->
        <p class="text-[17px] text-gray-400 leading-relaxed max-w-[540px] mx-auto mb-12">
            Bergabung dengan 100,000+ pelajar yang sudah membuktikan hasil nyata bersama 1Langkah. Tidak perlu kartu kredit.
        </p>
        
        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4 mb-16">
            <a href="{{ route('signup') }}" class="inline-flex items-center justify-center gap-2 px-9 py-4 bg-[#cc0000] hover:bg-[#aa0000] text-white font-bold rounded-2xl text-[16px] shadow-[0_0_40px_rgba(204,0,0,0.5)] hover:shadow-[0_0_50px_rgba(204,0,0,0.6)] transition-all">
                Daftar Gratis Sekarang
                <svg class="w-4 h-4 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-9 py-4 bg-[#111111] hover:bg-[#1a1a1a] border border-white/5 text-gray-300 hover:text-white font-bold rounded-2xl text-[16px] transition-all">
                Sudah punya akun
            </a>
        </div>
        
        <!-- Features / Checkmarks -->
        <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-4">
            <div class="flex items-center gap-2 text-[13px] text-gray-500 font-medium">
                <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Gratis untuk selamanya
            </div>
            <div class="flex items-center gap-2 text-[13px] text-gray-500 font-medium">
                <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Tanpa kartu kredit
            </div>
            <div class="flex items-center gap-2 text-[13px] text-gray-500 font-medium">
                <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Batalkan kapan saja
            </div>
            <div class="flex items-center gap-2 text-[13px] text-gray-500 font-medium">
                <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                300+ hiring partner
            </div>
        </div>
        
    </div>
</section>

<!-- Footer Section -->
<footer class="bg-[#070707] border-t border-white/5 pt-20 pb-10">
    <div class="max-w-[1400px] mx-auto px-6 md:px-12">
        
        <!-- Top Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 md:gap-8 mb-20">
            
            <!-- Brand Column -->
            <div class="md:col-span-5 lg:col-span-4">
                <!-- Logo -->
                <a href="{{ route('landing') }}" class="flex items-center mb-6">
                    <svg width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                
                <!-- Description -->
                <p class="text-[15px] text-gray-500 leading-relaxed max-w-[320px]">
                    AI-Powered Learning Experience Platform yang membantu jutaan pelajar Indonesia mencapai karir impian.
                </p>
            </div>
            
            <!-- Link Columns -->
            <div class="md:col-span-7 lg:col-span-8 grid grid-cols-2 md:grid-cols-3 gap-8">
                
                <!-- Column 1 -->
                <div>
                    <h3 class="text-[13px] font-bold tracking-[0.15em] text-gray-400 uppercase mb-6">PLATFORM</h3>
                    <ul class="flex flex-col gap-4">
                        <li><a href="{{ route('kursus') }}" class="text-[15px] text-gray-500 hover:text-white transition-colors">Kursus</a></li>
                        <li><a href="{{ route('online-bootcamp') }}" class="text-[15px] text-gray-500 hover:text-white transition-colors">Bootcamp Online</a></li>
                        <li><a href="{{ route('offline-bootcamp') }}" class="text-[15px] text-gray-500 hover:text-white transition-colors">Bootcamp Offline</a></li>
                        <li><a href="{{ route('mentor') }}" class="text-[15px] text-gray-500 hover:text-white transition-colors">Mentor</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Job Board</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Community</a></li>
                    </ul>
                </div>
                
                <!-- Column 2 -->
                <div>
                    <h3 class="text-[13px] font-bold tracking-[0.15em] text-gray-400 uppercase mb-6">COMPANY</h3>
                    <ul class="flex flex-col gap-4">
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Karir</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Press</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Partner</a></li>
                    </ul>
                </div>
                
                <!-- Column 3 -->
                <div>
                    <h3 class="text-[13px] font-bold tracking-[0.15em] text-gray-400 uppercase mb-6">SUPPORT</h3>
                    <ul class="flex flex-col gap-4">
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Pusat Bantuan</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-[15px] text-gray-500 hover:text-white transition-colors">Status Sistem</a></li>
                    </ul>
                </div>
                
            </div>
            
        </div>
        
        <!-- Bottom Bar -->
        <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-[13px] text-gray-600">
                &copy; {{ date('Y') }} 1Langkah Technologies. All rights reserved.
            </div>
            <div class="text-[13px] text-gray-600">
                AI-Powered Learning Experience Platform &middot; Made in Indonesia 🇮🇩
            </div>
        </div>
        
    </div>
</footer>
@endsection
