@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', __('app.quiz_result') . ' - 1Langkah')

@section('header_title', __('app.quiz_result'))

@php
    $profile = $attempt->personality_profile;
    $unlockPrice = $quiz?->unlock_price ?: ($attempt->payment_amount ?: 30000);
    $freeLimit = $quiz?->free_question_limit ?: 10;
    $totalQuestions = $quiz ? $quiz->questions->count() : $attempt->total_questions;
@endphp

@section('content')
<div class="px-4 py-6 sm:px-8 w-full max-w-3xl mx-auto space-y-6 pb-16">

    <x-flash-messages />

    <!-- Email Delivery Status Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center flex-shrink-0 mt-0.5 sm:mt-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div class="text-xs sm:text-sm text-blue-900">
                <div class="font-extrabold">
                    {{ $attempt->is_paid ? '✓ Hasil Rangkuman Penuh Telah Dikirim ke Email Google Anda' : '📩 Hasil Preview Telah Dikirim ke Email Google Anda' }}
                </div>
                <p class="text-blue-700 mt-0.5">
                    Dikirim ke: <strong>{{ $attempt->google_email ?? $attempt->user?->email }}</strong>
                    @if($attempt->email_sent_at)
                        ({{ $attempt->email_sent_at->format('d M Y, H:i') }} WIB)
                    @endif
                </p>
            </div>
        </div>
        <span class="px-3 py-1 rounded-full text-[11px] font-extrabold {{ $attempt->is_paid ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }} whitespace-nowrap">
            {{ $attempt->is_paid ? 'FULL AKSES (LUNAS)' : 'MODE PREVIEW' }}
        </span>
    </div>

    <!-- Main Result & Personality Summary Card -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100">
            <!-- Score Circle -->
            <div class="w-28 h-28 relative flex-shrink-0">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" stroke="#e5e7eb" stroke-width="10" fill="none"/>
                    <circle cx="50" cy="50" r="45"
                        stroke="{{ $attempt->is_paid ? '#16a34a' : '#f59e0b' }}"
                        stroke-width="10"
                        fill="none"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $attempt->score * 2.83 }} 283"
                        class="transition-all duration-1000"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-extrabold text-gray-900">{{ number_format($attempt->score, 0) }}%</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $attempt->is_paid ? 'Skor Penuh' : 'Preview' }}</span>
                </div>
            </div>

            <div class="text-center sm:text-left flex-1">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold {{ $profile['badge_color'] }} mb-1.5">
                    {{ $profile['archetype'] }}
                </div>
                @if(!empty($profile['tagline']))
                <p class="text-xs sm:text-sm italic font-semibold text-gray-600 mb-1">{{ $profile['tagline'] }}</p>
                @endif
                <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900">
                    {{ $quiz->title ?? 'Tes Psikologi & Kepribadian' }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    ID Peserta: <strong>QUIZ-{{ $attempt->id }}</strong> • Terjawab: <strong>{{ is_array($attempt->answers) ? count($attempt->answers) : 0 }} dari {{ $totalQuestions }} Soal</strong>
                </p>
            </div>
        </div>

        <!-- 4 Personality Dimensions Bar -->
        @if(!empty($profile['dimensions']))
        <div class="mt-6 bg-gray-50 rounded-2xl p-5 border border-gray-100">
            <div class="text-xs font-extrabold text-gray-500 uppercase tracking-wider mb-3">Komposisi 4 Dimensi Kepribadian Anda</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                @foreach($profile['dimensions'] as $dimLabel => $dimPercent)
                <div>
                    <div class="flex justify-between text-xs font-bold text-gray-800 mb-1">
                        <span>{{ $dimLabel }}</span>
                        <span>{{ $loop->first || $attempt->is_paid ? $dimPercent . '%' : '🔒 ??%' }}</span>
                    </div>
                    <div class="w-full h-2.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $loop->first ? 'bg-[#cc0000]' : ($attempt->is_paid ? 'bg-gray-800' : 'bg-gray-300') }}" style="width: {{ $loop->first || $attempt->is_paid ? $dimPercent : 35 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Personality Summary Section (Preview vs Full Paid) -->
        <div class="mt-6">
            <h2 class="text-base font-extrabold text-gray-900 mb-3 flex items-center gap-2">
                <span>🧠 Rangkuman Analisis Kepribadian</span>
                @if(!$attempt->is_paid)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">Preview Singkat</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">Laporan Lengkap Terbuka</span>
                @endif
            </h2>

            @if($attempt->is_paid)
                <!-- FULL PERSONALITY SUMMARY & GIMMICKS (PAID) -->
                <div class="space-y-4">
                    <div class="bg-emerald-50/60 border border-emerald-200 rounded-2xl p-5 text-sm text-gray-800 leading-relaxed space-y-2">
                        <p class="font-bold text-emerald-950">{{ $profile['preview'] }}</p>
                        <p>{{ $profile['full_summary'] }}</p>
                    </div>

                    @if(!empty($profile['superpower']))
                    <div class="bg-indigo-50/70 border border-indigo-100 rounded-2xl p-4 text-sm">
                        <div class="text-xs font-extrabold text-indigo-700 uppercase tracking-wider mb-1">⚡ Superpower Utama</div>
                        <div class="font-bold text-gray-900">{{ $profile['superpower'] }}</div>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white border border-emerald-200 rounded-2xl p-4">
                            <div class="text-xs font-extrabold text-emerald-700 uppercase tracking-wider mb-2">💪 Kekuatan Utama (Strengths)</div>
                            <ul class="space-y-1.5 text-xs sm:text-sm text-gray-700 list-disc list-inside">
                                @foreach($profile['strengths'] ?? [] as $str)
                                    <li>{{ $str }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="bg-white border border-amber-200 rounded-2xl p-4">
                            <div class="text-xs font-extrabold text-amber-700 uppercase tracking-wider mb-2">⚠️ Titik Buta (Blind Spots)</div>
                            <ul class="space-y-1.5 text-xs sm:text-sm text-gray-700 list-disc list-inside">
                                @foreach($profile['blind_spots'] ?? [] as $bs)
                                    <li>{{ $bs }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-xs sm:text-sm">
                            <div class="text-xs font-extrabold text-gray-500 uppercase tracking-wider mb-1">🏢 Gaya Kerja & Lingkungan Ideal</div>
                            <p class="text-gray-800 leading-relaxed">{{ $profile['work_style'] ?? '-' }}</p>
                            @if(!empty($profile['partner_match']))
                                <div class="mt-2 pt-2 border-t border-gray-200 text-xs text-gray-600">
                                    <strong>Partner Kolaborasi Terbaik:</strong> {{ $profile['partner_match'] }}
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 text-xs sm:text-sm">
                            <div class="text-xs font-extrabold text-gray-500 uppercase tracking-wider mb-2">🎯 Rekomendasi Jalur Karir</div>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($profile['careers'] ?? [] as $career)
                                    <span class="px-3 py-1 bg-white border border-gray-200 rounded-full text-xs font-bold text-gray-800">{{ $career }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- PREVIEW ONLY + BLURRED LOCKED FULL SUMMARY & GIMMICKS -->
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 text-sm text-gray-800 leading-relaxed">
                    <p class="font-medium text-gray-900 mb-3">{{ $profile['preview'] }}</p>

                    <div class="relative mt-4 pt-4 border-t border-gray-200 overflow-hidden rounded-xl">
                        <div class="blur-[5px] select-none pointer-events-none text-gray-500 text-xs sm:text-sm leading-relaxed space-y-3">
                            <p>{{ $profile['full_summary'] }}</p>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-white rounded-lg border"><strong>⚡ Superpower:</strong> {{ $profile['superpower'] ?? '' }}</div>
                                <div class="p-3 bg-white rounded-lg border"><strong>🎯 Rekomendasi Karir:</strong> {{ implode(', ', $profile['careers'] ?? []) }}</div>
                            </div>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-b from-white/40 via-white/85 to-white flex flex-col items-center justify-center p-4 text-center">
                            <span class="text-xs font-extrabold text-amber-800 bg-amber-100 px-3 py-1 rounded-full mb-1">
                                🔒 Superpower, Kekuatan, Blind Spots & Rekomendasi Karir Masih Terkunci
                            </span>
                            <p class="text-xs text-gray-600 max-w-md">
                                Lakukan pembayaran Rp {{ number_format($unlockPrice, 0, ',', '.') }} untuk membuka analisis 4 dimensi kepribadian, kekuatan, blind spots, kecocokan karir, dan seluruh jawaban Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- CTA PAYMENT CARD (Shown when unpaid) -->
        @if(!$attempt->is_paid)
        <div class="mt-6 bg-gradient-to-br from-red-600 to-[#990000] rounded-2xl p-5 sm:p-6 text-white shadow-lg">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="text-lg sm:text-xl font-extrabold">Buka Hasil Lengkap Anda</h3>
                    <p class="text-xs sm:text-sm text-red-100">Lihat analisis kepribadian secara penuh.</p>
                </div>

                <a href="{{ route('quiz.checkout', ['quiz' => $quiz?->id ?? 1, 'attempt' => $attempt->id]) }}" class="w-full md:w-auto flex-shrink-0 px-6 py-3.5 bg-white hover:bg-gray-100 text-[#cc0000] font-extrabold rounded-xl transition-all shadow-md text-sm flex items-center justify-center cursor-pointer whitespace-nowrap">
                    Bayar Sekarang
                </a>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('quiz.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-bold rounded-full transition-colors">
                &larr; {{ __('app.back_to_quizzes') }}
            </a>
            @if($quiz)
            <a href="{{ route('quiz.start', ['quiz' => $quiz->id, 'started' => 1]) }}" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white text-xs sm:text-sm font-bold rounded-full transition-colors">
                {{ $attempt->is_paid ? 'Kerjakan Ulang Semua Soal (Full Akses Terbuka)' : 'Coba Lagi Tes' }} &rarr;
            </a>
            @endif
        </div>
    </div>

    <!-- All Answers Breakdown Section (LOCKED if unpaid, SHOWN if paid) -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="text-lg font-extrabold text-gray-900">Rincian Jawaban Tes Anda</h2>
                <p class="text-xs sm:text-sm text-gray-500">
                    {{ $attempt->is_paid ? 'Berikut adalah rincian seluruh jawaban Anda beserta pembahasan.' : 'Jawaban Anda dikunci dalam mode Preview dan tidak ditampilkan sebelum pembayaran.' }}
                </p>
            </div>
            @if($attempt->is_paid)
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-800">Terbuka</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-800">🔒 Terkunci</span>
            @endif
        </div>

        @if(!$attempt->is_paid)
            <!-- Locked Answers State -->
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-amber-100 text-amber-700 flex items-center justify-center">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-base font-extrabold text-gray-900">Daftar Jawaban Anda Dikunci ({{ is_array($attempt->answers) ? count($attempt->answers) : 0 }}/{{ $totalQuestions }} Soal)</h3>
                <p class="text-xs sm:text-sm text-gray-500 max-w-md mx-auto mt-1 mb-5">
                    Sesuai ketentuan tes psikologi, rincian jawaban yang telah Anda isi beserta soal #{{ $freeLimit + 1 }}–#{{ $totalQuestions }} dikunci dan baru ditampilkan setelah pembayaran Rp {{ number_format($unlockPrice, 0, ',', '.') }}.
                </p>
                <a href="{{ route('quiz.checkout', ['quiz' => $quiz?->id ?? 1, 'attempt' => $attempt->id]) }}" class="inline-block px-6 py-3 bg-[#cc0000] hover:bg-red-700 text-white text-xs sm:text-sm font-extrabold rounded-full transition-all shadow-sm cursor-pointer">
                    Checkout Rp {{ number_format($unlockPrice, 0, ',', '.') }} untuk Membuka Semua Jawaban
                </a>
            </div>
        @else
            <!-- Unlocked Answers List -->
            @if($quiz && $quiz->questions->count() > 0)
            <div class="space-y-4">
                @foreach($quiz->questions->sortBy('order')->values() as $idx => $question)
                @php
                    $userAnsId = $attempt->answers[$question->id] ?? null;
                    $selectedAns = $question->answers->firstWhere('id', (int) $userAnsId);
                    $correctAns = $question->answers->firstWhere('is_correct', true);
                    $isCorrect = $selectedAns && $selectedAns->is_correct;
                @endphp
                <div class="p-4 sm:p-5 rounded-2xl border {{ $userAnsId ? ($isCorrect ? 'border-emerald-200 bg-emerald-50/30' : 'border-gray-200 bg-gray-50/50') : 'border-amber-200 bg-amber-50/30' }}">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="font-bold text-sm text-gray-900">
                            {{ $idx + 1 }}. {{ $question->question }}
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold flex-shrink-0 {{ $userAnsId ? ($isCorrect ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800') : 'bg-amber-100 text-amber-800' }}">
                            {{ $userAnsId ? 'Terjawab' : 'Belum Dijawab' }}
                        </span>
                    </div>
                    <div class="text-xs sm:text-sm text-gray-700 space-y-1">
                        <div>
                            <span class="text-gray-500">Jawaban Anda:</span>
                            <strong class="text-gray-900">{{ $selectedAns?->answer_text ?? ($userAnsId ?: 'Tidak dijawab (Terkunci saat Preview / Waktu Habis)') }}</strong>
                        </div>
                        @if($correctAns)
                        <div>
                            <span class="text-gray-500">Respons Optimal:</span>
                            <strong class="text-emerald-700">{{ $correctAns->answer_text }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        @endif
    </div>

    <!-- Participant Personal Data Card -->
    <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-sm">
        <h2 class="font-extrabold text-gray-900 mb-4">Data Diri Peserta</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">ID Peserta / Attempt</span>
                <p class="font-bold text-gray-900">QUIZ-{{ $attempt->id }} (User ID: {{ $attempt->user_id }})</p>
            </div>
            <div>
                <span class="text-gray-500">Email Akun Google</span>
                <p class="font-bold text-gray-900">{{ $attempt->google_email ?? $attempt->user?->email ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.full_name') }}</span>
                <p class="font-medium text-gray-900">{{ $attempt->participant_name ?? $attempt->user?->name ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.ttl') }}</span>
                <p class="font-medium text-gray-900">{{ $attempt->formatted_ttl }}</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.whatsapp_number') }}</span>
                <p class="font-medium text-gray-900">{{ $attempt->whatsapp ?? '-' }}</p>
            </div>
            <div>
                <span class="text-gray-500">{{ __('app.full_address') }}</span>
                <p class="font-medium text-gray-900">{{ $attempt->address ?? '-' }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
