@extends('admin.layouts.app')
@section('title', 'Manage Events')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">
        {{ session('success') }}
    </div>
@endif

{{-- Table --}}
<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;">
        <h3 style="font-size:16px;font-weight:600;margin:0;">Daftar Events ({{ $events->total() }})</h3>
        <a href="{{ route('admin.events.new') }}"
            style="background:#d10000;color:#fff;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:600;text-decoration:none;display:inline-block;">
            + Tambah Event
        </a>
    </div>
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="text-align:left;border-bottom:2px solid #e5e7eb;font-size:12px;color:#6b7280;">
                <th style="padding:8px;">ID</th>
                <th style="padding:8px;">Judul</th>
                <th style="padding:8px;">Tipe</th>
                <th style="padding:8px;">Mulai</th>
                <th style="padding:8px;">Status</th>
                <th style="padding:8px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr style="border-bottom:1px solid #f3f4f6;font-size:13px;">
                <td style="padding:8px;color:#9ca3af;">{{ $event->id }}</td>
                <td style="padding:8px;font-weight:500;">{{ $event->title }}</td>
                <td style="padding:8px;">
                    <span style="background:#f3f4f6;padding:2px 8px;border-radius:12px;font-size:11px;text-transform:capitalize;">
                        {{ $event->type }}
                    </span>
                </td>
                <td style="padding:8px;">{{ $event->start_date->format('d M Y · H:i') }}</td>
                <td style="padding:8px;">
                    <span style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:12px;font-size:11px;text-transform:capitalize;">
                        {{ $event->status }}
                    </span>
                </td>
                <td style="padding:8px;">
                    <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                        <a href="{{ route('admin.events.manage', $event) }}"
                            style="background:#e0f2fe;color:#0369a1;border:none;padding:4px 10px;border-radius:4px;font-size:12px;text-decoration:none;display:inline-block;">
                            Kelola
                        </a>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                            onsubmit="return confirm('Hapus event ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                style="background:#fee2e2;color:#b91c1c;border:none;padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:16px;">{{ $events->links() }}</div>
</div>
@endsection