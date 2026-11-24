@extends('layouts.app', ['activePage' => 'siap.results'])

@section('title', 'Hasil Asesmen — SIAP 1Langkah')
@section('header_title', 'Hasil Asesmen SIAP')

@section('content')
<div class="mx-auto max-w-[1400px] px-1 pb-12 sm:px-2">
    <x-flash-messages />

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-xs text-slate-600">
        <a href="{{ route('siap.index') }}" class="inline-flex items-center gap-1.5 hover:text-[#E50000]">
            <x-icon name="grid" class="h-3.5 w-3.5" />
            <span>SIAP</span>
        </a>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">Hasil Asesmen</span>
    </div>

    @if(!$attempt)
        <section class="mx-auto max-w-2xl rounded-3xl border border-stone-200 bg-white p-8 text-center md:p-12">
            <span class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-50 text-blue-700">
                <x-icon name="sparkles" class="h-10 w-10" />
            </span>
            <h1 class="font-display text-2xl font-semibold text-[#292C33]">Petualanganmu belum dimulai</h1>
            <p class="mt-3 text-sm leading-7 text-slate-600">
                Isi asesmen untuk menemukan kecenderungan kepribadian &amp; kecerdasanmu. Hasil dihitung dari jawabanmu sendiri, bukan secara acak.
            </p>
            <div class="mt-6">
                <a href="{{ route('siap.index') }}"
                    class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Mulai Asesmen
                </a>
            </div>
        </section>
    @else
        @php
            $assessment = $attempt->assessment;
            $topScore = $scores[0] ?? null;
            $topCategory = $topScore ? $categoriesBySlug->get($topScore['slug'] ?? '') : null;
            $dominantProfile = $attempt->dominant_profile ?? [];
            $isCombination = (bool) ($dominantProfile['is_combination'] ?? false);
            $unlockPrice = $assessment?->unlock_price ?: 35000;
            $normalPrice = $assessment?->normal_price ?: 125000;
            $checkoutUrl = route('siap.checkout', [
                'product' => 'assessment_full',
                'assessment' => $assessment?->id,
                'attempt' => $attempt->id,
            ]);
        @endphp

        <!-- Attempt Switcher (when student has multiple completed attempts) -->
        @if($completedAttempts->count() > 1)
            <div class="mb-6 flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Riwayat Asesmen:</span>
                @foreach($completedAttempts as $item)
                    <a href="{{ route('siap.results', $item) }}"
                        class="rounded-full border px-3.5 py-1.5 text-xs font-semibold transition {{ $item->id === $attempt->id ? 'border-red-600 bg-[#FFF0EF] text-[#D70000]' : 'border-stone-200 bg-white text-slate-600 hover:bg-stone-50' }}">
                        {{ $item->assessment?->title }} · {{ $item->completed_at?->format('d M Y H:i') }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Email Delivery Banner -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-3.5 text-xs sm:text-sm text-blue-900">
            <div>
                <strong>{{ $attempt->is_full_unlocked ? '✓ Laporan Lengkap Telah Dikirim ke Email Google Anda' : '📩 Hasil Preview Telah Dikirim ke Email Google Anda' }}</strong>
                <span class="ml-1 text-blue-700">({{ $attempt->google_email ?? $attempt->user?->email }})</span>
            </div>
            <span class="rounded-full px-3 py-1 text-[11px] font-bold {{ $attempt->is_full_unlocked ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $attempt->is_full_unlocked ? 'FULL RESULT UNLOCKED' : 'MODE PREVIEW' }}
            </span>
        </div>

        <!-- Page Header -->
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-semibold leading-snug tracking-tight text-[#292C33] md:text-3xl">
                    Selamat, Kamu Berhasil Mengenali Potensimu!
                </h1>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    {{ $attempt->participant_name ?? $attempt->user?->name }} ·
                    {{ $assessment?->title }} ·
                    {{ $attempt->completed_at?->translatedFormat('d F Y') }} ·
                    <strong>{{ $attempt->is_full_unlocked ? 'FULL RESULT UNLOCKED' : 'Hasil eksplorasi awal' }}</strong>
                </p>
            </div>
            @if($certificate)
                <a href="{{ route('siap.certificates.print', $certificate) }}" target="_blank"
                    class="inline-flex min-h-11 items-center gap-2 rounded-xl border border-stone-200 bg-white px-4 py-2 text-sm font-semibold text-stone-800 shadow-sm hover:bg-stone-50">
                    <x-icon name="award" class="h-4 w-4 text-[#E50000]" />
                    <span>Lihat Sertifikat Asesmen</span>
                </a>
            @endif
        </div>

        <!-- Main Chart + Dominant #1 Highlight -->
        <div class="mt-7 grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <section class="rounded-2xl border border-stone-200 bg-white p-6">
                <h2 class="mb-6 font-display text-xl font-semibold text-[#292C33]">
                    {{ $assessment?->isForcedChoice() ? 'Hasil Profil Psycho Geometric' : 'Hasil Kecerdasanmu' }}
                </h2>

                <div class="space-y-5">
                    @foreach($scores as $i => $s)
                        @php
                            $isVisible = $attempt->is_full_unlocked || $i === 0;
                        @endphp
                        <div class="relative">
                            <div class="{{ !$isVisible ? 'pointer-events-none select-none blur-md' : '' }}" @if(!$isVisible) aria-hidden="true" @endif>
                                <div class="mb-2 flex justify-between gap-2 text-sm">
                                    <span class="font-medium text-[#292C33]">
                                        @if($isVisible)
                                            @if(!empty($s['symbol']))
                                                <span class="mr-1 font-bold" style="color: {{ $s['color'] }}">{{ $s['symbol'] }}</span>
                                            @endif
                                            {{ $s['name'] }}
                                        @else
                                            Kecenderungan terkunci
                                        @endif
                                    </span>
                                    <strong>{{ $isVisible ? $s['percentage'] . '%' : '••%' }}</strong>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-stone-100">
                                    <div class="h-full rounded-full transition-all"
                                        style="width: {{ $isVisible ? max(4, $s['percentage']) : max(20, 82 - $i * 8) }}%; background: {{ $isVisible ? $s['color'] : '#CBD5E1' }};"></div>
                                </div>
                            </div>

                            @if(!$isVisible)
                                <span class="absolute inset-0 flex items-center justify-center gap-2 text-xs font-semibold text-slate-700">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="10" width="12" height="12" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4"/></svg>
                                    Dominasi #{{ $i + 1 }} terkunci
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <p class="mt-6 text-xs leading-5 text-slate-600">
                    @if($assessment?->isForcedChoice())
                        Persentase dihitung dari jumlah pilihan setiap bentuk dibagi 25 pertanyaan. Profil dapat berupa kombinasi jika skor tertinggi sama atau berdekatan.
                    @else
                        Persentase dihitung dari skor jawaban dibanding skor maksimum setiap kategori. Semua orang memiliki kedelapan kecerdasan.
                    @endif
                </p>
            </section>

            <!-- Right Column: Dominant #1 + Paywall CTA -->
            <div class="space-y-5">
                @if($topScore && $topCategory)
                    <div class="rounded-2xl bg-[#EDE8F7] p-6">
                        <div class="mb-3 flex items-center justify-between">
                            @if($topCategory->symbol)
                                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-2xl font-bold" style="color: {{ $topCategory->color }}">
                                    {{ $topCategory->symbol }}
                                </span>
                            @else
                                <span style="color: {{ $topCategory->color }}">
                                    <x-icon :name="$topCategory->icon ?: 'sparkles'" class="h-9 w-9" />
                                </span>
                            @endif
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-purple-800">
                                DOMINASI #1 · {{ $topScore['percentage'] }}%
                            </span>
                        </div>

                        @if($isCombination)
                            <div class="mb-2 inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[11px] font-bold text-amber-800">
                                {{ $dominantProfile['label'] }}
                            </div>
                        @endif

                        <h2 class="my-2 font-display text-2xl font-semibold text-[#292C33]">{{ $topCategory->name }}</h2>
                        @if($topCategory->label)
                            <p class="mb-2 text-xs font-semibold text-purple-800">{{ $topCategory->label }}</p>
                        @endif
                        <p class="text-sm leading-7 text-slate-700">{{ $topCategory->description }}</p>

                        @if($assessment?->isForcedChoice() && !empty($topCategory->details['love_language']))
                            <p class="mt-4 rounded-xl bg-white/75 p-3 text-xs leading-6 text-slate-700">
                                <strong>Love Language:</strong> {{ $topCategory->details['love_language'] }}
                            </p>
                        @elseif(!empty($topCategory->details['activity']))
                            <p class="mt-4 text-sm font-medium text-[#292C33]">
                                Coba: {{ strtolower($topCategory->details['activity']) }}.
                            </p>
                        @endif
                    </div>
                @endif

                @if(!$attempt->is_full_unlocked)
                    <div class="rounded-2xl border border-red-200 bg-white p-6 shadow-sm">
                        <span class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="10" width="12" height="12" rx="2"/><path d="M8 10V6a4 4 0 0 1 8 0v4"/></svg>
                        </span>
                        <h3 class="font-display text-xl font-semibold text-[#292C33]">Buka Seluruh Hasil Analisis &amp; Sertifikat!</h3>
                        <p class="my-3 text-sm leading-6 text-slate-600">
                            Buka paket hasil lengkap untuk melihat diagram dominasi kedua hingga terakhir, panduan lengkap (Love Language, gaya belajar, komunikasi, kerja tim, karier &amp; latihan mingguan), serta Sertifikat Asesmen SIAP.
                        </p>
                        <p class="mb-4 text-xl font-bold text-red-600">
                            Rp{{ number_format($unlockPrice, 0, ',', '.') }}
                            <span class="text-sm font-normal text-slate-500 line-through">Rp{{ number_format($normalPrice, 0, ',', '.') }}</span>
                        </p>
                        <div class="flex flex-col gap-2.5">
                            <a href="{{ $checkoutUrl }}"
                                class="flex min-h-12 w-full items-center justify-center rounded-xl bg-[#E50000] px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                Buka Hasil Lengkap – Rp{{ number_format($unlockPrice, 0, ',', '.') }}
                            </a>
                            <a href="{{ route('siap.checkout', ['product' => 'bundle_siap', 'assessment' => $assessment?->id, 'attempt' => $attempt->id]) }}"
                                class="flex min-h-11 w-full items-center justify-center rounded-xl border border-red-200 bg-[#FFF3F0] px-4 py-2.5 text-xs font-semibold text-red-700 hover:bg-red-100">
                                Atau pilih Paket Lengkap SIAP (Rp{{ number_format($pricing['bundle_price'], 0, ',', '.') }})
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- PAID ONLY: All Category Detail Cards (NEVER rendered in DOM when unpaid) -->
        @if($attempt->is_full_unlocked)
            <div class="mt-8 grid gap-5 md:grid-cols-2">
                @foreach($scores as $i => $s)
                    @php
                        $cat = $categoriesBySlug->get($s['slug'] ?? '');
                        $details = $cat?->details ?? [];
                    @endphp
                    @if($cat)
                        <section class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    @if($cat->symbol)
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-xl font-bold" style="background-color: {{ $cat->color }}18; color: {{ $cat->color }}">
                                            {{ $cat->symbol }}
                                        </span>
                                    @else
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background-color: {{ $cat->color }}18; color: {{ $cat->color }}">
                                            <x-icon :name="$cat->icon ?: 'sparkles'" class="h-5 w-5" />
                                        </span>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-[#292C33]">{{ $cat->name }}</h3>
                                        @if($cat->label)
                                            <p class="text-xs font-medium" style="color: {{ $cat->color }}">{{ $cat->label }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($i < 3)
                                        <span class="block text-xs font-bold text-purple-700">DOMINASI #{{ $i + 1 }}</span>
                                    @endif
                                    <span class="text-sm font-bold text-[#292C33]">{{ $s['percentage'] }}%</span>
                                </div>
                            </div>

                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $cat->description }}</p>

                            @if($assessment?->isForcedChoice())
                                <dl class="mt-4 space-y-2.5 border-t border-stone-100 pt-4 text-xs sm:text-sm leading-6 text-slate-700">
                                    @foreach([
                                        'Love Language' => 'love_language',
                                        'Personal Strength' => 'personal_strength',
                                        'Learning Style & Strategy' => 'learning_style',
                                        'Communication Style' => 'communication_style',
                                        'Teamwork Style' => 'teamwork_style',
                                        'Academic Exploration' => 'academic_exploration',
                                        'Career Exploration' => 'career_exploration',
                                        'Entrepreneurship Potential' => 'entrepreneurship_potential',
                                        'Practice (Latihan Mingguan)' => 'practice',
                                    ] as $secTitle => $secKey)
                                        @if(!empty($details[$secKey]))
                                            <div>
                                                <dt class="font-semibold text-[#292C33]">{{ $secTitle }}</dt>
                                                <dd class="text-slate-600">{{ $details[$secKey] }}</dd>
                                            </div>
                                        @endif
                                    @endforeach
                                </dl>
                            @else
                                <div class="mt-4 space-y-2 border-t border-stone-100 pt-4 text-sm leading-6 text-slate-700">
                                    @if(!empty($details['activity']))
                                        <p><strong>Karakteristik &amp; aktivitas:</strong> {{ $details['activity'] }}.</p>
                                    @endif
                                    @if(!empty($details['major']))
                                        <p><strong>Pilihan jurusan relevan:</strong> {{ $details['major'] }}.</p>
                                    @endif
                                    @if(!empty($details['development_tips']))
                                        <p><strong>Pengembangan:</strong> {{ $details['development_tips'] }}</p>
                                    @endif
                                </div>
                            @endif
                        </section>
                    @endif
                @endforeach
            </div>

            <!-- Highlighted Recommendation Table -->
            @if(!empty($recommendations))
                <section class="mt-8 overflow-hidden rounded-3xl border-2 border-red-200 bg-white shadow-lg shadow-red-100/50">
                    <div class="border-b border-red-200 bg-[#FFF0EF] p-6 md:p-8">
                        <span class="mb-3 inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-red-700">
                            <x-icon name="sparkles" class="h-4 w-4" />
                            REKOMENDASI PERSONAL
                        </span>
                        <h2 class="max-w-2xl font-display text-2xl font-semibold leading-snug text-[#292C33]">
                            Langkah Selanjutnya untuk Mengembangkan Potensimu
                        </h2>
                        <p class="mt-2 text-sm leading-7 text-slate-700">
                            Rencana eksplorasi berdasarkan kecenderungan teratasmu:
                            <strong>{{ collect(array_slice($scores, 0, 3))->pluck('name')->implode(', ') }}</strong>.
                        </p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[560px] text-left text-sm">
                            <caption class="sr-only">Rekomendasi pengembangan personal berdasarkan hasil asesmen</caption>
                            <thead class="bg-stone-50">
                                <tr>
                                    <th scope="col" class="w-1/4 px-6 py-4 font-semibold text-[#292C33]">Fokus Pengembangan</th>
                                    <th scope="col" class="px-6 py-4 font-semibold text-[#292C33]">Rekomendasi untuk Kamu</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-200">
                                @foreach($recommendations as $row)
                                    <tr class="even:bg-red-50/40">
                                        <th scope="row" class="px-6 py-5 align-top font-semibold text-red-800">{{ $row['focus'] }}</th>
                                        <td class="px-6 py-5 leading-7 text-slate-700">{{ $row['recommendation'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="border-t border-red-100 bg-red-50/50 px-6 py-4 text-sm leading-6 text-slate-600">
                        Mulai dari satu aktivitas kecil yang kamu sukai. Rekomendasi ini adalah bahan eksplorasi, bukan penentu mutlak masa depanmu.
                    </p>
                </section>
            @endif
        @endif

        <!-- Disclaimer & Participant Summary -->
        <p class="mt-6 text-sm leading-6 text-slate-600">
            {{ $assessment?->disclaimer }}
        </p>

        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-stone-200 pt-6">
            <a href="{{ route('siap.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-stone-200 bg-white px-5 py-2.5 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                &larr; Kembali ke Menu Asesmen
            </a>
        </div>
    @endif
</div>
@endsection
