@extends('layouts.mentor')

@section('title', 'Buat Event Baru')
@section('header_title', 'Buat Event Baru')

@section('content')
    <x-flash-messages />

    {{-- Back Button --}}
    <a href="{{ route('mentor.events') }}"
       class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Kembali ke Event Saya
    </a>

    {{-- Form Card --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-6">Detail Event</h2>

        <form method="POST" action="{{ route('mentor.events.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Judul Event <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Workshop React Dasar"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tipe Event <span class="text-red-500">*</span>
                    </label>
                    <select name="type" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 cursor-pointer transition-colors">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="online" {{ old('type') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ old('type') === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="hybrid" {{ old('type') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Max Participants --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Maksimal Peserta
                    </label>
                    <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1"
                           placeholder="Kosongkan jika tidak terbatas"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('max_participants')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Start Date --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tanggal Selesai
                    </label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Lokasi
                    </label>
                    <input type="text" name="location" value="{{ old('location') }}"
                           placeholder="Contoh: Zoom / Jakarta"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Meeting URL --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Tautan Meeting
                    </label>
                    <input type="url" name="meeting_url" value="{{ old('meeting_url') }}"
                           placeholder="https://zoom.us/..."
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('meeting_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Short Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Deskripsi Singkat
                    </label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="300"
                           placeholder="Ringkasan singkat event (maks 300 karakter)"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    @error('short_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Deskripsi Lengkap
                    </label>
                    <textarea name="description" rows="5"
                              placeholder="Ceritakan detail event ini, termasuk jadwal, materi, dan manfaat untuk peserta"
                              class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors resize-y">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Banner URL --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        URL Banner
                    </label>
                    <input type="url" name="banner_url" value="{{ old('banner_url') }}"
                           placeholder="https://..."
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Masukkan URL gambar banner event (opsional)</p>
                    @error('banner_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors">
                    + Buat Event
                </button>
            </div>
        </form>
    </div>
@endsection
