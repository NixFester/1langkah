<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\ChapterProgress;
use App\Models\Course;
use App\Models\CourseRating;
use App\Models\Enrollment;
use Illuminate\View\View;

/**
 * Controller untuk Dashboard Mentor
 * Bertugas mengelola kursus sendiri dan melihat progress siswa
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama Mentor
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get mentor profile (guaranteed to exist due to IsMentor middleware)
        $mentorProfile = $user->mentor;

        // Statistik kursus mentor - match by name or mentor_id
        $courseIds = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->pluck('id');

        $stats = [
            'total_courses' => $courseIds->count(),
            'total_students' => Enrollment::whereIn('purchasable_id', $courseIds)
                ->where('purchasable_type', Course::class)
                ->distinct('user_id')
                ->count(),
            'total_enrollments' => Enrollment::whereIn('purchasable_id', $courseIds)
                ->where('purchasable_type', Course::class)
                ->count(),
            'avg_rating' => CourseRating::whereIn('course_id', $courseIds)->avg('rating') ?? 0,
        ];

        // Kursus saya
        $myCourses = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->withCount('enrollments')
            ->latest()
            ->take(5)
            ->get();

        // Siswa terbaru
        $recentStudents = Enrollment::whereIn('purchasable_id', $courseIds)
            ->where('purchasable_type', Course::class)
            ->with(['user', 'purchasable'])
            ->latest()
            ->take(10)
            ->get();

        // Completion rate per kursus
        $completionRates = [];
        foreach ($courseIds as $courseId) {
            $totalEnrollments = Enrollment::where('purchasable_id', $courseId)
                ->where('purchasable_type', Course::class)
                ->count();

            $completedEnrollments = Enrollment::where('purchasable_id', $courseId)
                ->where('purchasable_type', Course::class)
                ->whereNotNull('completed_at')
                ->count();

            $completionRates[$courseId] = $totalEnrollments > 0
                ? round(($completedEnrollments / $totalEnrollments) * 100)
                : 0;
        }

        // Rating terbaru
        $recentRatings = CourseRating::whereIn('course_id', $courseIds)
            ->with(['user', 'course'])
            ->latest()
            ->take(5)
            ->get();

        return view('mentor.dashboard', [
            'stats' => $stats,
            'myCourses' => $myCourses,
            'recentStudents' => $recentStudents,
            'completionRates' => $completionRates,
            'recentRatings' => $recentRatings,
            'mentorProfile' => $mentorProfile,
        ]);
    }

    /**
     * Menampilkan daftar kursus saya
     */
    public function myCourses(): View
    {
        $user = auth()->user();
        $mentorProfile = $user->mentor;

        $courses = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->withCount('enrollments')
            ->with('chapters.videos')
            ->latest()
            ->paginate(12);

        return view('mentor.my-courses', [
            'courses' => $courses,
        ]);
    }

    /**
     * Detail kursus dan progress siswa
     */
    public function courseDetail(Course $course): View
    {
        $user = auth()->user();

        // Verifikasi bahwa mentor ini adalah pengajar kursus
        $mentorProfile = $user->mentor;
        if ($course->mentor_name !== $user->name && $course->mentor_id !== $mentorProfile?->id) {
            abort(403, 'Anda bukan pengajar kursus ini.');
        }

        // Load data
        $course->load(['chapters.videos', 'chapters.resources']);

        // Statistik kursus
        $totalStudents = Enrollment::where('purchasable_id', $course->id)
            ->where('purchasable_type', Course::class)
            ->count();

        $completedStudents = Enrollment::where('purchasable_id', $course->id)
            ->where('purchasable_type', Course::class)
            ->whereNotNull('completed_at')
            ->count();

        $chapterIds = $course->chapters->pluck('id');
        $totalChapters = $chapterIds->count();

        // Calculate average progress: completed chapters / total chapters per student, then average
        $avgProgress = 0;
        if ($totalChapters > 0 && $totalStudents > 0) {
            $totalCompleted = ChapterProgress::whereIn('chapter_id', $chapterIds)
                ->where('is_completed', true)
                ->count();
            // Average progress as a percentage
            $avgProgress = round(($totalCompleted / ($totalStudents * $totalChapters)) * 100, 1);
        }

        // Siswa yang enrollment
        $enrolledStudents = Enrollment::where('purchasable_id', $course->id)
            ->where('purchasable_type', Course::class)
            ->with('user')
            ->get()
            ->map(function ($enrollment) use ($chapterIds, $totalChapters) {
                $completedChapters = ChapterProgress::where('user_id', $enrollment->user_id)
                    ->whereIn('chapter_id', $chapterIds)
                    ->where('is_completed', true)
                    ->count();

                return [
                    'user' => $enrollment->user,
                    'progress' => $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0,
                    'completed_chapters' => $completedChapters,
                    'total_chapters' => $totalChapters,
                    'last_activity' => ChapterProgress::where('user_id', $enrollment->user_id)
                        ->whereIn('chapter_id', $chapterIds)
                        ->latest()
                        ->first()?->updated_at,
                    'enrolled_at' => $enrollment->created_at,
                ];
            });

        // Rating
        $ratings = CourseRating::where('course_id', $course->id)
            ->with('user')
            ->latest()
            ->get();

        $avgRating = $ratings->avg('rating') ?? 0;

        return view('mentor.course-detail', [
            'course' => $course,
            'totalStudents' => $totalStudents,
            'completedStudents' => $completedStudents,
            'avgProgress' => round($avgProgress),
            'enrolledStudents' => $enrolledStudents,
            'ratings' => $ratings,
            'avgRating' => round($avgRating, 1),
        ]);
    }

    /**
     * Menampilkan daftar siswa saya
     */
    public function myStudents(): View
    {
        $user = auth()->user();
        $mentorProfile = $user->mentor;

        $courseIds = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->pluck('id');

        $students = Enrollment::whereIn('purchasable_id', $courseIds)
            ->where('purchasable_type', Course::class)
            ->with(['user', 'purchasable'])
            ->select('user_id')
            ->distinct()
            ->paginate(25);

        // Enrich dengan progress
        $students->getCollection()->transform(function ($enrollment) use ($courseIds) {
            $totalEnrollments = Enrollment::where('user_id', $enrollment->user_id)
                ->whereIn('purchasable_id', $courseIds)
                ->where('purchasable_type', Course::class)
                ->count();

            $completedEnrollments = Enrollment::where('user_id', $enrollment->user_id)
                ->whereIn('purchasable_id', $courseIds)
                ->where('purchasable_type', Course::class)
                ->whereNotNull('completed_at')
                ->count();

            $lastActivity = ChapterProgress::where('user_id', $enrollment->user_id)
                ->latest()
                ->first();

            return [
                'user' => $enrollment->user,
                'total_courses' => $totalEnrollments,
                'completed_courses' => $completedEnrollments,
                'last_activity' => $lastActivity?->updated_at,
            ];
        });

        return view('mentor.students', [
            'students' => $students,
        ]);
    }

    /**
     * Detail siswa
     */
    public function studentDetail(User $student): View
    {
        $user = auth()->user();
        $mentorProfile = $user->mentor;

        // Ambil kursus mentor
        $courseIds = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->pluck('id');

        // Ambil enrollments siswa di kursus mentor
        $enrollments = Enrollment::where('user_id', $student->id)
            ->whereIn('purchasable_id', $courseIds)
            ->where('purchasable_type', Course::class)
            ->with('purchasable')
            ->get();

        // Progress per kursus
        $progressData = $enrollments->map(function ($enrollment) {
            $course = $enrollment->purchasable;
            if (! $course) {
                return null;
            }

            $chapterIds = $course->chapters->pluck('id');
            $completedChapters = ChapterProgress::where('user_id', $enrollment->user_id)
                ->whereIn('chapter_id', $chapterIds)
                ->where('is_completed', true)
                ->count();

            $totalChapters = $chapterIds->count();

            return [
                'course' => $course,
                'enrollment' => $enrollment,
                'completed_chapters' => $completedChapters,
                'total_chapters' => $totalChapters,
                'progress_percent' => $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0,
                'last_activity' => ChapterProgress::where('user_id', $enrollment->user_id)
                    ->whereIn('chapter_id', $chapterIds)
                    ->latest()
                    ->first()?->updated_at,
            ];
        })->filter();

        return view('mentor.student-detail', [
            'student' => $student,
            'progressData' => $progressData,
        ]);
    }

    /**
     * Menampilkan feedback/rating
     */
    public function feedback(): View
    {
        $user = auth()->user();
        $mentorProfile = $user->mentor;

        $courseIds = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->pluck('id');

        $ratings = CourseRating::whereIn('course_id', $courseIds)
            ->with(['user', 'course'])
            ->when(request('course'), function ($query, $courseId) use ($courseIds) {
                if (in_array($courseId, $courseIds->toArray())) {
                    $query->where('course_id', $courseId);
                }
            })
            ->latest()
            ->paginate(25);

        // Statistik rating
        $ratingStats = [
            'avg' => CourseRating::whereIn('course_id', $courseIds)->avg('rating') ?? 0,
            'total' => CourseRating::whereIn('course_id', $courseIds)->count(),
            '5_stars' => CourseRating::whereIn('course_id', $courseIds)->where('rating', 5)->count(),
            '4_stars' => CourseRating::whereIn('course_id', $courseIds)->where('rating', 4)->count(),
            '3_stars' => CourseRating::whereIn('course_id', $courseIds)->where('rating', 3)->count(),
            '2_stars' => CourseRating::whereIn('course_id', $courseIds)->where('rating', 2)->count(),
            '1_star' => CourseRating::whereIn('course_id', $courseIds)->where('rating', 1)->count(),
        ];

        return view('mentor.feedback', [
            'ratings' => $ratings,
            'ratingStats' => $ratingStats,
        ]);
    }
}
