@extends('layouts.app', ['activePage' => 'siap.index'])

@section('title', 'Pengerjaan ' . $assessment->title . ' — SIAP 1Langkah')
@section('header_title', $assessment->title)

@php
    $questionsList = $assessment->questions->values();
    $totalQuestions = $questionsList->count();
    $initialAnswers = is_array($draft->answers) ? $draft->answers : [];

    $defaultGoogleEmail = old(
        'google_email',
        $draft->google_email
            ?? $lastPersonalInfo?->google_email
            ?? (\Illuminate\Support\Str::endsWith(strtolower($user->email ?? ''), ['@gmail.com', '@googlemail.com']) ? $user->email : '')
    );

    $questionsPayload = $questionsList->map(function ($q, $idx) {
        return [
            'id' => $q->id,
            'number' => $idx + 1,
            'text' => $q->question_text,
            'options' => $q->options ?? [],
        ];
    })->values();

    $likertChoices = [
        ['value' => 1, 'label' => 'Tidak Pernah'],
        ['value' => 2, 'label' => 'Jarang'],
        ['value' => 3, 'label' => 'Kadang-kadang'],
        ['value' => 4, 'label' => 'Sering'],
        ['value' => 5, 'label' => 'Sangat Sering'],
    ];

    $initialBiodata = [
        'participant_name' => old('participant_name', $draft->participant_name ?? $user->name ?? ''),
        'google_email' => $defaultGoogleEmail,
        'birth_place' => old('birth_place', $draft->birth_place ?? $user->birth_place ?? ''),
        'birth_date' => old('birth_date', $draft->birth_date?->format('Y-m-d') ?? $user->birth_date?->format('Y-m-d') ?? ''),
        'whatsapp' => old('whatsapp', $draft->whatsapp ?? $user->phone ?? ''),
        'address' => old('address', $draft->address ?? $user->address ?? ''),
    ];

    $saveProgressUrl = route('siap.save', $assessment);
@endphp

@section('content')
<div x-data="siapQuizRunner()" class="mx-auto max-w-3xl px-1 pb-16 sm:px-2">
    <x-flash-messages />

    <!-- Sticky Timer & Progress Header -->
    <div class="sticky top-16 z-40 mb-6 rounded-2xl border border-stone-200 bg-white/95 p-4 shadow-sm backdrop-blur-md">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-red-50 px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider text-[#E50000]">
                        {{ $assessment->badge }}
                    </span>
                    <span class="text-xs font-semibold text-slate-500" x-text="saveStatus"></span>
                </div>
                <h1 class="mt-1 font-display text-base font-semibold text-[#292C33] sm:text-lg">{{ $assessment->title }}</h1>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Terjawab</div>
                    <div class="text-sm font-bold text-[#292C33]">
                        <span x-text="answeredCount"></span>/{{ $totalQuestions }}
                    </div>
                </div>
                <div class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-3.5 py-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-600"></span>
                    </span>
                    <span class="font-mono text-lg font-extrabold text-red-600" id="siap-timer-display"
                        x-text="formatTime(timeRemaining)"
                        :class="{ 'animate-pulse text-red-700': timeRemaining <= 30 }"></span>
                </div>
            </div>
        </div>

        <!-- Progress bar -->
        <div class="mt-3">
            <div class="mb-1.5 flex justify-between text-xs text-slate-600">
                <span>Pertanyaan <strong x-text="currentIndex + 1"></strong> dari {{ $totalQuestions }}</span>
                <span><strong x-text="Math.round((answeredCount / {{ max(1, $totalQuestions) }}) * 100)"></strong>% selesai</span>
            </div>
            <div class="h-2 overflow-hidden rounded-full bg-stone-200">
                <div class="h-full rounded-full bg-[#E50000] transition-all duration-300"
                    :style="`width: ${(answeredCount / {{ max(1, $totalQuestions) }}) * 100}%`"></div>
            </div>
        </div>
    </div>

    <form action="{{ route('siap.submit', $assessment) }}" method="POST" id="siapQuizForm" @submit="handleFormSubmit($event)">
        @csrf
        <input type="hidden" name="time_spent_seconds" :value="timeSpent">
        <input type="hidden" name="auto_submitted" id="siap_auto_submitted" value="0">

        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <div class="mb-1 font-bold">Lengkapi Data Diri Terlebih Dahulu</div>
                <ul class="list-inside list-disc space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Biodata Card (Collapsible once valid) -->
        <div class="mb-6 rounded-3xl border border-stone-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-[#E50000]">
                        <x-icon name="user" class="h-5 w-5" />
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-[#292C33] sm:text-base">Data Diri Peserta &amp; Pengiriman Email</h2>
                        <p class="text-xs text-slate-500">Wajib diisi agar hasil tes terkirim otomatis ke akun Google Anda.</p>
                    </div>
                </div>
                <button type="button" @click="showBiodata = !showBiodata"
                    class="cursor-pointer rounded-full border border-stone-200 px-3.5 py-1.5 text-xs font-semibold text-stone-700 hover:bg-stone-50">
                    <span x-text="showBiodata ? 'Sembunyikan' : 'Edit Data Diri'"></span>
                </button>
            </div>

            <div x-show="showBiodata" x-transition class="mt-5 grid grid-cols-1 gap-4 border-t border-stone-100 pt-5 sm:grid-cols-2">
                <div>
                    <label for="participant_name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="participant_name" name="participant_name" required
                        x-model="biodata.participant_name"
                        placeholder="Nama lengkap siswa"
                        class="min-h-11 w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 text-sm text-stone-900 focus:border-red-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="google_email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Email Akun Google (@gmail.com) <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="google_email" name="google_email" required
                        x-model="biodata.google_email"
                        pattern="^[a-zA-Z0-9._%+\-]+@(gmail|googlemail)\.com$"
                        title="Harus menggunakan alamat email Google (@gmail.com)"
                        placeholder="namaanda@gmail.com"
                        class="min-h-11 w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 text-sm text-stone-900 focus:border-red-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="birth_place" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tempat Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="birth_place" name="birth_place" required
                        x-model="biodata.birth_place"
                        placeholder="Kota kelahiran"
                        class="min-h-11 w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 text-sm text-stone-900 focus:border-red-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="birth_date" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="birth_date" name="birth_date" required
                        x-model="biodata.birth_date"
                        class="min-h-11 w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 text-sm text-stone-900 focus:border-red-500 focus:bg-white focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label for="whatsapp" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Nomor WhatsApp <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" id="whatsapp" name="whatsapp" required
                        x-model="biodata.whatsapp"
                        placeholder="08xxxxxxxxxx"
                        class="min-h-11 w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 text-sm text-stone-900 focus:border-red-500 focus:bg-white focus:outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label for="address" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-700">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="2" required
                        x-model="biodata.address"
                        placeholder="Alamat tempat tinggal saat ini"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 p-3.5 text-sm text-stone-900 focus:border-red-500 focus:bg-white focus:outline-none"></textarea>
                </div>
            </div>
        </div>

        <!-- Hidden serialized inputs for ALL answers so 1-by-1 view still posts everything -->
        <template x-for="q in questions" :key="'hidden-' + q.id">
            <input type="hidden" :name="`answers[${q.id}]`" :value="answers[q.id] ?? ''">
        </template>

        <!-- Single Question Card -->
        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm md:p-10">
            <span class="text-xs font-bold uppercase tracking-wider text-[#E50000]">
                Pertanyaan <span x-text="currentIndex + 1"></span>
            </span>
            <h2 class="mb-7 mt-3 font-display text-xl font-semibold leading-relaxed text-[#292C33] md:text-2xl"
                x-text="currentQuestion.text"></h2>

            @if($assessment->isForcedChoice())
                <div class="space-y-3">
                    <template x-for="opt in currentQuestion.options" :key="opt.key">
                        <button type="button"
                            @click="selectAnswer(currentQuestion.id, opt.key)"
                            :aria-pressed="answers[currentQuestion.id] === opt.key"
                            class="flex min-h-14 w-full cursor-pointer items-center gap-4 rounded-xl border px-5 py-3 text-left text-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600"
                            :class="answers[currentQuestion.id] === opt.key
                                ? 'border-red-600 bg-red-50 font-semibold text-red-800'
                                : 'border-stone-200 text-stone-800 hover:bg-stone-50'">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-current text-xs font-bold"
                                x-text="opt.key"></span>
                            <span class="flex-1 leading-snug" x-text="opt.text"></span>
                            <span x-show="answers[currentQuestion.id] === opt.key" class="ml-auto shrink-0 text-red-600">
                                <x-icon name="check" class="h-5 w-5" />
                            </span>
                        </button>
                    </template>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($likertChoices as $choice)
                        <button type="button"
                            @click="selectAnswer(currentQuestion.id, {{ $choice['value'] }})"
                            :aria-pressed="Number(answers[currentQuestion.id]) === {{ $choice['value'] }}"
                            class="flex min-h-14 w-full cursor-pointer items-center gap-4 rounded-xl border px-5 py-3 text-left text-sm transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600"
                            :class="Number(answers[currentQuestion.id]) === {{ $choice['value'] }}
                                ? 'border-red-600 bg-red-50 font-semibold text-red-800'
                                : 'border-stone-200 text-stone-800 hover:bg-stone-50'">
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-current text-xs font-bold">
                                {{ $choice['value'] }}
                            </span>
                            <span class="flex-1">{{ $choice['label'] }}</span>
                            <span x-show="Number(answers[currentQuestion.id]) === {{ $choice['value'] }}" class="ml-auto shrink-0 text-red-600">
                                <x-icon name="check" class="h-5 w-5" />
                            </span>
                        </button>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Quick Question Navigator Dots -->
        <div class="mt-5 flex flex-wrap gap-1.5">
            <template x-for="(q, idx) in questions" :key="'dot-' + q.id">
                <button type="button" @click="currentIndex = idx"
                    class="h-8 min-w-8 cursor-pointer rounded-lg border px-2 text-xs font-semibold transition"
                    :class="idx === currentIndex
                        ? 'border-red-600 bg-[#E50000] text-white'
                        : (answers[q.id] ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-stone-200 bg-white text-stone-600 hover:bg-stone-50')"
                    x-text="idx + 1"></button>
            </template>
        </div>

        <!-- Prev / Next Buttons -->
        <div class="mt-6 flex items-center justify-between gap-3">
            <button type="button" :disabled="currentIndex === 0" @click="currentIndex--"
                class="min-h-12 cursor-pointer rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 transition hover:bg-stone-50 disabled:cursor-not-allowed disabled:opacity-50">
                Kembali
            </button>

            <button type="button"
                :disabled="!answers[currentQuestion.id]"
                @click="nextOrConfirm()"
                class="min-h-12 cursor-pointer rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50">
                <span x-text="currentIndex === questions.length - 1 ? 'Lihat Hasil Saya' : 'Selanjutnya →'"></span>
            </button>
        </div>

        <!-- Submit Confirmation Panel -->
        <div x-show="showConfirm" style="display: none;" class="mt-6 rounded-2xl border border-red-200 bg-white p-6 shadow-sm">
            <h3 class="font-display text-lg font-semibold text-[#292C33]">Siap mengenali potensimu?</h3>
            <p class="my-3 text-sm leading-6 text-slate-600">
                Pastikan semua jawaban dan data dirimu sudah lengkap. {{ $assessment->disclaimer }}
            </p>
            <div class="flex flex-wrap gap-3">
                <button type="submit" :disabled="submitting || answeredCount < questions.length"
                    class="min-h-12 cursor-pointer rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60">
                    <span x-text="submitting ? 'Menghitung hasil…' : 'Ya, Selesaikan Asesmen'"></span>
                </button>
                <button type="button" @click="showConfirm = false"
                    class="min-h-12 cursor-pointer rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                    Periksa Lagi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function siapQuizRunner() {
    const questions = @json($questionsPayload);
    const initialAnswers = @json($initialAnswers);
    const initialBiodata = @json($initialBiodata);

    const hasCompleteBiodata = Boolean(
        initialBiodata.participant_name &&
        initialBiodata.google_email &&
        initialBiodata.birth_place &&
        initialBiodata.birth_date &&
        initialBiodata.whatsapp &&
        initialBiodata.address
    );

    const firstUnanswered = questions.findIndex(q => !initialAnswers[q.id]);

    return {
        questions,
        answers: initialAnswers || {},
        biodata: initialBiodata,
        showBiodata: !hasCompleteBiodata || {{ $errors->any() ? 'true' : 'false' }},
        currentIndex: firstUnanswered === -1 ? 0 : firstUnanswered,
        timeRemaining: {{ (int) $remainingSeconds }},
        timeSpent: 0,
        interval: null,
        showConfirm: false,
        submitting: false,
        isAutoSubmitting: false,
        saveStatus: '',

        get currentQuestion() {
            return this.questions[this.currentIndex] || { id: 0, text: '', options: [] };
        },

        get answeredCount() {
            return this.questions.filter(q => Boolean(this.answers[q.id])).length;
        },

        init() {
            this.startCountdown();
        },

        startCountdown() {
            if (this.interval) clearInterval(this.interval);
            this.interval = setInterval(() => {
                if (this.timeRemaining > 0) {
                    this.timeRemaining--;
                    this.timeSpent++;
                }
                if (this.timeRemaining <= 0) {
                    this.autoSubmitOnTimeUp();
                }
            }, 1000);
        },

        formatTime(seconds) {
            if (seconds < 0) return '00:00';
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        },

        selectAnswer(questionId, value) {
            this.answers = { ...this.answers, [questionId]: value };
            this.autosaveProgress();
        },

        autosaveProgress() {
            this.saveStatus = 'Menyimpan…';
            fetch(@json($saveProgressUrl), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    answers: this.answers,
                    ...this.biodata,
                }),
            })
            .then(res => {
                this.saveStatus = res.ok ? 'Tersimpan otomatis' : '';
            })
            .catch(() => {
                this.saveStatus = '';
            });
        },

        nextOrConfirm() {
            if (this.currentIndex < this.questions.length - 1) {
                this.currentIndex++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                this.showConfirm = true;
            }
        },

        handleFormSubmit(e) {
            if (this.isAutoSubmitting) {
                return true;
            }

            const form = document.getElementById('siapQuizForm');
            if (form && !form.checkValidity()) {
                this.showBiodata = true;
                e.preventDefault();
                this.$nextTick(() => form.reportValidity());
                return false;
            }

            if (this.answeredCount < this.questions.length) {
                e.preventDefault();
                alert(`Harap lengkapi semua ${this.questions.length} pertanyaan sebelum menyelesaikan asesmen.`);
                return false;
            }

            if (this.interval) clearInterval(this.interval);
            this.submitting = true;
            return true;
        },

        autoSubmitOnTimeUp() {
            if (this.interval) clearInterval(this.interval);
            if (this.isAutoSubmitting) return;
            this.isAutoSubmitting = true;

            const autoInput = document.getElementById('siap_auto_submitted');
            if (autoInput) autoInput.value = '1';

            const form = document.getElementById('siapQuizForm');
            if (form) {
                form.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
                form.noValidate = true;
                form.submit();
            }
        }
    };
}
</script>
@endpush
