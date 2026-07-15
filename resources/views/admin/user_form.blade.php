@extends('layouts.app')

@section('title', isset($user) ? __('app.manage_users_title') : __('app.add_new_user'))

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="isset($user) ? __('app.manage_users') : __('app.add_new_user')"
        :description="__('app.user_form_desc')"
    >
        <x-slot:actionSlot>
            <a href="{{ isset($user) ? route('admin.users') : route('admin.users.new') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ isset($user) ? __('app.back') : __('app.back_to_list') }}
            </a>
        </x-slot:actionSlot>
    </x-page-header>

    <x-flash-messages />

    <!-- FORM CARD -->
    <x-form-card>
        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if(isset($user))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.full_name') }} <span class="text-red-500">*</span></label>
                    <input aria-label="Name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="{{ __('app.enter_full_name') }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.email') }} <span class="text-red-500">*</span></label>
                    <input aria-label="Email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="{{ __('app.email_active') }}" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.password') }} {!! !isset($user) ? '<span class="text-red-500">*</span>' : '' !!}</label>
                    <input aria-label="{{ isset($user) ? __('app.password_leave_blank') : __('app.enter_password') }}" type="password" name="password" placeholder="{{ isset($user) ? __('app.password_leave_blank') : __('app.enter_password') }}" {{ !isset($user) ? 'required' : '' }} class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.confirm_password') }} {!! !isset($user) ? '<span class="text-red-500">*</span>' : '' !!}</label>
                    <input aria-label="{{ __('app.retype_password') }}" type="password" name="password_confirmation" placeholder="{{ __('app.retype_password') }}" {{ !isset($user) ? 'required' : '' }} class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.access_role') }} <span class="text-red-500">*</span></label>
                    <select aria-label="Role" name="role" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">{{ __('app.select_role') }}</option>
                        <option value="student" {{ old('role', $user->role ?? '') === 'student' ? 'selected' : '' }}>{{ __('app.student') }}</option>
                        <option value="mentor" {{ old('role', $user->role ?? '') === 'mentor' ? 'selected' : '' }}>{{ __('app.mentor') }}</option>
                        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>{{ __('app.admin') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('app.profile_photo_optional') }}</label>
                    <input type="hidden" name="remove_photo" id="remove-photo-input" value="0">
                    @if(isset($user) && $user->profile_photo)
                        <div class="mb-3 relative inline-block" id="photo-preview-container">
                            <img decoding="async" loading="lazy" alt="" id="photo-preview" src="{{ str_starts_with($user->profile_photo, 'http') ? $user->profile_photo : asset($user->profile_photo) }}" alt="{{ __('app.profile_photo') }}" class="h-24 w-24 object-cover rounded-full border border-gray-200">
                            <button type="button" onclick="removePhotoPreview()" class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors" title="{{ __('app.remove_photo') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endif
                    <div id="photo-input-container" style="display: {{ isset($user) && $user->profile_photo ? 'none' : 'block' }};">
                        <input aria-label="Profile Photo File" type="file" name="profile_photo_file" accept="image/*" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-2 transition-colors">
                        <p class="text-xs text-gray-500 mt-1">{{ __('app.upload_image_hint') }}</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($user) ? __('app.save_changes') : __('app.add_user_plus') }}
                </button>
            </div>
        </form>
    </x-form-card>

</div>
@endsection

@push('scripts')
<script>
    const photoInput = document.querySelector('input[name="profile_photo_file"]');
    const removePhotoInput = document.getElementById('remove-photo-input');
    const photoInputContainer = document.getElementById('photo-input-container');
    
    function removePhotoPreview() {
        const previewDiv = document.getElementById('photo-preview-container');
        if (previewDiv) {
            previewDiv.style.display = 'none';
        }
        if (photoInput) {
            photoInput.value = '';
        }
        if (removePhotoInput) {
            removePhotoInput.value = '1';
        }
        if (photoInputContainer) {
            photoInputContainer.style.display = 'block';
        }
    }

    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewDiv = document.getElementById('photo-preview-container');
                    if (!previewDiv) {
                        previewDiv = document.createElement('div');
                        previewDiv.id = 'photo-preview-container';
                        previewDiv.className = 'mb-3 relative inline-block';
                        previewDiv.innerHTML = `
                            <img decoding="async" loading="lazy" id="photo-preview" src="" class="h-24 w-24 object-cover rounded-full border border-gray-200" alt="">
                            <button type="button" onclick="removePhotoPreview()" class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full p-1 shadow-md transition-colors" title="{{ __('app.remove_photo') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        `;
                        photoInputContainer.parentNode.insertBefore(previewDiv, photoInputContainer);
                    }
                    previewDiv.style.display = 'inline-block';
                    document.getElementById('photo-preview').src = e.target.result;
                    if (removePhotoInput) {
                        removePhotoInput.value = '0';
                    }
                    if (photoInputContainer) {
                        photoInputContainer.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush
