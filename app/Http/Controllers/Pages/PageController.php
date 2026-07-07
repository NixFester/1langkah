<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Bootcamp;
use App\Models\Completion;
use App\Models\Course;
use App\Models\CourseRating;
use App\Models\Enrollment;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\SessionProgress;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserActivityLog;
use App\Models\UserSetting;
use App\Models\VideoProgress;
use App\Services\CatalogService;
use App\Services\NotificationService;
use App\Services\XpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(protected CatalogService $catalog) {}

    /* -----------------------------------------------------------------
     * Public / guest pages
     * ----------------------------------------------------------------- */

    public function landing(): View
    {
        return view('pages.landing', [
            'courses' => $this->catalog->courses(),
            'mentors' => array_slice($this->catalog->mentors(), 0, 4),
            'testimonials' => $this->catalog->testimonials(),
            'bootcamp' => $this->catalog->onlineBootcamp(101),
        ]);
    }

    /* -----------------------------------------------------------------
     * Login / signup / logout pages
     * ----------------------------------------------------------------- */

    public function login(): View
    {
        return view('pages.login');
    }

    public function loginSubmit(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect to role-specific dashboard (matches role-flow-diagrams.md section 1)
            $user = Auth::user();

            return redirect()->to($user->getDashboardRoute());
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function signup(): View
    {
        return view('pages.signup');
    }

    public function signupSubmit(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->first_name.' '.$request->last_name,
            'email' => $request->email,
            'password' => $request->password,   // cast auto-hashes it
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    /* -----------------------------------------------------------------
     * pengaturan pages (sidebar + topbar)
     * ----------------------------------------------------------------- */

    public function pengaturan(): View
    {
        $user = auth()->user();
        // Load user settings with user relation
        $user->load('settings');

        // Create settings if not exists
        if (! $user->settings) {
            UserSetting::findOrCreateForUser($user->id);
            $user->refresh();
            $user->load('settings');
        }

        return view('pages.pengaturan', [
            'authUser' => $user,
        ]);
    }

    public function updatePengaturan(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'bio' => ['nullable', 'string', 'max:500'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['min:8'];
            $rules['password_confirmation'] = ['same:password'];
        }

        $request->validate($rules);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;

        if ($request->filled('password')) {
            $user->password = $request->password; // cast hashes it
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function pesan(): View
    {
        return view('pages.pesan');
    }

    /* -----------------------------------------------------------------
     * Dashboard pages (sidebar + topbar)
     * ----------------------------------------------------------------- */

    public function dashboard(): View
    {
        $userId = auth()->id();
        $userStats = $this->catalog->userStats($userId);
        $myCourses = $userId ? $this->catalog->userEnrolledCourses($userId) : [];
        $myBootcamps = $userId ? $this->catalog->userEnrolledBootcamps($userId) : [];
        $recentActivities = $userId ? $this->catalog->recentActivities($userId) : [];
        $upcomingEvents = $this->catalog->upcomingEvents($userId);
        $recommendedCourses = $userId ? $this->catalog->recommendedCourses($userId) : [];

        // Get user's achievements
        $userAchievements = [];
        if ($userId) {
            $userAchievements = UserAchievement::where('user_id', $userId)
                ->with('achievement')
                ->orderBy('earned_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Get XP data for dashboard widget
        $xpData = ['xp' => 0, 'level' => 1, 'xpToNextLevel' => 100, 'xpProgressPercent' => 0, 'leaderboard' => []];
        if ($userId) {
            $xpService = app(XpService::class);
            $user = auth()->user();
            $xpData['xp'] = $user->xp ?? 0;
            $xpData['level'] = $user->level ?? 1;
            $nextLevel = $xpService->getXpToNextLevel($userId);
            $xpData['xpToNextLevel'] = $nextLevel['xp_needed'] ?? 100;
            $xpData['xpProgressPercent'] = $nextLevel['progress_percent'] ?? 0;
            $xpData['leaderboard'] = $xpService->getLeaderboard(5);
        }

        return view('pages.dashboard', [
            'userStats' => $userStats,
            'activeCourses' => $myCourses,
            'myBootcamps' => $myBootcamps,
            'recentActivities' => $recentActivities,
            'upcomingEvents' => $upcomingEvents,
            'recommendedCourses' => $recommendedCourses,
            'userAchievements' => $userAchievements,
            'xpToNextLevel' => $xpData['xpToNextLevel'],
            'xpProgressPercent' => $xpData['xpProgressPercent'],
            'leaderboard' => $xpData['leaderboard'],
        ]);
    }

    public function kursus(): View
    {
        $userId = auth()->id();
        $myCourses = $userId ? $this->catalog->userEnrolledCourses($userId) : [];
        $userStats = $userId ? $this->catalog->userStats($userId) : [
            'courses_enrolled' => 0,
            'bootcamps_enrolled' => 0,
            'courses_completed' => 0,
            'bootcamps_completed' => 0,
            'certificates' => 0,
            'xp' => 0,
            'streak' => 0,
        ];
        $allCourses = $this->catalog->courses();

        // Courses not yet enrolled
        $enrolledIds = array_column($myCourses, 'id');
        $otherCourses = array_values(array_filter($allCourses, fn ($c) => ! in_array($c['id'], $enrolledIds)));

        return view('pages.kursus', [
            'courses' => $allCourses,
            'myCourses' => $myCourses,
            'otherCourses' => $otherCourses,
            'userStats' => $userStats,
            'categories' => $this->catalog->categories(),
            'levels' => $this->catalog->levels(),
        ]);
    }

    public function detailKursus(int $id): View
    {
        $course = $this->catalog->course($id) ?? $this->catalog->courses()[0];

        // Get gallery photos from database (pictures relationship)
        $courseModel = Course::with(['pictures', 'chapters.videos', 'chapters.resources'])->find($id);
        $photos = [];
        if ($courseModel && $courseModel->pictures) {
            $photos = $courseModel->pictures
                ->where('type', 'gallery')
                ->sortBy('order')
                ->map(fn ($pic) => ['url' => $pic->url, 'alt' => $pic->description ?? $course['title']])
                ->values()
                ->toArray();
        }

        // Get all resources for this course
        $resources = \DB::select('SELECT id, title, type, url, file_size, chapter_id FROM resources WHERE course_id = ?', [$id]);

        // Get chapters with videos
        $chapters = [];
        $completedVideoIds = [];
        $isCompleted = false;
        if ($courseModel) {
            // Get user's watched videos if logged in
            if (auth()->check()) {
                // First get all video IDs from all chapters
                $allVideoIds = [];
                foreach ($courseModel->chapters as $ch) {
                    foreach ($ch->videos as $v) {
                        $allVideoIds[] = $v->id;
                    }
                }

                // Get completed video IDs from video_progress table
                $completedVideoIds = VideoProgress::where('user_id', auth()->id())
                    ->whereIn('video_id', $allVideoIds)
                    ->where('is_completed', true)
                    ->pluck('video_id')
                    ->toArray();

                // Check if course is completed
                $isCompleted = Completion::where('user_id', auth()->id())
                    ->where('completable_type', Course::class)
                    ->where('completable_id', $id)
                    ->exists();
            }

            $chapters = $courseModel->chapters()
                ->with('videos')
                ->orderBy('order')
                ->orderBy('id')
                ->get()
                ->map(function ($ch) use ($completedVideoIds) {
                    // Get all video IDs in this chapter
                    $videoIds = $ch->videos->pluck('id')->toArray();

                    // Mark which videos are completed
                    $videos = $ch->videos->map(function ($v) use ($completedVideoIds) {
                        return [
                            'id' => $v->id,
                            'title' => $v->title,
                            'video_url' => $v->video_url,
                            'thumbnail_url' => $v->thumbnail_url,
                            'duration' => $v->duration,
                            'is_completed' => in_array($v->id, $completedVideoIds),
                        ];
                    })->toArray();

                    // Check if all videos in chapter are completed
                    $completedVideosInChapter = count(array_filter($videos, fn ($v) => $v['is_completed']));
                    $totalVideosInChapter = count($videos);
                    $allVideosCompleted = $totalVideosInChapter > 0 && $completedVideosInChapter >= $totalVideosInChapter;

                    return [
                        'id' => $ch->id,
                        'title' => $ch->title,
                        'lessons' => $ch->videos->count(),
                        'duration' => $ch->duration ?? '0h',
                        'video_url' => $ch->video_url,
                        'thumbnail_url' => $ch->thumbnail_url,
                        'description' => $ch->description,
                        'videos' => $videos,
                        'is_completed' => $allVideosCompleted,
                        'completed_videos' => $completedVideosInChapter,
                        'total_videos' => $totalVideosInChapter,
                    ];
                })
                ->toArray();
        }

        // Get reviews with pagination (server calculates average)
        $reviewsQuery = CourseRating::where('course_id', $id)
            ->with('user:id,name,profile_photo')
            ->orderBy('created_at', 'desc');

        $reviews = $reviewsQuery->paginate(5);

        // Get user's own rating if logged in
        $userRating = null;
        if (auth()->check()) {
            $userRating = CourseRating::where('user_id', auth()->id())
                ->where('course_id', $id)
                ->first();
        }

        $isEnrolled = auth()->check() && $this->isUserEnrolled(auth()->id(), 'course', $course['id']);

        // Get actual enrollment count from database
        $enrolledCount = Enrollment::where('purchasable_type', Course::class)
            ->where('purchasable_id', $id)
            ->count();

        return view('pages.detail-kursus', [
            'course' => $course,
            'chapters' => $chapters,
            'photos' => $photos,
            'resources' => $resources,
            'isEnrolled' => $isEnrolled,
            'reviews' => $reviews,
            'userRating' => $userRating,
            'enrolledCount' => $enrolledCount,
            'isCompleted' => $isCompleted,
        ]);
    }

    public function kursusSaya(): View
    {
        $userId = auth()->id();
        $myCourses = $this->catalog->userEnrolledCourses($userId);
        $userStats = $this->catalog->userStats($userId);
        $allCourses = $this->catalog->courses();

        // Courses not yet enrolled
        $enrolledIds = array_column($myCourses, 'id');
        $otherCourses = array_values(array_filter($allCourses, fn ($c) => ! in_array($c['id'], $enrolledIds)));

        // Completed courses (marked complete in DB or progress = 100)
        $completedCourses = array_values(array_filter($myCourses, fn ($c) => ($c['is_completed'] ?? false) || ($c['progress'] ?? 0) >= 100));

        return view('pages.kursus-saya', [
            'myCourses' => $myCourses,
            'completedCourses' => $completedCourses,
            'otherCourses' => $otherCourses,
            'userStats' => $userStats,
        ]);
    }

    public function bootcampsSaya(): View
    {
        $userId = auth()->id();

        return view('pages.bootcamps-saya', [
            'myBootcamps' => $this->catalog->userEnrolledBootcamps($userId),
            'userStats' => $this->catalog->userStats($userId),
        ]);
    }

    public function onlineBootcamp(): View
    {
        $bootcamps = $this->catalog->bootcamps()['online'];

        // Add enrolled count to each bootcamp
        foreach ($bootcamps as &$bootcamp) {
            $bootcamp['enrolled_count'] = Enrollment::where('purchasable_type', Bootcamp::class)
                ->where('purchasable_id', $bootcamp['id'])
                ->count();
        }

        return view('pages.online-bootcamp', [
            'bootcamps' => $bootcamps,
        ]);
    }

    public function detailOnlineBootcamp(int $id): View
    {
        $bootcamp = $this->catalog->onlineBootcamp($id) ?? $this->catalog->bootcamps()['online'][0];
        $isEnrolled = auth()->check() && $this->isUserEnrolled(auth()->id(), 'online', $bootcamp['id']);

        // Get actual enrollment count from database
        $enrolledCount = Enrollment::where('purchasable_type', Bootcamp::class)
            ->where('purchasable_id', $bootcamp['id'])
            ->count();

        // Get sessions with attendance status
        $sessions = $this->catalog->onlineSessions($bootcamp['id']);
        if (auth()->check() && ! empty($sessions)) {
            $sessionIds = array_column($sessions, 'id');
            $attendedSessionIds = SessionProgress::where('user_id', auth()->id())
                ->whereIn('bootcamp_session_id', $sessionIds)
                ->whereNotNull('clicked_at')
                ->pluck('bootcamp_session_id')
                ->toArray();

            // Add has_attended flag to each session
            foreach ($sessions as &$session) {
                $session['has_attended'] = in_array($session['id'] ?? 0, $attendedSessionIds);
            }
        }

        return view('pages.detail-online-bootcamp', [
            'bootcamp' => $bootcamp,
            'sessions' => $sessions,
            'isEnrolled' => $isEnrolled,
            'enrolledCount' => $enrolledCount,
        ]);
    }

    public function offlineBootcamp(): View
    {
        return view('pages.offline-bootcamp', [
            'bootcamps' => $this->catalog->bootcamps()['offline'],
        ]);
    }

    public function detailOfflineBootcamp(int $id): View
    {
        $bootcamp = $this->catalog->offlineBootcamp($id) ?? $this->catalog->bootcamps()['offline'][0];
        $isEnrolled = auth()->check() && $this->isUserEnrolled(auth()->id(), 'offline', $bootcamp['id']);

        // Get actual enrollment count from database
        $enrolledCount = Enrollment::where('purchasable_type', Bootcamp::class)
            ->where('purchasable_id', $bootcamp['id'])
            ->count();

        return view('pages.detail-offline-bootcamp', [
            'bootcamp' => $bootcamp,
            'features' => $this->catalog->offlineFeatures(),
            'isEnrolled' => $isEnrolled,
            'enrolledCount' => $enrolledCount,
        ]);
    }

    public function mentor(): View
    {
        return view('pages.mentor', [
            'mentors' => $this->catalog->mentors(),
            'categories' => $this->catalog->mentorCategories(),
        ]);
    }

    public function profilMentor(int $id): View
    {
        $mentor = $this->catalog->mentor($id) ?? $this->catalog->mentors()[0];

        return view('pages.profil-mentor', [
            'mentor' => $mentor,
        ]);
    }

    public function event(): View
    {
        return view('pages.event', [
            'events' => $this->catalog->events(),
        ]);
    }

    public function detailEvent(int $id): View
    {
        $event = $this->catalog->event($id);

        // Check if user is registered
        $isRegistered = false;
        if (auth()->check()) {
            $isRegistered = EventRegistration::where('user_id', auth()->id())
                ->where('event_id', $id)
                ->exists();
        }

        return view('pages.detail-event', [
            'event' => $event,
            'isRegistered' => $isRegistered,
        ]);
    }

    public function registerEvent(int $id): RedirectResponse
    {
        $event = Event::find($id);

        if (! $event) {
            return back()->with('error', 'Event tidak ditemukan.');
        }

        // Register user (check if already registered)
        $user = auth()->user();
        $existingReg = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if (! $existingReg) {
            // Generate unique ticket code
            $ticketCode = 'EVT-'.strtoupper(uniqid()).'-'.date('Ymd');

            EventRegistration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => 'registered',
                'ticket_code' => $ticketCode,
                'registered_at' => now(),
            ]);

            // Update registered count
            $event->increment('registered_count');
        }

        return back()->with('success', 'Berhasil mendaftar event!');
    }

    public function kalender(Request $request): View
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $userId = auth()->id();

        return view('pages.kalender', [
            // All events and bootcamps for client-side filtering
            'allCalendarEvents' => $this->catalog->calendarEvents($year, $month),
            // User's enrolled course IDs and bootcamp IDs for filtering
            'userEnrolledCourses' => $this->catalog->userEnrolledCourses($userId),
            'userEnrolledBootcamps' => $this->catalog->userEnrolledBootcamps($userId),
            'userRegisteredEvents' => $this->catalog->userRegisteredEvents($userId),
            'currentYear' => (int) $year,
            'currentMonth' => (int) $month,
        ]);
    }

    public function pembayaran(?int $id = null): View
    {
        // Pembayaran can be triggered from course, bootcamp, or mentor.
        // We resolve a "display item" from whichever catalog matches.
        $item = null;
        if ($course = $this->catalog->course((int) $id)) {
            $item = $course + ['kind' => 'course'];
        } elseif ($online = $this->catalog->onlineBootcamp((int) $id)) {
            $item = $online + ['kind' => 'online'];
        } elseif ($offline = $this->catalog->offlineBootcamp((int) $id)) {
            $item = $offline + ['kind' => 'offline'];
        } elseif ($mentor = $this->catalog->mentor((int) $id)) {
            $item = $mentor + ['kind' => 'mentor'];
        }

        if (! $item) {
            $course = $this->catalog->courses()[0];
            $item = $course + ['kind' => 'course'];
        }

        // Check if already enrolled
        $isEnrolled = false;
        if (auth()->check() && isset($item['id'])) {
            $isEnrolled = $this->isUserEnrolled(auth()->id(), $item['kind'], $item['id']);
        }

        return view('pages.pembayaran', [
            'item' => $item,
            'isEnrolled' => $isEnrolled,
        ]);
    }

    /**
     * Process mock payment and auto-enroll the user
     * (This is a mock payment - no actual payment is processed)
     */
    public function processPayment(Request $request): RedirectResponse
    {
        $request->validate([
            'item_id' => ['required', 'integer'],
            'item_kind' => ['required', 'string', 'in:course,online,offline'],
        ]);

        $user = auth()->user();
        $itemId = $request->input('item_id');
        $itemKind = $request->input('item_kind');

        // Determine the purchasable type
        $purchasableType = match ($itemKind) {
            'course' => Course::class,
            'online', 'offline' => Bootcamp::class,
            default => null,
        };

        if (! $purchasableType) {
            return redirect()->back()->with('error', 'Jenis item tidak valid.');
        }

        // Check if already enrolled
        if ($this->isUserEnrolled($user->id, $itemKind, $itemId)) {
            return redirect()->to($this->getEnrollmentRedirectUrl($itemKind, $itemId))
                ->with('info', 'Kamu sudah terdaftar di item ini.');
        }

        // Get item name for notification
        $itemName = $this->getItemName($itemKind, $itemId);

        // Create enrollment (mock payment - always successful)
        Enrollment::create([
            'user_id' => $user->id,
            'purchasable_type' => $purchasableType,
            'purchasable_id' => $itemId,
            'status' => 'active',
        ]);

        // Log activity
        UserActivityLog::create([
            'user_id' => $user->id,
            'action' => 'enrolled',
            'loggable_type' => $purchasableType,
            'loggable_id' => $itemId,
        ]);

        // Send notification
        app(NotificationService::class)->enrolled($user->id, $itemName, $itemKind, $itemId);

        return redirect()->to($this->getEnrollmentRedirectUrl($itemKind, $itemId))
            ->with('success', "Berhasil terdaftar di {$itemName}! Selamat belajar 🎉");
    }

    /**
     * Check if user is already enrolled in an item
     */
    private function isUserEnrolled(int $userId, string $kind, int $itemId): bool
    {
        $purchasableType = match ($kind) {
            'course' => Course::class,
            'online', 'offline' => Bootcamp::class,
            default => null,
        };

        if (! $purchasableType) {
            return false;
        }

        return Enrollment::where('user_id', $userId)
            ->where('purchasable_type', $purchasableType)
            ->where('purchasable_id', $itemId)
            ->exists();
    }

    /**
     * Get the redirect URL after successful enrollment
     */
    private function getEnrollmentRedirectUrl(string $kind, int $itemId): string
    {
        return match ($kind) {
            'course' => route('detail-kursus', ['id' => $itemId]),
            'online' => route('detail-online-bootcamp', ['id' => $itemId]),
            'offline' => route('detail-offline-bootcamp', ['id' => $itemId]),
            default => route('dashboard'),
        };
    }

    /**
     * Get item name for success message
     */
    private function getItemName(string $kind, int $itemId): string
    {
        return match ($kind) {
            'course' => ($course = Course::find($itemId)) ? $course->title : 'kursus',
            'online' => ($bootcamp = Bootcamp::find($itemId)) ? $bootcamp->title : 'bootcamp',
            'offline' => ($bootcamp = Bootcamp::find($itemId)) ? $bootcamp->title : 'bootcamp',
            default => 'item',
        };
    }
}
