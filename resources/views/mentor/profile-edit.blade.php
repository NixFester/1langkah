@extends('layouts.mentor')

@section('title', 'Edit Biodata Mentor')
@section('header_title', 'Edit Biodata Mentor')

@section('content')
<div class="w-full px-2 pb-8">
    <div class="page-title" style="margin-bottom:8px">Edit Biodata Mentor</div>
    <p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">Sesuaikan informasi publik Anda yang akan dilihat oleh siswa saat memilih mentor.</p>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:var(--radius-sm);padding:12px 16px;font-size:13px;color:#065f46;margin-bottom:20px;display:flex;align-items:center;gap:8px">
            <x-icon name="check" style="width:16px;height:16px;color:#065f46" />
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:12px 16px;font-size:13px;color:#b91c1c;margin-bottom:20px;display:flex;align-items:center;gap:8px">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid-2" style="gap:28px;align-items:start">
        {{-- Left: Tips / Info --}}
        <div>
            <div class="card" style="padding:28px;background:linear-gradient(135deg,var(--primary),#b91c1c);color:white;margin-bottom:20px">
                <div style="font-size:18px;font-weight:700;margin-bottom:12px">Tips Profil Menarik ✨</div>
                <div style="font-size:13px;color:rgba(255,255,255,0.9);line-height:1.6;margin-bottom:16px">
                    Profil mentor yang lengkap dan jelas akan meningkatkan kepercayaan siswa untuk memesan sesi mentoring Anda.
                </div>
                <ul style="font-size:13px;color:rgba(255,255,255,0.9);line-height:1.6;padding-left:16px;margin:0" class="list-disc space-y-2">
                    <li>Gunakan role jabatan spesifik.</li>
                    <li>Sebutkan perusahaan saat ini atau portofolio terbesar.</li>
                    <li>Detailkan keahlian Anda agar mudah dicari.</li>
                    <li>Bio yang ramah namun profesional sangat disukai siswa.</li>
                </ul>
            </div>
            
            <div class="card" style="padding:24px">
                <div class="section-title" style="margin-bottom:14px">Info Mentor</div>
                <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
                    <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid var(--border-light)">
                        <span style="color:var(--text-muted)">Total Sesi</span>
                        <span style="font-weight:600">{{ $mentor->sessions_count ?? 0 }} sesi</span>
                    </div>
                    <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid var(--border-light)">
                        <span style="color:var(--text-muted)">Rating Rata-rata</span>
                        <div class="flex items-center gap-1 font-semibold text-amber-500">
                            {{ number_format($mentor->rating ?? 0, 1) }} ⭐
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Form Edit --}}
        <div>
            <form method="POST" action="{{ route('mentor.profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="card" style="padding:24px;margin-bottom:20px">
                    <div class="section-title" style="margin-bottom:18px">Informasi Dasar</div>
                    
                    <div class="input-group" style="margin-bottom:16px">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="name" class="input" required value="{{ old('name', $mentor->name) }}" />
                        @error('name')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                    </div>

                    <div class="input-group" style="margin-bottom:16px">
                        <label>Role / Jabatan *</label>
                        <input type="text" name="role" class="input" required value="{{ old('role', $mentor->role) }}" placeholder="Contoh: Senior Developer, Data Scientist" />
                        @error('role')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                    </div>

                    <div class="input-group" style="margin-bottom:16px">
                        <label>Perusahaan</label>
                        <input type="text" name="company" class="input" value="{{ old('company', $mentor->company) }}" placeholder="Contoh: Google, Tokopedia" />
                        @error('company')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                    </div>

                    <div class="input-group" style="margin-bottom:0">
                        <label>Harga per Sesi (Rp)</label>
                        <input type="text" name="price" class="input" value="{{ old('price', $mentor->price) }}" placeholder="50000" />
                        <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block">Kosongkan atau isi 0 untuk gratis</small>
                        @error('price')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="card" style="padding:24px;margin-bottom:20px">
                    <div class="section-title" style="margin-bottom:18px">Bio & Keahlian</div>

                    <div class="input-group" style="margin-bottom:16px">
                        <label>Deskripsi Diri</label>
                        <textarea name="bio" class="input" rows="4" placeholder="Ceritakan tentang pengalaman dan keahlian Anda...">{{ old('bio', $mentor->bio) }}</textarea>
                        <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block">Maksimal 2000 karakter</small>
                        @error('bio')<span style="color:#b91c1c;font-size:12px;margin-top:4px;display:block">{{ $message }}</span>@enderror
                    </div>

                    <div x-data="{
                        expertise: {{ json_encode(old('expertise', $mentor->expertise ?? [])) }},
                        newExpertise: '',
                        addExpertise() {
                            if (this.newExpertise.trim() && !this.expertise.includes(this.newExpertise.trim())) {
                                this.expertise.push(this.newExpertise.trim());
                                this.newExpertise = '';
                            }
                        },
                        removeExpertise(index) {
                            this.expertise.splice(index, 1);
                        }
                    }">
                        <div class="input-group" style="margin-bottom:8px">
                            <label>Tambahkan Keahlian</label>
                            <div style="display:flex;gap:8px">
                                <input type="text" x-model="newExpertise" @keydown.enter.prevent="addExpertise()" class="input" style="flex:1" placeholder="Contoh: Laravel, React, Python" />
                                <button type="button" @click="addExpertise()" class="btn btn-outline" style="white-space:nowrap">Tambah</button>
                            </div>
                        </div>

                        <template x-for="(exp, index) in expertise" :key="index">
                            <input type="hidden" name="expertise[]" :value="exp">
                        </template>

                        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px">
                            <template x-for="(exp, index) in expertise" :key="index">
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;background:#fee2e2;color:#990000;border-radius:999px;font-size:12px;font-weight:500;">
                                    <span x-text="exp"></span>
                                    <button type="button" @click="removeExpertise(index)" style="background:none;border:none;cursor:pointer;color:#dc2626;padding:0;display:flex;align-items:center">
                                        <svg style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        <p x-show="expertise.length === 0" style="color:var(--text-muted);font-size:12px;margin-top:8px">Belum ada keahlian ditambahkan</p>
                    </div>
                </div>

                <div class="card" style="padding:24px;margin-bottom:20px">
                    <div class="section-title" style="margin-bottom:18px">Kontak & Ketersediaan</div>

                    <div class="input-group" style="margin-bottom:16px">
                        <label>LinkedIn URL</label>
                        <input type="url" name="linkedin_url" class="input" value="{{ old('linkedin_url', $mentor->linkedin_url) }}" placeholder="https://linkedin.com/in/username" />
                    </div>

                    <div class="input-group" style="margin-bottom:16px">
                        <label>Nomor WhatsApp</label>
                        <input type="tel" name="phone" class="input" value="{{ old('phone', $mentor->phone) }}" placeholder="081234567890" />
                    </div>

                    <div x-data="{
                        availableDays: {{ json_encode(old('available_days', $availableDays ?? [])) }},
                        toggleDay(day) {
                            const index = this.availableDays.indexOf(day);
                            if (index > -1) {
                                this.availableDays.splice(index, 1);
                            } else {
                                this.availableDays.push(day);
                            }
                        },
                        isSelected(day) {
                            return this.availableDays.includes(day);
                        }
                    }">
                        <label style="display:block;font-size:13px;font-weight:600;margin-bottom:4px;color:var(--text-primary)">Hari Tersedia</label>
                        <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px">Pilih hari-hari ketika Anda tersedia untuk sesi mentoring</p>

                        <template x-for="day in availableDays" :key="day">
                            <input type="hidden" name="available_days[]" :value="day">
                        </template>

                        <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:8px">
                            @php
                                $days = [
                                    0 => ['label' => 'Minggu', 'short' => 'Min'],
                                    1 => ['label' => 'Senin', 'short' => 'Sen'],
                                    2 => ['label' => 'Selasa', 'short' => 'Sel'],
                                    3 => ['label' => 'Rabu', 'short' => 'Rab'],
                                    4 => ['label' => 'Kamis', 'short' => 'Kam'],
                                    5 => ['label' => 'Jumat', 'short' => 'Jum'],
                                    6 => ['label' => 'Sabtu', 'short' => 'Sab'],
                                ];
                            @endphp
                            @foreach($days as $index => $day)
                                <button type="button" @click="toggleDay({{ $index }})"
                                        :style="isSelected({{ $index }}) ? 'background:#cc0000;color:white;border-color:#cc0000' : ''"
                                        class="btn btn-outline" style="padding:8px 4px;font-size:12px;display:flex;flex-direction:column;align-items:center;gap:4px;">
                                    <span x-text="isSelected({{ $index }}) ? '{{ $day['label'] }}' : '{{ $day['short'] }}'"></span>
                                    <svg x-show="isSelected({{ $index }})" style="width:14px;height:14px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:24px">
                    <a href="{{ route('mentor.dashboard') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
