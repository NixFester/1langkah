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
    <div class="sidebar-header" style="display:flex; align-items:center; justify-content:space-between; width:100%; padding: 16px 20px;">
        <a href="{{ route('landing') }}" class="sidebar-logo-link" style="text-decoration:none;color:inherit;cursor:pointer; display:block; width:120px; flex-shrink:0; transition:width 0.3s cubic-bezier(0.4,0,0.2,1); overflow:hidden;">
            <svg width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip_logo_sidebar)">
                <path d="M22.3789 27.1026H16.3237V7.52808H22.3789V27.1026Z" fill="#D10000"/>
                <g filter="url(#filter_logo_sidebar)">
                    <path d="M22.3746 7.57027C22.374 7.57183 22.3735 7.57359 22.373 7.57546C22.3719 7.57922 22.3705 7.58367 22.369 7.58868C22.366 7.59876 22.3622 7.61144 22.3576 7.62646C22.3484 7.65648 22.3362 7.69629 22.3206 7.7453C22.2894 7.84337 22.2451 7.9786 22.1872 8.14667C22.0716 8.48256 21.9009 8.95166 21.6697 9.51938C21.2088 10.651 20.4993 12.1959 19.4946 13.8706C17.5141 17.1718 14.2201 21.2532 9.16017 23.4218L6.7749 17.8562C10.1921 16.3917 12.6499 13.5095 14.302 10.7556C15.1136 9.40283 15.6905 8.14735 16.0619 7.23532C16.2468 6.78124 16.3787 6.41755 16.4618 6.17631C16.5032 6.05587 16.5322 5.96636 16.5496 5.91188C16.5582 5.8847 16.5639 5.86625 16.5667 5.8571L16.5681 5.85254C16.568 5.85317 16.5678 5.85399 16.5675 5.8549C16.5674 5.85534 16.5668 5.8568 16.5667 5.8571C16.5665 5.85753 16.5667 5.85723 16.9297 5.96429L22.0125 7.45993C22.3708 7.56565 22.3753 7.56754 22.3752 7.56806C22.375 7.56844 22.3748 7.56949 22.3746 7.57027Z" fill="#E50000"/>
                </g>
                <!-- Text elements colored black for light background -->
                <g class="sidebar-logo-text">
                    <path d="M29.6583 7.91772V23.195H36.7659V26.9803H25.8457V7.91772H29.6583Z" fill="#0f172a"/>
                    <path d="M48.8093 15.2705H51.4236V27.0076H48.5371V25.8638C47.4477 26.7898 46.0317 27.3343 44.5067 27.3343C41.0482 27.3343 38.2705 24.5567 38.2705 21.1254C38.2705 17.6669 41.0482 14.9165 44.5067 14.9165C46.0317 14.9165 47.4477 15.4611 48.5371 16.387L48.8093 15.2705ZM47.0937 23.3857C47.6929 22.7594 47.9924 21.9696 47.9924 21.1254C47.9924 20.2812 47.6929 19.4643 47.0937 18.8652C46.5219 18.266 45.7593 17.9392 44.9424 17.9392C44.1255 17.9392 43.3629 18.266 42.7638 18.8652C42.1919 19.4643 41.8651 20.2812 41.8651 21.1254C41.8651 21.9696 42.1919 22.7594 42.7638 23.3857C43.3629 23.9848 44.1255 24.3116 44.9424 24.3116C45.7593 24.3116 46.5219 23.9848 47.0937 23.3857Z" fill="#0f172a"/>
                    <path d="M55.2833 15.2704L55.828 16.3052C56.7539 15.4883 57.9793 14.9436 59.1775 14.9436C62.3365 14.9436 64.9235 17.7213 64.9235 21.1798V27.0075H61.6012V21.1798C61.6012 19.4914 60.4029 17.9936 58.7691 17.9936C57.1351 17.9936 55.9369 19.4914 55.9369 21.1798V27.0075H52.5056V15.2704H55.2833Z" fill="#0f172a"/>
                    <path d="M78.5433 16.4688L76.5554 17.1223C77.3996 18.021 77.917 19.2192 77.917 20.5263C77.917 23.4674 75.33 25.8366 72.1165 25.8366C71.6808 25.8366 71.2723 25.7821 70.8639 25.7005C70.6188 26.1362 70.3737 26.6263 70.1831 27.1166C70.8095 27.0348 71.4902 26.9804 72.2527 26.9804C74.4313 26.9804 76.038 27.3344 77.1817 28.0425C78.38 28.805 79.0336 29.976 79.0336 31.3376C79.0336 32.7536 78.4344 33.8702 77.2635 34.6326C76.1469 35.3407 74.513 35.6947 72.2527 35.6947C69.9924 35.6947 68.3585 35.3407 67.242 34.6326C66.071 33.8702 65.4719 32.7536 65.4719 31.3376C65.4719 30.7929 65.5808 30.2755 65.7987 29.7853C66.3433 27.9608 67.596 25.9456 68.4947 24.6657C67.2148 23.6853 66.3706 22.1875 66.3706 20.5263C66.3706 17.5853 68.9576 15.216 72.1165 15.216C72.3889 15.216 72.6612 15.2433 72.9335 15.2705L78.5433 14.2085V16.4688ZM72.1165 18.2661C70.7277 18.2661 69.6657 19.3553 69.6657 20.5263C69.6657 21.8607 70.8911 22.7866 72.1165 22.7866C73.5327 22.7866 74.5947 21.6973 74.5947 20.5263C74.5947 19.2464 73.4781 18.2661 72.1165 18.2661ZM72.2527 32.5903C74.5947 32.5903 75.6839 32.2089 75.6839 31.3376C75.6839 30.5205 74.2952 30.0849 72.2527 30.0849C70.1013 30.0849 68.8214 30.6023 68.8214 31.3376C68.8214 32.2089 70.0742 32.5903 72.2527 32.5903Z" fill="#0f172a"/>
                    <path d="M92.57 27.0076H88.104L84.1827 22.242L83.3929 23.0589V27.0076H79.5803V6.74683H83.3929V19.0558L86.8786 15.2705H91.535L86.7697 20.1724L92.57 27.0076Z" fill="#0f172a"/>
                    <path d="M102.028 15.2705H104.643V27.0076H101.756V25.8638C100.667 26.7898 99.2509 27.3343 97.7254 27.3343C94.2667 27.3343 91.4893 24.5567 91.4893 21.1254C91.4893 17.6669 94.2667 14.9165 97.7254 14.9165C99.2509 14.9165 100.667 15.4611 101.756 16.387L102.028 15.2705ZM100.313 23.3857C100.911 22.7594 101.211 21.9696 101.211 21.1254C101.211 20.2812 100.911 19.4643 100.313 18.8652C99.7405 18.266 98.9782 17.9392 98.161 17.9392C97.3447 17.9392 96.5815 18.266 95.983 18.8652C95.4106 19.4643 95.0839 20.2812 95.0839 21.1254C95.0839 21.9696 95.4106 22.7594 95.983 23.3857C96.5815 23.9848 97.3447 24.3116 98.161 24.3116C98.9782 24.3116 99.7405 23.9848 100.313 23.3857Z" fill="#0f172a"/>
                    <path d="M109.346 6.74683V16.0058C110.354 15.325 111.552 14.9165 112.86 14.9165C116.291 14.9165 119.068 17.7214 119.068 21.1527V27.0076H115.473V21.1527C115.473 20.3085 115.147 19.5188 114.575 18.8924C114.003 18.2933 113.241 17.9666 112.423 17.9666C111.607 17.9666 110.817 18.2933 110.245 18.8924C109.673 19.5188 109.346 20.3085 109.346 21.1527V27.0076H105.725V6.74683H109.346Z" fill="#0f172a"/>
                </g>
            </g>
            <defs>
                <filter id="filter_logo_sidebar" x="0.599103" y="-0.00106061" width="28.5963" height="30.5653" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="0.3222" dy="0.6444"/>
                    <feGaussianBlur stdDeviation="3.249"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_306_8219"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_306_8219" result="shape"/>
                </filter>
                </clipPath>
            </defs>
        </svg>
        </a>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="collapse-btn text-gray-400 hover:text-gray-600 cursor-pointer hidden md:flex items-center justify-center p-1 rounded hover:bg-gray-100 transition-colors">
            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
    </div>
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
                        <span class="sidebar-text">{{ $item['label'] }}</span>
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
                <span class="sidebar-text">Keluar</span>
            </button>
        </form>
    </div>
    @endauth
</div>


<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
