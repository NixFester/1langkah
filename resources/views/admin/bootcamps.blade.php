@extends('layouts.app')

@section('title', 'Manage Bootcamps')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <div class="bg-white rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Kelola Bootcamp</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar bootcamp intensif ({{ $bootcamps->total() }}) di platform.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.bootcamps.new') }}" class="bg-[#cc0000] hover:bg-red-700 text-white font-bold py-2.5 px-5 rounded-full text-sm transition-colors shadow-lg shadow-red-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Bootcamp
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <!-- DATA TABLE -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-[0_2px_10px_rgb(0,0,0,0.02)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Bootcamp</th>
                        <th class="px-6 py-4 font-bold">Tipe</th>
                        <th class="px-6 py-4 font-bold">Mulai</th>
                        <th class="px-6 py-4 font-bold">Harga</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bootcamps as $bootcamp)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0 text-orange-600">
                                    <x-icon name="award" class="w-6 h-6" />
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 truncate">{{ $bootcamp->title }}</div>
                                    <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                        <x-icon name="users" class="w-3 h-3 text-gray-400" />
                                        Mentor: {{ $bootcamp->mentor_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($bootcamp->type === 'online')
                                <span class="bg-blue-50 text-blue-700 text-[11px] font-bold px-2.5 py-1 rounded-md">Online</span>
                            @else
                                <span class="bg-purple-50 text-purple-700 text-[11px] font-bold px-2.5 py-1 rounded-md">Offline</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $bootcamp->start_date }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-gray-900">
                                {{ $bootcamp->formatted_price }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.bootcamps.manage', $bootcamp) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                                    Kelola
                                </a>
                                <form method="POST" action="{{ route('admin.bootcamps.destroy', $bootcamp) }}" class="m-0" onsubmit="return confirm('Hapus bootcamp ini secara permanen?')">
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
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada data bootcamp.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bootcamps->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $bootcamps->links() }}
        </div>
        @endif
    </div>

</div>
@endsection