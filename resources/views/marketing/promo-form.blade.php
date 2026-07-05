@extends('layouts.marketing')

@section('title', isset($promo) ? 'Edit Promo' : 'Buat Promo Baru')
@section('header_title', isset($promo) ? 'Edit Promo' : 'Buat Promo Baru')

@section('content')
    <x-flash-messages />

    <div class="max-w-2xl mx-auto">
        <x-card-panel>
            <form action="{{ isset($promo) ? route('marketing.promo-codes.update', $promo) : route('marketing.promo-codes.store') }}" method="POST">
                @csrf
                @if(isset($promo))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Promo *</label>
                        <input type="text" name="name" value="{{ old('name', $promo->name ?? '') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Promo *</label>
                        <div class="flex gap-2">
                            <input type="text" name="code" id="code" value="{{ old('code', $promo->code ?? '') }}" required class="flex-1 border border-gray-300 rounded-lg px-4 py-2 uppercase focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                            <button type="button" onclick="generateCode()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Generate</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Diskon *</label>
                        <select name="type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="percentage" {{ (old('type', $promo->type ?? '') === 'percentage') ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed_amount" {{ (old('type', $promo->type ?? '') === 'fixed_amount') ? 'selected' : '' }}>Fixed Amount (Rp)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nilai Diskon *</label>
                        <input type="number" name="value" value="{{ old('value', $promo->value ?? '') }}" required min="1" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Uses (kosong = unlimited)</label>
                        <input type="number" name="max_uses" value="{{ old('max_uses', $promo->max_uses ?? '') }}" min="1" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min Purchase (Rp)</label>
                        <input type="number" name="min_purchase" value="{{ old('min_purchase', $promo->min_purchase ?? '') }}" min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Discount (Rp)</label>
                        <input type="number" name="max_discount" value="{{ old('max_discount', $promo->max_discount ?? '') }}" min="1" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at', $promo->starts_at?->toDateString() ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kadaluarsa</label>
                        <input type="date" name="expires_at" value="{{ old('expires_at', $promo->expires_at?->toDateString() ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-500 focus:border-pink-500">{{ old('description', $promo->description ?? '') }}</textarea>
                    </div>

                    @if(isset($promo))
                    <div class="col-span-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ ($promo->is_active ?? true) ? 'checked' : '' }} class="w-5 h-5 text-pink-600 rounded border-gray-300 focus:ring-pink-500">
                            <span class="text-sm font-medium text-gray-700">Promo Aktif</span>
                        </label>
                    </div>
                    @endif
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('marketing.promo-codes') }}" class="px-6 py-2 text-gray-600 hover:text-gray-800">Batal</a>
                    <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium">
                        {{ isset($promo) ? 'Simpan Perubahan' : 'Buat Promo' }}
                    </button>
                </div>
            </form>
        </x-card-panel>
    </div>

    @push('scripts')
    <script>
        function generateCode() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            let code = '';
            for (let i = 0; i < 8; i++) {
                code += chars[Math.floor(Math.random() * chars.length)];
            }
            document.getElementById('code').value = code;
        }
    </script>
    @endpush
@endsection
