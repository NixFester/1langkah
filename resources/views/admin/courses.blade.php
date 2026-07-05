@extends('layouts.app')

@section('title', 'Manage Courses')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        title="Kelola Kursus"
        :description="'Daftar kursus (' . $courses->total() . ') yang tersedia di platform.'"
        actionRoute="{{ route('admin.courses.new') }}"
        actionLabel="Tambah Kursus"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$courses">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold">Kursus</th>
                <th class="px-6 py-4 font-bold">Kategori</th>
                <th class="px-6 py-4 font-bold">Level</th>
                <th class="px-6 py-4 font-bold">Harga</th>
                <th class="px-6 py-4 font-bold text-right">Aksi</th>
            </tr>
        </template>

        @forelse($courses as $course)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0 text-red-600">
                        <x-icon name="book" class="w-6 h-6" />
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate">{{ $course->title }}</div>
                        <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                            <x-icon name="users" class="w-3 h-3 text-gray-400" />
                            {{ $course->mentor_name }} &bull; {{ $course->mentor_company }}
                        </div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="bg-gray-100 text-gray-700 text-[11px] font-bold px-2.5 py-1 rounded-md">{{ $course->category }}</span>
            </td>
            <td class="px-6 py-4">
                <x-stat-badge :status="$course->level" />
            </td>
            <td class="px-6 py-4">
                <div class="text-sm font-bold text-gray-900">
                    {{ $course->formatted_price }}
                </div>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.courses.manage', $course) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        Kelola
                    </a>
                    <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" class="m-0" onsubmit="return confirm('Hapus kursus ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            Hapus
                        </button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="px-6 py-8">
                <x-empty-state message="Belum ada data kursus." icon="book" />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
