@extends('layouts.app')

@section('title', isset($event) ? 'Kelola Event' : 'Tambah Event')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="isset($event) ? 'Kelola Event' : 'Tambah Event Baru'"
        description="Form ini digunakan untuk menambah atau mengubah detail agenda acara."
    >
        <x-slot:actionSlot>
            <a href="{{ route('admin.events') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </x-slot:actionSlot>
    </x-page-header>

    <x-flash-messages />

    <!-- FORM CARD -->
    <x-form-card>
        <form method="POST" action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @if(isset($event))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Judul Event <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" placeholder="Masukkan nama event" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipe Event <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="online" {{ old('type', $event->type ?? '') === 'online' ? 'selected' : '' }}>Online</option>
                        <option value="offline" {{ old('type', $event->type ?? '') === 'offline' ? 'selected' : '' }}>Offline</option>
                        <option value="hybrid" {{ old('type', $event->type ?? '') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="draft" {{ old('status', $event->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="upcoming" {{ old('status', $event->status ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ old('status', $event->status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status', $event->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $event->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Mulai <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', isset($event) && $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Selesai (Opsional)</label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', isset($event) && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi / Tautan Meeting</label>
                    <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}" placeholder="Contoh: Zoom / Jl. Sudirman No. 123" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="4" placeholder="Ceritakan detail event ini" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors resize-y">{{ old('description', $event->description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Gambar/Banner (Opsional)</label>
                    <input type="hidden" name="remove_banner" id="remove-banner-input" value="0">
                    @if(isset($event) && $event->banner_url)
                        <div class="mb-3 relative inline-block" id="image-preview-container">
                            <img id="image-preview" src="{{ str_starts_with($event->banner_url, 'http') ? $event->banner_url : asset($event->banner_url) }}" alt="Banner" class="h-32 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeImagePreview()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors" title="Hapus Gambar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endif
                    <div id="file-input-container" style="display: {{ isset($event) && $event->banner_url ? 'none' : 'block' }};">
                        <input type="file" name="banner_image" accept="image/*" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-2 transition-colors">
                        <input type="hidden" name="banner_url" value="{{ old('banner_url', $event->banner_url ?? '') }}">
                        <p class="text-xs text-gray-500 mt-1">Unggah gambar baru untuk banner event. Maksimal 2MB (JPG, PNG).</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($event) ? 'Simpan Perubahan' : '+ Tambah Event' }}
                </button>
            </div>
        </form>
    </x-form-card>

</div>
@endsection

@push('scripts')
<script>
    const imageInput = document.querySelector('input[name="banner_image"]');
    const removeBannerInput = document.getElementById('remove-banner-input');
    const fileInputContainer = document.getElementById('file-input-container');
    
    function removeImagePreview() {
        const previewDiv = document.getElementById('image-preview-container');
        if (previewDiv) {
            previewDiv.style.display = 'none';
        }
        if (imageInput) {
            imageInput.value = '';
        }
        if (removeBannerInput) {
            removeBannerInput.value = '1';
        }
        if (fileInputContainer) {
            fileInputContainer.style.display = 'block';
        }
    }

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewDiv = document.getElementById('image-preview-container');
                    if (!previewDiv) {
                        previewDiv = document.createElement('div');
                        previewDiv.id = 'image-preview-container';
                        previewDiv.className = 'mb-3 relative inline-block';
                        previewDiv.innerHTML = `
                            <img id="image-preview" src="" class="h-32 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeImagePreview()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors" title="Hapus Gambar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        `;
                        fileInputContainer.parentNode.insertBefore(previewDiv, fileInputContainer);
                    }
                    previewDiv.style.display = 'inline-block';
                    document.getElementById('image-preview').src = e.target.result;
                    if (removeBannerInput) {
                        removeBannerInput.value = '0';
                    }
                    if (fileInputContainer) {
                        fileInputContainer.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
