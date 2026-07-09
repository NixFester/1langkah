@extends('layouts.mentor')

@section('title', 'Dashboard Mentor')
@section('header_title', 'Dashboard Mentor')

@section('content')
    <x-flash-messages />

    <!-- HERO SECTION -->
    <div class="bg-gradient-to-br from-[#cc0000] to-[#aa0000] rounded-2xl sm:rounded-3xl p-6 sm:p-10 flex flex-col sm:flex-row items-center justify-between relative overflow-hidden shadow-lg sm:shadow-xl mb-8 mt-2">
        <!-- Glow effect -->
        <div class="absolute -right-20 -top-20 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-red-500 rounded-full blur-[60px] sm:blur-[80px] pointer-events-none opacity-40"></div>

        <div class="relative z-10 text-white w-full sm:w-2/3 space-y-3 sm:space-y-4">
            <div class="text-white/90 font-medium flex items-center gap-2 text-sm sm:text-base">
                Selamat datang kembali, Mentor! 👋
            </div>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">{{ auth()->user()->name ?? 'Mentor' }}</h1>
            <p class="text-white/80 text-[13px] sm:text-base leading-relaxed max-w-lg">{{ auth()->user()->mentor?->bio ?? 'Belum ada bio. Tambahkan di pengaturan profil Anda.' }}</p>

            <div class="pt-3 sm:pt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('mentor.sessions.index') }}" class="bg-white text-[#cc0000] hover:bg-gray-50 px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 shadow-sm inline-flex w-full sm:w-auto">
                    Kelola Sesi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('mentor.profile.edit') }}" class="bg-white/10 hover:bg-white/20 text-white border border-white/20 px-6 py-3 sm:py-2.5 rounded-xl sm:rounded-full font-bold text-sm transition-colors flex items-center justify-center sm:justify-start gap-2 inline-flex w-full sm:w-auto">
                    Edit Profil
                </a>
            </div>
        </div>

        <div class="relative z-10 mt-6 md:mt-0 w-full sm:w-auto flex justify-center sm:block order-first sm:order-last">
            <div class="w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 rounded-full border-4 border-white/20 p-1 relative">
                @if(auth()->user()->profile_photo)
                <img src="{{ auth()->user()->profile_photo }}" alt="Profile" class="w-full h-full rounded-full object-cover bg-red-900">
                @else
                <div class="w-full h-full rounded-full bg-red-800 flex items-center justify-center text-white text-3xl font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
                @endif
                <div class="absolute bottom-2 right-2 w-5 h-5 bg-green-500 border-2 border-[#cc0000] rounded-full"></div>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-8">
        <x-stat-card label="Kursus Saya" :value="$stats['total_courses']" icon="award" color="blue" />
        <x-stat-card label="Total Siswa" :value="$stats['total_students']" icon="users" color="green" />
        <x-stat-card label="Total Enrollments" :value="$stats['total_enrollments']" icon="book" color="purple" />
        <x-stat-card label="Rating Rata-rata" :value="number_format($stats['avg_rating'], 1)" icon="starEmpty" suffix=" ⭐" color="amber" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- My Courses --}}
        <x-card-panel title="Kursus Saya" :actionRoute="route('mentor.my-courses')">
            @if($myCourses->isEmpty())
                <x-empty-state message="Belum ada kursus" icon="book" />
            @else
                <div class="space-y-4">
                    @foreach($myCourses as $course)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-800">{{ $course->title }}</p>
                                <p class="text-sm text-gray-500">{{ $course->category }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">{{ $course->enrollments_count ?? 0 }}</p>
                                <p class="text-xs text-gray-400">siswa</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>

        {{-- Recent Students --}}
        <x-card-panel title="Siswa Terbaru" :actionRoute="route('mentor.students')">
            @if($recentStudents->isEmpty())
                <x-empty-state message="Belum ada siswa" icon="users" />
            @else
                <div class="space-y-4">
                    @foreach($recentStudents as $enrollment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                @if($enrollment->user?->profile_photo)
                                    <img src="{{ $enrollment->user->profile_photo }}" alt="{{ $enrollment->user->name }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-blue-600 font-bold">{{ substr($enrollment->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $enrollment->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $enrollment->enrollable?->title ?? 'Unknown Course' }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400">{{ $enrollment->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card-panel>
    </div>

    {{-- Recent Ratings --}}
    <x-card-panel title="Rating Terbaru" :actionRoute="route('mentor.feedback')" class="mt-6">
        @if($recentRatings->isEmpty())
            <x-empty-state message="Belum ada rating" icon="rating" />
        @else
            <div class="space-y-4">
                @foreach($recentRatings as $rating)
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $rating->rating ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800">{{ $rating->review ?? 'Tanpa review' }}</p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ $rating->user?->name ?? 'Anonymous' }} - {{ $rating->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-card-panel>
@endsection
