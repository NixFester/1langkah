@php
    /** @var string $activePage  id of the currently-active nav entry */
    $activePage = $activePage ?? 'dashboard';

    // Each nav item maps to a named Laravel route.
    $navGroups = [
        [
            'title' => 'MENU UTAMA',
            'items' => [
                ['id' => 'dashboard',       'icon' => 'home',      'label' => 'Dashboard',        'route' => 'dashboard'],
            ],
        ],
        [
            'title' => 'BELAJAR',
            'items' => [
                ['id' => 'kursus',          'icon' => 'book',      'label' => 'Kursus',           'route' => 'kursus'],
                ['id' => 'kursus-saya',     'icon' => 'trophy',    'label' => 'Kursus Saya',      'route' => 'kursus-saya'],
                ['id' => 'path',            'icon' => 'path',      'label' => 'Learning Path',    'route' => 'dashboard'],
                ['id' => 'quiz',            'icon' => 'quiz',      'label' => 'Quiz',             'route' => 'dashboard'],
            ],
        ],
        [
            'title' => 'BOOTCAMP',
            'items' => [
                ['id' => 'online-bootcamp',  'icon' => 'video',    'label' => 'Online Bootcamp',  'route' => 'online-bootcamp'],
                ['id' => 'offline-bootcamp', 'icon' => 'mapPin',   'label' => 'Offline Bootcamp', 'route' => 'offline-bootcamp'],
            ],
        ],
        [
            'title' => 'LAINNYA',
            'items' => [
                ['id' => 'ai-tools',  'icon' => 'ai',         'label' => 'AI Tools',   'route' => 'dashboard'],
                ['id' => 'mentor',    'icon' => 'users',      'label' => 'Mentor',     'route' => 'mentor'],
                ['id' => 'kalender',  'icon' => 'calendar',   'label' => 'Kalender',   'route' => 'kalender'],
                ['id' => 'pembayaran','icon' => 'creditCard', 'label' => 'Pembayaran', 'route' => 'pembayaran'],
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
    <div class="sidebar-footer">
        <a href="{{ route('profil-mentor', ['id' => 301]) }}" class="sidebar-user" style="text-decoration:none;color:inherit">
            <x-avatar initials="AK" />
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ $user['name'] }}</div>
                <div class="sidebar-user-role">{{ $user['role'] }}</div>
            </div>
            <x-icon name="chevronRight" />
        </a>
    </div>
</div>
