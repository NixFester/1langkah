@extends('layouts.app', ['activePage' => 'mentor'])

@section('title', $mentor['name'] . ' — 1Langkah')
@section('header_title', 'Profil Mentor')

@section('content')
@php
    $m = $mentor;
    $priceNumber = str_replace('/sesi', '', $m['price']);
    
    // Avatar Logic
    $firstName = explode(' ', $m['name'])[0];
    $isWoman = in_array($firstName, ['Siti', 'Dewi', 'Sari', 'Rina']);
    $genderPath = $isWoman ? 'women' : 'men';
    $picId = ($m['id'] % 70) + 1;
    $avatarUrl = "https://randomuser.me/api/portraits/{$genderPath}/{$picId}.jpg";
    
    // Simulate online
    $isOnline = $m['id'] % 2 !== 0;
@endphp

<div class="w-full px-2 pb-12">
    <!-- Back Navigation -->
    <a href="{{ route('mentor') }}" class="inline-flex items-center gap-2 text-[14px] font-medium text-gray-500 hover:text-gray-900 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        Kembali ke Mentor Marketplace
    </a>

    <!-- Top Banner (Red Hero) -->
    <div class="w-full bg-gradient-to-r from-[#b90000] to-[#800000] rounded-[24px] p-8 md:p-10 mb-8 flex flex-col md:flex-row items-center md:items-start md:justify-between gap-8 shadow-md relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute right-0 top-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row items-center md:items-center gap-6 relative z-10 w-full md:w-auto text-center md:text-left">
            <!-- Avatar -->
            <div class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-white p-1.5 shadow-lg flex-shrink-0">
                <img src="{{ $avatarUrl }}" alt="{{ $m['name'] }}" class="w-full h-full rounded-full object-cover">
            </div>
            
            <!-- Mentor Info -->
            <div class="text-white">
                <div class="flex flex-col md:flex-row items-center gap-3 mb-1.5 justify-center md:justify-start">
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ $m['name'] }}</h1>
                    <span class="px-3 py-1 bg-[#00e676] text-[#004d40] text-[11px] font-bold rounded-full shadow-sm">Available</span>
                </div>
                <div class="text-red-100 text-[15px] mb-4">
                    {{ $m['role'] }}<br>
                    <span class="font-bold text-white">{{ $m['company'] }}</span>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-3 text-[13px] text-white/90 font-medium">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        <span class="font-bold text-white">{{ number_format((float) ($m['rating'] ?? 0), 1) }}</span>
                        <span class="opacity-80">({{ $m['sessions'] }} ulasan)</span>
                    </div>
                    <span class="opacity-50">&middot;</span>
                    <span>{{ $m['sessions'] }} sesi selesai</span>
                </div>
            </div>
        </div>
        
        <!-- Pricing Header -->
        <div class="relative z-10 text-center md:text-right mt-4 md:mt-0 pt-4 md:pt-4">
            <div class="text-red-200 text-[12px] font-medium mb-0.5">mulai dari</div>
            <div class="text-white text-3xl md:text-4xl font-extrabold tracking-tight mb-1">{{ $priceNumber }}</div>
            <div class="text-red-200 text-[12px] font-medium">per sesi (60 menit)</div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column -->
        <div class="lg:col-span-8 flex flex-col gap-6">
            <!-- Tentang Mentor -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-5">Tentang Mentor</h3>
                <div class="text-[15px] text-gray-600 leading-relaxed space-y-4">
                    <p>{{ $m['name'] }} adalah {{ $m['role'] }} di {{ $m['company'] }} dengan pengalaman membangun produk digital skala besar. Bergabung dengan 1Langkah sebagai mentor sejak 2023 dan telah membantu ratusan learner mencapai karir impian mereka di industri teknologi.</p>
                    <p>{{ $m['bio'] ?? '' }} {{ $firstName }} percaya bahwa kunci sukses dalam karir tech adalah memahami "mengapa" di balik setiap teknologi, bukan sekadar "bagaimana" menggunakannya.</p>
                </div>
            </div>

            <!-- LinkedIn Profile -->
            @if(!empty($m['linkedin_url']))
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-5">LinkedIn Profile</h3>
                <div class="rounded-xl overflow-hidden border border-gray-200">
                    <iframe
                        src="https://www.linkedin.com/embed/feed/update/{{ $m['linkedin_url'] }}"
                        height="400"
                        frameborder="0"
                        allowfullscreen=""
                        title="LinkedIn Profile"
                        class="w-full">
                    </iframe>
                </div>
                <a href="{{ $m['linkedin_url'] }}" target="_blank" class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-700 text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    Lihat Profile di LinkedIn
                </a>
            </div>
            @endif

            <!-- Bidang Keahlian -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-5">Bidang Keahlian</h3>
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($m['expertise'] as $skill)
                        <span class="px-4 py-1.5 bg-red-50 border border-red-100 text-[#dc2626] text-[13px] font-bold rounded-full">{{ $skill }}</span>
                    @endforeach
                </div>
                
                <!-- Stats Row -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-gray-50 rounded-2xl p-4 text-center flex flex-col justify-center">
                        <div class="text-xl font-black text-red-700 mb-1">{{ $m['sessions'] }}+</div>
                        <div class="text-[11px] text-gray-500 font-medium">Total Sesi</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4 text-center flex flex-col justify-center">
                        <div class="text-xl font-black text-red-700 mb-1">{{ number_format((float) ($m['rating'] ?? 0), 1) }}/5</div>
                        <div class="text-[11px] text-gray-500 font-medium">Rating</div>
                    </div>
                    <div class="bg-gray-50 rounded-2xl p-4 text-center flex flex-col justify-center">
                        <div class="text-xl font-black text-red-700 mb-1">&lt; 2 jam</div>
                        <div class="text-[11px] text-gray-500 font-medium">Respon</div>
                    </div>
                </div>
            </div>

            <!-- Ulasan Learner -->
            <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Ulasan Learner</h3>
                
                <div class="space-y-6">
                    <!-- Review 1 -->
                    <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#d00000] text-white flex items-center justify-center font-bold text-[13px]">AF</div>
                                <div>
                                    <div class="text-[14px] font-bold text-gray-900">Ahmad Fauzi</div>
                                    <div class="text-[11px] text-gray-400">12 Jun 2025</div>
                                </div>
                            </div>
                            <div class="flex text-yellow-400">
                                @for($i=0; $i<5; $i++) <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> @endfor
                            </div>
                        </div>
                        <p class="text-[14px] text-gray-600 leading-relaxed">Sangat membantu! Penjelasannya clear, contoh real-world, dan responsif banget. Highly recommended!</p>
                    </div>
                    
                    <!-- Review 2 -->
                    <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-600 text-white flex items-center justify-center font-bold text-[13px]">SR</div>
                                <div>
                                    <div class="text-[14px] font-bold text-gray-900">Siti Rahma</div>
                                    <div class="text-[11px] text-gray-400">3 Jun 2025</div>
                                </div>
                            </div>
                            <div class="flex text-yellow-400">
                                @for($i=0; $i<5; $i++) <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> @endfor
                            </div>
                        </div>
                        <p class="text-[14px] text-gray-600 leading-relaxed">{{ $firstName }} sabar banget dalam menjelaskan konsep yang sulit. Sesi pertama tapi langsung dapat banyak insight.</p>
                    </div>

                    <!-- Review 3 -->
                    <div class="pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-red-700 text-white flex items-center justify-center font-bold text-[13px]">DP</div>
                                <div>
                                    <div class="text-[14px] font-bold text-gray-900">Dito Pratama</div>
                                    <div class="text-[11px] text-gray-400">28 Mei 2025</div>
                                </div>
                            </div>
                            <div class="flex text-yellow-400">
                                @for($i=0; $i<4; $i++) <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> @endfor
                                <svg class="w-3.5 h-3.5 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            </div>
                        </div>
                        <p class="text-[14px] text-gray-600 leading-relaxed">Good session, banyak tips praktis untuk interview. Akan book lagi untuk sesi berikutnya.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Sticky Sidebar) -->
        <div class="lg:col-span-4">
            <div class="bg-white border border-gray-100 rounded-3xl p-6 lg:p-8 shadow-sm lg:sticky lg:top-24">
                <h3 class="text-[17px] font-bold text-gray-900 mb-5">Jadwal Tersedia</h3>
                
                <div class="space-y-3 mb-8">
                    <!-- Schedule Items -->
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div>
                            <div class="text-[14px] font-bold text-gray-900 mb-0.5">Senin</div>
                            <div class="text-[12px] text-gray-400">19.00 – 21.00 WIB</div>
                        </div>
                        <span class="px-3 py-1 bg-[#f0fdf4] text-[#16a34a] text-[11px] font-bold rounded-full">Tersedia</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div>
                            <div class="text-[14px] font-bold text-gray-900 mb-0.5">Rabu</div>
                            <div class="text-[12px] text-gray-400">19.00 – 21.00 WIB</div>
                        </div>
                        <span class="px-3 py-1 bg-[#f0fdf4] text-[#16a34a] text-[11px] font-bold rounded-full">Tersedia</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors">
                        <div>
                            <div class="text-[14px] font-bold text-gray-900 mb-0.5">Jumat</div>
                            <div class="text-[12px] text-gray-400">20.00 – 22.00 WIB</div>
                        </div>
                        <span class="px-3 py-1 bg-[#f0fdf4] text-[#16a34a] text-[11px] font-bold rounded-full">Tersedia</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-xl opacity-60">
                        <div>
                            <div class="text-[14px] font-bold text-gray-900 mb-0.5">Sabtu</div>
                            <div class="text-[12px] text-gray-400">10.00 – 12.00 WIB</div>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold rounded-full">Penuh</span>
                    </div>
                </div>

                <div class="space-y-3 mb-8">
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-gray-500">Durasi sesi</span>
                        <span class="font-bold text-gray-900">60 menit</span>
                    </div>
                    <div class="flex justify-between items-center text-[13px]">
                        <span class="text-gray-500">Via</span>
                        <span class="font-bold text-gray-900">Google Meet / Zoom</span>
                    </div>
                </div>

                <div class="text-center mb-6">
                    <div class="text-[28px] font-extrabold text-gray-900 tracking-tight mb-1">{{ $priceNumber }}</div>
                    <div class="text-[12px] text-gray-400 font-medium">per sesi</div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('pembayaran', ['id' => $m['id']]) }}" class="w-full bg-[#d00000] hover:bg-red-700 text-white font-bold py-3.5 rounded-full text-center transition-colors shadow-sm text-[15px]">
                        Book Sesi Sekarang
                    </a>
                    <button class="w-full bg-white border border-gray-200 text-gray-700 font-bold py-3.5 rounded-full text-center hover:bg-gray-50 transition-colors shadow-sm text-[15px]">
                        Kirim Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
