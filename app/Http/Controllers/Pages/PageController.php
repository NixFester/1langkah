<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            'courses'     => $this->catalog->courses(),
            'mentors'     => array_slice($this->catalog->mentors(), 0, 4),
            'testimonials'=> $this->catalog->testimonials(),
            'bootcamp'    => $this->catalog->onlineBootcamp(101),
        ]);
    }

    public function login(): View
    {
        return view('pages.login');
    }

    public function loginSubmit(Request $request): RedirectResponse
    {
        // Demo only — just redirect to dashboard regardless of input.
        return redirect()->route('dashboard');
    }

    public function signup(): View
    {
        return view('pages.signup');
    }

    public function signupSubmit(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    /* -----------------------------------------------------------------
     * Dashboard pages (sidebar + topbar)
     * ----------------------------------------------------------------- */

    public function dashboard(): View
    {
        $courses = $this->catalog->courses();

        return view('pages.dashboard', [
            'user'           => $this->catalog->user(),
            'activeCourses'  => array_values(array_filter($courses, fn ($c) => $c['progress'] > 0)),
            'newCourses'     => array_values(array_filter($courses, fn ($c) => $c['progress'] === 0)),
            'leaderboard'    => $this->catalog->leaderboard(),
            'activities'     => $this->catalog->activities(),
            'skills'         => $this->catalog->skills(),
            'weeklyHours'    => $this->catalog->weeklyHours(),
        ]);
    }

    public function kursus(): View
    {
        return view('pages.kursus', [
            'courses'    => $this->catalog->courses(),
            'categories' => $this->catalog->categories(),
            'levels'     => $this->catalog->levels(),
        ]);
    }

    public function detailKursus(int $id): View
    {
        $course = $this->catalog->course($id) ?? $this->catalog->courses()[0];

        return view('pages.detail-kursus', [
            'course'   => $course,
            'chapters' => $this->catalog->chapters(),
        ]);
    }

    public function kursusSaya(): View
    {
        $courses = $this->catalog->courses();

        return view('pages.kursus-saya', [
            'myCourses'    => array_values(array_filter($courses, fn ($c) => $c['progress'] > 0)),
            'otherCourses' => array_values(array_filter($courses, fn ($c) => $c['progress'] === 0)),
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
            'sessions' => $this->catalog->onlineSessions(),
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

        return view('pages.pembayaran', [
            'item' => $item,
        ]);
    }
}
