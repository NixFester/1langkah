@extends('layouts.app', ['activePage' => 'komunitas'])

@section('title', __('app.create_post') . ' — ' . __('app.community_1langkah'))
@section('header_title', __('app.create_post'))
@section('header_action')
    <a href="{{ route('komunitas') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        {{ __('app.cancel') }}
    </a>
@endsection

@section('content')
<div class="w-full px-2 pb-8">
    <div class="max-w-3xl mx-auto">

        <!-- Form Card -->
        <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-bold text-gray-900 text-xl">{{ __('app.create_new_post') }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ __('app.create_post_desc') }}</p>
            </div>

            <form action="{{ route('komunitas.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ __('app.title') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           :placeholder="'{{ __('app.post_title_placeholder') }}'"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm @error('title') border-red-500 @enderror"
                           required maxlength="255">
                    @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1"><span id="title-count">0</span>/255 {{ __('app.characters') }}</p>
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ __('app.post_content') }} <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="8"
                              :placeholder="'{{ str_replace("\n", '\n', __('app.post_content_placeholder')) }}'"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none @error('content') border-red-500 @enderror"
                              required>{{ old('content') }}</textarea>
                    @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1"><span id="content-count">0</span>/10,000 {{ __('app.characters') }}</p>
                </div>

                <!-- Image URLs -->
                <div>
                    <label for="image_urls" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ __('app.image_optional') }}
                    </label>
                    <textarea id="image_urls" name="image_urls" rows="2"
                              :placeholder="'{{ __('app.image_url_placeholder') }}'"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none @error('image_urls') border-red-500 @enderror">{{ old('image_urls') }}</textarea>
                    @error('image_urls')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex items-start gap-2 mt-2">
                        <svg class="w-4 h-4 text-gray-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xs text-gray-500">{{ __('app.image_url_help') }}</p>
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-3 grid gap-2 grid-cols-3"></div>
                </div>

                <!-- Preview Button -->
                <button type="button" onclick="togglePreview()"
                        class="flex items-center gap-2 px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span id="preview-text">{{ __('app.show_preview') }}</span>
                </button>

                <!-- Preview Section -->
                <div id="preview-section" class="hidden bg-gray-50 border border-gray-200 rounded-xl p-5">
                    <h2 class="font-bold text-gray-900 mb-3" id="preview-title">{{ __('app.preview_title') }}</h2>
                    <div id="preview-content" class="text-gray-700 whitespace-pre-wrap"></div>
                    <div id="preview-images" class="mt-4 flex gap-2 overflow-x-auto"></div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('komunitas') }}" class="px-5 py-2.5 text-gray-600 bg-gray-100 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-colors">
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-lg font-semibold text-sm hover:bg-red-700 transition-colors">
                        {{ __('app.post') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Guidelines -->
        <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl p-5">
            <h2 class="font-semibold text-blue-800 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ __('app.community_guidelines') }}
            </h2>
            <ul class="text-sm text-blue-700 space-y-1.5 ml-7">
                <li>• {{ __('app.guideline_1') }}</li>
                <li>• {{ __('app.guideline_2') }}</li>
                <li>• {{ __('app.guideline_3') }}</li>
                <li>• {{ __('app.guideline_4') }}</li>
                <li>• {{ __('app.guideline_5') }}</li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
const titleInput = document.getElementById('title');
const contentInput = document.getElementById('content');
const imageUrlsInput = document.getElementById('image_urls');

const titleCount = document.getElementById('title-count');
const contentCount = document.getElementById('content-count');

// Update character counts
titleInput.addEventListener('input', function() {
    titleCount.textContent = this.value.length;
    document.getElementById('preview-title').textContent = this.value || '{{ __('app.preview_title') }}';
});

contentInput.addEventListener('input', function() {
    contentCount.textContent = this.value.length;
    document.getElementById('preview-content').textContent = this.value;
});

// Image URL parsing and preview
imageUrlsInput.addEventListener('input', function() {
    updateImagePreview();
});

function updateImagePreview() {
    const container = document.getElementById('image-preview');
    const previewImages = document.getElementById('preview-images');
    const urls = imageUrlsInput.value.split(',').map(url => url.trim()).filter(url => {
        return url && /^https?:\/\/.+\..+/.test(url);
    });

    container.innerHTML = '';
    previewImages.innerHTML = '';

    urls.forEach((url, index) => {
        // Create thumbnail in form section
        const thumbDiv = document.createElement('div');
        thumbDiv.className = 'relative group';
        thumbDiv.innerHTML = `
            <img decoding="async" loading="lazy" src="${url}" alt="Preview ${index + 1}"
                 class="w-full h-24 object-cover rounded-lg bg-gray-100"
                 onerror="this.src='https://via.placeholder.com/150?text=Invalid+URL'">
            <button type="button" onclick="removeImage(${index})"
                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        `;
        container.appendChild(thumbDiv);

        // Create preview in preview section
        const previewImg = document.createElement('img');
        previewImg.src = url;
        previewImg.alt = `Preview ${index + 1}`;
        previewImg.className = 'h-32 w-auto rounded-lg object-cover';
        previewImg.onerror = function() { this.style.display = 'none'; };
        previewImages.appendChild(previewImg);
    });
}

function removeImage(index) {
    const urls = imageUrlsInput.value.split(',').map(url => url.trim());
    urls.splice(index, 1);
    imageUrlsInput.value = urls.join(', ');
    updateImagePreview();
}

// Toggle preview
let isPreviewVisible = false;
function togglePreview() {
    isPreviewVisible = !isPreviewVisible;
    const section = document.getElementById('preview-section');
    const text = document.getElementById('preview-text');

    if (isPreviewVisible) {
        section.classList.remove('hidden');
        text.textContent = '{{ __('app.hide_preview') }}';
    } else {
        section.classList.add('hidden');
        text.textContent = '{{ __('app.show_preview') }}';
    }
}

// Initialize counts
titleCount.textContent = titleInput.value.length;
contentCount.textContent = contentInput.value.length;
</script>
@endpush
@endsection
