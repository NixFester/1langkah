<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Bootcamp;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function __construct(protected CatalogService $catalog) {}

    /* -----------------------------------------------------------------
     * Public / guest pages
     * ----------------------------------------------------------------- */

    public function landing(): View
    {
        return view('pages.landing', [
            'courses'     => $this->catalog->courses(),
            'mentors'     => array_slice($this->catalog->mentors(), 0, 4),
            'testimonials'=> $this->catalog->testimonials(),
            'bootcamp'    => $this->catalog->onlineBootcamp(101),
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
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Add this check:
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->intended(route('dashboard'));
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
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'   => ['required', 'min:8'],
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->first_name . ' ' . $request->last_name,
            'email'    => $request->email,
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
        return view('pages.pengaturan', [
            'authUser' => auth()->user(),
        ]);
    }

    public function updatePengaturan(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'bio'   => ['nullable', 'string', 'max:500'],
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['min:8'];
            $rules['password_confirmation'] = ['same:password'];
        }

        $request->validate($rules);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->bio   = $request->bio;

        if ($request->filled('password')) {
            $user->password = $request->password; // cast hashes it
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /* -----------------------------------------------------------------
     * Dashboard pages (sidebar + topbar)
     * ----------------------------------------------------------------- */

    public function dashboard(): View
    {
        $userId = auth()->id();
        $userStats = $this->catalog->userStats($userId);
        $myCourses = $userId ? $this->catalog->userEnrolledCourses($userId) : [];

        return view('pages.dashboard', [
            'user'           => $this->catalog->user(),
            'userStats'     => $userStats,
            'activeCourses'  => $myCourses,
            'newCourses'     => [],
            'leaderboard'    => $this->catalog->leaderboard(),
            'activities'     => $this->catalog->activities(),
            'skills'         => $this->catalog->skills(),
            'weeklyHours'    => $this->catalog->weeklyHours(),
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
        $otherCourses = array_values(array_filter($allCourses, fn ($c) => !in_array($c['id'], $enrolledIds)));

        return view('pages.kursus', [
            'courses'     => $allCourses,
            'myCourses'   => $myCourses,
            'otherCourses' => $otherCourses,
            'userStats'   => $userStats,
            'categories' => $this->catalog->categories(),
            'levels'      => $this->catalog->levels(),
        ]);
    }

    public function detailKursus(int $id): View
    {
        $course = $this->catalog->course($id) ?? $this->catalog->courses()[0];

        // Get gallery photos (type = array)
        $photos = collect($course['gallery'] ?? [])->map(fn($url) => ['url' => $url, 'alt' => $course['title']])->toArray();

        return view('pages.detail-kursus', [
            'course'   => $course,
            'chapters' => $this->catalog->chapters($course['id']),
            'photos'   => $photos,
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
        $otherCourses = array_values(array_filter($allCourses, fn ($c) => !in_array($c['id'], $enrolledIds)));

        // Completed courses (marked complete in DB or progress = 100)
        $completedCourses = array_values(array_filter($myCourses, fn ($c) => ($c['is_completed'] ?? false) || ($c['progress'] ?? 0) >= 100));

        return view('pages.kursus-saya', [
            'myCourses'       => $myCourses,
            'completedCourses' => $completedCourses,
            'otherCourses'     => $otherCourses,
            'userStats'        => $userStats,
        ]);
    }

    public function onlineBootcamp(): View
    {
        return view('pages.online-bootcamp', [
            'bootcamps' => $this->catalog->bootcamps()['online'],
        ]);
    }

    public function detailOnlineBootcamp(int $id): View
    {
        $bootcamp = $this->catalog->onlineBootcamp($id) ?? $this->catalog->bootcamps()['online'][0];

        return view('pages.detail-online-bootcamp', [
            'bootcamp' => $bootcamp,
            'sessions' => $this->catalog->onlineSessions($bootcamp['id']),
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

        return view('pages.detail-offline-bootcamp', [
            'bootcamp' => $bootcamp,
            'features' => $this->catalog->offlineFeatures(),
        ]);
    }

    public function mentor(): View
    {
        return view('pages.mentor', [
            'mentors'    => $this->catalog->mentors(),
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

    public function kalender(): View
    {
        return view('pages.kalender', [
            'events' => $this->catalog->calendarEvents(),
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

        if (!$purchasableType) {
            return redirect()->back()->with('error', 'Jenis item tidak valid.');
        }

        // Check if already enrolled
        if ($this->isUserEnrolled($user->id, $itemKind, $itemId)) {
            return redirect()->to($this->getEnrollmentRedirectUrl($itemKind, $itemId))
                ->with('info', 'Kamu sudah terdaftar di item ini.');
        }

        // Create enrollment (mock payment - always successful)
        Enrollment::create([
            'user_id' => $user->id,
            'purchasable_type' => $purchasableType,
            'purchasable_id' => $itemId,
            'status' => 'active',
        ]);

        // Log activity
        $itemName = $this->getItemName($itemKind, $itemId);
        \App\Models\UserActivityLog::create([
            'user_id' => $user->id,
            'action' => 'enrolled',
            'loggable_type' => $purchasableType,
            'loggable_id' => $itemId,
        ]);

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

        if (!$purchasableType) {
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
            'course' => route('detail-kursus', $itemId),
            'online' => route('detail-online-bootcamp', $itemId),
            'offline' => route('detail-offline-bootcamp', $itemId),
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
