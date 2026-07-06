@extends('layouts.guest')

@section('title', 'Masuk — 1Langkah')

@section('body')
<!-- MOBILE VIEW (Shown only on small screens) -->
<div class="flex md:hidden h-[100dvh] w-full flex-col bg-gradient-to-br from-[#fff1f1] to-[#f7f8f9] overflow-hidden">
    <div class="w-full h-full overflow-y-auto flex flex-col px-5 py-4 mx-auto items-center">
        <div class="my-auto flex flex-col items-center justify-center w-full max-w-[420px] pb-4">
    <!-- Logo & Header -->
    <div class="text-center mb-6">
        <a href="{{ route('landing') }}" class="inline-block mb-4">
            <svg width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_306_8219)">
                    <path d="M22.3789 27.1026H16.3237V7.52808H22.3789V27.1026Z" fill="#D10000"/>
                    <path d="M22.3746 7.57027C22.374 7.57183 22.3735 7.57359 22.373 7.57546C22.3719 7.57922 22.3705 7.58367 22.369 7.58868C22.366 7.59876 22.3622 7.61144 22.3576 7.62646C22.3484 7.65648 22.3362 7.69629 22.3206 7.7453C22.2894 7.84337 22.2451 7.9786 22.1872 8.14667C22.0716 8.48256 21.9009 8.95166 21.6697 9.51938C21.2088 10.651 20.4993 12.1959 19.4946 13.8706C17.5141 17.1718 14.2201 21.2532 9.16017 23.4218L6.7749 17.8562C10.1921 16.3917 12.6499 13.5095 14.302 10.7556C15.1136 9.40283 15.6905 8.14735 16.0619 7.23532C16.2468 6.78124 16.3787 6.41755 16.4618 6.17631C16.5032 6.05587 16.5322 5.96636 16.5496 5.91188C16.5582 5.8847 16.5639 5.86625 16.5667 5.8571L16.5681 5.85254C16.568 5.85317 16.5678 5.85399 16.5675 5.8549C16.5674 5.85534 16.5668 5.8568 16.5667 5.8571C16.5665 5.85753 16.5667 5.85723 16.9297 5.96429L22.0125 7.45993C22.3708 7.56565 22.3753 7.56754 22.3752 7.56806C22.375 7.56844 22.3748 7.56949 22.3746 7.57027Z" fill="#E50000"/>
                    <path d="M29.6583 7.91772V23.195H36.7659V26.9803H25.8457V7.91772H29.6583Z" fill="#0f172a"/>
                    <path d="M48.8093 15.2705H51.4236V27.0076H48.5371V25.8638C47.4477 26.7898 46.0317 27.3343 44.5067 27.3343C41.0482 27.3343 38.2705 24.5567 38.2705 21.1254C38.2705 17.6669 41.0482 14.9165 44.5067 14.9165C46.0317 14.9165 47.4477 15.4611 48.5371 16.387L48.8093 15.2705ZM47.0937 23.3857C47.6929 22.7594 47.9924 21.9696 47.9924 21.1254C47.9924 20.2812 47.6929 19.4643 47.0937 18.8652C46.5219 18.266 45.7593 17.9392 44.9424 17.9392C44.1255 17.9392 43.3629 18.266 42.7638 18.8652C42.1919 19.4643 41.8651 20.2812 41.8651 21.1254C41.8651 21.9696 42.1919 22.7594 42.7638 23.3857C43.3629 23.9848 44.1255 24.3116 44.9424 24.3116C45.7593 24.3116 46.5219 23.9848 47.0937 23.3857Z" fill="#0f172a"/>
                    <path d="M55.2833 15.2704L55.828 16.3052C56.7539 15.4883 57.9793 14.9436 59.1775 14.9436C62.3365 14.9436 64.9235 17.7213 64.9235 21.1798V27.0075H61.6012V21.1798C61.6012 19.4914 60.4029 17.9936 58.7691 17.9936C57.1351 17.9936 55.9369 19.4914 55.9369 21.1798V27.0075H52.5056V15.2704H55.2833Z" fill="#0f172a"/>
                    <path d="M78.5433 16.4688L76.5554 17.1223C77.3996 18.021 77.917 19.2192 77.917 20.5263C77.917 23.4674 75.33 25.8366 72.1165 25.8366C71.6808 25.8366 71.2723 25.7821 70.8639 25.7005C70.6188 26.1362 70.3737 26.6263 70.1831 27.1166C70.8095 27.0348 71.4902 26.9804 72.2527 26.9804C74.4313 26.9804 76.038 27.3344 77.1817 28.0425C78.38 28.805 79.0336 29.976 79.0336 31.3376C79.0336 32.7536 78.4344 33.8702 77.2635 34.6326C76.1469 35.3407 74.513 35.6947 72.2527 35.6947C69.9924 35.6947 68.3585 35.3407 67.242 34.6326C66.071 33.8702 65.4719 32.7536 65.4719 31.3376C65.4719 30.7929 65.5808 30.2755 65.7987 29.7853C66.3433 27.9608 67.596 25.9456 68.4947 24.6657C67.2148 23.6853 66.3706 22.1875 66.3706 20.5263C66.3706 17.5853 68.9576 15.216 72.1165 15.216C72.3889 15.216 72.6612 15.2433 72.9335 15.2705L78.5433 14.2085V16.4688ZM72.1165 18.2661C70.7277 18.2661 69.6657 19.3553 69.6657 20.5263C69.6657 21.8607 70.8911 22.7866 72.1165 22.7866C73.5327 22.7866 74.5947 21.6973 74.5947 20.5263C74.5947 19.2464 73.4781 18.2661 72.1165 18.2661ZM72.2527 32.5903C74.5947 32.5903 75.6839 32.2089 75.6839 31.3376C75.6839 30.5205 74.2952 30.0849 72.2527 30.0849C70.1013 30.0849 68.8214 30.6023 68.8214 31.3376C68.8214 32.2089 70.0742 32.5903 72.2527 32.5903Z" fill="#0f172a"/>
                    <path d="M92.57 27.0076H88.104L84.1827 22.242L83.3929 23.0589V27.0076H79.5803V6.74683H83.3929V19.0558L86.8786 15.2705H91.535L86.7697 20.1724L92.57 27.0076Z" fill="#0f172a"/>
                    <path d="M102.028 15.2705H104.643V27.0076H101.756V25.8638C100.667 26.7898 99.2509 27.3343 97.7254 27.3343C94.2667 27.3343 91.4893 24.5567 91.4893 21.1254C91.4893 17.6669 94.2667 14.9165 97.7254 14.9165C99.2509 14.9165 100.667 15.4611 101.756 16.387L102.028 15.2705ZM100.313 23.3857C100.911 22.7594 101.211 21.9696 101.211 21.1254C101.211 20.2812 100.911 19.4643 100.313 18.8652C99.7405 18.266 98.9782 17.9392 98.161 17.9392C97.3447 17.9392 96.5815 18.266 95.983 18.8652C95.4106 19.4643 95.0839 20.2812 95.0839 21.1254C95.0839 21.9696 95.4106 22.7594 95.983 23.3857C96.5815 23.9848 97.3447 24.3116 98.161 24.3116C98.9782 24.3116 99.7405 23.9848 100.313 23.3857Z" fill="#0f172a"/>
                    <path d="M109.346 6.74683V16.0058C110.354 15.325 111.552 14.9165 112.86 14.9165C116.291 14.9165 119.068 17.7214 119.068 21.1527V27.0076H115.473V21.1527C115.473 20.3085 115.147 19.5188 114.575 18.8924C114.003 18.2933 113.241 17.9666 112.423 17.9666C111.607 17.9666 110.817 18.2933 110.245 18.8924C109.673 19.5188 109.346 20.3085 109.346 21.1527V27.0076H105.725V6.74683H109.346Z" fill="#0f172a"/>
                </g>
            </svg>
        </a>
        <h2 class="text-[24px] font-extrabold text-[#111827] mb-2 tracking-tight">Selamat datang kembali 👋</h2>
        <p class="text-[14px] text-gray-500">Masuk ke akun 1Langkah-mu</p>
    </div>

    <!-- Login Form Area -->
    <div class="w-full bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
        
        <!-- Form -->
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-3 mb-5 text-[13px] text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Email Input -->
            <div class="mb-4">
                <label class="block text-[13px] font-bold text-[#374151] mb-1.5">Email</label>
                <input type="email" name="email" required placeholder="email@example.com" class="w-full px-5 py-2.5 bg-[#f9fafb] border border-gray-200 rounded-xl text-[14px] text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-[#cc0000] transition-all placeholder:text-gray-400">
            </div>

            <!-- Password Input -->
            <div class="mb-5">
                <label class="block text-[13px] font-bold text-[#374151] mb-1.5">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-5 py-2.5 bg-[#f9fafb] border border-gray-200 rounded-xl text-[20px] text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-[#cc0000] transition-all placeholder:text-gray-300 placeholder:tracking-[0.2em] placeholder:text-[16px] tracking-widest">
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#cc0000] focus:ring-[#cc0000]">
                    <span class="text-[13px] font-medium text-[#4b5563]">Ingat saya</span>
                </label>
                <a href="#" class="text-[13px] font-bold text-[#cc0000] hover:text-[#aa0000] transition-colors">
                    Lupa password?
                </a>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 bg-[#d10000] hover:bg-[#aa0000] text-white font-bold rounded-xl text-[15px] transition-colors mb-5 flex items-center justify-center">
                Masuk
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center gap-4 mb-5">
            <div class="flex-1 h-px bg-gray-100"></div>
            <div class="text-[12px] font-medium text-gray-400">atau</div>
            <div class="flex-1 h-px bg-gray-100"></div>
        </div>

        <!-- Google Sign In -->
        <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors mb-5">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            <span class="text-[13px] font-bold text-[#374151]">Masuk dengan Google</span>
        </button>

        <!-- Signup Link -->
        <div class="text-center text-[13px] text-gray-500 font-medium">
            Belum punya akun? <a href="{{ route('signup') }}" class="text-[#cc0000] font-bold hover:underline">Daftar gratis</a>
        </div>
        
    </div>
    </div>
    </div>
</div>

<!-- DESKTOP & TABLET VIEW (Shown on md and up) -->
<div class="hidden md:flex min-h-screen lg:h-screen w-full bg-[#f7f8f9] lg:overflow-hidden">
    
    <!-- Left Column (Dark Theme) -->
    <div class="hidden md:flex flex-col w-[50%] bg-[#080202] relative p-10 lg:p-14 overflow-hidden border-r border-white/5">
        
        <!-- Subtle Red Glow -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-[#990000]/20 blur-[130px] rounded-full pointer-events-none -translate-y-1/4 translate-x-1/4"></div>
        
        <!-- Logo -->
        <a href="{{ route('landing') }}" class="relative z-10 inline-block mb-12">
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

        <!-- Content Area -->
        <div class="relative z-10 flex-1 flex flex-col justify-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-[#5c1313] bg-[#3a0808] w-fit mb-6">
                <svg class="w-3.5 h-3.5 text-[#FF7070]" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_306_7921)"><path d="M4.96847 7.74988C4.92383 7.57684 4.83364 7.41893 4.70728 7.29257C4.58092 7.16621 4.42301 7.07602 4.24997 7.03138L1.18247 6.24038C1.13014 6.22553 1.08407 6.19401 1.05128 6.1506C1.01848 6.1072 1.00073 6.05428 1.00073 5.99988C1.00073 5.94548 1.01848 5.89256 1.05128 5.84916C1.08407 5.80575 1.13014 5.77423 1.18247 5.75938L4.24997 4.96788C4.42294 4.92328 4.58082 4.83317 4.70717 4.7069C4.83353 4.58063 4.92375 4.42282 4.96847 4.24988L5.75947 1.18238C5.77417 1.12984 5.80566 1.08355 5.84913 1.05058C5.8926 1.0176 5.94566 0.999756 6.00022 0.999756C6.05478 0.999756 6.10784 1.0176 6.15131 1.05058C6.19478 1.08355 6.22627 1.12984 6.24097 1.18238L7.03147 4.24988C7.07611 4.42292 7.1663 4.58083 7.29266 4.70719C7.41902 4.83355 7.57693 4.92374 7.74997 4.96838L10.8175 5.75888C10.8702 5.77343 10.9167 5.80488 10.9499 5.84842C10.983 5.89195 11.001 5.94516 11.001 5.99988C11.001 6.0546 10.983 6.10781 10.9499 6.15134C10.9167 6.19488 10.8702 6.22633 10.8175 6.24088L7.74997 7.03138C7.57693 7.07602 7.41902 7.16621 7.29266 7.29257C7.1663 7.41893 7.07611 7.57684 7.03147 7.74988L6.24047 10.8174C6.22577 10.8699 6.19428 10.9162 6.15081 10.9492C6.10734 10.9822 6.05428 11 5.99972 11C5.94516 11 5.8921 10.9822 5.84863 10.9492C5.80516 10.9162 5.77367 10.8699 5.75897 10.8174L4.96847 7.74988Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 1.5V3.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 2.5H9" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 8.5V9.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/><path d="M2.5 9H1.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_306_7921"><rect width="12" height="12" fill="white"/></clipPath></defs></svg>
                <span class="text-[10px] sm:text-xs font-bold tracking-[0.15em] text-[#FF7070] uppercase">AI-POWERED LEARNING EXPERIENCE PLATFORM</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl lg:text-[46px] font-extrabold text-white leading-[1.15] tracking-tight mb-5 max-w-lg">
                Satu langkah<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#FF3333] to-[#cc0000]">mengubah karir</span><br>
                kamu selamanya.
            </h1>

            <!-- Desc -->
            <p class="text-[15px] text-gray-400 leading-relaxed max-w-[420px] mb-12">
                Bergabung dengan 100,000+ pelajar yang sudah membuktikan hasilnya bersama 1Langkah.
            </p>

            <!-- Testimonials Stack -->
            <div class="flex flex-col gap-3 mb-12 max-w-[440px]">
                <!-- Card 1 -->
                <div class="flex items-center justify-between bg-[#1a0a0a] border border-[#2a1313] rounded-2xl p-3.5">
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?img=1" class="w-10 h-10 rounded-full object-cover">
                        <div class="text-[13px] font-medium text-gray-400">Pindah karir ke tech dalam 6 bulan</div>
                    </div>
                    <div class="flex items-center gap-0.5 text-[#ffb800]">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="flex items-center justify-between bg-[#1a0a0a] border border-[#2a1313] rounded-2xl p-3.5">
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?img=11" class="w-10 h-10 rounded-full object-cover">
                        <div class="text-[13px] font-medium text-gray-400">Diterima jadi Data Scientist di Gojek</div>
                    </div>
                    <div class="flex items-center gap-0.5 text-[#ffb800]">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="flex items-center justify-between bg-[#1a0a0a] border border-[#2a1313] rounded-2xl p-3.5">
                    <div class="flex items-center gap-4">
                        <img src="https://i.pravatar.cc/100?img=5" class="w-10 h-10 rounded-full object-cover">
                        <div class="text-[13px] font-medium text-gray-400">Portfolio dilirik 3 perusahaan top</div>
                    </div>
                    <div class="flex items-center gap-0.5 text-[#ffb800]">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.431 8.2 1.192-5.934 5.787 1.4 8.168L12 18.896l-7.334 3.857 1.4-8.168L.132 9.21l8.2-1.192z"/></svg>
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="flex items-center gap-10">
                <div>
                    <div class="text-[22px] font-extrabold text-white mb-0.5">100K+</div>
                    <div class="text-[12px] text-gray-500 font-medium">Pelajar</div>
                </div>
                <div>
                    <div class="text-[22px] font-extrabold text-white mb-0.5">800+</div>
                    <div class="text-[12px] text-gray-500 font-medium">Kursus</div>
                </div>
                <div>
                    <div class="text-[22px] font-extrabold text-white mb-0.5">95%</div>
                    <div class="text-[12px] text-gray-500 font-medium">Completion</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column (Login Form) -->
    <div class="w-full md:w-[50%] flex items-center justify-center p-6 md:p-10 relative lg:overflow-y-auto lg:h-full">
        
        <!-- Login Card -->
        <div class="w-full max-w-[480px] bg-white rounded-[32px] p-10 shadow-[0_24px_60px_rgba(0,0,0,0.06)] border border-gray-100">
            
            <!-- Header -->
            <h2 class="text-[28px] font-extrabold text-[#111827] mb-2 tracking-tight">Selamat datang kembali</h2>
            <p class="text-[15px] text-gray-500 leading-relaxed mb-8">
                Masuk ke akun 1Langkah-mu dan lanjutkan perjalanan belajar.
            </p>

            <!-- Google Sign In -->
            <button type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3.5 bg-white border border-gray-200 rounded-full hover:bg-gray-50 transition-colors mb-7 shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="text-[14px] font-bold text-[#374151]">Masuk dengan Google</span>
            </button>

            <!-- Divider -->
            <div class="flex items-center gap-4 mb-7">
                <div class="flex-1 h-px bg-gray-100"></div>
                <div class="text-[12px] font-medium text-gray-400">atau dengan email</div>
                <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-3 mb-5 text-[13px] text-red-600">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Email Input -->
                <div class="mb-5">
                    <label class="block text-[11px] font-bold text-[#6b7280] uppercase tracking-wider mb-2">EMAIL</label>
                    <input type="email" name="email" required placeholder="email@example.com" class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-full text-[14px] text-gray-900 focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-[#cc0000] transition-all placeholder:text-gray-400 font-medium">
                </div>

                <!-- Password Input -->
                <div class="mb-6">
                    <label class="block text-[11px] font-bold text-[#6b7280] uppercase tracking-wider mb-2">PASSWORD</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-5 py-3.5 bg-white border border-gray-200 rounded-full text-[16px] text-gray-900 focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-[#cc0000] transition-all placeholder:text-gray-300 font-sans tracking-[0.2em]">
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-8">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <div class="relative flex items-center justify-center w-[18px] h-[18px] rounded-[5px] border-2 border-gray-300 bg-white group-hover:border-[#cc0000] transition-colors">
                            <input type="checkbox" name="remember" class="absolute opacity-0 w-full h-full cursor-pointer peer">
                            <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            <div class="absolute inset-0 rounded-[3px] bg-[#cc0000] border-[#cc0000] opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                        </div>
                        <span class="text-[14px] font-medium text-[#4b5563] select-none">Ingat saya</span>
                    </label>
                    <a href="#" class="text-[14px] font-bold text-[#cc0000] hover:text-[#aa0000] transition-colors">
                        Lupa password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-4 bg-[#cc0000] hover:bg-[#aa0000] text-white font-bold rounded-full text-[15px] shadow-[0_12px_30px_rgba(204,0,0,0.35)] hover:shadow-[0_15px_35px_rgba(204,0,0,0.45)] transition-all mb-8 flex items-center justify-center">
                    Masuk ke Dashboard
                </button>
            </form>

            <!-- Signup Link -->
            <div class="text-center text-[13px] text-gray-500 font-medium">
                Belum punya akun? <a href="{{ route('signup') }}" class="text-[#cc0000] font-bold hover:underline">Daftar gratis</a>
            </div>
            
        </div>
    </div>
    
</div>
@endsection
