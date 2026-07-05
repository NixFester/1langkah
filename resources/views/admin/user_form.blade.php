@extends('layouts.app')

@section('title', isset($user) ? 'Kelola User' : 'Tambah User')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        :title="isset($user) ? 'Kelola User' : 'Tambah User Baru'"
        description="Form ini dipakai untuk mengatur akun user, role, dan kredensial dasar."
    >
        <x-slot:actionSlot>
            <a href="{{ isset($user) ? route('admin.users') : route('admin.users.new') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-5 rounded-full text-sm transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ isset($user) ? 'Kembali' : 'Kembali ke Daftar' }}
            </a>
        </x-slot:actionSlot>
    </x-page-header>

    <x-flash-messages />

    <!-- FORM CARD -->
    <x-form-card>
        <form method="POST" action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-6">
            @csrf
            @if(isset($user))
                @method('PATCH')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Masukkan nama lengkap" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="Masukkan email aktif" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Password {!! !isset($user) ? '<span class="text-red-500">*</span>' : '' !!}</label>
                    <input type="password" name="password" placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin mengubah' : 'Masukkan password' }}" {{ !isset($user) ? 'required' : '' }} class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password {!! !isset($user) ? '<span class="text-red-500">*</span>' : '' !!}</label>
                    <input type="password" name="password_confirmation" placeholder="Ketik ulang password" {{ !isset($user) ? 'required' : '' }} class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Role Akses <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 cursor-pointer transition-colors">
                        <option value="">-- Pilih Role --</option>
                        <option value="student" {{ old('role', $user->role ?? '') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="mentor" {{ old('role', $user->role ?? '') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                        <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Foto Profil (Opsional)</label>
                    <input type="text" name="profile_photo" value="{{ old('profile_photo', $user->profile_photo ?? '') }}" placeholder="https://..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block p-3 transition-colors">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-3 px-8 rounded-full text-sm transition-colors shadow-lg shadow-red-200 w-full sm:w-auto">
                    {{ isset($user) ? 'Simpan Perubahan' : '+ Tambah User' }}
                </button>
            </div>
        </form>
    </x-form-card>

</div>
@endsection
