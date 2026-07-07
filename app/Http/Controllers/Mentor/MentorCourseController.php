<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\ChapterVideo;
use App\Models\Course;
use App\Models\Mentor as MentorModel;
use App\Models\Option;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorCourseController extends Controller
{
    /**
     * Get mentor profile from authenticated user
     */
    private function getMentorProfile(): ?MentorModel
    {
        $user = auth()->user();

        return MentorModel::where('name', $user->name)->first();
    }

    /**
     * Authorize that the current user owns this course
     */
    private function authorizeOwnership(Course $course): void
    {
        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        if ($course->mentor_name !== $user->name && $course->mentor_id !== $mentorProfile?->id) {
            abort(403, 'Anda bukan pengajar kursus ini.');
        }
    }

    /**
     * Display a listing of the mentor's courses
     */
    public function index(): View
    {
        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        $courses = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->withCount('enrollments')
            ->with('chapters.videos')
            ->latest()
            ->paginate(12);

        return view('mentor.courses.index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Show the form for creating a new course
     */
    public function create(): View
    {
        $levels = Option::getOptionsForSelect('course_level');

        return view('mentor.courses.create', compact('levels'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'mentor_company' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'level' => 'required|'.Option::getValidationRule('course_level'),
            'price' => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|url|max:500',
        ]);

        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        // Set mentor info
        $data['mentor_name'] = $user->name;
        $data['mentor_id'] = $mentorProfile?->id;

        $course = Course::create($data);

        return redirect()->route('mentor.courses.edit', $course)
            ->with('success', 'Kursus berhasil dibuat.');
    }

    /**
     * Show the form for editing a course
     */
    public function edit(Course $course): View
    {
        $this->authorizeOwnership($course);
        $course->load(['chapters.videos', 'chapters.resources', 'courseResources']);
        $levels = Option::getOptionsForSelect('course_level');

        return view('mentor.courses.edit', [
            'course' => $course,
            'levels' => $levels,
        ]);
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwnership($course);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'mentor_company' => 'nullable|string|max:255',
            'category' => 'required|string|max:100',
            'level' => 'required|'.Option::getValidationRule('course_level'),
            'price' => 'required|string|max:50',
            'color' => 'nullable|string|max:20',
            'badge' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
        ]);

        $course->update($data);

        return redirect()->back()->with('success', 'Kursus berhasil diperbarui.');
    }

    /**
     * Remove the specified course
     */
    public function destroy(Course $course): RedirectResponse
    {
        $this->authorizeOwnership($course);
        $course->delete();

        return redirect()->route('mentor.courses.index')
            ->with('success', 'Kursus berhasil dihapus.');
    }

    /* ── Chapter Management ─────────────────────────────────────────── */

    /**
     * Add a chapter to a course
     */
    public function storeChapter(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwnership($course);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'lessons' => 'nullable|integer|min:1',
            'duration' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $course->chapters()->create($data);

        return redirect()->back()->with('success', 'Bab berhasil ditambahkan.');
    }

    /**
     * Update a chapter
     */
    public function updateChapter(Request $request, Course $course, Chapter $chapter): RedirectResponse
    {
        $this->authorizeOwnership($course);

        if ($chapter->course_id !== $course->id) {
            abort(404, 'Bab tidak ditemukan.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'lessons' => 'nullable|integer|min:1',
            'duration' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $chapter->update($data);

        return redirect()->back()->with('success', 'Bab berhasil diperbarui.');
    }

    /**
     * Delete a chapter
     */
    public function destroyChapter(Course $course, Chapter $chapter): RedirectResponse
    {
        $this->authorizeOwnership($course);

        if ($chapter->course_id !== $course->id) {
            abort(404, 'Bab tidak ditemukan.');
        }

        $chapter->delete();

        return redirect()->back()->with('success', 'Bab berhasil dihapus.');
    }

    /* ── Video Management ───────────────────────────────────────────── */

    /**
     * Add a video to a chapter
     */
    public function storeChapterVideo(Request $request, Course $course, Chapter $chapter): RedirectResponse
    {
        $this->authorizeOwnership($course);

        if ($chapter->course_id !== $course->id) {
            abort(404, 'Bab tidak ditemukan.');
        }

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

        return redirect()->back()->with('success', 'Video berhasil ditambahkan.');
    }

    /**
     * Delete a chapter video
     */
    public function destroyChapterVideo(Course $course, Chapter $chapter, ChapterVideo $video): RedirectResponse
    {
        $this->authorizeOwnership($course);

        if ($chapter->course_id !== $course->id || $video->chapter_id !== $chapter->id) {
            abort(404, 'Video tidak ditemukan.');
        }

        $video->delete();

        return redirect()->back()->with('success', 'Video berhasil dihapus.');
    }

    /* ── Resource Management ────────────────────────────────────────── */

    /**
     * Add a resource to a course
     */
    public function storeResource(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeOwnership($course);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,zip,video,link,github,file',
            'url' => 'required|url|max:500',
            'file_size' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $data['course_id'] = $course->id;
        $data['chapter_id'] = null;
        $data['order'] = $course->courseResources()->max('order') + 1;

        Resource::create($data);

        return redirect()->back()->with('success', 'Resource berhasil ditambahkan.');
    }

    /**
     * Delete a resource
     */
    public function destroyResource(Course $course, Resource $resource): RedirectResponse
    {
        $this->authorizeOwnership($course);

        if ($resource->course_id !== $course->id) {
            abort(404, 'Resource tidak ditemukan.');
        }

        $resource->delete();

        return redirect()->back()->with('success', 'Resource berhasil dihapus.');
    }
}
