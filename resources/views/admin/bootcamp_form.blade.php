@extends('layouts.app')

@section('title', isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp')

@section('content')
<div x-data="{ bootcampType: '{{ old('type', $bootcamp->type ?? '') }}', sessions: [] }" class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp Baru'"
        description="Form ini digunakan untuk menambah atau mengubah detail program bootcamp."
    >
        <x-slot:actionSlot>
            <a href="{{ route('admin.bootcamps') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </x-slot:actionSlot>
    </x-admin-page-header>

    <x-flash-messages />

    <!-- FORM CARD -->
    <x-form-card>
        <form method="POST" action="{{ isset($bootcamp) ? route('admin.bootcamps.update', $bootcamp) : route('admin.bootcamps.store') }}" class="space-y-6">
            @csrf
            @if(isset($bootcamp))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <x-form-input
                        name="title"
                        label="Judul Bootcamp"
                        placeholder="Masukkan judul bootcamp"
                        :required="true"
                        :value="$bootcamp->title ?? null"
                    />
                </div>

                <!-- Mentor Name -->
                <x-form-input
                    name="mentor_name"
                    label="Nama Mentor"
                    placeholder="Nama mentor utama"
                    :required="true"
                    :value="$bootcamp->mentor_name ?? null"
                />

                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Bootcamp <span class="text-red-500">*</span></label>
                    <select name="type" x-model="bootcampType" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="online" {{ old('type', $bootcamp->type ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ old('type', $bootcamp->type ?? '') === 'offline' ? 'selected' : '' }}>Offline</option>
                    </select>
                </div>

                <!-- Price -->
                <x-form-input
                    name="price"
                    label="Harga"
                    placeholder="Contoh: 6500000 (tanpa Rp/titik)"
                    :required="true"
                    :value="$bootcamp->price ?? null"
                />

                <!-- Start Date -->
                <x-form-input
                    name="start_date"
                    label="Tanggal Mulai"
                    placeholder="Contoh: 11 Agu 2026"
                    :required="true"
                    :value="$bootcamp->start_date ?? null"
                />

                <!-- Sessions Info -->
                <x-form-input
                    name="sessions_info"
                    label="Info Sesi"
                    placeholder="Contoh: 7 sesi LIVE via Zoom"
                    :value="$bootcamp->sessions_info ?? null"
                />

                <!-- Location (Only for offline) -->
                <div x-show="bootcampType === 'offline'" class="transition-all">
                    <x-form-input
                        name="location"
                        label="Lokasi"
                        placeholder="Contoh: gedung A, Jakarta"
                        :value="$bootcamp->location ?? null"
                    />
                </div>

                <!-- Color -->
                <div class="md:col-span-2">
                    <x-form-input
                        name="color"
                        label="Warna Utama (Opsional)"
                        placeholder="Kode hex, cth: #667eea"
                        :value="$bootcamp->color ?? null"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <x-form-input
                    name="short_description"
                    label="Deskripsi Singkat"
                    placeholder="Ringkasan singkat bootcamp"
                    :value="$bootcamp->short_description ?? null"
                />

                <x-form-input
                    name="description"
                    type="textarea"
                    label="Deskripsi Lengkap"
                    :rows="4"
                    placeholder="Deskripsi lengkap bootcamp"
                    :value="$bootcamp->description ?? null"
                />
            </div>

            <!-- Hidden sessions data for submission -->
            <template x-for="(session, index) in sessions" :key="index">
                <div>
                    <input type="hidden" :name="'sessions[' + index + '][date]'" :value="session.date">
                    <input type="hidden" :name="'sessions[' + index + '][topic]'" :value="session.topic">
                    <input type="hidden" :name="'sessions[' + index + '][time]'" :value="session.time">
                    <input type="hidden" :name="'sessions[' + index + '][meeting_url]'" :value="session.meeting_url">
                    <input type="hidden" :name="'sessions[' + index + '][description]'" :value="session.description">
                </div>
            </template>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($bootcamp) ? 'Simpan Perubahan' : '+ Tambah Bootcamp' }}
                </button>
            </div>
        </form>
    </x-admin.form-card>

    @if(isset($bootcamp))
    <!-- SESSIONS SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ADD SESSION -->
        <x-form-card title="Tambah Jadwal Sesi" subtitle="Meeting URL diperlukan untuk sesi online" class="lg:col-span-1 h-fit">
            <form method="POST" action="{{ route('admin.bootcamps.sessions.store', $bootcamp) }}" class="space-y-4">
                @csrf
                <x-form-input name="date" label="Tanggal Sesi" placeholder="Contoh: 15 Agu 2026, 09:00 WIB" :required="true" />
                <x-form-input name="topic" label="Topik Sesi" placeholder="Contoh: Pengenalan React Native" :required="true" />
                <x-form-input name="time" label="Waktu" placeholder="Contoh: 14:00 - 16:00 WIB" :required="true" />
                <div x-show="bootcampType === 'online'">
                    <x-form-input name="meeting_url" type="url" label="Meeting URL (Wajib untuk online)" placeholder="https://zoom.us/j/xxx atau Google Meet link" />
                </div>
                <x-form-input name="description" type="textarea" label="Deskripsi" :rows="2" placeholder="Deskripsi singkat sesi (opsional)" />
                <div class="pt-2">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors shadow-lg w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Sesi
                    </button>
                </div>
            </form>
        </x-admin.form-card>

        <!-- LIST SESSIONS -->
        <x-form-card title="Daftar Jadwal Sesi" class="lg:col-span-2">
            @if($bootcamp->sessions->isEmpty())
                <x-empty-state message="Belum ada jadwal sesi untuk bootcamp ini." icon="calendar" />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/30 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">No</th>
                                <th class="px-6 py-4 font-bold">Tanggal</th>
                                <th class="px-6 py-4 font-bold">Topik</th>
                                <th class="px-6 py-4 font-bold">Waktu</th>
                                @if($bootcamp->type === 'online')
                                    <th class="px-6 py-4 font-bold">Meeting URL</th>
                                    <th class="px-6 py-4 font-bold">Password</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($bootcamp->sessions as $index => $session)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $session->date }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700">{{ $session->topic }}</div>
                                    @if($session->description)
                                        <div class="text-xs text-gray-400 mt-1">{{ Str::limit($session->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="bg-blue-50 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $session->time }}</span>
                                </td>
                                @if($bootcamp->type === 'online')
                                    <td class="px-6 py-4">
                                        @if($session->meeting_url)
                                            <a href="{{ $session->meeting_url }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-800 underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                Buka Link
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">Belum ada URL</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-amber-50 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-md">{{ $session->password ?? '—' }}</span>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-admin.form-card>

    </div>
    @endif

    @if(isset($bootcamp))
    <!-- PICTURES SECTION -->
    <x-form-card title="Gambar Bootcamp">
        <!-- ADD PICTURE FORM -->
        <form method="POST" action="{{ route('admin.pictures.store', ['bootcamp', $bootcamp->id]) }}" class="p-6 border-b border-gray-100">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-form-input name="image_url" type="url" label="URL Gambar" placeholder="https://contoh.com/gambar.jpg" :required="true" />
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe</label>
                    <select name="type" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                        <option value="gallery">Gallery</option>
                        <option value="thumbnail">Thumbnail</option>
                    </select>
                </div>
                <x-form-input name="description" label="Deskripsi" placeholder="Deskripsi gambar (opsional)" />
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors flex items-center gap-2">
                    + Tambah Gambar
                </button>
            </div>
        </form>

        <!-- PICTURES GRID -->
        <x-picture-grid :pictures="$bootcamp->pictures()->orderBy('type')->get()" />
    </x-admin.form-card>
    @endif

</div>
@endsection
