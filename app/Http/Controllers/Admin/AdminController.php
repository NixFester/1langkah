<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bootcamp;
use App\Models\Chapter;
use App\Models\ChapterVideo;
use App\Models\Course;
use App\Models\Event;
use App\Models\Option;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Get options for select dropdowns
     */
    private function getOptions(string $category): array
    {
        return Option::getOptionsForSelect($category);
    }

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
        $roles = $this->getOptions('user_role');
        return view('admin.users', compact('users', 'roles'));
    }

    public function createUserForm(): View
    {
        $roles = $this->getOptions('user_role');
        return view('admin.user_form', compact('roles'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|' . Option::getValidationRule('user_role'),
            'profile_photo' => 'nullable|string|max:255',
        ]);

        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function manageUser(User $user): View
    {
        $roles = $this->getOptions('user_role');
        return view('admin.user_form', compact('user', 'roles'));
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|' . Option::getValidationRule('user_role'),
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
        $request->validate(['role' => 'required|' . Option::getValidationRule('user_role')]);
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
        $levels = $this->getOptions('course_level');
        return view('admin.course_form', compact('levels'));
    }

    public function storeCourse(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'mentor_name'       => 'required|string|max:255',
            'mentor_company'    => 'required|string|max:255',
            'category'          => 'required|string|max:100',
            'level'             => 'required|' . Option::getValidationRule('course_level'),
            'price'             => 'required|string|max:50',
            'color'             => 'nullable|string|max:20',
            'badge'             => 'nullable|string|max:50',
            'rating'            => 'nullable|numeric|min:0|max:5',
            'students_count'    => 'nullable|integer|min:0',
            'progress'          => 'nullable|integer|min:0|max:100',
            'mentor_id'         => 'nullable|exists:mentors,id',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
        ]);

        $course = Course::create($data);

        // Create chapters if provided
        if ($request->has('chapters')) {
            $chapters = $request->input('chapters');
            foreach ($chapters as $chapter) {
                if (!empty($chapter['title'])) {
                    $course->chapters()->create([
                        'title' => $chapter['title'],
                        'lessons' => $chapter['lessons'] ?? 1,
                        'duration' => $chapter['duration'] ?? '',
                        'video_url' => $chapter['video_url'] ?? null,
                        'thumbnail_url' => $chapter['thumbnail_url'] ?? null,
                        'description' => $chapter['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.courses.manage', $course)->with('success', 'Kursus berhasil ditambahkan.');
    }

    public function manageCourse(Course $course): View
    {
        $course->load(['chapters.videos', 'chapters.resources', 'resources']);
        $levels = $this->getOptions('course_level');
        return view('admin.course_manage', compact('course', 'levels'));
    }

    public function updateCourse(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'mentor_name'    => 'required|string|max:255',
            'mentor_company' => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'level'          => 'required|' . Option::getValidationRule('course_level'),
            'price'          => 'required|string|max:50',
            'color'          => 'nullable|string|max:20',
            'badge'          => 'nullable|string|max:50',
            'rating'         => 'nullable|numeric|min:0|max:5',
            'students_count' => 'nullable|integer|min:0',
            'progress'       => 'nullable|integer|min:0|max:100',
            'mentor_id'      => 'nullable|exists:mentors,id',
            'description'    => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
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
            'description' => 'nullable|string',
        ]);

        $course->chapters()->create($data);
        return redirect()->route('admin.courses.manage', $course)->with('success', 'Bab berhasil ditambahkan.');
    }

    public function updateChapter(Request $request, Course $course, Chapter $chapter): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'lessons' => 'required|integer|min:1',
            'duration' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $chapter->update($data);
        return redirect()->route('admin.courses.manage', $course)->with('success', 'Bab berhasil diperbarui.');
    }

    public function destroyChapter(Course $course, Chapter $chapter): RedirectResponse
    {
        $chapter->delete();
        return back()->with('success', 'Bab berhasil dihapus.');
    }

    public function storeChapterVideo(Request $request, Course $course, Chapter $chapter): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required|url|max:500',
            'thumbnail_url' => 'nullable|url|max:500',
            'duration' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $data['chapter_id'] = $chapter->id;
        $data['order'] = $chapter->videos()->max('order') + 1;

        ChapterVideo::create($data);
        return back()->with('success', 'Video berhasil ditambahkan.');
    }

    public function destroyChapterVideo(Course $course, Chapter $chapter, ChapterVideo $video): RedirectResponse
    {
        $video->delete();
        return back()->with('success', 'Video berhasil dihapus.');
    }

    public function storeResource(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,zip,video,link,github,file',
            'url' => 'required|url|max:500',
            'file_size' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $data['course_id'] = $course->id;
        $data['order'] = $course->resources()->max('order') + 1;

        Resource::create($data);
        return back()->with('success', 'Resource berhasil ditambahkan.');
    }

    public function destroyResource(Course $course, Resource $resource): RedirectResponse
    {
        $resource->delete();
        return back()->with('success', 'Resource berhasil dihapus.');
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
        $types = $this->getOptions('bootcamp_type');
        return view('admin.bootcamp_form', compact('types'));
    }

    public function storeBootcamp(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'mentor_name'       => 'required|string|max:255',
            'type'              => 'required|' . Option::getValidationRule('bootcamp_type'),
            'price'             => 'required|string|max:50',
            'start_date'        => 'required|string|max:100',
            'location'          => 'nullable|string|max:255',
            'sessions_info'     => 'nullable|string|max:255',
            'color'             => 'nullable|string|max:20',
            'participants'      => 'nullable|integer|min:0',
            'mentor_id'         => 'nullable|exists:mentors,id',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
        ]);

        $bootcamp = Bootcamp::create($data);

        // Create sessions if provided
        if ($request->has('sessions')) {
            $sessions = $request->input('sessions');
            foreach ($sessions as $session) {
                if (!empty($session['topic'])) {
                    $bootcamp->sessions()->create([
                        'date' => $session['date'] ?? '',
                        'topic' => $session['topic'],
                        'time' => $session['time'] ?? '',
                        'meeting_url' => $session['meeting_url'] ?? null,
                        'description' => $session['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.bootcamps.manage', $bootcamp)->with('success', 'Bootcamp berhasil ditambahkan.');
    }

    public function manageBootcamp(Bootcamp $bootcamp): View
    {
        $bootcamp->load('sessions');
        $types = $this->getOptions('bootcamp_type');
        return view('admin.bootcamp_form', compact('bootcamp', 'types'));
    }

    public function updateBootcamp(Request $request, Bootcamp $bootcamp): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'mentor_name' => 'required|string|max:255',
            'type'        => 'required|' . Option::getValidationRule('bootcamp_type'),
            'price'       => 'required|string|max:50',
            'start_date'  => 'required|string|max:100',
            'location'    => 'nullable|string|max:255',
            'sessions_info' => 'nullable|string|max:255',
            'color'       => 'nullable|string|max:20',
            'participants' => 'nullable|integer|min:0',
            'mentor_id' => 'nullable|exists:mentors,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
        ]);

        $bootcamp->update($data);
        return redirect()->route('admin.bootcamps')->with('success', 'Bootcamp berhasil diperbarui.');
    }

    public function storeSession(Request $request, Bootcamp $bootcamp): RedirectResponse
    {
        $data = $request->validate([
            'date' => 'required|string|max:100',
            'topic' => 'required|string|max:255',
            'time' => 'required|string|max:100',
        ]);

        $bootcamp->sessions()->create(array_merge($data, [
            'password' => $bootcamp->isOnline() ? \App\Models\BootcampSession::generatePassword() : null,
        ]));
        return redirect()->route('admin.bootcamps.manage', $bootcamp)->with('success', 'Sesi berhasil ditambahkan.');
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
        $types = $this->getOptions('event_type');
        $statuses = $this->getOptions('event_status');
        return view('admin.event_form', compact('types', 'statuses'));
    }

    public function storeEvent(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'             => 'required|string|max:255',
            'type'              => 'required|' . Option::getValidationRule('event_type'),
            'start_date'        => 'required|date',
            'end_date'          => 'nullable|date|after_or_equal:start_date',
            'status'            => 'required|' . Option::getValidationRule('event_status'),
            'location'          => 'nullable|string|max:255',
            'meeting_url'       => 'nullable|url|max:255',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'timezone'          => 'nullable|string|max:50',
            'max_participants'  => 'nullable|integer|min:1',
            'color'             => 'nullable|string|max:20',
            'banner_url'        => 'nullable|url|max:255',
        ]);

        $data['slug']       = \Illuminate\Support\Str::slug($data['title']) . '-' . time();
        $data['created_by'] = auth()->id();

        Event::create($data);
        return redirect()->route('admin.events')->with('success', 'Event berhasil ditambahkan.');
    }

    public function manageEvent(Event $event): View
    {
        $types = $this->getOptions('event_type');
        $statuses = $this->getOptions('event_status');
        return view('admin.event_form', compact('event', 'types', 'statuses'));
    }

    public function updateEvent(Request $request, Event $event): RedirectResponse
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'type'       => 'required|' . Option::getValidationRule('event_type'),
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'status'     => 'required|' . Option::getValidationRule('event_status'),
            'location'   => 'nullable|string|max:255',
            'meeting_url'=> 'nullable|url|max:255',
            'description'=> 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'timezone'          => 'nullable|string|max:50',
            'max_participants'  => 'nullable|integer|min:1',
            'color'             => 'nullable|string|max:20',
            'banner_url'        => 'nullable|url|max:255',
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