@extends('layouts.mentor')

@section('title', 'Buat Bootcamp Baru')
@section('header_title', 'Buat Bootcamp Baru')

@section('content')
<div class="w-full px-2 pb-8">
    <div class="mb-6">
        <a href="{{ route('mentor.bootcamps.index') }}" class="inline-flex items-center gap-2 text-[14px] text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Bootcamp
        </a>
    </div>

    <div class="page-title" style="margin-bottom:8px">Buat Bootcamp Baru</div>
    <p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">Jadwalkan bootcamp interaktif untuk membimbing siswa secara intensif.</p>

    <form method="POST" action="{{ route('mentor.bootcamps.store') }}">
        @csrf

        {{-- Basic Info --}}
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:18px">Informasi Dasar</div>

            <div class="input-group" style="margin-bottom:16px">
                <label>Judul Bootcamp <span style="color:#cc0000">*</span></label>
                <input type="text" name="title" class="input" required value="{{ old('title') }}" placeholder="Contoh: Intensive Web Development Bootcamp" />
                @error('title')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>Tipe <span style="color:#cc0000">*</span></label>
                    <select name="type" class="input" required>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('type')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>

                <div class="input-group" style="margin-bottom:0">
                    <label>Harga <span style="color:#cc0000">*</span></label>
                    <input type="text" name="price" class="input" required value="{{ old('price', 'Rp 0') }}" />
                    @error('price')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>Tanggal Mulai <span style="color:#cc0000">*</span></label>
                    <input type="text" name="start_date" class="input" required value="{{ old('start_date') }}" placeholder="Contoh: 15 Jan 2025" />
                    @error('start_date')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>

                <div class="input-group" style="margin-bottom:0">
                    <label>Jumlah Peserta</label>
                    <input type="number" name="participants" class="input" value="{{ old('participants') }}" min="0" placeholder="Kapasitas maksimal" />
                    @error('participants')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label>Lokasi (Untuk Offline/Hybrid)</label>
                <input type="text" name="location" class="input" value="{{ old('location') }}" placeholder="Contoh: Gedung 1Langkah, Jakarta" />
                @error('location')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Description --}}
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:18px">Deskripsi Bootcamp</div>

            <div class="input-group" style="margin-bottom:16px">
                <label>Deskripsi Singkat</label>
                <input type="text" name="short_description" class="input" value="{{ old('short_description') }}" maxlength="255" placeholder="Ringkasan singkat tentang bootcamp ini" />
                @error('short_description')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>

            <div class="input-group" style="margin-bottom:0">
                <label>Deskripsi Lengkap</label>
                <textarea name="description" class="input" rows="5" placeholder="Detailkan materi, persyaratan, dan benefit yang akan didapat...">{{ old('description') }}</textarea>
                @error('description')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Sessions --}}
        <div class="card" style="padding:24px;margin-bottom:20px">
            <div class="section-title" style="margin-bottom:18px">Sesi Bootcamp</div>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">Tambahkan jadwal pertemuan atau modul yang akan dibahas selama bootcamp berlangsung.</p>

            <div id="sessions-container" class="space-y-4 mb-4">
                {{-- Sessions will be added here via JS --}}
            </div>

            <button type="button" onclick="addSession()" class="btn btn-outline" style="width:100%;border-style:dashed;border-color:#d1d5db;color:var(--text-secondary);display:flex;align-items:center;justify-content:center;gap:8px">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Sesi
            </button>
        </div>

        {{-- Appearance --}}
        <div class="card" style="padding:24px;margin-bottom:24px">
            <div class="section-title" style="margin-bottom:18px">Tampilan Visual</div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div class="input-group" style="margin-bottom:0">
                    <label>Warna Aksen</label>
                    <div style="display:flex;align-items:center;gap:8px">
                        <input type="color" name="color" value="{{ old('color', '#cc0000') }}" style="width:40px;height:40px;border-radius:8px;border:1px solid var(--border-light);cursor:pointer;padding:2px">
                        <input type="text" name="color_text" class="input" value="{{ old('color', '#cc0000') }}" style="flex:1" />
                    </div>
                </div>

                <div class="input-group" style="margin-bottom:0">
                    <label>Info Sesi Label</label>
                    <input type="text" name="sessions_info" class="input" value="{{ old('sessions_info') }}" placeholder="Contoh: 8x pertemuan" />
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:12px">
            <a href="{{ route('mentor.bootcamps.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Buat Bootcamp</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let sessionCount = 0;
function addSession() {
    sessionCount++;
    const container = document.getElementById('sessions-container');
    const html = `
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 session-item relative">
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 transition-colors" title="Hapus sesi">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
            <div style="font-weight:600;font-size:14px;margin-bottom:12px;color:var(--text-primary)">Sesi Baru</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-3">
                <div class="input-group mb-0">
                    <input type="text" name="sessions[${sessionCount}][date]" placeholder="Tanggal" class="input" style="font-size:13px">
                </div>
                <div class="input-group mb-0">
                    <input type="text" name="sessions[${sessionCount}][topic]" placeholder="Topik" class="input" style="font-size:13px">
                </div>
                <div class="input-group mb-0">
                    <input type="text" name="sessions[${sessionCount}][time]" placeholder="Waktu" class="input" style="font-size:13px">
                </div>
                <div class="input-group mb-0">
                    <input type="text" name="sessions[${sessionCount}][meeting_url]" placeholder="Meeting URL" class="input" style="font-size:13px">
                </div>
            </div>
            <div class="input-group mb-0">
                <input type="text" name="sessions[${sessionCount}][description]" placeholder="Deskripsi (opsional)" class="input" style="font-size:13px">
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', html);
}
</script>
@endpush
@endsection
