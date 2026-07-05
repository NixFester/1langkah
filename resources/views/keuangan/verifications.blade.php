@extends('layouts.keuangan')

@section('title', 'Verifikasi Pembayaran')

@section('header_title', 'Verifikasi Pembayaran')

@section('content')
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 font-medium">
                    Filter
                </button>
                <a href="{{ route('keuangan.verifications') }}" class="ml-2 px-4 py-2 text-gray-600 hover:text-gray-800">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kursus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($verifications as $v)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                    <span class="text-amber-600 font-bold text-sm">{{ substr($v->user->name ?? 'U', 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $v->user->name ?? 'Unknown' }}</p>
                                    <p class="text-xs text-gray-400">{{ $v->user->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-800">{{ $v->course_title }}</td>
                        <td class="px-6 py-4 font-bold text-gray-800">Rp {{ number_format($v->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php $badge = $v->status_badge; @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge['class'] }}">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $v->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('keuangan.verifications.show', $v) }}" class="text-amber-600 hover:text-amber-700 font-medium text-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Tidak ada data verifikasi pembayaran
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $verifications->withQueryString()->links() }}
    </div>
@endsection
