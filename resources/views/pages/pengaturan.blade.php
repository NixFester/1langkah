@extends('layouts.app', ['activePage' => 'pengaturan'])

@section('title', __('app.account_settings') . ' — 1Langkah')

@section('content')
@php $u = $authUser; @endphp
<div class="w-full px-2 pb-8">

<div class="page-title" style="margin-bottom:8px">{{ __('app.account_settings') }}</div>
<p style="font-size:14px;color:var(--text-muted);margin-bottom:28px">{{ __('app.account_settings_desc') }}</p>

{{-- Success flash --}}
@if(session('success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:var(--radius-sm);padding:12px 16px;font-size:13px;color:#065f46;margin-bottom:20px;display:flex;align-items:center;gap:8px">
        <x-icon name="check" style="width:16px;height:16px;color:#065f46" />
        {{ session('success') }}
    </div>
@endif

<div class="grid-2" style="gap:28px;align-items:start">

    {{-- Left: avatar + stats --}}
    <div>
        <div class="card" style="padding:28px;text-align:center;margin-bottom:20px">
            <div style="position:relative; width:96px; height:96px; margin:0 auto 16px;">
                <!-- Main Avatar -->
                <div style="width:100%; height:100%; border-radius:50%; background:linear-gradient(135deg,var(--primary),#b91c1c); display:flex; align-items:center; justify-content:center; font-size:48px; font-weight:700; color:#fff; border:3px solid #fee2e2; overflow:hidden;" id="avatar-display">
                    @if($u->profile_photo)
                        <img src="{{ $u->profile_photo }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    @endif
                </div>
                <!-- Camera Upload Badge -->
                <label for="avatar_upload_main" style="position:absolute; bottom:0; right:0; width:32px; height:32px; background-color:var(--primary); border:3px solid #fff; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#fff; transition:transform 0.2s; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <svg style="width:14px; height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <circle cx="12" cy="13" r="3"></circle>
                    </svg>
                </label>
                <input type="file" id="avatar_upload_main" name="avatar" accept="image/jpeg,image/png,image/jpg" style="display:none;" onchange="uploadAvatar(this)">
            </div>
            <div style="font-size:18px;font-weight:700;margin-bottom:4px">{{ $u->name }}</div>
            <div style="font-size:13px;color:var(--text-muted);margin-bottom:16px">{{ $u->email }}</div>
            <div style="display:flex;justify-content:center;gap:24px">
                <div style="text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--primary)">{{ number_format($u->xp) }}</div>
                    <div style="font-size:11px;color:var(--text-light)">{{ __('app.total_xp') }}</div>
                </div>
                <div style="text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--gold)">{{ $u->streak }}</div>
                    <div style="font-size:11px;color:var(--text-light)">{{ __('app.day_streak') }}</div>
                </div>
                <div style="text-align:center">
                    <div style="font-size:20px;font-weight:700;color:var(--success)">{{ $u->certificates->count() }}</div>
                    <div style="font-size:11px;color:var(--text-light)">{{ __('app.certificates') }}</div>
                </div>
            </div>
        </div>

        <div class="card" style="padding:20px">
            <div class="section-title" style="margin-bottom:14px">{{ __('app.account_info') }}</div>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
                <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid var(--border-light)">
                    <span style="color:var(--text-muted)">{{ __('app.joined_since') }}</span>
                    <span style="font-weight:600">{{ $u->created_at?->format('d M Y') ?? 'Jan 2025' }}</span>
                </div>
                <div class="flex justify-between" style="padding:8px 0;border-bottom:1px solid var(--border-light)">
                    <span style="color:var(--text-muted)">{{ __('app.account_status') }}</span>
                    <x-badge :text="__('app.active')" type="success" />
                </div>
                <div class="flex justify-between" style="padding:8px 0">
                    <span style="color:var(--text-muted)">{{ __('app.plan') }}</span>
                    <x-badge :text="__('app.free')" type="dark" />
                </div>
            </div>
        </div>
    </div>

    {{-- Right: edit form --}}
    <div>
        <form id="profile-form" method="POST" action="{{ route('pengaturan.update') }}" enctype="multipart/form-data">
            @csrf

            {{-- Validation errors --}}
            @if($errors->any())
                <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:var(--radius-sm);padding:12px 16px;font-size:13px;color:#b91c1c;margin-bottom:16px">
                    <ul style="margin:0;padding-left:16px">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="card" style="padding:24px;margin-bottom:20px">
                <div class="section-title" style="margin-bottom:18px">{{ __('app.profile_info') }}</div>

                <div class="input-group" style="margin-bottom:16px">
                    <label>{{ __('app.full_name') }}</label>
                    <input class="input" name="name" value="{{ old('name', $u->name) }}" required />
                </div>
                <div class="input-group" style="margin-bottom:16px">
                    <label>Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email', $u->email) }}" required />
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.bio') }}</label>
                    <textarea class="input" name="bio" rows="3" :placeholder="'{{ __('app.bio_placeholder') }}'">{{ old('bio', $u->bio) }}</textarea>
                    <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block">{{ __('app.bio_help') }}</small>
                </div>
            </div>

            <div class="card" style="padding:24px;margin-bottom:20px">
                <div class="section-title" style="margin-bottom:4px">{{ __('app.change_password') }}</div>
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:18px">{{ __('app.change_password_help') }}</p>
                <div class="input-group" style="margin-bottom:16px">
                    <label>{{ __('app.new_password') }}</label>
                    <input class="input" type="password" name="password" :placeholder="'{{ __('app.new_password_placeholder') }}'" />
                </div>
                <div class="input-group" style="margin-bottom:0">
                    <label>{{ __('app.confirm_new_password') }}</label>
                    <input class="input" type="password" name="password_confirmation" :placeholder="'{{ __('app.confirm_new_password_placeholder') }}'" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-full">{{ __('app.save_changes') }}</button>
        </form>

        {{-- Notification Preferences --}}
        <div class="card" style="padding:24px;margin-top:20px">
            <div class="section-title" style="margin-bottom:18px">{{ __('app.notification_preferences') }}</div>

            <div id="notification-feedback" style="display:none;padding:12px;border-radius:8px;margin-bottom:16px;font-size:13px;"></div>

            {{-- Email Notifications --}}
            <div style="margin-bottom:24px">
                <div style="font-size:13px;font-weight:600;color:var(--text-muted);margin-bottom:12px;display:flex;align-items:center;gap:8px">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    {{ __('app.email_notifications') }}
                </div>
                <div style="display:flex;flex-direction:column;gap:10px">
                    @php
                        $emailPrefs = $u->settings->notification_preferences ?? [
                            'email_course_updates' => true,
                            'email_bootcamp_reminders' => true,
                            'email_event_announcements' => true,
                            'email_forum_replies' => true,
                            'email_achievements' => true,
                            'email_weekly_progress' => false,
                        ];
                    @endphp
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="email_course_updates" value="1" {{ ($emailPrefs['email_course_updates'] ?? true) ? 'checked' : '' }} onchange="updateNotificationPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_course_updates') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="email_bootcamp_reminders" value="1" {{ ($emailPrefs['email_bootcamp_reminders'] ?? true) ? 'checked' : '' }} onchange="updateNotificationPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_bootcamp_reminders') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="email_event_announcements" value="1" {{ ($emailPrefs['email_event_announcements'] ?? true) ? 'checked' : '' }} onchange="updateNotificationPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_event_announcements') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="email_forum_replies" value="1" {{ ($emailPrefs['email_forum_replies'] ?? true) ? 'checked' : '' }} onchange="updateNotificationPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_forum_replies') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="email_achievements" value="1" {{ ($emailPrefs['email_achievements'] ?? true) ? 'checked' : '' }} onchange="updateNotificationPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_achievements') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="email_weekly_progress" value="1" {{ ($emailPrefs['email_weekly_progress'] ?? false) ? 'checked' : '' }} onchange="updateNotificationPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_weekly_progress') }}</span>
                    </label>
                </div>
            </div>

            {{-- Privacy Settings --}}
            <div>
                <div style="font-size:13px;font-weight:600;color:var(--text-muted);margin-bottom:12px;display:flex;align-items:center;gap:8px">
                    <svg style="width:16px;height:16px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    {{ __('app.privacy') }}
                </div>
                <div style="display:flex;flex-direction:column;gap:10px">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="show_profile_publicly" value="1" {{ ($u->settings->show_profile_publicly ?? true) ? 'checked' : '' }} onchange="updatePrivacyPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_public_profile') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="show_progress_publicly" value="1" {{ ($u->settings->show_progress_publicly ?? true) ? 'checked' : '' }} onchange="updatePrivacyPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_public_progress') }}</span>
                    </label>
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;font-size:13px;">
                        <input type="checkbox" name="allow_mentor_contact" value="1" {{ ($u->settings->allow_mentor_contact ?? true) ? 'checked' : '' }} onchange="updatePrivacyPref(this)" class="pref-checkbox">
                        <span>{{ __('app.pref_allow_mentor_contact') }}</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="card" style="padding:24px;margin-top:20px;border:1px solid #fca5a5">
            <div class="section-title" style="margin-bottom:8px;color:#b91c1c">{{ __('app.danger_zone') }}</div>
            <p style="font-size:13px;color:var(--text-muted);margin-bottom:16px">{{ __('app.danger_zone_desc') }}</p>
            <button class="btn btn-outline" style="border-color:#fca5a5;color:#b91c1c" onclick="alert('{{ __('app.delete_account_alert') }}')">
                {{ __('app.delete_account') }}
            </button>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
// CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

// Show feedback message
function showFeedback(message, type = 'success') {
    const el = document.getElementById('notification-feedback');
    el.textContent = message;
    el.style.display = 'block';
    el.style.backgroundColor = type === 'success' ? '#d1fae5' : '#fee2e2';
    el.style.borderColor = type === 'success' ? '#6ee7b7' : '#fca5a5';
    el.style.color = type === 'success' ? '#065f46' : '#b91c1c';

    setTimeout(() => {
        el.style.display = 'none';
    }, 3000);
}

// Update notification preference
async function updateNotificationPref(checkbox) {
    const name = checkbox.name;
    const value = checkbox.checked;

    try {
        const response = await fetch('/api/settings/notifications', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ [name]: value })
        });

        const data = await response.json();
        if (data.success) {
            showFeedback('Preferensi berhasil disimpan!');
        }
    } catch (error) {
        showFeedback('Gagal menyimpan preferensi', 'error');
        checkbox.checked = !value;
    }
}

// Update privacy preference
async function updatePrivacyPref(checkbox) {
    const name = checkbox.name;
    const value = checkbox.checked;

    try {
        const response = await fetch('/api/settings/privacy', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ [name]: value })
        });

        const data = await response.json();
        if (data.success) {
            showFeedback('Pengaturan privasi berhasil disimpan!');
        }
    } catch (error) {
        showFeedback('Gagal menyimpan pengaturan', 'error');
        checkbox.checked = !value;
    }
}

// Upload avatar
async function uploadAvatar(input) {
    if (!input.files || !input.files[0]) return;

    const formData = new FormData();
    formData.append('avatar', input.files[0]);

    try {
        const response = await fetch('/api/settings/avatar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            // Update avatar display
            const display = document.getElementById('avatar-display');
            display.innerHTML = `<img src="${data.data.avatar_url}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">`;
            showFeedback('Avatar berhasil diupload!');

            // Update topbar avatar too
            const topbarAvatar = document.querySelector('.topbar-avatar');
            if (topbarAvatar) {
                topbarAvatar.src = data.data.avatar_url;
            }
        } else {
            showFeedback(data.message || 'Gagal upload avatar', 'error');
        }
    } catch (error) {
        showFeedback('Gagal upload avatar', 'error');
    }
}
</script>
@endpush
