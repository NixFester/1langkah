@extends('layouts.app')

@section('title', isset($bootcamp) ? 'Kelola Bootcamp' : 'Tambah Bootcamp')

@section('content')
<div class="px-6 py-8 sm:px-10 w-full max-w-4xl mx-auto space-y-6">

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
                        <select name="type" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
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

                <!-- Location -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi (Khusus Offline)</label>
                    <input type="text" name="location" value="{{ old('location', $bootcamp->location ?? '') }}" placeholder="Contoh: Gedung A, Jakarta" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>

                <!-- Color -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Warna Utama (Opsional)</label>
                    <input type="text" name="color" value="{{ old('color', $bootcamp->color ?? '') }}" placeholder="Kode hex, cth: #667eea" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($bootcamp) ? 'Simpan Perubahan' : '+ Tambah Bootcamp' }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
