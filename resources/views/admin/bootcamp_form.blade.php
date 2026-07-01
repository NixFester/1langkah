@extends('layouts.app')

@section('title', isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp')

@section('content')
<div x-data="{ bootcampType: '{{ old('type', $bootcamp->type ?? '') }}', sessions: [] }" class="px-6 py-8 sm:px-10 w-full max-w-5xl mx-auto space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp Baru' }}</h1>
            <p class="text-sm text-gray-500 mt-1">Form ini digunakan untuk menambah atau mengubah detail program bootcamp.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.bootcamps') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <ul class="text-sm font-medium list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- FORM CARD -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <form method="POST" action="{{ isset($bootcamp) ? route('admin.bootcamps.update', $bootcamp) : route('admin.bootcamps.store') }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @if(isset($bootcamp))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Bootcamp <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $bootcamp->title ?? '') }}" placeholder="Masukkan judul bootcamp" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Mentor Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Mentor <span class="text-red-500">*</span></label>
                    <input type="text" name="mentor_name" value="{{ old('mentor_name', $bootcamp->mentor_name ?? '') }}" placeholder="Nama mentor utama" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Bootcamp <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="type" x-model="bootcampType" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="online" {{ old('type', $bootcamp->type ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ old('type', $bootcamp->type ?? '') === 'offline' ? 'selected' : '' }}>Offline</option>
                        </select>
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                    <input type="text" name="price" value="{{ old('price', $bootcamp->price ?? '') }}" placeholder="Contoh: 6500000 (tanpa Rp/titik)" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Start Date (Text based) -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="text" name="start_date" value="{{ old('start_date', $bootcamp->start_date ?? '') }}" placeholder="Contoh: 11 Agu 2026" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Sessions Info -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Info Sesi</label>
                    <input type="text" name="sessions_info" value="{{ old('sessions_info', $bootcamp->sessions_info ?? '') }}" placeholder="Contoh: 7 sesi LIVE via Zoom" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Location (Only for offline) -->
                <div x-show="bootcampType === 'offline'" class="transition-all">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $bootcamp->location ?? '') }}" placeholder="Contoh: gedung A, Jakarta" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Color -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Warna Utama (Opsional)</label>
                    <input type="text" name="color" value="{{ old('color', $bootcamp->color ?? '') }}" placeholder="Kode hex, cth: #667eea" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
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
    </div>

    @if(!isset($bootcamp))
    <!-- ADD SESSIONS SECTION (Only shown when creating new bootcamp) -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Tambah Jadwal Sesi</h3>
                <p class="text-xs text-gray-500 mt-1">Tambahkan jadwal sesi untuk bootcamp ini (opsional)</p>
            </div>
            <button type="button" @click="sessions.push({ date: '', topic: '', time: '', meeting_url: '', description: '' })" class="bg-gray-900 hover:bg-black text-white text-sm font-bold py-2 px-4 rounded-full flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Sesi
            </button>
        </div>

        <!-- Dynamic Sessions List -->
        <div class="p-6 space-y-4" x-show="sessions.length > 0">
            <template x-for="(session, index) in sessions" :key="index">
                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 relative">
                    <button type="button" @click="sessions.splice(index, 1)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Sesi <span class="text-red-500">*</span></label>
                            <input type="text" x-model="session.date" placeholder="Contoh: 15 Agu 2026, 09:00 WIB" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Topik Sesi <span class="text-red-500">*</span></label>
                            <input type="text" x-model="session.topic" placeholder="Contoh: Pengenalan React Native" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Waktu <span class="text-red-500">*</span></label>
                            <input type="text" x-model="session.time" placeholder="Contoh: 14:00 - 16:00 WIB" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div x-show="bootcampType === 'online'">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Meeting URL</label>
                            <input type="url" x-model="session.meeting_url" placeholder="https://zoom.us/j/xxx" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi</label>
                            <textarea x-model="session.description" rows="1" placeholder="Deskripsi singkat sesi (opsional)" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg p-2.5 focus:ring-gray-900 focus:border-gray-900 resize-none"></textarea>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div x-show="sessions.length === 0" class="p-8 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-gray-500 text-sm">Klik "Tambah Sesi" untuk menambahkan jadwal sesi</p>
        </div>
    </div>
    @endif

    @if(isset($bootcamp))
    <!-- SESSIONS SECTION (Shown when editing existing bootcamp) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ADD SESSION -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-1 h-fit">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Tambah Jadwal Sesi</h3>
                <p class="text-xs text-gray-500 mt-1">
                    <template x-if="bootcampType === 'online'">
                        <span>Meeting URL diperlukan untuk sesi online</span>
                    </template>
                    <template x-if="bootcampType === 'offline' || bootcampType === ''">
                        <span>Offline bootcamp tidak memerlukan URL</span>
                    </template>
                </p>
            </div>
            <form method="POST" action="{{ route('admin.bootcamps.sessions.store', $bootcamp) }}" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Sesi <span class="text-red-500">*</span></label>
                    <input type="text" name="date" placeholder="Contoh: 15 Agu 2026, 09:00 WIB" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Topik Sesi <span class="text-red-500">*</span></label>
                    <input type="text" name="topic" placeholder="Contoh: Pengenalan React Native" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Waktu <span class="text-red-500">*</span></label>
                    <input type="text" name="time" placeholder="Contoh: 14:00 - 16:00 WIB" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <!-- Meeting URL - Only for online bootcamps -->
                <div x-show="bootcampType === 'online'">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Meeting URL <span class="text-red-500">*</span></label>
                    <input type="url" name="meeting_url" placeholder="https://zoom.us/j/xxx atau Google Meet link" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="description" rows="2" placeholder="Deskripsi singkat sesi (opsional)" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-gray-900 focus:border-gray-900 block p-3 transition-colors resize-none"></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors shadow-lg w-full flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Sesi
                    </button>
                </div>
            </form>
        </div>

        <!-- LIST SESSIONS -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-2">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Daftar Jadwal Sesi</h3>
                <span class="bg-gray-200 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-full">{{ $bootcamp->sessions->count() }} Sesi</span>
            </div>

            @if($bootcamp->sessions->isEmpty())
                <div class="p-8 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Belum ada jadwal sesi untuk bootcamp ini.</p>
                </div>
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
        </div>

    </div>
    @endif

</div>
@endsection
