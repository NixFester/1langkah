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

<div class="topbar">
    <div class="topbar-search">
        <input class="input input-search" placeholder="Cari kursus, mentor, proyek..." />
    </div>

    @auth
    <div class="topbar-right">
        {{-- XP Badge --}}
        <div class="xp-badge">&#9889; {{ $catalogUser['xp'] }}</div>

        {{-- Bell + Notification Overlay --}}
        <div style="position:relative">
            <button id="notif-btn" class="topbar-icon" style="background:none;border:none;cursor:pointer;position:relative" onclick="toggleNotif(event)">
                <x-icon name="bell" />
                @if($unreadCount > 0)
                    <div class="dot" style="position:absolute;top:0;right:0;width:8px;height:8px;background:#f5576c;border-radius:50%;border:2px solid var(--bg-main)"></div>
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

        {{-- Settings → pengaturan page --}}
        <a href="{{ route('pengaturan') }}" class="topbar-icon" style="text-decoration:none;color:inherit">
            <x-icon name="settings" />
        </a>

        {{-- Avatar → pengaturan page --}}
        <a href="{{ route('pengaturan') }}" style="text-decoration:none;color:inherit">
            <div class="avatar avatar-sm" style="background:linear-gradient(135deg,var(--primary),#b91c1c)">{{ $initials }}</div>
        </a>
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
}
function closeNotif() {
    document.getElementById('notif-panel').style.display    = 'none';
    document.getElementById('notif-backdrop').style.display = 'none';
}
</script>
@endauth