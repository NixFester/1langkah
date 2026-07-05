@extends('layouts.keuangan')

@section('title', 'Enrollments')
@section('header_title', 'Daftar Enrollments')

@section('content')
    <x-flash-messages />

    <x-data-table :paginator="$enrollments">
        <template #thead>
            <tr class="bg-gray-50">
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Siswa</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kursus</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Bayar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Verifikasi</th>
            </tr>
        </template>
        @forelse($enrollments as $e)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-600 font-bold text-sm">{{ substr($e->user->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $e->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $e->user->email ?? '' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-800">{{ $e->course_title }}</td>
                <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $e->verified_at?->format('d/m/Y H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-12">
                    <x-empty-state message="Tidak ada data enrollments" icon="users" />
                </td>
            </tr>
        @endforelse
    </x-data-table>
@endsection
