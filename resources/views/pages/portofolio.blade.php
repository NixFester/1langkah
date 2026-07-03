@extends('layouts.app')

@section('title', 'Portofolio Saya')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Portofolio Saya</h1>
            <p class="text-sm text-gray-500">Skills dan pencapaian dari kursus & bootcamp</p>
        </div>
        <div class="flex gap-3">
            <button onclick="sharePortfolio()" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                Bagikan
            </button>
            <a href="{{ route('scan-qr') }}" class="bg-[#cc0000] hover:bg-red-700 text-white px-4 py-2 rounded-full text-sm font-medium flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                Scan QR Hadir
            </a>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-gradient-to-r from-[#cc0000] to-red-700 rounded-3xl p-6 md:p-8 text-white relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <div class="w-24 h-24 rounded-full bg-white/20 p-1">
                <div class="w-full h-full rounded-full bg-white/30 flex items-center justify-center text-3xl font-bold">
                    {{ $portfolio['user']['initials'] ?? 'ME' }}
                </div>
            </div>
            <div class="text-center md:text-left flex-1">
                <h2 class="text-2xl font-bold">{{ $portfolio['user']['name'] ?? 'User' }}</h2>
                <p class="text-white/80 text-sm mt-1 max-w-lg">
                    {{ $portfolio['user']['bio'] ?: 'Belum ada bio. Tambahkan di pengaturan.' }}
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-1.5 text-sm">
                        <span class="font-bold">{{ $portfolio['user']['xp'] ?? 0 }}</span> XP
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-1.5 text-sm">
                        Bergabung {{ $portfolio['user']['joined_at'] ?? '' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-[#cc0000]">{{ $portfolio['stats']['courses_completed'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Kursus Selesai</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-green-600">{{ $portfolio['stats']['bootcamps_completed'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Bootcamp Selesai</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-purple-600">{{ $portfolio['stats']['skills_acquired'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Skills</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-3xl font-bold text-orange-500">{{ $portfolio['stats']['reviews_written'] ?? 0 }}</div>
            <div class="text-sm text-gray-500 mt-1">Reviews</div>
        </div>
    </div>

    <!-- Achievements -->
    @if(!empty($portfolio['achievements']))
    <div class="bg-white rounded-xl p-5 border border-gray-200 mb-6">
        <h3 class="font-bold text-slate-800 text-[15px] mb-4">Achievements</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($portfolio['achievements'] as $achievement)
            <div class="bg-white border border-gray-200 rounded-lg px-3.5 py-2.5 flex items-center gap-3 w-[220px]">
                <div class="w-9 h-9 bg-slate-50 rounded-full flex items-center justify-center text-lg flex-shrink-0">
                    {{ $achievement['icon'] }}
                </div>
                <div class="min-w-0">
                    <div class="text-[12px] font-bold text-slate-700 truncate">{{ $achievement['name'] }}</div>
                    <div class="text-[10px] text-slate-400 mt-0.5 truncate" title="{{ $achievement['desc'] }}">{{ $achievement['desc'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Skills Section -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
        <h3 class="font-bold text-gray-900 text-[16px] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            Skills yang Dikuasai
        </h3>
        @if(!empty($portfolio['skills']))
        <div class="flex flex-wrap gap-3">
            @foreach($portfolio['skills'] as $skill)
            <div class="bg-white border border-gray-200 rounded-full pl-4 pr-1.5 py-1.5 flex items-center gap-3">
                <span class="text-[13px] font-bold text-gray-800">{{ $skill['name'] }}</span>
                @if($skill['rating'] > 0)
                <span class="border border-gray-100 text-gray-400 text-[11px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1 bg-gray-50/50">
                    {{ number_format((float) ($skill['rating'] ?? 0), 1) }}
                </span>
                @else
                <span class="border border-gray-100 bg-gray-50/50 w-8 h-5 rounded-full block"></span>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-sm">Belum ada skills.</p>
        @endif
    </div>

    <!-- Courses Completed (Sorted by Rating) -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
        <h3 class="font-bold text-gray-900 text-[16px] mb-4">Kursus yang Telah Diselesaikan</h3>
        @if(!empty($portfolio['courses']))
        <div class="space-y-3">
            @foreach($portfolio['courses'] as $course)
            <div class="flex gap-4 items-center p-3 bg-gray-50/80 rounded-[14px]">
                <div class="w-14 h-14 rounded-xl bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($course['thumbnail'])
                    <img src="{{ $course['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-800 text-[14px] truncate">{{ $course['title'] }}</h4>
                    <p class="text-[12px] text-gray-500">{{ $course['category'] ?? '' }} • Selesai {{ $course['completed_at'] }}</p>
                </div>
                <div class="flex items-center gap-2 pr-2">
                    @if($course['rating'] > 0)
                    <div class="flex items-center gap-1 bg-white border border-gray-100 text-gray-400 text-[10px] font-bold px-2 py-1 rounded-md">
                        <span>{{ number_format((float) ($course['rating'] ?? 0), 1) }}</span>
                        <svg class="w-2.5 h-2.5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    @endif
                    @if($course['user_rating'])
                    <div class="flex items-center gap-1 bg-white border border-gray-200 text-gray-700 text-[10px] font-bold px-2 py-1 rounded-md shadow-sm">
                        <span>Review: {{ $course['user_rating'] }}</span>
                        <svg class="w-2.5 h-2.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-2xl p-6 text-center">
            <p class="text-gray-500 font-medium text-sm">Belum ada kursus yang diselesaikan.</p>
        </div>
        @endif
    </div>

    <!-- Bootcamps Completed -->
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6">
        <h3 class="font-bold text-gray-900 text-[16px] mb-4">Bootcamp yang Telah Diselesaikan</h3>
        @if(!empty($portfolio['bootcamps']))
        <div class="space-y-3">
            @foreach($portfolio['bootcamps'] as $bootcamp)
            <div class="flex gap-4 items-center p-3 bg-gray-50/80 rounded-[14px]">
                <div class="w-14 h-14 rounded-xl bg-gray-200 overflow-hidden flex-shrink-0">
                    @if($bootcamp['thumbnail'])
                    <img src="{{ $bootcamp['thumbnail'] }}" class="w-full h-full object-cover" alt="">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-gray-800 text-[14px] truncate">{{ $bootcamp['title'] }}</h4>
                    <p class="text-[12px] text-gray-500">{{ ucfirst($bootcamp['type']) }} • Selesai {{ $bootcamp['completed_at'] }}</p>
                </div>
                <div class="flex items-center gap-2 pr-2">
                    @if($bootcamp['rating'] > 0)
                    <div class="flex items-center gap-1 bg-white border border-gray-100 text-gray-400 text-[10px] font-bold px-2 py-1 rounded-md">
                        <span>{{ number_format((float) ($bootcamp['rating'] ?? 0), 1) }}</span>
                        <svg class="w-2.5 h-2.5 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-gray-50 rounded-2xl p-6 text-center">
            <p class="text-gray-500 font-medium text-sm">Belum ada bootcamp yang diselesaikan.</p>
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
function sharePortfolio() {
    fetch('{{ route("portofolio.share") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            navigator.clipboard.writeText(data.data.share_url);
            alert('Link portofolio berhasil disalin!\n\n' + data.data.share_url);
        }
    })
    .catch(err => console.error(err));
}
</script>
@endpush
