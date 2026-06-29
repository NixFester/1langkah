<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bootcamp;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /* ── Dashboard ─────────────────────────────────────────────────── */

    public function dashboard(): View
    {
        $stats = [
            'users'     => User::count(),
            'courses'   => Course::count(),
            'bootcamps' => Bootcamp::count(),
            'revenue'   => 'Rp 0',
        ];

        $recentUsers   = User::latest()->take(5)->get();
        $recentCourses = Course::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCourses'));
    }

    /* ── Users ──────────────────────────────────────────────────────── */

    public function users(): View
    {
        $users = User::latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function createUserForm(): View
    {
        return view('admin.user_form');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:student,mentor,admin',
            'profile_photo' => 'nullable|string|max:255',
        ]);

        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function manageUser(User $user): View
    {
        return view('admin.user_form', compact('user'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:student,mentor,admin',
            'profile_photo' => 'nullable|string|max:255',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $request->validate(['role' => 'required|in:student,mentor,admin']);
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role user berhasil diubah.');
    }

    /* ── Courses ────────────────────────────────────────────────────── */

    public function courses(): View
    {
        $courses = Course::latest()->paginate(15);
        return view('admin.courses', compact('courses'));
    }

    public function createCourseForm(): View
    {
        return view('admin.course_form');
    }

    public function storeCourse(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'mentor_name'    => 'required|string|max:255',
            'mentor_company' => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'level'          => 'required|in:Beginner,Intermediate,Advanced',
            'price'          => 'required|string|max:50',
            'color'          => 'nullable|string|max:20',
        ]);

        Course::create($data);
        return redirect()->route('admin.courses')->with('success', 'Kursus berhasil ditambahkan.');
    }

    public function manageCourse(Course $course): View
    {
        $course->load('chapters');
        return view('admin.course_form', compact('course'));
    }

    public function updateCourse(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'mentor_name'    => 'required|string|max:255',
            'mentor_company' => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'level'          => 'required|in:Beginner,Intermediate,Advanced',
            'price'          => 'required|string|max:50',
            'color'          => 'nullable|string|max:20',
        ]);

        $course->update($data);
        return redirect()->route('admin.courses.manage', $course)->with('success', 'Kursus berhasil diperbarui.');
    }

    public function storeChapter(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'lessons' => 'required|integer|min:1',
            'duration' => 'required|string|max:100',
        ]);

        $course->chapters()->create($data);
        return redirect()->route('admin.courses.manage', $course)->with('success', 'Bab berhasil ditambahkan.');
    }

    public function destroyCourse(Course $course): RedirectResponse
    {
        $course->delete();
        return back()->with('success', 'Kursus berhasil dihapus.');
    }

    /* ── Bootcamps ──────────────────────────────────────────────────── */

    public function bootcamps(): View
    {
        $bootcamps = Bootcamp::latest()->paginate(15);
        return view('admin.bootcamps', compact('bootcamps'));
    }

    public function createBootcampForm(): View
    {
        return view('admin.bootcamp_form');
    }

    public function storeBootcamp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'mentor_name' => 'required|string|max:255',
            'type'        => 'required|in:online,offline',
            'price'       => 'required|string|max:50',
            'start_date'  => 'required|string|max:100',
            'location'    => 'nullable|string|max:255',
            'sessions_info' => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:20',
        ]);

        Bootcamp::create($data);
        return redirect()->route('admin.bootcamps')->with('success', 'Bootcamp berhasil ditambahkan.');
    }

    public function manageBootcamp(Bootcamp $bootcamp): View
    {
        return view('admin.bootcamp_form', compact('bootcamp'));
    }

    public function updateBootcamp(Request $request, Bootcamp $bootcamp): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'mentor_name' => 'required|string|max:255',
            'type'        => 'required|in:online,offline',
            'price'       => 'required|string|max:50',
            'start_date'  => 'required|string|max:100',
            'location'    => 'nullable|string|max:255',
            'sessions_info' => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:20',
        ]);

        $bootcamp->update($data);
        return redirect()->route('admin.bootcamps')->with('success', 'Bootcamp berhasil diperbarui.');
    }

    public function destroyBootcamp(Bootcamp $bootcamp): RedirectResponse
    {
        $bootcamp->delete();
        return back()->with('success', 'Bootcamp berhasil dihapus.');
    }

    /* ── Events ─────────────────────────────────────────────────────── */

    public function events(): View
    {
        $events = Event::latest('start_date')->paginate(15);
        return view('admin.events', compact('events'));
    }

    public function createEventForm(): View
    {
        return view('admin.event_form');
    }

    public function storeEvent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|in:online,offline,hybrid',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|in:draft,upcoming,ongoing,completed,cancelled',
            'location'   => 'nullable|string|max:255',
            'meeting_url'=> 'nullable|url|max:255',
            'description'=> 'nullable|string',
        ]);

        $data['slug']       = \Illuminate\Support\Str::slug($data['title']) . '-' . time();
        $data['created_by'] = auth()->id();

        Event::create($data);
        return redirect()->route('admin.events')->with('success', 'Event berhasil ditambahkan.');
    }

    public function manageEvent(Event $event): View
    {
        return view('admin.event_form', compact('event'));
    }

    public function updateEvent(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|in:online,offline,hybrid',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|in:draft,upcoming,ongoing,completed,cancelled',
            'location'   => 'nullable|string|max:255',
            'meeting_url'=> 'nullable|url|max:255',
            'description'=> 'nullable|string',
        ]);

        $data['slug']       = \Illuminate\Support\Str::slug($data['title']) . '-' . time();
        $data['created_by'] = auth()->id();

        $event->update($data);
        return redirect()->route('admin.events')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroyEvent(Event $event): RedirectResponse
    {
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }
}