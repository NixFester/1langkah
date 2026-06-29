@extends('admin.layouts.app')
@section('title', 'Manage Users')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#b91c1c;">
        {{ session('error') }}
    </div>
@endif

<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <h3 style="font-size:16px;font-weight:600;margin:0;">Daftar User ({{ $users->total() }})</h3>
        <a href="{{ route('admin.users.new') }}"
            style="background:#d10000;color:#fff;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;display:inline-block;">
            + Tambah User
        </a>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="text-align:left;border-bottom:2px solid #e5e7eb;font-size:12px;color:#6b7280;">
                <th style="padding:8px;">ID</th>
                <th style="padding:8px;">Nama</th>
                <th style="padding:8px;">Email</th>
                <th style="padding:8px;">Role</th>
                <th style="padding:8px;">Bergabung</th>
                <th style="padding:8px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="border-bottom:1px solid #f3f4f6;font-size:13px;">
                <td style="padding:8px;color:#9ca3af;">{{ $user->id }}</td>
                <td style="padding:8px;font-weight:500;">{{ $user->name }}</td>
                <td style="padding:8px;color:#6b7280;">{{ $user->email }}</td>
                <td style="padding:8px;">
                    <form method="POST" action="{{ route('admin.users.role', $user) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <select name="role" onchange="this.form.submit()"
                            style="padding:2px 6px;border:1px solid #e5e7eb;border-radius:4px;font-size:12px;background:#f9fafb;">
                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                            <option value="mentor"  {{ $user->role === 'mentor'  ? 'selected' : '' }}>Mentor</option>
                            <option value="admin"   {{ $user->role === 'admin'   ? 'selected' : '' }}>Admin</option>
                        </select>
                    </form>
                </td>
                <td style="padding:8px;color:#9ca3af;font-size:12px;">{{ $user->created_at->format('d M Y') }}</td>
                <td style="padding:8px;">
                    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        <a href="{{ route('admin.users.manage', $user) }}"
                            style="background:#e0f2fe;color:#0369a1;border:none;padding:4px 10px;border-radius:4px;font-size:12px;text-decoration:none;display:inline-block;">
                            Kelola
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                            onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="background:#fee2e2;color:#b91c1c;border:none;padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                        @else
                        <span style="font-size:12px;color:#9ca3af;">Kamu</span>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $users->links() }}</div>
</div>
@endsection