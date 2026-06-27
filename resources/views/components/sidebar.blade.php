@php
    /** @var string $activePage  id of the currently-active nav entry */
    $activePage = $activePage ?? 'dashboard';

    // Each nav item maps to a named Laravel route.
    $isAuth = auth()->check();

    $navGroups = [
        ...($isAuth ? [[
            'title' => 'MENU UTAMA',
            'items' => [
                ['id' => 'dashboard', 'icon' => 'home', 'label' => 'Dashboard', 'route' => 'dashboard'],
            ],
        ]] : []),
        [
            'title' => 'BELAJAR',
            'items' => [
                ['id' => 'kursus', 'icon' => 'book', 'label' => 'Kursus', 'route' => 'kursus'],
                ...($isAuth ? [
                    ['id' => 'kursus-saya', 'icon' => 'trophy',  'label' => 'Kursus Saya',   'route' => 'kursus-saya'],
                    ['id' => 'path',        'icon' => 'path',    'label' => 'Learning Path', 'route' => 'dashboard'],
                    ['id' => 'quiz',        'icon' => 'quiz',    'label' => 'Quiz',          'route' => 'dashboard'],
                ] : []),
            ],
        ],
        [
            'title' => 'BOOTCAMP',
            'items' => [
                ['id' => 'online-bootcamp',  'icon' => 'video',  'label' => 'Online Bootcamp',  'route' => 'online-bootcamp'],
                ['id' => 'offline-bootcamp', 'icon' => 'mapPin', 'label' => 'Offline Bootcamp', 'route' => 'offline-bootcamp'],
            ],
        ],
        [
            'title' => 'LAINNYA',
            'items' => [
                ...($isAuth ? [
                    ['id' => 'ai-tools', 'icon' => 'ai',       'label' => 'AI Tools', 'route' => 'dashboard'],
                ] : []),
                ['id' => 'mentor',   'icon' => 'users',     'label' => 'Mentor',   'route' => 'mentor'],
                ...($isAuth ? [
                    ['id' => 'kalender', 'icon' => 'calendar', 'label' => 'Kalender', 'route' => 'kalender'],
                ] : []),
            ],
        ],
    ];

    $user = app(\App\Services\CatalogService::class)->user();
@endphp
<div class="sidebar">
    <a href="{{ route('landing') }}" class="sidebar-header" style="text-decoration:none;color:inherit;cursor:pointer">
        <div class="sidebar-logo">1</div>
        <span class="sidebar-brand">1Langkah</span>
    </a>
    <div class="sidebar-nav">
        @foreach($navGroups as $g)
            <div class="nav-section">
                <div class="nav-section-title">{{ $g['title'] }}</div>
                @foreach($g['items'] as $item)
                    @php
                        $isActive = $activePage === $item['id'];
                        // Treat "kursus-saya" & "detail-kursus" as part of the Kursus group
                        if ($item['id'] === 'kursus' && in_array($activePage, ['kursus-saya', 'detail-kursus'])) {
                            $isActive = true;
                        }
                        if ($item['id'] === 'online-bootcamp' && $activePage === 'detail-online-bootcamp') {
                            $isActive = true;
                        }
                        if ($item['id'] === 'offline-bootcamp' && $activePage === 'detail-offline-bootcamp') {
                            $isActive = true;
                        }
                        if ($item['id'] === 'mentor' && $activePage === 'profil-mentor') {
                            $isActive = true;
                        }
                    @endphp
                    <a href="{{ route($item['route']) }}" class="nav-item {{ $isActive ? 'active' : '' }}">
                        <x-icon :name="$item['icon']" />
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
    @auth
    <div class="sidebar-footer">
        @php $authUser = auth()->user(); @endphp
        <a href="{{ route('pengaturan') }}" class="sidebar-user" style="text-decoration:none;color:inherit">
            <div class="avatar" style="background:linear-gradient(135deg,var(--primary),#b91c1c)">
                {{ strtoupper(substr($authUser->name, 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $authUser->name }}</div>
                <div class="sidebar-user-role">{{ $authUser->email }}</div>
            </div>
            <x-icon name="settings" />
        </a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:8px">
            @csrf
            <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;color:var(--text-muted)">
                <x-icon name="logout" />
                <span>Keluar</span>
            </button>
        </form>
    </div>
    @endauth
</div>
