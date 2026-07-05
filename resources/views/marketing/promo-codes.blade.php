@extends('layouts.marketing')

@section('title', 'Kode Promo')

@section('header_title', 'Kode Promo')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            {{-- Filters --}}
            <form method="GET" class="flex gap-2">
                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    <option value="maxed" {{ request('status') === 'maxed' ? 'selected' : '' }}>Habis Pakai</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700">Filter</button>
            </form>
        </div>
        <a href="{{ route('marketing.promo-codes.create') }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Promo Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Digunakan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($promos as $promo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-pink-100 text-pink-700 rounded font-bold text-sm">{{ $promo->code }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-800">{{ $promo->name }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $promo->type_label }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-800">{{ $promo->used_count }}</span>
                                @if($promo->max_uses)
                                    <span class="text-gray-400">/ {{ $promo->max_uses }}</span>
                                    <div class="w-16 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-pink-500" style="width: {{ min(100, $promo->usage_percentage) }}%"></div>
                                    </div>
                                @else
                                    <span class="text-gray-400">/ ∞</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($promo->is_active && $promo->isValid())
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Aktif</span>
                            @elseif($promo->expires_at && $promo->expires_at->lt(now()))
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">Kadaluarsa</span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('marketing.promo-codes.edit', $promo) }}" class="text-blue-600 hover:text-blue-700 text-sm">Edit</a>
                                <form action="{{ route('marketing.promo-codes.toggle', $promo) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">
                                        {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('marketing.promo-codes.destroy', $promo) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada promo codes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $promos->withQueryString()->links() }}
    </div>
@endsection
