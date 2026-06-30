<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
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
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['min:8'];
            $rules['password_confirmation'] = ['same:password'];
        }

        $request->validate($rules);

        $user->name  = $request->name;
        $user->email = $request->email;

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
            'chapters' => $this->catalog->chapters($course['id']),
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

        return view('pages.pembayaran', [
            'item' => $item,
        ]);
    }
}
