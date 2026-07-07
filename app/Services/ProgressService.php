<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Bootcamp;
use App\Models\BootcampSession;
use App\Models\Chapter;
use App\Models\ChapterProgress;
use App\Models\ChapterVideo;
use App\Models\Completion;
use App\Models\Course;
use App\Models\SessionProgress;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\UserSkill;
use App\Models\VideoProgress;

class ProgressService
{
    private ?NotificationService $notificationService = null;

    private ?XpService $xpService = null;

    /**
     * Get notification service (lazy loaded)
     */
    private function getNotificationService(): NotificationService
    {
        if ($this->notificationService === null) {
            $this->notificationService = app(NotificationService::class);
        }

        return $this->notificationService;
    }

    /**
     * Get XP service (lazy loaded)
     */
    private function getXpService(): XpService
    {
        if ($this->xpService === null) {
            $this->xpService = app(XpService::class);
        }

        return $this->xpService;
    }

    // ── Course Progress ──────────────────────────────────────────────────────

    /**
     * Mark a video as watched
     */
    public function markChapterWatched(int $userId, int $chapterId, int $progressSeconds = 0, ?int $videoId = null, ?int $courseId = null): array
    {
        $chapter = Chapter::with('videos')->find($chapterId);

        if (! $chapter) {
            return ['success' => false, 'message' => 'Chapter not found'];
        }

        $user = User::find($userId);

        // Get the video ID to track (should be passed from the view)
        if (! $videoId) {
            // If no videoId, get the first video from the chapter
            $firstVideo = $chapter->videos->first();
            if ($firstVideo) {
                $videoId = $firstVideo->id;
            }
        }

        // Get video for notification
        $video = $chapter->videos->firstWhere('id', $videoId);
        $videoTitle = $video ? $video->title : 'Video';
        $courseId = $courseId ?? $chapter->course_id;

        // Check if already completed using VideoProgress table
        $existingProgress = VideoProgress::where('user_id', $userId)
            ->where('video_id', $videoId)
            ->where('is_completed', true)
            ->exists();

        if ($existingProgress) {
            return [
                'success' => true,
                'message' => 'Video already completed',
                'video_id' => $videoId,
                'chapter_completed' => false,
                'course_completed' => false,
            ];
        }

        // Mark the specific video as watched in video_progress table
        VideoProgress::updateOrCreate(
            ['user_id' => $userId, 'video_id' => $videoId],
            [
                'watched_at' => now(),
                'watch_duration' => $progressSeconds,
                'is_completed' => true,
            ]
        );

        // Award XP for video watched (first completion only)
        $this->getXpService()->awardXp(
            $user,
            'video_watched',
            VideoProgress::class,
            $videoId
        );

        // Log activity
        $this->logActivity($userId, ChapterVideo::class, $videoId, 'watched');

        // Send notification for video completed
        if ($courseId) {
            $course = Course::find($courseId);
            if ($course) {
                $this->getNotificationService()->videoCompleted($userId, $videoTitle, $course->title, $courseId);
            }
        }

        // Check if chapter is completed
        $chapterCompleted = $this->checkChapterCompleted($userId, $chapter);

        // Check if course is completed
        $courseCompleted = false;
        if ($courseId) {
            $courseCompleted = $this->checkAndMarkCourseCompleted($userId, $courseId);
        }

        return [
            'success' => true,
            'message' => 'Video marked as watched',
            'chapter_id' => $chapterId,
            'video_id' => $videoId,
            'chapter_completed' => $chapterCompleted,
            'course_completed' => $courseCompleted,
        ];
    }

    /**
     * Check if all videos in a chapter are completed
     */
    private function checkChapterCompleted(int $userId, Chapter $chapter): bool
    {
        $videos = $chapter->videos;
        if ($videos->isEmpty()) {
            return false;
        }

        $videoIds = $videos->pluck('id')->toArray();
        $totalVideos = count($videoIds);

        // Count completed videos for this chapter using VideoProgress table
        $completedCount = VideoProgress::where('user_id', $userId)
            ->whereIn('video_id', $videoIds)
            ->where('is_completed', true)
            ->count();

        // All videos must be completed
        if ($completedCount >= $totalVideos) {
            // Award XP for chapter completed
            $this->getXpService()->awardXp(
                User::find($userId),
                'chapter_completed',
                Chapter::class,
                $chapter->id
            );

            // Send notification for chapter completed
            $course = Course::find($chapter->course_id);
            if ($course) {
                $this->getNotificationService()->chapterCompleted($userId, $chapter->title, $course->title, $course->id);
            }

            return true;
        }

        return false;
    }

    /**
     * Check if all chapters in a course are completed and mark course as completed
     */
    private function checkAndMarkCourseCompleted(int $userId, int $courseId): bool
    {
        $course = Course::with('chapters.videos')->find($courseId);

        if (! $course) {
            return false;
        }

        // Get all video IDs in this course
        $allVideoIds = [];
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->videos as $video) {
                $allVideoIds[] = $video->id;
            }
        }

        if (empty($allVideoIds)) {
            return false;
        }

        // Count completed videos using VideoProgress table
        $completedCount = VideoProgress::where('user_id', $userId)
            ->whereIn('video_id', $allVideoIds)
            ->where('is_completed', true)
            ->count();

        // If all videos are completed, mark the course as completed
        if ($completedCount >= count($allVideoIds)) {
            // Check if already marked as completed
            $alreadyCompleted = Completion::where('user_id', $userId)
                ->where('completable_type', Course::class)
                ->where('completable_id', $courseId)
                ->exists();

            if (! $alreadyCompleted) {
                Completion::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'completable_type' => Course::class,
                        'completable_id' => $courseId,
                    ],
                    [
                        'completed_at' => now(),
                    ]
                );

                // Log completion activity
                $this->logActivity($userId, Course::class, $courseId, 'completed');

                // Send notification for course completed
                $this->getNotificationService()->courseCompleted($userId, $course->title, $courseId);
            }

            return true;
        }

        return false;
    }

    /**
     * Get user's progress for a course
     */
    public function getCourseProgress(int $userId, int $courseId): array
    {
        $course = Course::with('chapters.videos')->find($courseId);

        if (! $course) {
            return [
                'completed' => 0,
                'total' => 0,
                'percentage' => 0,
                'chapters' => [],
            ];
        }

        $allVideoIds = [];
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->videos as $video) {
                $allVideoIds[] = $video->id;
            }
        }

        $totalVideos = count($allVideoIds);

        if ($totalVideos === 0) {
            return [
                'completed' => 0,
                'total' => 0,
                'percentage' => 0,
                'chapters' => [],
            ];
        }

        $completedVideos = ChapterProgress::where('user_id', $userId)
            ->whereIn('chapter_id', $allVideoIds)
            ->where('is_completed', true)
            ->count();

        $percentage = round(($completedVideos / $totalVideos) * 100, 1);

        return [
            'completed' => $completedVideos,
            'total' => $totalVideos,
            'percentage' => $percentage,
            'chapters' => [],
        ];
    }

    /**
     * Check if course is completed
     */
    public function isCourseCompleted(int $userId, int $courseId): bool
    {
        return Completion::where('user_id', $userId)
            ->where('completable_type', Course::class)
            ->where('completable_id', $courseId)
            ->exists();
    }

    // ── Online Bootcamp Progress ─────────────────────────────────────────────

    /**
     * Mark a session as clicked (meeting link accessed)
     */
    public function markSessionClicked(int $userId, int $sessionId): array
    {
        // Check if already clicked
        $existing = SessionProgress::where('user_id', $userId)
            ->where('bootcamp_session_id', $sessionId)
            ->whereNotNull('clicked_at')
            ->exists();

        if ($existing) {
            return [
                'success' => true,
                'message' => 'Session already joined',
                'session_id' => $sessionId,
            ];
        }

        $sessionProgress = SessionProgress::updateOrCreate(
            ['user_id' => $userId, 'bootcamp_session_id' => $sessionId],
            ['clicked_at' => now()]
        );

        // Award XP for session clicked (first click only)
        $user = User::find($userId);
        if ($user) {
            $this->getXpService()->awardXp(
                $user,
                'session_clicked',
                SessionProgress::class,
                $sessionProgress->id
            );
        }

        // Send notification
        $session = BootcampSession::find($sessionId);
        if ($session) {
            $bootcamp = $session->bootcamp;
            if ($bootcamp) {
                $this->getNotificationService()->sessionJoined(
                    $userId,
                    $session->topic,
                    $bootcamp->title,
                    $bootcamp->id
                );
            }
        }

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

        if (! $bootcamp) {
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

        $clickedCount = $sessionProgress->filter(fn ($p) => $p->clicked_at !== null)->count();
        $completedCount = $sessionProgress->where('completed', true)->count();

        return [
            'clicked' => $clickedCount,
            'completed' => $completedCount,
            'total' => $totalSessions,
            'percentage' => round(($clickedCount / $totalSessions) * 100, 1),
            'sessions' => $sessions->map(fn ($s) => [
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
            'records' => $records->map(fn ($r) => [
                'date' => $r->attendance_date,
                'verified' => $r->verified,
                'scanned_at' => $r->scanned_at,
            ]),
        ];
    }

    /**
     * Generate QR code for attendance
     * Optionally accepts userId for generating individual short codes
     */
    public function generateAttendanceQrCode(int $bootcampId, string $date, ?int $userId = null): array
    {
        $uniqueCode = md5("bootcamp_{$bootcampId}_date_{$date}_".now()->timestamp);

        // Generate 4-char short code (no I, O, 0, 1)
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $shortCode = '';
        for ($i = 0; $i < 4; $i++) {
            $shortCode .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $createData = [
            'bootcamp_id' => $bootcampId,
            'attendance_date' => $date,
            'qr_code' => $uniqueCode,
            'short_code' => $shortCode,
            'verified' => false,
        ];

        if ($userId) {
            $createData['user_id'] = $userId;
        }

        // Create attendance record if not exists
        AttendanceRecord::firstOrCreate(
            [
                'bootcamp_id' => $bootcampId,
                'attendance_date' => $date,
                'user_id' => $userId,
            ],
            $createData
        );

        return [
            'qr_code' => $uniqueCode,
            'short_code' => $shortCode,
        ];
    }

    // ── Skills Tracking ──────────────────────────────────────────────────────

    /**
     * Track skills from completed course
     */
    public function trackCourseSkills(int $userId, int $courseId): array
    {
        $course = Course::find($courseId);

        if (! $course) {
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

        if (! $bootcamp) {
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
                    'sources' => $grouped->map(fn ($s) => [
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
        UserActivityLog::create([
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

        if (! $user) {
            return [];
        }

        $enrolledCourses = $user->enrolledCourses()->count();
        $enrolledBootcamps = $user->enrolledBootcamps()->count();

        $courseProgress = Course::whereHas('enrollments', fn ($q) => $q->where('user_id', $userId))
            ->with('chapters')
            ->get()
            ->map(fn ($c) => $this->getCourseProgress($userId, $c->id)['percentage'])
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
        $coursesCompleted = Completion::where('user_id', $userId)
            ->where('completable_type', Course::class)
            ->count();

        $score = ($skillsCount * 5) + ($coursesCompleted * 15);

        return min(100, $score);
    }
}
