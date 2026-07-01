<?php

namespace App\Services;

use App\Models\Chapter;
use App\Models\ChapterProgress;
use App\Models\SessionProgress;
use App\Models\AttendanceRecord;
use App\Models\UserSkill;
use App\Models\Course;
use App\Models\Bootcamp;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProgressService
{
    // ── Course Progress ──────────────────────────────────────────────────────

    /**
     * Mark a chapter as watched/clicked
     */
    public function markChapterWatched(int $userId, int $chapterId, int $progressSeconds = 0): array
    {
        $chapter = Chapter::find($chapterId);

        if (!$chapter) {
            return ['success' => false, 'message' => 'Chapter not found'];
        }

        ChapterProgress::updateOrCreate(
            ['user_id' => $userId, 'chapter_id' => $chapterId],
            [
                'watched_at' => now(),
                'progress_seconds' => $progressSeconds,
            ]
        );

        // Log activity
        $this->logActivity($userId, Chapter::class, $chapterId, 'watched');

        return [
            'success' => true,
            'message' => 'Chapter marked as watched',
            'chapter_id' => $chapterId,
        ];
    }

    /**
     * Get user's progress for a course
     */
    public function getCourseProgress(int $userId, int $courseId): array
    {
        $chapters = Chapter::where('course_id', $courseId)->get();
        $totalChapters = $chapters->count();

        if ($totalChapters === 0) {
            return [
                'completed' => 0,
                'total' => 0,
                'percentage' => 0,
                'chapters' => [],
            ];
        }

        $completedChapters = ChapterProgress::where('user_id', $userId)
            ->whereIn('chapter_id', $chapters->pluck('id'))
            ->count();

        $percentage = round(($completedChapters / $totalChapters) * 100, 1);

        $chapterProgress = ChapterProgress::where('user_id', $userId)
            ->whereIn('chapter_id', $chapters->pluck('id'))
            ->get()
            ->keyBy('chapter_id');

        return [
            'completed' => $completedChapters,
            'total' => $totalChapters,
            'percentage' => $percentage,
            'chapters' => $chapters->map(fn($ch) => [
                'id' => $ch->id,
                'title' => $ch->title,
                'watched' => $chapterProgress->has($ch->id),
                'watched_at' => $chapterProgress->get($ch->id)?->watched_at,
                'progress_seconds' => $chapterProgress->get($ch->id)?->progress_seconds,
            ]),
        ];
    }

    /**
     * Check if course is completed
     */
    public function isCourseCompleted(int $userId, int $courseId): bool
    {
        $progress = $this->getCourseProgress($userId, $courseId);
        return $progress['percentage'] >= 100;
    }

    // ── Online Bootcamp Progress ─────────────────────────────────────────────

    /**
     * Mark a session as clicked (meeting link accessed)
     */
    public function markSessionClicked(int $userId, int $sessionId): array
    {
        $sessionProgress = SessionProgress::updateOrCreate(
            ['user_id' => $userId, 'bootcamp_session_id' => $sessionId],
            ['clicked_at' => now()]
        );

        return [
            'success' => true,
            'message' => 'Session marked as accessed',
            'session_id' => $sessionId,
        ];
    }

    /**
     * Mark session as completed
     */
    public function markSessionCompleted(int $userId, int $sessionId): array
    {
        $sessionProgress = SessionProgress::updateOrCreate(
            ['user_id' => $userId, 'bootcamp_session_id' => $sessionId],
            ['completed' => true]
        );

        return [
            'success' => true,
            'message' => 'Session marked as completed',
        ];
    }

    /**
     * Get user's progress for an online bootcamp
     */
    public function getBootcampSessionProgress(int $userId, int $bootcampId): array
    {
        $bootcamp = Bootcamp::with('sessions')->find($bootcampId);

        if (!$bootcamp) {
            return ['success' => false, 'message' => 'Bootcamp not found'];
        }

        $sessions = $bootcamp->sessions;
        $totalSessions = $sessions->count();

        if ($totalSessions === 0) {
            return [
                'completed' => 0,
                'total' => 0,
                'percentage' => 0,
                'sessions' => [],
            ];
        }

        $sessionProgress = SessionProgress::where('user_id', $userId)
            ->whereIn('bootcamp_session_id', $sessions->pluck('id'))
            ->get()
            ->keyBy('bootcamp_session_id');

        $clickedCount = $sessionProgress->filter(fn($p) => $p->clicked_at !== null)->count();
        $completedCount = $sessionProgress->where('completed', true)->count();

        return [
            'clicked' => $clickedCount,
            'completed' => $completedCount,
            'total' => $totalSessions,
            'percentage' => round(($clickedCount / $totalSessions) * 100, 1),
            'sessions' => $sessions->map(fn($s) => [
                'id' => $s->id,
                'topic' => $s->topic,
                'date' => $s->date,
                'time' => $s->time,
                'meeting_url' => $s->meeting_url ?? $s->link ?? null,
                'clicked' => $sessionProgress->has($s->id) && $sessionProgress->get($s->id)->clicked_at !== null,
                'completed' => $sessionProgress->has($s->id) && $sessionProgress->get($s->id)->completed,
                'clicked_at' => $sessionProgress->get($s->id)?->clicked_at,
            ]),
        ];
    }

    // ── Offline Bootcamp Attendance ────────────────────────────────────────

    /**
     * Verify QR code attendance
     */
    public function verifyAttendance(int $userId, int $bootcampId, string $qrCode): array
    {
        return AttendanceRecord::verifyAttendance($userId, $bootcampId, $qrCode);
    }

    /**
     * Get user's attendance for offline bootcamp
     */
    public function getAttendanceRecords(int $userId, int $bootcampId): array
    {
        $records = AttendanceRecord::where('user_id', $userId)
            ->where('bootcamp_id', $bootcampId)
            ->get();

        $total = $records->count();
        $verified = $records->where('verified', true)->count();

        return [
            'total' => $total,
            'verified' => $verified,
            'records' => $records->map(fn($r) => [
                'date' => $r->attendance_date,
                'verified' => $r->verified,
                'scanned_at' => $r->scanned_at,
            ]),
        ];
    }

    /**
     * Generate QR code for attendance
     */
    public function generateAttendanceQrCode(int $bootcampId, string $date): string
    {
        $uniqueCode = md5("bootcamp_{$bootcampId}_date_{$date}_" . now()->timestamp);

        // Create attendance record if not exists
        AttendanceRecord::firstOrCreate(
            [
                'bootcamp_id' => $bootcampId,
                'attendance_date' => $date,
                'qr_code' => $uniqueCode,
            ],
            [
                'verified' => false,
            ]
        );

        return $uniqueCode;
    }

    // ── Skills Tracking ──────────────────────────────────────────────────────

    /**
     * Track skills from completed course
     */
    public function trackCourseSkills(int $userId, int $courseId): array
    {
        $course = Course::find($courseId);

        if (!$course) {
            return ['success' => false, 'message' => 'Course not found'];
        }

        $skills = $course->skills;

        foreach ($skills as $skill) {
            UserSkill::updateOrCreate(
                [
                    'user_id' => $userId,
                    'skill_name' => $skill,
                    'source_type' => 'course',
                    'source_id' => $courseId,
                ],
                [
                    'rating' => $course->average_rating,
                ]
            );
        }

        return [
            'success' => true,
            'skills' => $skills,
        ];
    }

    /**
     * Track skills from completed bootcamp
     */
    public function trackBootcampSkills(int $userId, int $bootcampId): array
    {
        $bootcamp = Bootcamp::find($bootcampId);

        if (!$bootcamp) {
            return ['success' => false, 'message' => 'Bootcamp not found'];
        }

        $skills = $bootcamp->skills;

        foreach ($skills as $skill) {
            UserSkill::updateOrCreate(
                [
                    'user_id' => $userId,
                    'skill_name' => $skill,
                    'source_type' => 'bootcamp',
                    'source_id' => $bootcampId,
                ],
                [
                    'rating' => $bootcamp->average_rating,
                ]
            );
        }

        return [
            'success' => true,
            'skills' => $skills,
        ];
    }

    /**
     * Get user's skill summary
     */
    public function getUserSkillsSummary(int $userId): array
    {
        $skills = UserSkill::where('user_id', $userId)
            ->orderBy('rating', 'desc')
            ->get()
            ->groupBy('skill_name')
            ->map(function ($grouped) {
                return [
                    'name' => $grouped->first()->skill_name,
                    'count' => $grouped->count(),
                    'best_rating' => $grouped->max('rating'),
                    'sources' => $grouped->map(fn($s) => [
                        'type' => $s->source_type,
                        'id' => $s->source_id,
                    ]),
                ];
            })
            ->values()
            ->sortByDesc('best_rating')
            ->toArray();

        return $skills;
    }

    // ── Activity Logging ──────────────────────────────────────────────────────

    /**
     * Log user activity
     */
    public function logActivity(int $userId, string $loggableType, int $loggableId, string $action): void
    {
        \App\Models\UserActivityLog::create([
            'user_id' => $userId,
            'loggable_type' => $loggableType,
            'loggable_id' => $loggableId,
            'action' => $action,
        ]);
    }

    // ── User Dashboard Stats ─────────────────────────────────────────────────

    /**
     * Get dashboard stats for user
     */
    public function getDashboardStats(int $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [];
        }

        $enrolledCourses = $user->enrolledCourses()->count();
        $enrolledBootcamps = $user->enrolledBootcamps()->count();

        $courseProgress = Course::whereHas('enrollments', fn($q) => $q->where('user_id', $userId))
            ->with('chapters')
            ->get()
            ->map(fn($c) => $this->getCourseProgress($userId, $c->id)['percentage'])
            ->avg() ?? 0;

        $totalSkills = UserSkill::where('user_id', $userId)->count();

        return [
            'xp' => $user->xp,
            'streak' => $user->streak,
            'enrolled_courses' => $enrolledCourses,
            'enrolled_bootcamps' => $enrolledBootcamps,
            'avg_course_progress' => round($courseProgress, 1),
            'total_skills' => $totalSkills,
            'career_readiness' => $this->calculateCareerReadiness($userId),
        ];
    }

    /**
     * Calculate career readiness percentage
     */
    private function calculateCareerReadiness(int $userId): int
    {
        // Simple calculation based on skills and progress
        $skillsCount = UserSkill::where('user_id', $userId)->count();
        $coursesCompleted = \App\Models\Completion::where('user_id', $userId)
            ->where('completable_type', Course::class)
            ->count();

        $score = ($skillsCount * 5) + ($coursesCompleted * 15);
        return min(100, $score);
    }
}
