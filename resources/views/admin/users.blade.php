@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="w-full px-2 pb-8 space-y-6">

    <!-- PAGE HEADER -->
    <x-page-header
        title="Kelola Pengguna"
        :description="'Daftar pengguna (' . $users->total() . ') yang terdaftar di sistem.'"
        actionRoute="{{ route('admin.users.new') }}"
        actionLabel="Tambah User"
    />

    <x-flash-messages />

    <!-- DATA TABLE -->
    <x-data-table :paginator="$users">
        <template #thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-bold">User</th>
                <th class="px-6 py-4 font-bold">Role</th>
                <th class="px-6 py-4 font-bold">Bergabung</th>
                <th class="px-6 py-4 font-bold text-right">Aksi</th>
            </tr>
        </template>

        @forelse($users as $user)
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->profile_photo ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=random' }}" class="w-10 h-10 rounded-full object-cover shadow-sm" alt="{{ $user->name }}">
                    <div>
                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="inline-block m-0">
                    @csrf @method('PATCH')
                    <select name="role" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-lg focus:ring-red-500 focus:border-red-500 block w-full py-1.5 px-2.5 cursor-pointer font-medium appearance-none">
                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="mentor"  {{ $user->role === 'mentor'  ? 'selected' : '' }}>Mentor</option>
                        <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                    </select>
                </form>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">
                {{ $user->created_at->format('d M Y') }}
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.users.manage', $user) }}" class="inline-flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                        Edit
                    </a>
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="m-0" onsubmit="return confirm('Hapus user {{ $user->name }} secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors">
                            Hapus
                        </button>
                    </form>
                    @else
                    <span class="inline-flex items-center justify-center bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-xs font-bold">Kamu</span>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="px-6 py-8">
                <x-empty-state message="Belum ada data user." icon="users" />
            </td>
        </tr>
        @endforelse
    </x-data-table>

</div>
@endsection
