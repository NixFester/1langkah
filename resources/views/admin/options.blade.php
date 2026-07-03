@extends('layouts.app')
@section('title', 'Kelola Options')
@section('content')

@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#065f46;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:12px 16px;margin-bottom:16px;color:#b91c1c;">{{ session('error') }}</div>
@endif

<div class="admin-card" style="margin-bottom:24px;">
    <h3 style="font-size:18px;font-weight:700;margin:0 0 16px;">Tambah Option Baru</h3>
    <form method="POST" action="{{ route('admin.options.store') }}">
        @csrf
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;align-items:end;">
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;">Category</label>
                <input name="category" placeholder="user_role" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;width:100%;">
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;">Key</label>
                <input name="key" placeholder="new_role" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;width:100%;">
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;">Label</label>
                <input name="label" placeholder="New Role" required style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;width:100%;">
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;">Color (Hex)</label>
                <input name="color" placeholder="#3b82f6" style="padding:8px 12px;border:1px solid #e5e7eb;border-radius:6px;font-size:13px;width:100%;">
            </div>
            <div>
                <button type="submit" style="background:#d10000;color:#fff;padding:8px 16px;border-radius:6px;font-size:13px;font-weight:600;border:none;cursor:pointer;width:100%;">+ Tambah</button>
            </div>
        </div>
    </form>
</div>

@foreach($options as $category => $categoryOptions)
<div class="admin-card" style="margin-bottom:20px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
        <h3 style="font-size:16px;font-weight:700;margin:0;">{{ str_replace('_', ' ', ucfirst($category)) }}</h3>
        <span style="background:#f3f4f6;padding:4px 10px;border-radius:12px;font-size:12px;color:#6b7280;">{{ $categoryOptions->count() }} options</span>
    </div>
    <table style="width:100%;border-collapse:collapse;table-layout:fixed;">
        <thead>
            <tr style="text-align:left;border-bottom:2px solid #e5e7eb;font-size:12px;color:#6b7280;">
                <th style="padding:8px;width:20%;">Key</th>
                <th style="padding:8px;width:25%;">Label</th>
                <th style="padding:8px;width:20%;">Color</th>
                <th style="padding:8px;width:10%;">Sort</th>
                <th style="padding:8px;width:15%;">Status</th>
                <th style="padding:8px;width:10%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryOptions as $option)
            <tr style="border-bottom:1px solid #f3f4f6;font-size:13px;">
                <td style="padding:8px;font-family:monospace;background:#f9fafb;">{{ $option->key }}</td>
                <td style="padding:8px;">{{ $option->label }}</td>
                <td style="padding:8px;">
                    @if($option->color)
                        <span style="display:inline-block;width:20px;height:20px;background:{{ $option->color }};border-radius:4px;border:1px solid #e5e7eb;"></span>
                        <span style="font-size:11px;color:#9ca3af;margin-left:4px;">{{ $option->color }}</span>
                    @else
                        <span style="color:#9ca3af;font-size:12px;">-</span>
                    @endif
                </td>
                <td style="padding:8px;">{{ $option->sort_order }}</td>
                <td style="padding:8px;">
                    <form method="POST" action="{{ route('admin.options.update', $option) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <select name="is_active" onchange="this.form.submit()" style="padding:2px 8px;border:1px solid #e5e7eb;border-radius:4px;font-size:12px;">
                            <option value="1" {{ $option->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$option->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </form>
                </td>
                <td style="padding:8px;">
                    <form method="POST" action="{{ route('admin.options.destroy', $option) }}" onsubmit="return confirm('Hapus option ini?')" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#fee2e2;color:#b91c1c;border:none;padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endforeach

@endsection
