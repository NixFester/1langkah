@extends('layouts.app')

@section('title', isset($event) ? __('app.manage_events') : __('app.add_new_event'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="isset($event) ? __('app.manage_events') : __('app.add_new_event')"
        :description="__('app.event_form_desc')"
    >
        <x-slot:actionSlot>
            <a href="{{ route('admin.events') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('app.back') }}
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
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.event_title') }} <span class="text-red-500">*</span></label>
                    <input aria-label="Title" type="text" name="title" value="{{ old('title', $event->title ?? '') }}" :placeholder="__('app.enter_event_name')" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.event_type') }} <span class="text-red-500">*</span></label>
                    <select aria-label="Type" name="type" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">{{ __('app.select_type') }}</option>
                        <option value="online" {{ old('type', $event->type ?? '') === 'online' ? 'selected' : '' }}>{{ __('app.online') }}</option>
                        <option value="offline" {{ old('type', $event->type ?? '') === 'offline' ? 'selected' : '' }}>{{ __('app.offline') }}</option>
                        <option value="hybrid" {{ old('type', $event->type ?? '') === 'hybrid' ? 'selected' : '' }}>{{ __('app.hybrid') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.status') }} <span class="text-red-500">*</span></label>
                    <select aria-label="Status" name="status" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="draft" {{ old('status', $event->status ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="upcoming" {{ old('status', $event->status ?? '') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="ongoing" {{ old('status', $event->status ?? '') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status', $event->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $event->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.start') }} <span class="text-red-500">*</span></label>
                    <input aria-label="Start Date" type="datetime-local" name="start_date" value="{{ old('start_date', isset($event) && $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.end_optional') }}</label>
                    <input aria-label="End Date" type="datetime-local" name="end_date" value="{{ old('end_date', isset($event) && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.location_meeting_link') }}</label>
                    <input aria-label="Location" type="text" name="location" value="{{ old('location', $event->location ?? '') }}" :placeholder="__('app.example_location_meeting')" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.description_optional') }}</label>
                    <textarea aria-label="{{ __('app.event_desc_placeholder') }}" name="description" rows="4" placeholder="{{ __('app.event_desc_placeholder') }}" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors resize-y">{{ old('description', $event->description ?? '') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.banner_image_optional') }}</label>
                    <input type="hidden" name="remove_banner" id="remove-banner-input" value="0">
                    @if(isset($event) && $event->banner_url)
                        <div class="mb-3 relative inline-block" id="image-preview-container">
                            <img decoding="async" loading="lazy" alt="" id="image-preview" src="{{ str_starts_with($event->banner_url, 'http') ? $event->banner_url : asset($event->banner_url) }}" :alt="__('app.banner')" class="h-32 object-cover rounded-lg border border-gray-200">
                            <button type="button" onclick="removeImagePreview()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors" title="{{ __('app.remove_photo') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endif
                    <div id="file-input-container" style="display: {{ isset($event) && $event->banner_url ? 'none' : 'block' }};">
                        <input aria-label="Banner Image" type="file" name="banner_image" accept="image/*" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-2 transition-colors">
                        <input type="hidden" name="banner_url" value="{{ old('banner_url', $event->banner_url ?? '') }}">
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.event_upload_image_hint') }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($event) ? __('app.save_changes') : __('app.add_event_plus') }}
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
                            <img decoding="async" loading="lazy" id="image-preview" src="" class="h-32 object-cover rounded-lg border border-gray-200" alt="">
                            <button type="button" onclick="removeImagePreview()" class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors" title="{{ __('app.remove_photo') }}">
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
