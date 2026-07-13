@php
    $authUser = auth()->user();
    $initials = $authUser ? strtoupper(substr($authUser->name, 0, 2)) : '';
    $unreadCount = $authUser ? \App\Models\Notification::where('user_id', $authUser->id)->where('is_read', false)->count() : 0;

    // Role labels
    $roleLabels = [
        'superadmin' => 'Super Admin',
        'admin'      => 'Admin',
        'keuangan'   => 'Keuangan',
        'marketing'  => 'Marketing',
        'mentor'     => 'Mentor',
        'student'    => 'Student',
    ];
    $roleLabel = $authUser ? ($roleLabels[$authUser->role] ?? ucfirst($authUser->role)) : 'User';
    $isStudent = $authUser && $authUser->role === 'student';
@endphp

<div class="topbar px-3 sm:px-8 flex items-center gap-3 sm:gap-6">
    <!-- Kiri -->
    <div style="flex:1;display:flex;align-items:center;gap:16px;">
        <button @click="sidebarMobileOpen = true" class="lg:hidden text-gray-500 hover:text-gray-900 focus:outline-none flex items-center justify-center p-1.5 sm:p-1 -ml-1.5 sm:ml-0 rounded-md hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        @hasSection('header_title')
            <div style="font-size:20px;font-weight:700;color:var(--text-primary);" class="hidden sm:block">
                @yield('header_title')
            </div>
        @endif
    </div>

    <!-- Tengah -->
    <div class="topbar-search hidden md:block" style="flex:0 1 auto;width:100%;max-width:480px;">
        <input aria-label="{{ __('app.search_placeholder') }}" class="input input-search" placeholder="{{ __('app.search_placeholder') }}" style="width:100%; height:42px; border-radius:999px; border:1px solid #e5e7eb; background-color:#ffffff; padding-left:44px; font-size:14px; color:#374151; box-shadow:0 1px 2px rgba(0,0,0,0.02); transition:all 0.2s;" onfocus="this.style.borderColor='#d10000';this.style.boxShadow='0 0 0 3px rgba(209,0,0,0.1)';" onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='0 1px 2px rgba(0,0,0,0.02)';" />
    </div>

    @auth
    <!-- Kanan -->
    <div class="topbar-right" style="flex:1;display:flex;align-items:center;justify-content:flex-end;gap:12px;">

        {{-- Language Switcher --}}
        <div style="position:relative; margin-right: 8px;">
            <button type="button" disabled class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-xs font-bold text-gray-700 cursor-not-allowed opacity-50" title="Switch Language" aria-label="{{ strtoupper(app()->getLocale()) == 'EN' ? 'EN' : 'ID' }} - Switch Language">
                {{ strtoupper(app()->getLocale()) == 'EN' ? 'EN' : 'ID' }}
            </button>
        </div>

        {{-- Bell + Notification Overlay --}}
        <div style="position:relative">
            <button id="notif-btn" type="button" class="topbar-icon" style="background:none;border:none;cursor:pointer;position:relative" onclick="toggleNotif(event)" aria-label="Toggle Notifications">
                <x-icon name="bell" />
                <div id="notif-dot" class="dot" style="position:absolute;top:0;right:0;width:8px;height:8px;background:#f5576c;border-radius:50%;border:2px solid var(--bg-main);{{ $unreadCount == 0 ? 'display:none' : '' }}"></div>
            </button>

            {{-- Overlay panel --}}
            <div id="notif-panel" class="hidden absolute top-[calc(100%+4px)] -right-12 sm:right-0 w-[310px] sm:w-[360px] bg-white rounded-2xl shadow-xl border border-gray-100 z-[999] overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/80">
                    <div class="font-bold text-[14px] text-gray-900">{{ __('app.notifications') }}</div>
                    <div id="notif-new-badge"></div>
                </div>
                <div id="notif-list" class="max-h-[340px] overflow-y-auto">
                    <div class="py-8 px-5 text-center text-gray-500">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <p class="text-[13px] font-medium">{{ __('app.loading_notifications') }}</p>
                    </div>
                </div>
                <div class="p-3 border-t border-gray-100 bg-gray-50/50 text-center">
                    <button onclick="markAllNotificationsRead()" class="text-xs text-[#cc0000] font-semibold bg-transparent border-none cursor-pointer w-full py-1 hover:text-[#990000] transition-colors">{{ __('app.mark_all_read') }}</button>
                </div>
            </div>
        </div>

        {{-- Avatar & User Info --}}
        <a href="{{ route('pengaturan') }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:10px;margin-left:8px;" aria-label="Profile Settings">
            @if($authUser && $authUser->profile_photo)
            <img src="{{ $authUser->profile_photo }}" alt="Profile" style="width:36px; height:36px; border-radius:50%; object-fit:cover; flex-shrink:0; background:#cc0000; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            @else
            <div class="avatar" style="background:linear-gradient(135deg, #cc0000, #990000); width:36px; height:36px; font-size:14px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; flex-shrink:0; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ $initials }}</div>
            @endif
            <div class="hidden sm:flex" style="flex-direction:column; align-items:flex-start; justify-content:center;">
                <span style="font-size:14px;font-weight:700;color:var(--text-primary);line-height:1.2;">{{ $authUser->name }}</span>
                <span style="font-size:12px;font-weight:600;color:#6b7280;line-height:1.1;margin-top:2px;">{{ $isStudent ? number_format((int) ($authUser->xp ?? 0)) . ' XP' : $roleLabel }}</span>
            </div>
        </a>
    </div>
    @else
    <!-- Kanan (Guest) -->
    <div class="topbar-right" style="flex:1;display:flex;align-items:center;justify-content:flex-end;gap:12px;">
        {{-- Language Switcher --}}
        <div style="position:relative; margin-right: 8px;">
            <button type="button" disabled class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-xs font-bold text-gray-700 cursor-not-allowed opacity-50" title="Switch Language" aria-label="{{ strtoupper(app()->getLocale()) == 'EN' ? 'EN' : 'ID' }} - Switch Language">
                {{ strtoupper(app()->getLocale()) == 'EN' ? 'EN' : 'ID' }}
            </button>
        </div>
        
        <a href="{{ route('login') }}" style="font-size:14px;font-weight:600;color:var(--text-secondary);text-decoration:none;padding:8px 16px;">{{ __('app.login') }}</a>
        <a href="{{ route('signup') }}" style="font-size:14px;font-weight:600;color:#fff;background:var(--primary);text-decoration:none;padding:8px 20px;border-radius:999px;">{{ __('app.register') }}</a>
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
    const isOpen = !panel.classList.contains('hidden');

    if (isOpen) {
        panel.classList.add('hidden');
        backdrop.style.display = 'none';
    } else {
        panel.classList.remove('hidden');
        backdrop.style.display = 'block';
        loadNotifications();
    }
}

function closeNotif() {
    document.getElementById('notif-panel').classList.add('hidden');
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
        document.getElementById('notif-list').innerHTML = '<div class="py-8 px-5 text-center text-gray-500"><p class="text-[13px] font-medium">{{ __('app.failed_load_notifications') }}</p></div>';
    });
}

function renderNotifications() {
    const list = document.getElementById('notif-list');

    if (notifications.length === 0) {
        list.innerHTML = '<div class="py-8 px-5 text-center text-gray-500"><svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0v-2a2 2 0 00-2-2H6a2 2 0 00-2 2"></path></svg><p class="text-[13px] font-medium">{{ __('app.no_notifications') }}</p></div>';
        return;
    }

    list.innerHTML = notifications.map(n => {
        const colors = getColor(n.color);
        const icon = getIcon(n.icon);
        return `
            <div class="flex gap-3 p-3.5 border-b border-gray-50 hover:bg-gray-50 cursor-pointer transition-colors ${n.is_read ? 'bg-white' : 'bg-red-50/30'}"
                 onclick="handleNotificationClick(${n.id}, '${n.link || ''}')">
                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0" style="background:${colors.bg};color:${colors.text}">
                    ${icon}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[12.5px] leading-snug ${n.is_read ? 'text-gray-500' : 'text-gray-900 font-semibold'}">${n.title}</div>
                    <div class="text-[11px] text-gray-500 mt-0.5 leading-tight">${n.message}</div>
                    <div class="text-[10px] text-gray-500 mt-1.5 font-medium">${n.created_at}</div>
                </div>
                ${n.is_read ? '' : '<div class="w-1.5 h-1.5 rounded-full bg-[#cc0000] shrink-0 mt-1"></div>'}
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
            badge.innerHTML = `<span style="font-size:11px;background:#cc0000;color:#fff;border-radius:999px;padding:2px 8px">${unreadCount} {{ __('app.new') }}</span>`;
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
