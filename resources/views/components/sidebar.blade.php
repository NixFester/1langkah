@php
    /** @var string $activePage  id of the currently-active nav entry */
    $activePage = $activePage ?? 'dashboard';

    use App\Models\MentorSession;

    // Each nav item maps to a named Laravel route.
    $isAuth = auth()->check();
    $user = $isAuth ? auth()->user() : null;
    $isAdmin = $user && in_array($user->role, ['superadmin', 'admin', 'keuangan', 'marketing']);
    $isSuperAdmin = $user && $user->role === 'superadmin';
    $isKeuangan = $user && $user->role === 'keuangan';
    $isMarketing = $user && $user->role === 'marketing';
    $isMentor = $user && $user->role === 'mentor';
    $isAdminRoute = request()->routeIs('admin.*');
    $isSuperAdminRoute = request()->routeIs('superadmin.*');
    $isKeuanganRoute = request()->routeIs('keuangan.*');
    $isMarketingRoute = request()->routeIs('marketing.*');
    $isMentorRoute = request()->routeIs('mentor.*');

    // Role label helper
    $roleLabels = [
        'superadmin' => __('app.superadmin') ?? 'Super Admin',
        'admin'      => __('app.admin') ?? 'Admin',
        'keuangan'   => __('app.finance') ?? 'Keuangan',
        'marketing'  => __('app.marketing') ?? 'Marketing',
        'mentor'     => __('app.mentor') ?? 'Mentor',
        'student'    => __('app.student') ?? 'Student',
    ];
    $roleLabel = $user ? ($roleLabels[$user->role] ?? ucfirst($user->role)) : 'User';

    $navItems = [];
    $roleNavGroups = [];
    $isSuperAdmin = $user && $user->role === 'superadmin';

    // For superadmin, prepare all role navigation groups for accordion
    if ($isSuperAdmin) {
        $roleNavGroups = [
            'superadmin' => [
                'label' => __('app.superadmin'),
                'icon' => 'superadmin',
                'color' => '#D10000',
                'items' => [
                    ['id' => 'superadmin.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'superadmin.dashboard'],
                    ['id' => 'superadmin.users', 'icon' => 'users', 'label' => __('app.manage_users'), 'route' => 'superadmin.users'],
                    ['id' => 'superadmin.audit-logs', 'icon' => 'shield', 'label' => __('app.audit_log'), 'route' => 'superadmin.audit-logs'],
                    ['id' => 'superadmin.system-stats', 'icon' => 'barChart', 'label' => __('app.system_stats'), 'route' => 'superadmin.system-stats'],
                ]
            ],
            'admin' => [
                'label' => __('app.admin'),
                'icon' => 'admin',
                'color' => '#D10000',
                'items' => [
                    ['id' => 'admin.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'admin.dashboard'],
                    ['id' => 'admin.users', 'icon' => 'users', 'label' => __('app.manage_users'), 'route' => 'admin.users'],
                    ['id' => 'admin.courses', 'icon' => 'book', 'label' => __('app.manage_courses'), 'route' => 'admin.courses'],
                    ['id' => 'admin.bootcamps', 'icon' => 'award', 'label' => __('app.manage_bootcamps'), 'route' => 'admin.bootcamps'],
                    ['id' => 'admin.events', 'icon' => 'calendar', 'label' => __('app.manage_events'), 'route' => 'admin.events'],
                    ['id' => 'admin.quizzes', 'icon' => 'quiz', 'label' => __('app.manage_quizzes'), 'route' => 'admin.quizzes'],
                    ['id' => 'admin.options', 'icon' => 'settings', 'label' => __('app.settings'), 'route' => 'admin.options'],
                ]
            ],
            'keuangan' => [
                'label' => __('app.finance'),
                'icon' => 'keuangan',
                'color' => '#D10000',
                'items' => [
                    ['id' => 'keuangan.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'keuangan.dashboard'],
                    ['id' => 'keuangan.verifications', 'icon' => 'creditCard', 'label' => __('app.payment_verification'), 'route' => 'keuangan.verifications'],
                    ['id' => 'keuangan.enrollments', 'icon' => 'users', 'label' => __('app.enrollments'), 'route' => 'keuangan.enrollments'],
                    ['id' => 'keuangan.reports', 'icon' => 'barChart', 'label' => __('app.revenue_report'), 'route' => 'keuangan.reports'],
                ]
            ],
            'marketing' => [
                'label' => __('app.marketing'),
                'icon' => 'marketing',
                'color' => '#D10000',
                'items' => [
                    ['id' => 'marketing.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'marketing.dashboard'],
                    ['id' => 'marketing.promo-codes', 'icon' => 'award', 'label' => __('app.promo_codes'), 'route' => 'marketing.promo-codes'],
                    ['id' => 'marketing.analytics', 'icon' => 'barChart', 'label' => __('app.analytics'), 'route' => 'marketing.analytics'],
                ]
            ],
            'mentor' => [
                'label' => __('app.mentor'),
                'icon' => 'mentor',
                'color' => '#D10000',
                'items' => [
                    ['id' => 'mentor.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'mentor.dashboard'],
                    ['id' => 'mentor.profile.edit', 'icon' => 'user', 'label' => __('app.edit_profile'), 'route' => 'mentor.profile.edit'],
                    ['id' => 'mentor.my-courses', 'icon' => 'award', 'label' => __('app.my_courses'), 'route' => 'mentor.my-courses'],
                    ['id' => 'mentor.courses.index', 'icon' => 'folder', 'label' => __('app.manage_courses'), 'route' => 'mentor.courses.index'],
                    ['id' => 'mentor.quizzes.index', 'icon' => 'quiz', 'label' => __('app.manage_quizzes'), 'route' => 'mentor.quizzes.index'],
                    ['id' => 'mentor.sessions.index', 'icon' => 'video', 'label' => __('app.mentoring_sessions'), 'route' => 'mentor.sessions.index'],
                    ['id' => 'mentor.bootcamps.index', 'icon' => 'target', 'label' => __('app.my_bootcamps'), 'route' => 'mentor.bootcamps.index'],
                    ['id' => 'mentor.events', 'icon' => 'calendar', 'label' => __('app.my_events'), 'route' => 'mentor.events'],
                    ['id' => 'mentor.students', 'icon' => 'users', 'label' => __('app.my_students'), 'route' => 'mentor.students'],
                    ['id' => 'mentor.feedback', 'icon' => 'starEmpty', 'label' => __('app.feedback_rating'), 'route' => 'mentor.feedback'],
                ]
            ],
        ];

        // Determine which group is active based on current route
        $activeGroup = 'superadmin';
        if (request()->routeIs('admin.*')) $activeGroup = 'admin';
        elseif (request()->routeIs('keuangan.*')) $activeGroup = 'keuangan';
        elseif (request()->routeIs('marketing.*')) $activeGroup = 'marketing';
        elseif (request()->routeIs('mentor.*')) $activeGroup = 'mentor';

        $navItems = $roleNavGroups[$activeGroup]['items'] ?? [];
    }
    // Superadmin routes
    elseif ($isSuperAdminRoute) {
        $navItems = [
            ['id' => 'superadmin.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'superadmin.dashboard'],
            ['id' => 'superadmin.users', 'icon' => 'users', 'label' => __('app.manage_users'), 'route' => 'superadmin.users'],
            ['id' => 'superadmin.audit-logs', 'icon' => 'shield', 'label' => __('app.audit_log'), 'route' => 'superadmin.audit-logs'],
            ['id' => 'superadmin.system-stats', 'icon' => 'barChart', 'label' => __('app.system_stats'), 'route' => 'superadmin.system-stats'],
            ['id' => 'back-to-app', 'icon' => 'arrowRight', 'label' => __('app.back_to_app'), 'route' => 'dashboard'],
        ];
    }
    // Keuangan routes
    elseif ($isKeuanganRoute) {
        $navItems = [
            ['id' => 'keuangan.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'keuangan.dashboard'],
            ['id' => 'keuangan.verifications', 'icon' => 'creditCard', 'label' => __('app.payment_verification'), 'route' => 'keuangan.verifications'],
            ['id' => 'keuangan.enrollments', 'icon' => 'users', 'label' => __('app.enrollments'), 'route' => 'keuangan.enrollments'],
            ['id' => 'keuangan.reports', 'icon' => 'barChart', 'label' => __('app.revenue_report'), 'route' => 'keuangan.reports'],
            ['id' => 'back-to-app', 'icon' => 'arrowRight', 'label' => __('app.back_to_app'), 'route' => 'dashboard'],
        ];
    }
    // Marketing routes
    elseif ($isMarketingRoute) {
        $navItems = [
            ['id' => 'marketing.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'marketing.dashboard'],
            ['id' => 'marketing.promo-codes', 'icon' => 'award', 'label' => __('app.promo_codes'), 'route' => 'marketing.promo-codes'],
            ['id' => 'marketing.analytics', 'icon' => 'barChart', 'label' => __('app.analytics'), 'route' => 'marketing.analytics'],
            ['id' => 'back-to-app', 'icon' => 'arrowRight', 'label' => __('app.back_to_app'), 'route' => 'dashboard'],
        ];
    }
    // Mentor routes
    elseif ($isMentorRoute) {
        // Check if user has a mentor profile
        $hasMentorProfile = $isMentor && $user && $user->mentor !== null;
        $isEditProfileRoute = request()->routeIs('mentor.profile.edit') || request()->routeIs('mentor.profile.update');

        // If no mentor profile and not on edit page, show only edit profile link
        if (!$hasMentorProfile && !$isEditProfileRoute) {
            $navItems = [
                ['id' => 'mentor.profile.edit', 'icon' => 'user', 'label' => __('app.edit_profile'), 'route' => 'mentor.profile.edit'],
                ['id' => 'back-to-app', 'icon' => 'arrowRight', 'label' => __('app.back_to_app'), 'route' => 'dashboard'],
            ];
        } else {
            // Full mentor navigation when profile exists or on edit page
            $navItems = [
                ['id' => 'mentor.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'mentor.dashboard'],
                ['id' => 'mentor.profile.edit', 'icon' => 'user', 'label' => __('app.edit_profile'), 'route' => 'mentor.profile.edit'],
                ['id' => 'mentor.my-courses', 'icon' => 'award', 'label' => __('app.my_courses'), 'route' => 'mentor.my-courses'],
                ['id' => 'mentor.courses.index', 'icon' => 'folder', 'label' => __('app.manage_courses'), 'route' => 'mentor.courses.index'],
                ['id' => 'mentor.quizzes.index', 'icon' => 'quiz', 'label' => __('app.manage_quizzes'), 'route' => 'mentor.quizzes.index'],
                ['id' => 'mentor.sessions.index', 'icon' => 'video', 'label' => __('app.mentoring_sessions'), 'route' => 'mentor.sessions.index'],
                ['id' => 'mentor.bootcamps.index', 'icon' => 'target', 'label' => __('app.my_bootcamps'), 'route' => 'mentor.bootcamps.index'],
                ['id' => 'mentor.events', 'icon' => 'calendar', 'label' => __('app.my_events'), 'route' => 'mentor.events'],
                ['id' => 'mentor.students', 'icon' => 'users', 'label' => __('app.my_students'), 'route' => 'mentor.students'],
                ['id' => 'mentor.feedback', 'icon' => 'starEmpty', 'label' => __('app.feedback_rating'), 'route' => 'mentor.feedback'],
                ['id' => 'back-to-app', 'icon' => 'arrowRight', 'label' => __('app.back_to_app'), 'route' => 'dashboard'],
            ];
        }
    }
    // Admin routes
    elseif ($isAdminRoute) {
        $navItems = [
            ['id' => 'admin.dashboard', 'icon' => 'grid', 'label' => __('app.dashboard'), 'route' => 'admin.dashboard'],
            ['id' => 'admin.users', 'icon' => 'users', 'label' => __('app.manage_users'), 'route' => 'admin.users'],
            ['id' => 'admin.courses', 'icon' => 'book', 'label' => __('app.manage_courses'), 'route' => 'admin.courses'],
            ['id' => 'admin.bootcamps', 'icon' => 'award', 'label' => __('app.manage_bootcamps'), 'route' => 'admin.bootcamps'],
            ['id' => 'admin.events', 'icon' => 'calendar', 'label' => __('app.manage_events'), 'route' => 'admin.events'],
            ['id' => 'admin.quizzes', 'icon' => 'quiz', 'label' => __('app.manage_quizzes'), 'route' => 'admin.quizzes'],
            ['id' => 'admin.options', 'icon' => 'settings', 'label' => __('app.settings'), 'route' => 'admin.options'],
            ['id' => 'back-to-app', 'icon' => 'arrowRight', 'label' => __('app.back_to_app'), 'route' => 'dashboard'],
        ];
    } else {
        // User Navigation
        if ($isAuth) {
            $navItems[] = ['id' => 'dashboard', 'icon' => 'dashboard', 'label' => __('app.dashboard'), 'route' => 'dashboard'];
        }

        $belajarSubItems = [
            ['id' => 'kursus', 'icon' => 'kursus', 'label' => __('app.course'), 'route' => 'kursus'],
        ];

        if ($isAuth) {
            $belajarSubItems[] = ['id' => 'kursus-saya', 'icon' => 'award', 'label' => __('app.my_courses'), 'route' => 'kursus-saya'];
            $belajarSubItems[] = ['id' => 'quizzes', 'icon' => 'quiz', 'label' => __('app.quiz'), 'route' => 'quiz.index'];
        }

        $belajarSubItems[] = ['id' => 'online-bootcamp', 'icon' => 'online_bootcamp', 'label' => __('app.online_bootcamp'), 'route' => 'online-bootcamp'];
        $belajarSubItems[] = ['id' => 'offline-bootcamp', 'icon' => 'offline_bootcamp', 'label' => __('app.offline_bootcamp'), 'route' => 'offline-bootcamp'];

        if ($isAuth) {
            $belajarSubItems[] = ['id' => 'bootcamps-saya', 'icon' => 'target', 'label' => __('app.my_bootcamps'), 'route' => 'bootcamps-saya'];
        }

        $navItems[] = [
            'id' => 'belajar', 'icon' => 'belajar', 'label' => __('app.learning'),
            'subItems' => $belajarSubItems
        ];

        if ($isAuth) {
            $navItems[] = ['id' => 'event', 'icon' => 'calendar', 'label' => __('app.event'), 'route' => 'event'];
            $navItems[] = ['id' => 'portofolio', 'icon' => 'layers', 'label' => __('app.portfolio'), 'route' => 'portofolio'];
            $navItems[] = ['id' => 'komunitas', 'icon' => 'users', 'label' => __('app.community'), 'route' => 'komunitas'];
            $navItems[] = ['id' => 'achievement', 'icon' => 'trophy', 'label' => __('app.achievements'), 'route' => 'achievement'];
        }

        $navItems[] = ['id' => 'mentor', 'icon' => 'mentor', 'label' => __('app.mentor'), 'route' => 'mentor'];

        if ($isAuth) {
            // Only show "Sesi Mentoring" if user has active mentor sessions
            $hasActiveMentorSession = MentorSession::where('user_id', auth()->id())
                ->whereIn('status', [MentorSession::STATUS_PENDING, MentorSession::STATUS_ACTIVE])
                ->exists();
            if ($hasActiveMentorSession) {
                $navItems[] = ['id' => 'my-sessions', 'icon' => 'video', 'label' => __('app.mentoring_sessions'), 'route' => 'my-sessions'];
            }
            $navItems[] = ['id' => 'kalender', 'icon' => 'calendar', 'label' => __('app.calendar'), 'route' => 'kalender'];
        }

        if ($isAdmin) {
            $navItems[] = [
                'id' => 'admin-panel', 'icon' => 'settings', 'label' => __('app.admin_panel'),'route' => 'admin.dashboard'];
        }
        
        if ($isMentor) {
            $navItems[] = [
                'id' => 'mentor-panel', 'icon' => 'user', 'label' => __('app.mentor_panel'), 'route' => 'mentor.dashboard'];
        }
    }
@endphp

<div class="sidebar">
    <div class="sidebar-header" style="display:flex; align-items:center; justify-content:space-between; width:100%; height: var(--topbar-h, 64px); padding: 0 20px;">
        <div class="sidebar-logo-link" style="color:inherit; display:block; width:120px; flex-shrink:0; transition:width 0.3s cubic-bezier(0.4,0,0.2,1); overflow:hidden;">
            <svg width="120" height="36" viewBox="0 0 120 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip_logo_sidebar)">
                <path d="M22.3789 27.1026H16.3237V7.52808H22.3789V27.1026Z" fill="#D10000"/>
                <g filter="url(#filter_logo_sidebar)">
                    <path d="M22.3746 7.57027C22.374 7.57183 22.3735 7.57359 22.373 7.57546C22.3719 7.57922 22.3705 7.58367 22.369 7.58868C22.366 7.59876 22.3622 7.61144 22.3576 7.62646C22.3484 7.65648 22.3362 7.69629 22.3206 7.7453C22.2894 7.84337 22.2451 7.9786 22.1872 8.14667C22.0716 8.48256 21.9009 8.95166 21.6697 9.51938C21.2088 10.651 20.4993 12.1959 19.4946 13.8706C17.5141 17.1718 14.2201 21.2532 9.16017 23.4218L6.7749 17.8562C10.1921 16.3917 12.6499 13.5095 14.302 10.7556C15.1136 9.40283 15.6905 8.14735 16.0619 7.23532C16.2468 6.78124 16.3787 6.41755 16.4618 6.17631C16.5032 6.05587 16.5322 5.96636 16.5496 5.91188C16.5582 5.8847 16.5639 5.86625 16.5667 5.8571L16.5681 5.85254C16.568 5.85317 16.5678 5.85399 16.5675 5.8549C16.5674 5.85534 16.5668 5.8568 16.5667 5.8571C16.5665 5.85753 16.5667 5.85723 16.9297 5.96429L22.0125 7.45993C22.3708 7.56565 22.3753 7.56754 22.3752 7.56806C22.375 7.56844 22.3748 7.56949 22.3746 7.57027Z" fill="#E50000"/>
                </g>
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
        </div>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="collapse-btn text-gray-500 hover:text-gray-600 cursor-pointer hidden md:flex items-center justify-center p-1 rounded hover:bg-gray-100 transition-colors" aria-label="Toggle Sidebar">
            <svg class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
    </div>
    <div class="sidebar-nav">
        {{-- Superadmin Accordion Navigation --}}
        @if($isSuperAdmin && !empty($roleNavGroups))
            <div x-data="{ activeGroup: '{{ $activeGroup ?? 'superadmin' }}' }">
                {{-- Role Navigation Groups as Accordion --}}
                @foreach($roleNavGroups as $groupKey => $group)
                    <div class="nav-section" style="padding: 0 12px;">
                        {{-- Group Header --}}
                        <button
                            @click="activeGroup === '{{ $groupKey }}' ? activeGroup = null : activeGroup = '{{ $groupKey }}'"
                            class="nav-item"
                            style="width: 100%; justify-content: space-between; margin-bottom: 2px; {{ $activeGroup === $groupKey ? 'background-color: #ffe4e6; border-left: 3px solid #cc0000; color: #cc0000;' : '' }}"
                        >
                            <div style="display:flex; align-items:center; gap:10px;">
                                <x-icon :name="$group['icon']" style="{{ $activeGroup === $groupKey ? 'color: #cc0000;' : 'color: inherit;' }}" />
                                <span class="sidebar-text" style="font-size: 14px; font-weight: 500;">{{ $group['label'] }}</span>
                            </div>
                            <svg class="sidebar-text transition-transform duration-200" :class="activeGroup === '{{ $groupKey }}' ? 'rotate-180' : ''" style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Group Items --}}
                        <div x-show="activeGroup === '{{ $groupKey }}'" style="display: none;" class="sidebar-text pt-1 pb-2 nav-sub-container">
                            <div style="padding-left: 12px; display: flex; flex-direction: column; gap: 4px; border-left: 1px solid var(--border-light); margin-left: 20px; margin-top: 8px;">
                                @foreach($group['items'] as $item)
                                    @php
                                        $isItemActive = ($activePage ?? 'dashboard') === ($item['id'] ?? '');
                                    @endphp
                                    <a href="{{ route($item['route'] ?? 'dashboard') }}"
                                       class="nav-item"
                                       style="padding: 8px 14px; border-radius: 9999px; margin-bottom: 0; {{ $isItemActive ? 'background-color: #ffe4e6; color: #cc0000; font-weight: 600;' : 'color: inherit;' }}">
                                        <x-icon :name="$item['icon']" style="width:15px;height:15px; {{ $isItemActive ? 'color: #cc0000;' : '' }}" />
                                        <span style="font-size: 13px;">{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Back to App --}}
                <div class="nav-section" style="padding: 0 12px; margin-top: 16px;">
                    <a href="{{ route('dashboard') }}" class="nav-item" style="margin-bottom: 2px;">
                        <x-icon name="arrowRight" />
                        <span class="sidebar-text" style="font-size: 14px; font-weight: 500;">{{ __('app.back_to_app') }}</span>
                    </a>
                </div>
            </div>
        @else
        {{-- Regular Navigation --}}
        @foreach($navItems as $item)
            @php
                $hasSubItems = isset($item['subItems']) && count($item['subItems']) > 0;
                $isDropdown = isset($item['subItems']);

                $isParentActive = false;
                if ($hasSubItems) {
                    foreach ($item['subItems'] as $subItem) {
                        if ($subItem['id'] === $activePage) $isParentActive = true;
                        if ($subItem['id'] === 'kursus' && in_array($activePage, ['kursus', 'detail-kursus'])) $isParentActive = true;
                        if ($subItem['id'] === 'online-bootcamp' && in_array($activePage, ['online-bootcamp', 'detail-online-bootcamp'])) $isParentActive = true;
                        if ($subItem['id'] === 'offline-bootcamp' && in_array($activePage, ['offline-bootcamp', 'detail-offline-bootcamp'])) $isParentActive = true;
                        if (in_array($subItem['id'], ['quiz', 'quizzes']) && (Str::startsWith($activePage, 'quiz.') || Str::startsWith($activePage, 'quizzes'))) $isParentActive = true;
                    }
                }

                $isActive = $item['id'] === $activePage;
                if ($item['id'] === 'mentor' && $activePage === 'profil-mentor') $isActive = true;
                if ($item['id'] === 'komunitas' && in_array($activePage, ['komunitas', 'komunitas.show', 'komunitas.create'])) $isActive = true;
            @endphp

            @if($isDropdown)
                <div x-data="{ open: {{ $isParentActive ? 'true' : 'false' }} }" class="nav-section" style="padding: 0 12px;">
                    <button @click="open = !open" class="nav-item" style="width: 100%; justify-content: space-between; margin-bottom: 2px; {{ $isParentActive ? 'background-color: #ffe4e6; border-left: 3px solid #cc0000; color: #cc0000;' : '' }}">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <x-icon :name="$item['icon']" style="{{ $isParentActive ? 'color: #cc0000;' : 'color: inherit;' }}" />
                            <span class="sidebar-text" style="font-size: 14px; font-weight: 500;">{{ $item['label'] }}</span>
                        </div>
                        <svg class="sidebar-text transition-transform duration-200" :class="open ? 'rotate-180' : ''" style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    @if($hasSubItems)
                    <div x-show="open" style="display: {{ $isParentActive ? 'block' : 'none' }}" class="sidebar-text pt-1 pb-2 nav-sub-container">
                        <div style="padding-left: 12px; display: flex; flex-direction: column; gap: 4px; border-left: 1px solid var(--border-light); margin-left: 20px; margin-top: 8px;">
                            @foreach($item['subItems'] as $subItem)
                                @php
                                    $isSubActive = $activePage === $subItem['id'];
                                    if ($subItem['id'] === 'kursus' && in_array($activePage, ['kursus', 'detail-kursus'])) $isSubActive = true;
                                    if ($subItem['id'] === 'online-bootcamp' && in_array($activePage, ['online-bootcamp', 'detail-online-bootcamp'])) $isSubActive = true;
                                    if ($subItem['id'] === 'offline-bootcamp' && in_array($activePage, ['offline-bootcamp', 'detail-offline-bootcamp'])) $isSubActive = true;
                                    if ($subItem['id'] === 'bootcamps-saya' && in_array($activePage, ['bootcamps-saya'])) $isSubActive = true;
                                    if (in_array($subItem['id'], ['quiz', 'quizzes']) && (Str::startsWith($activePage, 'quiz.') || Str::startsWith($activePage, 'quizzes'))) $isSubActive = true;

                                    $linkHref = isset($subItem['url']) ? $subItem['url'] : route($subItem['route'] ?? 'dashboard');
                                @endphp
                                <a href="{{ $linkHref }}" class="nav-item" style="padding: 8px 14px; border-radius: 9999px; margin-bottom: 0; {{ $isSubActive ? 'background-color: #ffe4e6; color: #cc0000; font-weight: 600;' : 'color: inherit;' }}">
                                    <x-icon :name="$subItem['icon']" style="width:15px;height:15px; {{ $isSubActive ? 'color: #cc0000;' : '' }}" />
                                    <span style="font-size: 13px;">{{ $subItem['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @else
                <div class="nav-section" style="padding: 0 12px;">
                    @php
                        $linkHref = isset($item['url']) ? $item['url'] : route($item['route'] ?? 'dashboard');
                    @endphp
                    <a href="{{ $linkHref }}" class="nav-item {{ $isActive ? 'active' : '' }}" style="margin-bottom: 2px;">
                        <x-icon :name="$item['icon']" />
                        <span class="sidebar-text" style="font-size: 14px; font-weight: 500;">{{ $item['label'] }}</span>
                    </a>
                </div>
            @endif
        @endforeach
        @endif
    </div>
    @auth
    <div class="sidebar-footer" style="padding: 16px 0 20px 0; border-top: none;">
        @php $authUser = auth()->user(); @endphp
        <div class="flex flex-col w-full">
            <div class="nav-section" style="padding: 0 12px; margin-bottom: 8px;">
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0 w-full">
                    @csrf
                    <button type="submit" class="nav-item logout-btn w-full bg-transparent border-none cursor-pointer text-left m-0 transition-colors">
                        <svg class="transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="sidebar-text transition-colors" style="font-size: 14px; font-weight: 500;">{{ __('app.logout') }}</span>
                    </button>
                </form>
            </div>

            <div class="flex items-center w-full" style="padding: 0 16px;">
                <a href="{{ route('pengaturan') }}" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:12px;flex:1;">
                    @if($authUser && $authUser->profile_photo)
                    <img decoding="async" loading="lazy" alt="" src="{{ $authUser->profile_photo }}" alt="Profile" style="width:40px;height:40px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    @else
                    <div class="avatar" style="background:linear-gradient(135deg,var(--primary),#b91c1c);width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:16px;">
                        {{ strtoupper(substr($authUser->name ?? 'A', 0, 2)) }}
                    </div>
                    @endif
                    <div class="sidebar-user-info" style="flex:1;">
                        <div class="sidebar-user-name" style="font-weight:700;font-size:14px;line-height:1.2;color:#1f2937;">{{ $authUser->name ?? 'Atta Ul Karim' }}</div>
                        <div class="sidebar-user-role" style="font-size:12px;color:#6b7280;margin-top:2px;">{{ $roleLabel }}</div>
                    </div>
                </a>
                <a href="{{ route('pengaturan') }}" class="text-gray-500 transition-colors ml-auto shrink-0 flex items-center justify-center pl-2 settings-btn" title="{{ __('app.settings') }}" aria-label="Settings">
                    <x-icon name="settings" style="width:20px;height:20px;" class="transition-colors" />
                </a>
            </div>
        </div>
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

.logout-btn:hover,
.logout-btn:hover .sidebar-text,
.logout-btn:hover svg {
    color: #cc0000 !important;
}

.settings-btn:hover,
.settings-btn:hover svg {
    color: #cc0000 !important;
}
</style>
