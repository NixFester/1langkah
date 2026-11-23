@extends('layouts.app', ['activePage' => 'quizzes'])

@section('title', $quiz->title . ' - Quiz')
@section('header_title', __('app.take_quiz'))

@php
    $totalQuestions = $quiz->questions->count();
    $unlockPrice = $quiz->unlock_price ?: 30000;
    $showPreTestModal = $quiz->is_global && !$isPaidAccess && !request()->boolean('started') && !$errors->any();
    $defaultGoogleEmail = old(
        'google_email',
        $lastPersonalInfo->google_email
            ?? (\Illuminate\Support\Str::endsWith(strtolower($user->email ?? ''), ['@gmail.com', '@googlemail.com']) ? $user->email : '')
    );
@endphp

@section('content')
<div x-data="quizTimer({{ $showPreTestModal ? 'true' : 'false' }})" class="w-full max-w-4xl mx-auto pb-24 pt-2">

    <x-flash-messages />

    <!-- PRE-TEST NOTIFICATION MODAL (CTA: Bayar Sekarang Rp 30.000 / Mulai Tes Preview) -->
    <div x-show="showStartModal" style="display: {{ $showPreTestModal ? 'flex' : 'none' }};" class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm" x-transition.opacity>
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-gray-100" @click.stop>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0 border border-red-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-red-100 text-red-700 uppercase tracking-wider">
                        Informasi Tes Psikologi Global
                    </span>
                    <h2 class="text-xl font-extrabold text-gray-900 mt-0.5">{{ $quiz->title }}</h2>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-200/80 space-y-2.5 text-sm text-gray-700 mb-6">
                <div class="flex items-start gap-2.5">
                    <span class="text-red-600 font-bold">⏱</span>
                    <span><strong>Batas Waktu {{ $quiz->time_limit_minutes ?? 1 }} Menit:</strong> Saat waktu habis, tes akan <strong>otomatis berakhir</strong> dan hasil dikirim ke <strong>Email Akun Google (@gmail.com)</strong> Anda.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="text-amber-600 font-bold">🔒</span>
                    <span><strong>Mode Preview ({{ $freeLimit }} dari {{ $totalQuestions }} Soal):</strong> Tanpa pembayaran, Anda hanya dapat mengisi <strong>{{ $freeLimit }} soal pertama</strong> dan hanya menerima <strong>Hasil Preview</strong>.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="text-emerald-600 font-bold">✨</span>
                    <span><strong>Full Akses (Rp {{ number_format($unlockPrice, 0, ',', '.') }}):</strong> Buka seluruh <strong>{{ $totalQuestions }} soal</strong>, dapatkan <strong>Rangkuman Kepribadian Penuh</strong>, dan rincian semua jawaban di layar & email Anda.</span>
                </div>
            </div>

            <div class="space-y-3">
                <!-- CTA 1: Bayar Sekarang (Mock Payment Rp 30.000) -->
                <form action="{{ route('quiz.prepay', $quiz) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full py-3.5 px-6 bg-[#cc0000] hover:bg-red-700 text-white font-extrabold rounded-2xl transition-all shadow-lg shadow-red-600/25 flex items-center justify-center gap-2 text-sm sm:text-base cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                        <span>Bayar Sekarang & Buka Semua Soal (Rp {{ number_format($unlockPrice, 0, ',', '.') }})</span>
                    </button>
                </form>

                <!-- CTA 2: Mulai Tes (Mode Preview) -->
                <button @click="startTestNow()" type="button" class="w-full py-3.5 px-6 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-2xl transition-colors text-sm sm:text-base cursor-pointer">
                    Mulai Tes Sekarang (Mode Preview {{ $freeLimit }} Soal) &rarr;
                </button>
            </div>
        </div>
    </div>

    <!-- Quiz Info Header (Sticky so timer is always visible) -->
    <div class="sticky top-16 z-40 bg-white/95 backdrop-blur-md rounded-2xl shadow-sm border border-gray-200 p-5 sm:p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex-1">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                @if($quiz->is_global)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-red-100 text-red-700 uppercase">Tes Psikologi Global</span>
                @endif
                @if($isPaidAccess)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-700">✓ Full Akses Terbuka ({{ $totalQuestions }} Soal)</span>
                @elseif($quiz->is_global)
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold bg-amber-100 text-amber-800">🔒 Mode Preview ({{ $freeLimit }}/{{ $totalQuestions }} Soal)</span>
                @endif
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 tracking-tight">{{ $quiz->title }}</h1>
            @if($quiz->description)
            <p class="text-gray-500 text-xs sm:text-sm leading-relaxed max-w-2xl mt-0.5">{{ $quiz->description }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 sm:gap-6 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0 border-gray-100">
            <div class="text-left md:text-right">
                <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('app.progress') }}</div>
                <div class="text-sm font-bold text-gray-900"><span id="answered-count">0</span> / {{ $freeLimit }} Soal Aktif</div>
            </div>
            @if($quiz->time_limit_minutes)
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-2.5 flex items-center gap-3">
                <div class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                </div>
                <span class="font-mono font-extrabold text-red-600 text-lg sm:text-xl tracking-tight" id="timer-display" x-text="formatTime(timeRemaining)" :class="{'animate-pulse text-red-700': timeRemaining <= 30}"></span>
            </div>
            @endif
        </div>
    </div>

    <!-- Banner Upgrade Full Access if currently in Preview Mode -->
    @if($quiz->is_global && !$isPaidAccess)
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-4 sm:p-5 mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="text-xs sm:text-sm text-amber-900">
            <div class="font-extrabold text-amber-950 mb-0.5">🔒 Anda sedang mengerjakan dalam Mode Preview ({{ $freeLimit }} dari {{ $totalQuestions }} Soal)</div>
            <p>Soal nomor {{ $freeLimit + 1 }} s/d {{ $totalQuestions }} serta Rangkuman Kepribadian Penuh dikunci. Bayar <strong>Rp {{ number_format($unlockPrice, 0, ',', '.') }}</strong> untuk membuka seluruh soal & laporan lengkap.</p>
        </div>
        <form action="{{ route('quiz.prepay', $quiz) }}" method="POST" class="flex-shrink-0 w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-[#cc0000] hover:bg-red-700 text-white text-xs font-extrabold rounded-full transition-all shadow-sm whitespace-nowrap cursor-pointer">
                Bayar Rp {{ number_format($unlockPrice, 0, ',', '.') }} (Buka Semua)
            </button>
        </form>
    </div>
    @endif

    <!-- Quiz Form -->
    <form action="{{ route('quiz.submit', $quiz) }}" method="POST" id="quizForm" @submit="handleSubmit">
        @csrf
        <input type="hidden" name="started_at" :value="startedAt">
        <input type="hidden" name="time_spent_seconds" :value="timeSpent">
        <input type="hidden" name="auto_submitted" id="auto_submitted_input" value="0">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-6 text-sm">
            <div class="font-bold mb-1">{{ __('app.personal_data_error') }}</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Personal Data Form (Data Diri Peserta + Email Akun Google) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ __('app.participant_personal_data') }}</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Lengkapi data diri & akun Google (@gmail.com) untuk pengiriman hasil tes.</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M21.35 11.1h-9.17v2.73h6.51c-.33 3.81-3.5 5.44-6.5 5.44C8.36 19.27 5 15.25 5 12c0-3.25 3.36-7.27 7.2-7.27 3.09 0 4.9 1.97 4.9 1.97L19 4.72S16.56 2 12.1 2C6.42 2 2.03 6.8 2.03 12c0 5.05 4.13 10 10.22 10 5.35 0 9.25-3.67 9.25-9.09 0-1.15-.15-1.81-.15-1.81Z"/></svg>
                    <span>Wajib Email Google (@gmail.com)</span>
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Lengkap -->
                <div>
                    <label for="participant_name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        {{ __('app.full_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="participant_name" name="participant_name"
                        value="{{ old('participant_name', $lastPersonalInfo->participant_name ?? $user->name ?? '') }}"
                        placeholder="{{ __('app.full_name_placeholder') }}"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-gray-50 focus:bg-white">
                </div>

                <!-- Email Akun Google (@gmail.com) -->
                <div>
                    <label for="google_email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Email Akun Google (@gmail.com) <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="google_email" name="google_email"
                        value="{{ $defaultGoogleEmail }}"
                        placeholder="contoh: namaanda@gmail.com"
                        pattern="^[a-zA-Z0-9._%+\-]+@(gmail|googlemail)\.com$"
                        title="Harus menggunakan alamat email Google (@gmail.com)"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-gray-50 focus:bg-white">
                    <p class="text-[11px] text-gray-500 mt-1">Hasil tes otomatis dikirim ke email Google ini saat waktu berakhir.</p>
                </div>

                <!-- Tempat Lahir -->
                <div>
                    <label for="birth_place" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        {{ __('app.birth_place') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="birth_place" name="birth_place"
                        value="{{ old('birth_place', $lastPersonalInfo->birth_place ?? '') }}"
                        placeholder="{{ __('app.birth_place_placeholder') }}"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-gray-50 focus:bg-white">
                </div>

                <!-- Tanggal Lahir -->
                <div>
                    <label for="birth_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        {{ __('app.birth_date') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="birth_date" name="birth_date"
                        value="{{ old('birth_date', isset($lastPersonalInfo?->birth_date) ? $lastPersonalInfo->birth_date->format('Y-m-d') : '') }}"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-gray-50 focus:bg-white">
                </div>

                <!-- No WhatsApp -->
                <div class="sm:col-span-2">
                    <label for="whatsapp" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        {{ __('app.whatsapp_number') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="whatsapp" name="whatsapp"
                        value="{{ old('whatsapp', $lastPersonalInfo->whatsapp ?? '') }}"
                        placeholder="{{ __('app.whatsapp_placeholder') }}"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all bg-gray-50 focus:bg-white">
                </div>

                <!-- Alamat Lengkap -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        {{ __('app.full_address') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2"
                        placeholder="{{ __('app.full_address_placeholder') }}"
                        required
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none resize-y transition-all bg-gray-50 focus:bg-white">{{ old('address', $lastPersonalInfo->address ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Questions List -->
        <div class="space-y-6">
            @foreach($quiz->questions->sortBy('order')->values() as $index => $question)
            @php
                $isLocked = ($index >= $freeLimit) && !$isPaidAccess;
            @endphp

            <div class="relative bg-white rounded-2xl shadow-sm border {{ $isLocked ? 'border-amber-200 bg-gray-50/70' : 'border-gray-200 hover:shadow-md' }} p-6 sm:p-8 transition-shadow overflow-hidden">
                @if($isLocked)
                <!-- Locked Question Overlay -->
                <div class="absolute inset-0 z-20 bg-white/75 backdrop-blur-[2px] flex flex-col items-center justify-center p-6 text-center">
                    <div class="w-11 h-11 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center mb-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div class="text-sm font-extrabold text-gray-900">Soal #{{ $index + 1 }} Terkunci (Mode Preview)</div>
                    <p class="text-xs text-gray-600 max-w-md mt-1 mb-3">
                        Bayar <strong>Rp {{ number_format($unlockPrice, 0, ',', '.') }}</strong> untuk membuka soal #{{ $freeLimit + 1 }}–#{{ $totalQuestions }} beserta hasil rangkuman kepribadian penuh.
                    </p>
                </div>
                @endif

                <!-- Question Header -->
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-6 {{ $isLocked ? 'opacity-40 select-none pointer-events-none' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full {{ $isLocked ? 'bg-gray-200 text-gray-500' : 'bg-red-50 text-red-600' }} font-bold flex items-center justify-center flex-shrink-0 text-sm">
                            {{ $index + 1 }}
                        </div>
                        <div class="pt-1.5 flex-1">
                            <h2 class="text-[17px] font-semibold text-gray-900 leading-relaxed">{{ $question->question }}</h2>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 sm:pl-0 pl-14 flex-shrink-0">
                        @if($isLocked)
                        <span class="inline-flex px-2.5 py-1 bg-amber-100 text-amber-800 text-[11px] font-bold uppercase tracking-wider rounded-md">🔒 Terkunci</span>
                        @elseif($question->is_required)
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold uppercase tracking-wider rounded-md">{{ __('app.required') }}</span>
                        @endif
                        <span class="inline-flex px-2.5 py-1 bg-gray-100 text-gray-500 text-[11px] font-bold uppercase tracking-wider rounded-md">{{ $question->points }} {{ __('app.points') }}</span>
                    </div>
                </div>

                <!-- Answers -->
                <div class="ml-0 sm:ml-14 {{ $isLocked ? 'opacity-40 select-none pointer-events-none' : '' }}">
                    @if(in_array($question->type, ['multiple_choice', 'true_false']))
                        <div class="grid gap-3">
                            @foreach($question->answers->sortBy('order') as $answer)
                            <label class="group relative flex items-start p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 has-[:checked]:border-red-500 has-[:checked]:bg-red-50 transition-all duration-200">
                                <input aria-label="Input Field" type="radio" name="answers[{{ $question->id }}]" value="{{ $answer->id }}" class="absolute opacity-0 w-0 h-0" {{ $isLocked ? 'disabled' : 'required' }}>
                                <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 group-has-[:checked]:border-red-600 flex items-center justify-center bg-white mr-3.5 mt-0.5 transition-colors">
                                    <div class="w-2.5 h-2.5 rounded-full bg-red-600 scale-0 group-has-[:checked]:scale-100 transition-transform"></div>
                                </div>
                                <span class="text-gray-700 font-medium text-sm sm:text-[15px] group-has-[:checked]:text-red-900 leading-snug w-full block">
                                    {{ $answer->answer_text }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    @elseif($question->type === 'essay')
                        <textarea aria-label="Input Field" name="answers[{{ $question->id }}]" rows="4"
                            style="box-sizing: border-box;"
                            class="w-full border-2 border-gray-200 rounded-xl p-4 text-gray-700 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none resize-y transition-all bg-gray-50 focus:bg-white"
                            placeholder="{{ __('app.write_answer_here') }}" {{ $isLocked ? 'disabled' : 'required' }}></textarea>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Floating Submit Button -->
        <button type="submit" class="fixed bottom-6 right-6 sm:bottom-10 sm:right-10 z-50 px-8 py-4 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white font-bold rounded-full transition-all shadow-[0_8px_30px_rgba(220,38,38,0.4)] hover:shadow-[0_12px_40px_rgba(220,38,38,0.5)] hover:-translate-y-1 flex items-center justify-center gap-2 group cursor-pointer">
            <span>{{ __('app.finish_and_submit') }}</span>
            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
function quizTimer(initialShowModal) {
    return {
        timeRemaining: {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes * 60 : 60 }},
        startedAt: new Date().toISOString(),
        showStartModal: initialShowModal,
        timeSpent: 0,
        interval: null,
        isAutoSubmitting: false,

        init() {
            if (!this.showStartModal) {
                this.startCountdown();
            }

            this.updateAnsweredCount();

            document.querySelectorAll('#quizForm input[type="radio"]:not([disabled]), #quizForm textarea[name^="answers"]:not([disabled])').forEach(el => {
                el.addEventListener('change', () => this.updateAnsweredCount());
                el.addEventListener('keyup', () => this.updateAnsweredCount());
            });

            window.scrollTo({ left: 0, behavior: 'instant' });
        },

        startTestNow() {
            this.showStartModal = false;
            this.startedAt = new Date().toISOString();
            this.startCountdown();
        },

        startCountdown() {
            if (this.interval) clearInterval(this.interval);
            if (this.timeRemaining > 0) {
                this.interval = setInterval(() => {
                    this.timeRemaining--;
                    this.timeSpent++;
                    if (this.timeRemaining <= 0) {
                        this.autoSubmitOnTimeUp();
                    }
                }, 1000);
            }
        },

        formatTime(seconds) {
            if (seconds < 0) return '00:00';
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        updateAnsweredCount() {
            const groups = {};
            document.querySelectorAll('#quizForm input[type="radio"]:checked:not([disabled])').forEach(r => groups[r.name] = true);
            document.querySelectorAll('#quizForm textarea[name^="answers"]:not([disabled])').forEach(t => {
                if (t.value.trim().length > 0) groups[t.name] = true;
            });
            const count = Object.keys(groups).length;
            const deskCount = document.getElementById('answered-count');
            if (deskCount) deskCount.textContent = count;
        },

        handleSubmit(e) {
            if (this.isAutoSubmitting) {
                return true;
            }

            const totalActive = {{ $freeLimit }};
            const answered = document.getElementById('answered-count') ? parseInt(document.getElementById('answered-count').textContent) : 0;

            if (answered < totalActive) {
                const msg = `Anda baru menjawab ${answered} dari ${totalActive} soal yang terbuka. Yakin ingin mengakhiri tes sekarang?`;
                if (!confirm(msg)) {
                    e.preventDefault();
                    return false;
                }
            }
            if (this.interval) clearInterval(this.interval);
            return true;
        },

        autoSubmitOnTimeUp() {
            if (this.interval) clearInterval(this.interval);
            this.isAutoSubmitting = true;

            const autoInput = document.getElementById('auto_submitted_input');
            if (autoInput) autoInput.value = '1';

            const form = document.getElementById('quizForm');
            if (form) {
                // Remove required attributes so browser HTML5 validation never blocks auto-submit
                form.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
                form.noValidate = true;
                form.submit();
            }
        }
    }
}
</script>
@endpush
