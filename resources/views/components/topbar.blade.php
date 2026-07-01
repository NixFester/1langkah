@php
    $notifications = [
        ['icon' => 'zap',      'text' => 'Kamu mendapat badge "7 Day Streak"!',              'time' => '2 menit lalu',  'color' => '#ffb900', 'unread' => true],
        ['icon' => 'book',     'text' => 'Lesson baru: "Advanced React Patterns" tersedia',   'time' => '1 jam lalu',    'color' => '#667eea', 'unread' => true],
        ['icon' => 'users',    'text' => 'Mentor Rudi Yesaya membalas pesanmu',               'time' => '3 jam lalu',    'color' => '#10b981', 'unread' => false],
        ['icon' => 'award',    'text' => 'Selamat! Kamu selesaikan kursus Data Science',      'time' => 'Kemarin',       'color' => '#f5576c', 'unread' => false],
        ['icon' => 'calendar', 'text' => 'Pengingat: Bootcamp session besok jam 10:00 WIB',  'time' => 'Kemarin',       'color' => '#764ba2', 'unread' => false],
        ['icon' => 'trophy',   'text' => 'Kamu naik ke peringkat #4 leaderboard minggu ini', 'time' => '2 hari lalu',   'color' => '#43e97b', 'unread' => false],
    ];
    $unreadCount = count(array_filter($notifications, fn($n) => $n['unread']));
    $authUser = auth()->user();
    $initials = $authUser ? strtoupper(substr($authUser->name, 0, 1)) : '';
    $catalogUser = $authUser ? app(\App\Services\CatalogService::class)->user() : null;
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
                @if($unreadCount > 0)
                    <div id="notif-dot" class="dot" style="position:absolute;top:0;right:0;width:8px;height:8px;background:#f5576c;border-radius:50%;border:2px solid var(--bg-main)"></div>
                @endif
            </button>

            {{-- Overlay panel --}}
            <div id="notif-panel" style="display:none;position:absolute;top:calc(100% + 10px);right:0;width:340px;background:#fff;border-radius:var(--radius-xl);box-shadow:0 8px 32px rgba(0,0,0,.12);border:1px solid var(--border-light);z-index:999">
                <div style="padding:16px 20px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;justify-content:space-between">
                    <div style="font-weight:700;font-size:14px">Notifikasi</div>
                    @if($unreadCount > 0)
                        <span style="font-size:11px;background:var(--primary);color:#fff;border-radius:999px;padding:2px 8px">{{ $unreadCount }} baru</span>
                    @endif
                </div>
                <div style="max-height:340px;overflow-y:auto">
                    @foreach($notifications as $n)
                        <div style="display:flex;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border-light);{{ $n['unread'] ? 'background:var(--primary-bg)' : '' }};cursor:pointer;transition:background .15s" onmouseover="this.style.background='var(--bg-gray)'" onmouseout="this.style.background='{{ $n['unread'] ? 'var(--primary-bg)' : 'transparent' }}'">
                            <div style="width:36px;height:36px;border-radius:50%;background:{{ $n['color'] }}22;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:{{ $n['color'] }}">
                                <x-icon :name="$n['icon']" style="width:16px;height:16px" />
                            </div>
                            <div style="flex:1;min-width:0">
                                <div style="font-size:12px;line-height:1.5;{{ $n['unread'] ? 'font-weight:600' : 'color:var(--text-secondary)' }}">{{ $n['text'] }}</div>
                                <div style="font-size:11px;color:var(--text-light);margin-top:3px">{{ $n['time'] }}</div>
                            </div>
                            @if($n['unread'])
                                <div style="width:7px;height:7px;border-radius:50%;background:var(--primary);flex-shrink:0;margin-top:5px"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div style="padding:12px 20px;text-align:center">
                    <span style="font-size:12px;color:var(--primary);cursor:pointer;font-weight:600">Tandai semua sudah dibaca</span>
                </div>
            </div>
        </div>

        {{-- Message icon --}}
        <div style="position:relative">
            <a href="#" id="btn-pesan" class="topbar-icon" style="background:none;border:none;cursor:pointer;position:relative;display:flex;color:inherit;text-decoration:none" onclick="event.preventDefault(); const msgDot = document.getElementById('msg-dot'); if(msgDot) msgDot.style.display = 'none';">
                <x-icon name="message" />
                <div id="msg-dot" class="dot" style="position:absolute;top:0;right:0;width:8px;height:8px;background:#f5576c;border-radius:50%;border:2px solid var(--bg-main)"></div>
            </a>
        </div>

        {{-- Avatar & User Info --}}
        <a href="{{ route('pengaturan') }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:10px;margin-left:8px;">
            <div class="avatar" style="background:#cc0000; width:32px; height:32px; font-size:13px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; flex-shrink:0;">{{ $initials }}</div>
            <div style="display:flex; flex-direction:column; align-items:flex-start; justify-content:center;">
                <span style="font-size:15px;font-weight:800;color:var(--text-primary);line-height:1.1;">{{ $authUser->name }}</span>
                <span style="font-size:13px;font-weight:500;color:var(--text-light);line-height:1.1;margin-top:4px;">{{ number_format((int) $catalogUser['xp']) }} XP</span>
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
function toggleNotif(e) {
    e.stopPropagation();
    const panel    = document.getElementById('notif-panel');
    const backdrop = document.getElementById('notif-backdrop');
    const isOpen   = panel.style.display !== 'none';
    
    panel.style.display    = isOpen ? 'none' : 'block';
    backdrop.style.display = isOpen ? 'none' : 'block';
    
    // Nonaktifkan (sembunyikan) dot notifikasi saat diklik
    const notifDot = document.getElementById('notif-dot');
    if (notifDot) notifDot.style.display = 'none';
}

function closeNotif() {
    document.getElementById('notif-panel').style.display    = 'none';
    document.getElementById('notif-backdrop').style.display = 'none';
}
</script>
@endauth