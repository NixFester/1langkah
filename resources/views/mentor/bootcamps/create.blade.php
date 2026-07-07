@extends('layouts.mentor')

@section('title', 'Buat Bootcamp Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('mentor.bootcamps.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Buat Bootcamp Baru</h1>

        <form method="POST" action="{{ route('mentor.bootcamps.store') }}" class="space-y-6">
            @csrf

            {{-- Basic Info --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Bootcamp *</label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe *</label>
                        <select name="type" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                        <input type="text" name="price" required value="{{ old('price', 'Rp 0') }}"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai *</label>
                        <input type="text" name="start_date" required value="{{ old('start_date') }}"
                               placeholder="Contoh: 15 Jan 2025"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Peserta</label>
                        <input type="number" name="participants" value="{{ old('participants') }}" min="0"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('participants')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           placeholder="Untuk bootcamp offline/hybrid"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Description --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Deskripsi</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="255"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('short_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap</label>
                    <textarea name="description" rows="5"
                              class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Sessions --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Sesi Bootcamp</h3>

                <div id="sessions-container" class="space-y-3">
                    {{-- Sessions will be added here via JS or keep empty for manual addition later --}}
                </div>

                <button type="button" onclick="addSession()" class="px-4 py-2 border border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-600 text-sm">
                    + Tambah Sesi
                </button>
            </div>

            {{-- Appearance --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Tampilan</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                        <div class="flex items-center gap-2">
                            <input type="color" name="color" value="{{ old('color', '#3B82F6') }}"
                                   class="w-12 h-10 border border-gray-200 rounded-lg cursor-pointer">
                            <input type="text" name="color_text" value="{{ old('color', '#3B82F6') }}"
                                   class="flex-1 border border-gray-200 rounded-lg px-4 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Info Sesi</label>
                        <input type="text" name="sessions_info" value="{{ old('sessions_info') }}"
                               placeholder="Contoh: 8x pertemuan"
                               class="w-full border border-gray-200 rounded-lg px-4 py-2">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4 border-t">
                <a href="{{ route('mentor.bootcamps.index') }}" class="px-6 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                    Buat Bootcamp
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let sessionCount = 0;
function addSession() {
    sessionCount++;
    const container = document.getElementById('sessions-container');
    const html = `
        <div class="p-4 bg-gray-50 rounded-lg session-item">
            <div class="grid grid-cols-4 gap-3">
                <input type="text" name="sessions[${sessionCount}][date]" placeholder="Tanggal" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <input type="text" name="sessions[${sessionCount}][topic]" placeholder="Topik" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <input type="text" name="sessions[${sessionCount}][time]" placeholder="Waktu" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <input type="text" name="sessions[${sessionCount}][meeting_url]" placeholder="Meeting URL" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
            <input type="text" name="sessions[${sessionCount}][description]" placeholder="Deskripsi (opsional)" class="mt-2 w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            <button type="button" onclick="this.parentElement.remove()" class="mt-2 text-red-600 hover:text-red-800 text-sm">Hapus</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
@endpush
@endsection
