@extends('layouts.mentor')

@section('title', 'Edit Biodata Mentor')

@section('header_title', 'Edit Biodata Mentor')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Biodata Mentor</h1>

        <form method="POST" action="{{ route('mentor.profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- Basic Info --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Informasi Dasar</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" required value="{{ old('name', $mentor->name) }}"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role / Jabatan *</label>
                    <input type="text" name="role" required value="{{ old('role', $mentor->role) }}"
                           placeholder="Contoh: Senior Developer, Data Scientist"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Perusahaan</label>
                    <input type="text" name="company" value="{{ old('company', $mentor->company) }}"
                           placeholder="Contoh: Google, Tokopedia"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('company')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Sesi</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="text" name="price" value="{{ old('price', $mentor->price) }}"
                               placeholder="50000"
                               class="w-full border border-gray-200 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <p class="text-gray-500 text-xs mt-1">Kosongkan atau isi 0 untuk gratis</p>
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Expertise --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Keahlian (Expertise)</h3>

                <div x-data="{
                    expertise: {{ json_encode(old('expertise', $mentor->expertise ?? [])) }},
                    newExpertise: '',
                    addExpertise() {
                        if (this.newExpertise.trim() && !this.expertise.includes(this.newExpertise.trim())) {
                            this.expertise.push(this.newExpertise.trim());
                            this.newExpertise = '';
                        }
                    },
                    removeExpertise(index) {
                        this.expertise.splice(index, 1);
                    }
                }">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tambahkan Keahlian</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" x-model="newExpertise" @keydown.enter.prevent="addExpertise()"
                               placeholder="Contoh: Laravel, React, Python"
                               class="flex-1 border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" @click="addExpertise()"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                            Tambah
                        </button>
                    </div>

                    {{-- Hidden input to submit expertise array --}}
                    <template x-for="(exp, index) in expertise" :key="index">
                        <input type="hidden" name="expertise[]" :value="exp">
                    </template>

                    <div class="flex flex-wrap gap-2 mt-2">
                        <template x-for="(exp, index) in expertise" :key="index">
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                <span x-text="exp"></span>
                                <button type="button" @click="removeExpertise(index)" class="hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        </template>
                    </div>
                    <p x-show="expertise.length === 0" class="text-gray-400 text-sm mt-2">Belum ada keahlian ditambahkan</p>
                </div>
                @error('expertise')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bio --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Bio</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Diri</label>
                    <textarea name="bio" rows="4"
                              placeholder="Ceritakan tentang pengalaman dan keahlian Anda..."
                              class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bio', $mentor->bio) }}</textarea>
                    <p class="text-gray-500 text-xs mt-1">Maksimal 2000 karakter</p>
                    @error('bio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Contact --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Informasi Kontak</h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $mentor->linkedin_url) }}"
                           placeholder="https://linkedin.com/in/username"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('linkedin_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                    <input type="tel" name="phone" value="{{ old('phone', $mentor->phone) }}"
                           placeholder="081234567890"
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Available Days --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-gray-900 border-b pb-2">Hari Tersedia</h3>

                <div x-data="{
                    availableDays: {{ json_encode(old('available_days', $availableDays ?? [])) }},
                    toggleDay(day) {
                        const index = this.availableDays.indexOf(day);
                        if (index > -1) {
                            this.availableDays.splice(index, 1);
                        } else {
                            this.availableDays.push(day);
                        }
                    },
                    isSelected(day) {
                        return this.availableDays.includes(day);
                    }
                }">
                    <p class="text-sm text-gray-600 mb-3">Pilih hari-hari ketika Anda tersedia untuk mentoring</p>

                    {{-- Hidden inputs --}}
                    <template x-for="day in availableDays" :key="day">
                        <input type="hidden" name="available_days[]" :value="day">
                    </template>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @php
                            $days = [
                                0 => ['label' => 'Minggu', 'short' => 'Min'],
                                1 => ['label' => 'Senin', 'short' => 'Sen'],
                                2 => ['label' => 'Selasa', 'short' => 'Sel'],
                                3 => ['label' => 'Rabu', 'short' => 'Rab'],
                                4 => ['label' => 'Kamis', 'short' => 'Kam'],
                                5 => ['label' => 'Jumat', 'short' => 'Jum'],
                                6 => ['label' => 'Sabtu', 'short' => 'Sab'],
                            ];
                        @endphp
                        @foreach($days as $index => $day)
                            <button type="button"
                                    @click="toggleDay({{ $index }})"
                                    :class="isSelected({{ $index }}) ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-gray-700 border-gray-200 hover:border-blue-300'"
                                    class="px-4 py-3 border rounded-lg text-sm font-medium transition flex flex-col items-center">
                                <span x-text="isSelected({{ $index }}) ? '{{ $day['label'] }}' : '{{ $day['short'] }}'"></span>
                                <svg x-show="isSelected({{ $index }})" class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        @endforeach
                    </div>
                </div>
                @error('available_days')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('mentor.dashboard') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
