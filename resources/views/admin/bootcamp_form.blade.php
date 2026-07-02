@extends('layouts.app')

@section('title', isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp')

@section('content')
<div x-data="{ bootcampType: '{{ old('type', $bootcamp->type ?? '') }}', sessions: [] }" class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
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
    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <form method="POST" action="{{ isset($bootcamp) ? route('admin.bootcamps.update', $bootcamp) : route('admin.bootcamps.store') }}" class="p-6 space-y-6">
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

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $bootcamp->short_description ?? '') }}" placeholder="Ringkasan singkat bootcamp" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" rows="4" placeholder="Deskripsi lengkap bootcamp" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors resize-none">{{ old('description', $bootcamp->description ?? '') }}</textarea>
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
    
    @if(isset($bootcamp))
    <!-- SESSIONS SECTION (Shown when editing existing bootcamp) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- ADD SESSION -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-1 h-fit">
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
        <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden lg:col-span-2">
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

    @if(isset($bootcamp))
    <!-- PICTURES SECTION -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Gambar Bootcamp</h3>
        </div>
        <!-- ADD PICTURE FORM -->
        <form method="POST" action="{{ route('admin.pictures.store', ['bootcamp', $bootcamp->id]) }}" class="p-6 border-b border-gray-100">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Gambar <span class="text-red-500">*</span></label>
                    <input type="url" name="image_url" placeholder="https://contoh.com/gambar.jpg" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl p-3">
                    <p class="text-xs text-gray-500 mt-1">Masukkan URL gambar dari CDN</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe</label>
                    <select name="type" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                        <option value="gallery">Gallery</option>
                        <option value="thumbnail">Thumbnail</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                    <input type="text" name="description" placeholder="Deskripsi gambar (opsional)" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3">
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-gray-900 hover:bg-black text-white font-bold py-3 px-6 rounded-full text-sm transition-colors flex items-center gap-2">
                    + Tambah Gambar
                </button>
            </div>
        </form>

        <!-- PICTURES GRID -->
        @php $pictures = $bootcamp->pictures()->orderBy('type')->get() @endphp
        @if(count($pictures) === 0)
        <div class="p-8 text-center">
            <p class="text-gray-500 text-sm">Belum ada gambar untuk bootcamp ini.</p>
        </div>
        @else
        <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($pictures as $picture)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden relative group">
                <div class="aspect-video bg-gray-100">
                    <img src="{{ $picture->url }}" alt="{{ $picture->description ?? 'Bootcamp image' }}" class="w-full h-full object-cover" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 60%27%3E%3Crect fill=%27%23f3f4f6%27 width=%27100%27 height=%2760%27/%3E%3Ctext x=%2750%27 y=%2735%27 text-anchor=%27middle%27 fill=%27%239ca3af%27 font-family=%27sans-serif%27 font-size=%2712%27%3EGambar tidak ditemukan%3C/text%3E%3C/svg%3E'">
                </div>
                <div class="p-2">
                    <span class="inline-block px-2 py-1 text-xs font-bold rounded-full {{ $picture->type === 'thumbnail' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($picture->type) }}
                    </span>
                </div>
                <form method="POST" action="{{ route('admin.pictures.destroy', $picture) }}" onsubmit="return confirm('Hapus gambar ini?')" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

</div>
@endsection
