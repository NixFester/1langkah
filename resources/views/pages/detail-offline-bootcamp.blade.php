@extends('layouts.app', ['activePage' => 'offline-bootcamp'])

@section('title', $bootcamp['title'] . ' — 1Langkah')
@section('header_title', 'Offline Bootcamp')

@section('content')
@inject('catalog', 'App\Services\CatalogService')
@php
    $b = $bootcamp;
    $allBootcamps = $catalog->bootcamps()['offline'];
    $benefitsRaw = $b['benefits'] ?? null;
    $benefits = [];
    if (is_array($benefitsRaw)) {
        $benefits = $benefitsRaw;
    } elseif (is_string($benefitsRaw) && !empty(trim($benefitsRaw))) {
        $decoded = json_decode($benefitsRaw, true);
        $benefits = is_array($decoded) ? $decoded : [$benefitsRaw];
    }
    if (empty($benefits)) {
        $benefits = $catalog->offlineFeatures();
    }
    $jadwalKelas = $b['jadwal_kelas'] ?? [];
    $ticketCode = null;
    $userAttendanceRecords = collect();
    if (auth()->check() && $isEnrolled) {
        $ticketCode = \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('purchasable_type', \App\Models\Bootcamp::class)
            ->where('purchasable_id', $b['id'])
            ->value('ticket_code');

        // Get attendance records for this user and bootcamp
        $userAttendanceRecords = \App\Models\AttendanceRecord::where('user_id', auth()->id())
            ->where('bootcamp_id', $b['id'])
            ->orderBy('attendance_date', 'desc')
            ->get();
    }
@endphp

<div class="w-full px-2 pb-8">
    <!-- Header (Same as Offline Bootcamp) -->
    <div class="mb-8 -mt-2">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Offline Bootcamp</h1>
        <p class="text-gray-500 text-base">Belajar tatap muka intensif di kampus 1Langkah — pengalaman immersive yang tak tergantikan</p>
    </div>

    <!-- Alert / Info Banner -->
    <div class="bg-[#3e2723] rounded-2xl p-6 md:p-8 text-white mb-10 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-md">
        <div class="flex items-center gap-5 md:gap-6">
            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="text-[22px] font-bold mb-1.5 tracking-tight">Tatap Muka · Belajar Langsung di Kampus</h3>
                <p class="text-[#d7ccc8] text-[15px] leading-relaxed max-w-2xl font-medium">Fasilitas lengkap, networking nyata, dan pengalaman belajar intensif bersama instruktur & sesama peserta.</p>
            </div>
        </div>
        <div class="flex items-center gap-8 md:gap-10 lg:pr-6">
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">3 Kota</div>
                <div class="text-[13px] text-[#bcaaa4] font-medium mt-1">Tersedia</div>
            </div>
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">{{ $enrolledCount }}</div>
                <div class="text-[13px] text-[#bcaaa4] font-medium mt-1">Siswa</div>
            </div>
            <div class="text-center">
                <div class="text-[28px] font-extrabold leading-tight">Sertifikat</div>
                <div class="text-[13px] text-[#bcaaa4] font-medium mt-1">Terverifikasi</div>
            </div>
        </div>
    </div>

    <!-- Master-Detail Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Master List -->
        <div class="lg:col-span-4 flex flex-col gap-5">
            @foreach($allBootcamps as $item)
                @php
                    $isActive = $item['id'] == $b['id'];
                    // Get actual enrolled count from database
                    $itemEnrolledCount = \App\Models\Enrollment::where('purchasable_type', \App\Models\Bootcamp::class)
                        ->where('purchasable_id', $item['id'])
                        ->count();
                    $totalSlots = 20;
                    $sisa = max(0, $totalSlots - $itemEnrolledCount);
                    $percentage = $totalSlots > 0 ? (($totalSlots - $sisa) / $totalSlots) * 100 : 0;
                    $colorClass = $sisa > 5 ? 'bg-[#f59e0b]' : ($sisa > 2 ? 'bg-[#f59e0b]' : 'bg-red-500');
                    $textColor = $sisa > 5 ? 'text-[#f59e0b]' : ($sisa > 2 ? 'text-[#f59e0b]' : 'text-red-500');
                @endphp
                
                <a href="{{ route('detail-offline-bootcamp', ['id' => $item['id']]) }}" class="block bg-white rounded-2xl p-5 border {{ $isActive ? 'border-red-600 shadow-[0_0_0_1px_#e11d48,0_4px_12px_rgb(0,0,0,0.05)]' : 'border-gray-200 shadow-sm hover:border-gray-300' }} transition-all">
                    <!-- Top Badges -->
                    <div class="flex gap-2 mb-3">
                        <span class="px-2.5 py-0.5 {{ $isActive ? 'bg-[#d00000] text-white' : 'bg-red-50 text-[#d00000]' }} text-[11px] font-bold rounded-full">{{ $loop->first ? 'Paling Diminati' : ($loop->iteration == 2 ? 'Weekend Class' : 'Eksklusif') }}</span>
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-500 text-[11px] font-semibold rounded-full">{{ $loop->first ? 'All Level' : ($loop->iteration == 2 ? 'Beginner' : 'Intermediate') }}</span>
                    </div>
                    
                    <h3 class="text-[15px] font-bold text-gray-900 leading-snug mb-1">{{ $item['title'] }}</h3>
                    <p class="text-[12px] text-gray-500 mb-4">{{ $item['mentor'] }}</p>
                    
                    <div class="flex items-center gap-4 text-[11px] text-gray-500 font-medium mb-4">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $loop->first ? '8 Minggu' : ($loop->iteration == 2 ? '8 Minggu' : '10 Minggu') }}
                        </div>
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $loop->first ? '2× seminggu' : ($loop->iteration == 2 ? 'Sabtu & Minggu' : '3× seminggu') }}
                        </div>
                    </div>

                    <!-- Enrolled Count -->
                    <div class="mb-4">
                        <div class="flex items-center gap-2 text-[11px] font-medium text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.196A3 3 0 007 18v-2m.232-.172a3 3 0 014.232 2.196A3 3 0 0013.536 16M7 8a3 3 0 100-6 3 3 0 000 6z"></path></svg>
                            {{ $itemEnrolledCount }} siswa enrolled
                        </div>
                    </div>
                    
                    <div class="w-full h-px bg-gray-100 mb-3"></div>
                    
                    <div class="flex items-end justify-between">
                        <div>
                            <div class="text-[11px] font-medium text-gray-400 mb-0.5">Mulai {{ $item['startDate'] }}</div>
                            <div class="text-[15px] font-extrabold text-black">{{ $item['formatted_price'] ?? 'Gratis' }}</div>
                        </div>
                        <div>
                            @php
                                $badgeText = 'Soft Skills';
                                if ($loop->iteration == 2) $badgeText = 'Seni & Musik';
                                if ($loop->iteration == 3) $badgeText = 'Leadership';
                            @endphp
                            <span class="px-2.5 py-1 bg-red-50 text-red-600 text-[11px] font-bold rounded-full">{{ $badgeText }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Right Column: Detail Pane -->
        <div class="lg:col-span-8">
            <div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm lg:sticky lg:top-24">
                
                <!-- Hero Image -->
                <div class="relative w-full h-[280px] bg-gray-900">
                    @if(!empty($b['thumbnail']))
                        <img src="{{ $b['thumbnail'] }}" alt="{{ $b['title'] }}" class="w-full h-full object-cover opacity-60">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg,{{ $b['color'] }},{{ $b['color'] }}cc)"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent pointer-events-none"></div>
                    
                    <!-- Close button -->
                    <a href="{{ route('offline-bootcamp') }}" class="absolute top-4 right-4 w-8 h-8 bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-black/60 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                    
                    <!-- Title Overlay -->
                    <div class="absolute bottom-6 left-8 right-8 text-white">
                        <h1 class="text-2xl font-extrabold mb-2 text-white">{{ $b['title'] }}</h1>
                        <div class="flex items-center gap-4 text-[13px] font-medium text-gray-200">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ explode(',', $b['location'])[0] }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Mulai {{ $b['startDate'] }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Content Body -->
                <div class="p-8">
                    <!-- Location Detailed -->
                    <div class="flex items-center gap-3 text-gray-500 mb-8">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="text-[14px] font-medium">Gedung 1Langkah Hub, {{ $b['location'] }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-4 pb-8 border-b border-gray-100 mb-8">
                        @if(!empty($isEnrolled))
                            <a href="{{ route('bootcamps-saya') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-full text-center transition-colors shadow-sm">
                                Sudah Terdaftar — Lihat Bootcamp Saya
                            </a>
                        @else
                            <a href="{{ route('pembayaran', ['id' => $b['id']]) }}" class="flex-1 bg-[#d00000] hover:bg-red-700 text-white font-bold py-3.5 px-6 rounded-full text-center transition-colors shadow-sm">
                                Daftar Sekarang — {{ $b['formatted_price'] ?? 'Gratis' }}
                            </a>
                        @endif
                        <button class="px-8 py-3.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-full hover:bg-gray-50 transition-colors shadow-sm">
                            Simpan
                        </button>
                    </div>

                    @if(!empty($ticketCode))
                    <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 p-5">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-red-700">Tiket Offline Bootcamp</p>
                                <h3 class="text-lg font-bold text-gray-900 mt-1">Tunjukkan tiket ini saat masuk</h3>
                                <p class="text-sm text-gray-600 mt-2">Admin dapat memindai kode ini melalui halaman scan untuk memastikan kamu adalah pemegang tiket yang sah.</p>
                            </div>
                            <div class="rounded-2xl bg-white p-3 border border-red-100 shadow-sm">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($ticketCode) }}" alt="Ticket QR" class="w-40 h-40 object-contain">
                            </div>
                        </div>
                        <div class="mt-4 rounded-xl border border-red-100 bg-white/80 p-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">Kode tiket</p>
                            <p class="mt-2 font-mono text-2xl font-bold tracking-[0.35em] text-gray-900">{{ $ticketCode }}</p>
                        </div>
                    </div>
                    @endif

                    {{-- Attendance Codes Section (for enrolled students) --}}
                    @if($isEnrolled && $userAttendanceRecords->count() > 0)
                    <div class="mb-8 rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-[17px] font-bold text-gray-900">Kode Absensi Sesi</h3>
                        </div>
                        <p class="text-sm text-gray-600 mb-4">Kode ini digunakan untuk mencatat kehadiran kamu di setiap sesi. Mentor akan memberikan instruksi cara menggunakan kode.</p>

                        <div class="space-y-3">
                            @foreach($userAttendanceRecords as $record)
                            <div class="bg-white rounded-xl p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg {{ $record->verified ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }} flex items-center justify-center">
                                        @if($record->verified)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-500">
                                            @if($record->verified)
                                                <span class="text-emerald-600">Hadir</span>
                                            @else
                                                <span class="text-amber-600">Belum absen</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider">Kode</p>
                                    <p class="font-mono text-xl font-bold tracking-widest {{ $record->verified ? 'text-emerald-600' : 'text-gray-900' }}">{{ $record->short_code ?? '—' }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif($isEnrolled)
                    <div class="mb-8 rounded-2xl border border-gray-200 bg-gray-50 p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="text-[17px] font-bold text-gray-900">Kode Absensi Belum Tersedia</h3>
                        </div>
                        <p class="text-sm text-gray-600">Mentor akan memberikan kode absensi sebelum sesi dimulai. Pastikan untuk selalu mengecek halaman ini sebelum kelas.</p>
                    </div>
                    @endif

                    <!-- Jadwal & Fasilitas Layout -->
                    <div class="flex flex-col md:flex-row gap-10">
                        <!-- Jadwal Kelas -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <h3 class="text-[17px] font-bold text-gray-900">Jadwal Kelas</h3>
                            </div>
                            <div class="flex flex-col gap-3">
                                <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                                    <div class="text-[13px] font-bold text-gray-900 w-12">Selasa</div>
                                    <div class="text-[12px] text-gray-500 font-medium flex items-center gap-1.5 border-l border-gray-200 pl-4 w-32">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        18.00–20.00 WIB
                                    </div>
                                    <div class="text-[13px] font-medium text-gray-700 flex-1">Vocal & Body Language</div>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-4">
                                    <div class="text-[13px] font-bold text-gray-900 w-12">Kamis</div>
                                    <div class="text-[12px] text-gray-500 font-medium flex items-center gap-1.5 border-l border-gray-200 pl-4 w-32">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        18.00–20.00 WIB
                                    </div>
                                    <div class="text-[13px] font-medium text-gray-700 flex-1">Praktik & Feedback Langsung</div>
                                </div>
                            </div>
                        </div>

                        <!-- Yang Didapatkan -->
                        <div class="flex-1">
                            <h3 class="text-[17px] font-bold text-gray-900 mb-4 pt-1">Yang Didapatkan</h3>
                            <div class="flex flex-col gap-3">
                                @foreach($benefits as $f)
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <span class="text-[14px] text-gray-600 font-medium">{{ $f }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Kelas Section -->
                    @if(is_array($jadwalKelas) || is_object($jadwalKelas))
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h3 class="text-[17px] font-bold text-gray-900 mb-4">Detail Jadwal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($jadwalKelas as $jadwal)
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold">
                                        {{ substr($jadwal['hari'] ?? 'H', 0, 3) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $jadwal['hari'] ?? 'Hari' }}</p>
                                        <p class="text-sm text-gray-500">{{ $jadwal['waktu'] ?? '00:00' }} WIB</p>
                                        <p class="text-sm text-gray-600">{{ $jadwal['topik'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
