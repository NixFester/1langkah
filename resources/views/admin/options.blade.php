@extends('layouts.app')
@section('title', 'Kelola Options')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#b91c1c;">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 p-5 sm:p-6">
    <h3 class="text-lg font-bold text-gray-800 mb-4 sm:mb-5">Tambah Option Baru</h3>
    <form method="POST" action="{{ route('admin.options.store') }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3 sm:gap-4 items-end">
            <div>
                <label class="text-xs font-medium text-gray-500 block mb-1.5">Category</label>
                <input name="category" placeholder="user_role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 block mb-1.5">Key</label>
                <input name="key" placeholder="new_role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 block mb-1.5">Label</label>
                <input name="label" placeholder="New Role" required class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500 block mb-1.5">Color (Hex)</label>
                <input name="color" placeholder="#3b82f6" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="pt-2 sm:pt-0">
                <button type="submit" class="w-full bg-[#cc0000] text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition-colors shadow-sm">
                    + Tambah
                </button>
            </div>
        </div>
    </form>
</div>

@foreach($options as $category => $categoryOptions)
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="p-4 sm:p-5 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100">
        <h3 class="text-base sm:text-lg font-bold text-gray-800 m-0">{{ str_replace('_', ' ', ucfirst($category)) }}</h3>
        <span class="bg-gray-100 px-3 py-1 rounded-full text-xs font-medium text-gray-600">{{ $categoryOptions->count() }} options</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-4 sm:px-5 py-3 font-bold whitespace-nowrap">Key</th>
                    <th class="px-4 sm:px-5 py-3 font-bold whitespace-nowrap">Label</th>
                    <th class="px-4 sm:px-5 py-3 font-bold whitespace-nowrap">Color</th>
                    <th class="px-4 sm:px-5 py-3 font-bold whitespace-nowrap text-center">Sort</th>
                    <th class="px-4 sm:px-5 py-3 font-bold whitespace-nowrap text-center">Status</th>
                    <th class="px-4 sm:px-5 py-3 font-bold whitespace-nowrap text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryOptions as $option)
                <tr class="border-t border-gray-100 hover:bg-gray-50/50 transition-colors text-sm">
                    <td class="px-4 sm:px-5 py-3.5 font-mono text-gray-600 bg-gray-50/30 whitespace-nowrap">{{ $option->key }}</td>
                    <td class="px-4 sm:px-5 py-3.5 text-gray-800 whitespace-nowrap">{{ $option->label }}</td>
                    <td class="px-4 sm:px-5 py-3.5 whitespace-nowrap">
                        @if($option->color)
                            <div class="flex items-center gap-2">
                                <span class="block w-5 h-5 rounded shadow-sm border border-black/10 flex-shrink-0" style="background-color: {{ $option->color }};"></span>
                                <span class="text-xs text-gray-400">{{ $option->color }}</span>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-4 sm:px-5 py-3.5 text-center text-gray-600 whitespace-nowrap">{{ $option->sort_order }}</td>
                    <td class="px-4 sm:px-5 py-3.5 text-center whitespace-nowrap">
                        <form method="POST" action="{{ route('admin.options.update', $option) }}" class="inline-block m-0">
                            @csrf @method('PATCH')
                            <select name="is_active" onchange="this.form.submit()" class="bg-white border border-gray-200 text-gray-700 text-xs rounded focus:ring-red-500 focus:border-red-500 py-1 px-2 cursor-pointer font-medium min-w-[80px]">
                                <option value="1" {{ $option->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$option->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 sm:px-5 py-3.5 text-right whitespace-nowrap">
                        <form method="POST" action="{{ route('admin.options.destroy', $option) }}" onsubmit="return confirm('Hapus option ini?')" class="inline-block m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded text-xs font-bold transition-colors">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

@endsection
