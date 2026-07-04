@php
    $authUser = auth()->user();
    $initials = $authUser ? strtoupper(substr($authUser->name, 0, 2)) : '';
    $unreadCount = $authUser ? \App\Models\Notification::where('user_id', $authUser->id)->where('is_read', false)->count() : 0;
@endphp

<div class="topbar" style="display:flex;align-items:center;padding:0 32px;gap:24px;">
    <!-- Kiri -->
    <div style="flex:1;display:flex;align-items:center;gap:16px;">
        <button @click="sidebarMobileOpen = true" class="lg:hidden text-gray-500 hover:text-gray-900 focus:outline-none flex items-center justify-center p-1 rounded-md hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        @hasSection('header_title')
            <div style="font-size:20px;font-weight:700;color:var(--text-primary);" class="hidden sm:block">
                @yield('header_title')
            </div>
        @endif
    </div>

    <!-- Tengah -->
    <div class="topbar-search" style="flex:0 1 auto;width:100%;max-width:480px;">
        <input class="input input-search" placeholder="Cari kursus, mentor, proyek..." style="width:100%; height:42px; border-radius:999px; border:1px solid #e5e7eb; background-color:#ffffff; padding-left:44px; font-size:14px; color:#374151; box-shadow:0 1px 2px rgba(0,0,0,0.02); transition:all 0.2s;" onfocus="this.style.borderColor='#d10000';this.style.boxShadow='0 0 0 3px rgba(209,0,0,0.1)';" onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='0 1px 2px rgba(0,0,0,0.02)';" />
    </div>

    @auth
    <!-- Kanan -->
    <div class="topbar-right" style="flex:1;display:flex;align-items:center;justify-content:flex-end;gap:12px;">

        {{-- Bell + Notification Overlay --}}
        <div style="position:relative">
            <button id="notif-btn" class="topbar-icon" style="background:none;border:none;cursor:pointer;position:relative" onclick="toggleNotif(event)">
                <x-icon name="bell" />
                <div id="notif-dot" class="dot" style="position:absolute;top:0;right:0;width:8px;height:8px;background:#f5576c;border-radius:50%;border:2px solid var(--bg-main);{{ $unreadCount == 0 ? 'display:none' : '' }}"></div>
            </button>

            {{-- Overlay panel --}}
            <div id="notif-panel" style="display:none;position:absolute;top:calc(100% + 10px);right:0;width:360px;background:#fff;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,.12);border:1px solid #f0f0f0;z-index:999">
                <div style="padding:16px 20px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between">
                    <div style="font-weight:700;font-size:14px">Notifikasi</div>
                    <div id="notif-new-badge"></div>
                </div>
                <div id="notif-list" style="max-height:340px;overflow-y:auto">
                    <div style="padding:40px 20px;text-align:center;color:#9ca3af">
                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <p style="font-size:13px">Memuat notifikasi...</p>
                    </div>
                </div>
                <div style="padding:12px 20px;text-align:center;border-top:1px solid #f0f0f0">
                    <button onclick="markAllNotificationsRead()" style="font-size:12px;color:#cc0000;cursor:pointer;font-weight:600;background:none;border:none;">Tandai semua sudah dibaca</button>
                </div>
            </div>
        </div>

        {{-- Avatar & User Info --}}
        <a href="{{ route('pengaturan') }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:10px;margin-left:8px;">
            @if($authUser && $authUser->profile_photo)
            <img src="{{ $authUser->profile_photo }}" alt="Profile" style="width:32px; height:32px; border-radius:50%; object-fit:cover; flex-shrink:0; background:#cc0000;">
            @else
            <div class="avatar" style="background:#cc0000; width:32px; height:32px; font-size:13px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; flex-shrink:0;">{{ $initials }}</div>
            @endif
            <div style="display:flex; flex-direction:column; align-items:flex-start; justify-content:center;">
                <span style="font-size:15px;font-weight:800;color:var(--text-primary);line-height:1.1;">{{ $authUser->name }}</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-light);line-height:1.1;margin-top:4px;">{{ ($authUser->role ?? '') === 'student' ? number_format((int) ($authUser->xp ?? 0)) . ' XP' : ucfirst($authUser->role ?? 'User') }}</span>
            </div>
        </a>
    </div>
    @else
    <!-- Kanan (Guest) -->
    <div class="topbar-right" style="flex:1;display:flex;align-items:center;justify-content:flex-end;gap:12px;">
        <a href="{{ route('login') }}" style="font-size:14px;font-weight:600;color:var(--text-secondary);text-decoration:none;padding:8px 16px;">Masuk</a>
        <a href="{{ route('signup') }}" style="font-size:14px;font-weight:600;color:#fff;background:var(--primary);text-decoration:none;padding:8px 20px;border-radius:999px;">Daftar</a>
    </div>
    @endauth
</div>

{{-- Overlay backdrop (click outside to close) --}}
@auth
<div id="notif-backdrop" style="display:none;position:fixed;inset:0;z-index:998" onclick="closeNotif()"></div>

<script>
// Notification data
var notifications = [];
var unreadCount = {{ $unreadCount }};

// Icon map for notification types
var iconMap = {
    'bell': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>',
    'play-circle': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    'check-circle': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    'trophy': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4a6 6 0 016 6h4a6 6 0 016-6V5m0 4h10a2 2 0 012 2v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-4m0 8l6 6m-6-6l6-6"></path></svg>',
    'video': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>',
    'user-plus': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>',
    'book': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
    'calendar': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>',
};

// Color map
var colorMap = {
    'red': { bg: 'rgba(239, 68, 68, 0.1)', text: '#ef4444' },
    'blue': { bg: 'rgba(59, 130, 246, 0.1)', text: '#3b82f6' },
    'green': { bg: 'rgba(34, 197, 94, 0.1)', text: '#22c55e' },
    'emerald': { bg: 'rgba(16, 185, 129, 0.1)', text: '#10b981' },
    'yellow': { bg: 'rgba(234, 179, 8, 0.1)', text: '#eab308' },
    'orange': { bg: 'rgba(249, 115, 22, 0.1)', text: '#f97316' },
    'purple': { bg: 'rgba(168, 85, 247, 0.1)', text: '#a855f7' },
};

function getIcon(name) {
    return iconMap[name] || iconMap['bell'];
}

function getColor(colorName) {
    return colorMap[colorName] || colorMap['blue'];
}

function toggleNotif(e) {
    e.stopPropagation();
    const panel = document.getElementById('notif-panel');
    const backdrop = document.getElementById('notif-backdrop');
    const isOpen = panel.style.display !== 'none';

    panel.style.display = isOpen ? 'none' : 'block';
    backdrop.style.display = isOpen ? 'none' : 'block';

    if (!isOpen) {
        loadNotifications();
    }
}

function closeNotif() {
    document.getElementById('notif-panel').style.display = 'none';
    document.getElementById('notif-backdrop').style.display = 'none';
}

function loadNotifications() {
    fetch('/api/notifications', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            notifications = data.notifications;
            unreadCount = data.unread_count;
            renderNotifications();
            updateBadge();
        }
    })
    .catch(err => {
        console.error('Error loading notifications:', err);
        document.getElementById('notif-list').innerHTML = '<div style="padding:40px 20px;text-align:center;color:#9ca3af"><p style="font-size:13px">Gagal memuat notifikasi</p></div>';
    });
}

function renderNotifications() {
    const list = document.getElementById('notif-list');

    if (notifications.length === 0) {
        list.innerHTML = '<div style="padding:40px 20px;text-align:center;color:#9ca3af"><svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0v-2a2 2 0 00-2-2H6a2 2 0 00-2 2"></path></svg><p style="font-size:13px">Tidak ada notifikasi</p></div>';
        return;
    }

    list.innerHTML = notifications.map(n => {
        const colors = getColor(n.color);
        const icon = getIcon(n.icon);
        return `
            <div style="display:flex;gap:12px;padding:14px 20px;border-bottom:1px solid #f0f0f0;${n.is_read ? '' : 'background:rgba(204,0,0,0.03)'};cursor:pointer;transition:background .15s"
                 onclick="handleNotificationClick(${n.id}, '${n.link || ''}')"
                 onmouseover="this.style.background='#f9fafb'"
                 onmouseout="this.style.background='${n.is_read ? 'transparent' : 'rgba(204,0,0,0.03)'}'">
                <div style="width:36px;height:36px;border-radius:50%;background:${colors.bg};display:flex;align-items:center;justify-content:center;flex-shrink:0;color:${colors.text}">
                    ${icon}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:12px;line-height:1.5;${n.is_read ? 'color:#6b7280' : 'color:#1f2937;font-weight:500'}">${n.title}</div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:2px">${n.message}</div>
                    <div style="font-size:11px;color:#9ca3af;margin-top:4px">${n.created_at}</div>
                </div>
                ${n.is_read ? '' : '<div style="width:7px;height:7px;border-radius:50%;background:#cc0000;flex-shrink:0;margin-top:5px"></div>'}
            </div>
        `;
    }).join('');
}

function updateBadge() {
    const dot = document.getElementById('notif-dot');
    const badge = document.getElementById('notif-new-badge');

    if (dot) {
        dot.style.display = unreadCount > 0 ? 'block' : 'none';
    }

    if (badge) {
        if (unreadCount > 0) {
            badge.innerHTML = `<span style="font-size:11px;background:#cc0000;color:#fff;border-radius:999px;padding:2px 8px">${unreadCount} baru</span>`;
        } else {
            badge.innerHTML = '';
        }
    }
}

function handleNotificationClick(id, link) {
    markAsRead(id);
    closeNotif();
    if (link) {
        window.location.href = link;
    }
}

function markAsRead(id) {
    fetch('/api/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            unreadCount = Math.max(0, unreadCount - 1);
            updateBadge();
            // Update the notification in the list
            const n = notifications.find(x => x.id === id);
            if (n) n.is_read = true;
            renderNotifications();
        }
    })
    .catch(err => console.error('Error marking as read:', err));
}

function markAllNotificationsRead() {
    fetch('/api/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            unreadCount = 0;
            updateBadge();
            notifications.forEach(n => n.is_read = true);
            renderNotifications();
        }
    })
    .catch(err => console.error('Error marking all as read:', err));
}
</script>
@endauth
