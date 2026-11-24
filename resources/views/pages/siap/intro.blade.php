@extends('layouts.app', ['activePage' => 'siap.index'])

@section('title', $assessment->title . ' — SIAP 1Langkah')
@section('header_title', $assessment->title)

@section('content')
<div x-data="siapStartModal()" class="mx-auto max-w-[1400px] px-1 pb-12 sm:px-2">
    <x-flash-messages />

    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-xs text-slate-600">
        <a href="{{ route('siap.index') }}" class="inline-flex items-center gap-1.5 hover:text-[#E50000]">
            <x-icon name="grid" class="h-3.5 w-3.5" />
            <span>SIAP</span>
        </a>
        <span class="px-1 text-stone-400">/</span>
        <span class="font-semibold text-stone-800">{{ $assessment->title }}</span>
    </div>

    <!-- Hero Title -->
    <div class="max-w-3xl">
        <h1 class="font-display text-2xl font-semibold leading-snug tracking-tight text-[#292C33] md:text-3xl">
            {{ $assessment->isForcedChoice() ? 'Kenali Kecenderungan Dirimu Lewat Lima Bentuk!' : 'Setiap Anak Itu Cerdas dengan Caranya Sendiri!' }}
        </h1>
        <p class="mt-3 text-sm leading-7 text-slate-600">
            {{ $assessment->description }}
        </p>
    </div>

    <!-- Categories Grid -->
    <div class="my-7 grid gap-4 md:grid-cols-2 xl:grid-cols-{{ $assessment->categories->count() <= 5 ? '5' : '4' }}">
        @foreach($assessment->categories as $category)
            <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    @if($category->symbol)
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl text-2xl font-bold" style="background-color: {{ $category->color }}18; color: {{ $category->color }}">
                            {{ $category->symbol }}
                        </span>
                    @else
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl" style="background-color: {{ $category->color }}18; color: {{ $category->color }}">
                            <x-icon :name="$category->icon ?: 'sparkles'" class="h-6 w-6" />
                        </span>
                    @endif
                </div>
                <h2 class="font-semibold text-[#292C33]">{{ $category->name }}</h2>
                @if($category->label)
                    <p class="mt-1 text-xs font-semibold" style="color: {{ $category->color }}">{{ $category->label }}</p>
                @endif
                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $category->description }}</p>
            </div>
        @endforeach
    </div>

    <!-- Info + Start Banner -->
    <div class="flex flex-wrap items-center justify-between gap-5 rounded-2xl bg-[#EAF3F5] p-6">
        <div class="space-y-1 text-sm leading-6 text-[#292C33]">
            <p class="font-semibold">
                {{ $assessment->question_count }} pertanyaan
            </p>
            <p class="text-slate-600">
                Tidak ada jawaban benar atau salah. Jawab secara spontan dan jujur sesuai kebiasaanmu.
            </p>
            @if($latestCompleted)
                <p class="text-xs font-semibold text-[#3F6F76]">
                    Diselesaikan {{ $latestCompleted->completed_at?->diffForHumans() }} ·
                    <a href="{{ route('siap.results', $latestCompleted) }}" class="underline hover:text-[#E50000]">Lihat hasil asesmen</a>
                </p>
            @endif
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('siap.index') }}"
                class="inline-flex min-h-12 items-center justify-center rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 transition hover:bg-stone-50">
                Kembali
            </a>
            @if($latestCompleted)
                <a href="{{ route('siap.results', $latestCompleted) }}"
                    class="inline-flex min-h-12 items-center justify-center rounded-xl bg-stone-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-black">
                    Lihat Hasil Asesmen
                </a>
            @else
                <button type="button" @click="openModal()"
                    class="inline-flex min-h-12 cursor-pointer items-center justify-center rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-600 focus-visible:ring-offset-4">
                    {{ $inProgressAttempt ? 'Lanjutkan Pengisian Asesmen' : 'Mulai Pengisian Asesmen' }}
                </button>
            @endif
        </div>
    </div>

    <!-- Pre-Start Confirmation Modal (matching Figma StartDialog) -->
    <div x-show="open" style="display: none;" @keydown.escape.window="open = false"
        class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto bg-stone-950/50 p-5 backdrop-blur-sm"
        x-transition.opacity>
        <div @click.stop role="dialog" aria-modal="true" aria-labelledby="start-title" aria-describedby="start-description"
            class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl md:p-8">
            <span class="mb-5 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <x-icon name="sparkles" class="h-7 w-7" />
            </span>
            <h2 id="start-title" class="font-display text-2xl font-semibold text-[#292C33]">Siap mengenal dirimu lebih dekat?</h2>
            <p id="start-description" class="mt-3 text-sm leading-7 text-slate-600">
                Sebelum mulai, yuk siapkan diri dan lengkapi data dirimu agar hasil asesmen dapat dikirim ke email Google kamu.
            </p>
            <ul class="my-6 space-y-4">
                @foreach([
                    ['Pastikan internetmu stabil', 'Pilih tempat yang nyaman dengan koneksi internet yang baik.'],
                    ['Jawab dengan jujur', 'Pilih yang paling sesuai dengan dirimu. Tidak ada jawaban benar atau salah.'],
                    ['Tidak perlu terburu-buru', 'Baca setiap pertanyaan dengan tenang dan isi sesuai kebiasaanmu.'],
                ] as [$itemTitle, $itemText])
                    <li class="flex items-start gap-3">
                        <x-icon name="check" class="mt-1 h-5 w-5 text-green-700" />
                        <div>
                            <h3 class="text-sm font-semibold text-[#292C33]">{{ $itemTitle }}</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-600">{{ $itemText }}</p>
                        </div>
                    </li>
                @endforeach
                <li class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-3.5">
                    <x-icon name="shieldCheck" class="mt-0.5 h-5 w-5 shrink-0 text-amber-700" />
                    <div>
                        <h3 class="text-sm font-bold text-amber-900">Pengerjaan hanya dapat dilakukan 1x</h3>
                        <p class="mt-1 text-sm leading-6 text-amber-800">Pastikan kamu siap sebelum memulai. Setiap asesmen hanya dapat dikerjakan satu kali dan tidak dapat diulang.</p>
                    </div>
                </li>
            </ul>
            <div class="flex flex-wrap justify-end gap-3">
                <button type="button" @click="open = false"
                    class="min-h-12 cursor-pointer rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-800 hover:bg-stone-50">
                    Batal
                </button>
                <a href="{{ route('siap.take', $assessment) }}"
                    class="inline-flex min-h-12 items-center justify-center rounded-xl bg-[#E50000] px-6 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                    Tes Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function siapStartModal() {
    return {
        open: false,
        openModal() {
            this.open = true;
        }
    };
}
</script>
@endpush
