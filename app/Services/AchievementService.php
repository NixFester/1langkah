<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\Bootcamp;
use App\Models\Completion;
use App\Models\Course;
use App\Models\CourseRating;
use App\Models\Enrollment;
use App\Models\EventRegistration;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\TestAttempt;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserActivityLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AchievementService
{
    /**
     * Trigger types
     */
    public const TRIGGER_COURSE_ENROLLED = 'course_enrolled';

    public const TRIGGER_COURSE_COMPLETED = 'course_completed';

    public const TRIGGER_COURSE_CATEGORY_ENROLLED = 'course_category_enrolled';

    public const TRIGGER_QUIZ_PASSED = 'quiz_passed';

    public const TRIGGER_QUIZ_SCORE_ABOVE = 'quiz_score_above';

    public const TRIGGER_FORUM_POST = 'forum_post';

    public const TRIGGER_FORUM_REPLY = 'forum_reply';

    public const TRIGGER_FORUM_VOTE_RECEIVED = 'forum_vote_received';

    public const TRIGGER_BOOTCAMP_ENROLLED = 'bootcamp_enrolled';

    public const TRIGGER_BOOTCAMP_COMPLETED = 'bootcamp_completed';

    public const TRIGGER_REVIEW_WRITTEN = 'review_written';

    public const TRIGGER_EVENT_REGISTERED = 'event_registered';

    public const TRIGGER_EVENT_ATTENDED = 'event_attended';

    public const TRIGGER_STREAK_DAYS = 'streak_days';

    public const TRIGGER_TOTAL_XP = 'total_xp';

    public const TRIGGER_MULTI_TYPE = 'multi_type';

    public const TRIGGER_LEVEL_REACHED = 'level_reached';

    /**
     * Check and award achievements for a user
     */
    public function checkAndAward(User $user, string $triggerType, array $context = []): Collection
    {
        $newAchievements = collect();

        // Find all active achievements matching this trigger type
        $achievements = Achievement::where('trigger_type', $triggerType)
            ->where('is_active', true)
            ->get();

        // Also check multi_type achievements for any trigger
        $multiTypeAchievements = Achievement::where('trigger_type', 'multi_type')
            ->where('is_active', true)
            ->get();

        // Check single-type achievements
        foreach ($achievements as $achievement) {
            if ($this->awardIfEligible($user, $achievement)) {
                $newAchievements->push($achievement);
            }
        }

        // Check multi-type achievements
        foreach ($multiTypeAchievements as $achievement) {
            if ($this->awardIfEligible($user, $achievement)) {
                $newAchievements->push($achievement);
            }
        }

        return $newAchievements;
    }

    protected function checkLevelReached(User $user, array $conditions): bool
    {
        $required = $conditions['level'] ?? 1;

        return $user->level >= $required;
    }

    /**
     * Check if user is eligible and award achievement
     */
    protected function awardIfEligible(User $user, Achievement $achievement): bool
    {
        // Skip if already has this achievement
        if (UserAchievement::userHasAchievement($user->id, $achievement->id)) {
            return false;
        }

        // Check if conditions are met
        $stats = $this->getUserStats($user, $achievement->trigger_type, $achievement->trigger_conditions ?? []);
        $conditions = $achievement->trigger_conditions ?? [];

        $isEligible = match ($achievement->trigger_type) {
            // Course achievements
            self::TRIGGER_COURSE_ENROLLED => $this->checkCourseEnrollment($user, $conditions),
            self::TRIGGER_COURSE_COMPLETED => $this->checkCourseCompleted($user, $conditions),
            self::TRIGGER_COURSE_CATEGORY_ENROLLED => $this->checkCategoryEnrollment($user, $conditions),

            // Quiz achievements
            self::TRIGGER_QUIZ_PASSED => $this->checkQuizPassed($user, $conditions),
            self::TRIGGER_QUIZ_SCORE_ABOVE => $this->checkQuizScoreAbove($user, $conditions),

            // Forum achievements
            self::TRIGGER_FORUM_POST => $this->checkForumPosts($user, $conditions),
            self::TRIGGER_FORUM_REPLY => $this->checkForumReplies($user, $conditions),
            self::TRIGGER_FORUM_VOTE_RECEIVED => $this->checkVotesReceived($user, $conditions),

            // Bootcamp achievements
            self::TRIGGER_BOOTCAMP_ENROLLED => $this->checkBootcampEnrollment($user, $conditions),
            self::TRIGGER_BOOTCAMP_COMPLETED => $this->checkBootcampCompleted($user, $conditions),

            // Review achievements
            self::TRIGGER_REVIEW_WRITTEN => $this->checkReviewsWritten($user, $conditions),

            // Event achievements
            self::TRIGGER_EVENT_REGISTERED => $this->checkEventRegistered($user, $conditions),
            self::TRIGGER_EVENT_ATTENDED => $this->checkEventAttended($user, $conditions),

            // Other achievements
            self::TRIGGER_STREAK_DAYS => $this->checkStreakDays($user, $conditions),
            self::TRIGGER_TOTAL_XP => $this->checkTotalXP($user, $conditions),
            self::TRIGGER_MULTI_TYPE => $this->checkMultiType($user, $conditions),
            self::TRIGGER_LEVEL_REACHED => $this->checkLevelReached($user, $conditions),

            default => false,
        };

        if ($isEligible) {
            $userAchievement = UserAchievement::awardToUser($user->id, $achievement->id);
            if ($userAchievement) {
                // Award XP
                if ($achievement->xp_reward > 0) {
                    $this->awardXp($user, $achievement->xp_reward);
                }
                // Send notification
                $this->sendNotification($user, $achievement);

                return true;
            }
        }

        return false;
    }

    // ── Achievement Type Checkers ─────────────────────────────────────────────

    protected function checkCourseEnrollment(User $user, array $conditions): bool
    {
        $required = $conditions['enrolled_count'] ?? $conditions['count'] ?? 1;
        $count = Enrollment::where('user_id', $user->id)
            ->where('purchasable_type', Course::class)
            ->count();

        return $count >= $required;
    }

    protected function checkCourseCompleted(User $user, array $conditions): bool
    {
        $required = $conditions['completed_courses'] ?? $conditions['count'] ?? 1;
        $count = Completion::where('user_id', $user->id)
            ->where('completable_type', Course::class)
            ->count();

        return $count >= $required;
    }

    protected function checkCategoryEnrollment(User $user, array $conditions): bool
    {
        $category = $conditions['category'] ?? null;
        $count = $conditions['count'] ?? 2;

        if (! $category) {
            return false;
        }

        $enrolled = Enrollment::where('user_id', $user->id)
            ->where('purchasable_type', Course::class)
            ->with('purchasable')
            ->get();

        $categoryCount = $enrolled->filter(function ($e) use ($category) {
            return $e->purchasable && strtolower($e->purchasable->category) === strtolower($category);
        })->count();

        return $categoryCount >= $count;
    }

    protected function checkQuizPassed(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = TestAttempt::where('user_id', $user->id)
            ->where('passed', true)
            ->count();

        return $count >= $required;
    }

    protected function checkQuizScoreAbove(User $user, array $conditions): bool
    {
        $minScore = $conditions['score'] ?? 90;
        $count = $conditions['count'] ?? 1;
        $highScores = TestAttempt::where('user_id', $user->id)
            ->where('passed', true)
            ->where('score', '>=', $minScore)
            ->count();

        return $highScores >= $count;
    }

    protected function checkForumPosts(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = ForumPost::where('user_id', $user->id)->count();

        return $count >= $required;
    }

    protected function checkForumReplies(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = ForumReply::where('user_id', $user->id)->count();

        return $count >= $required;
    }

    protected function checkVotesReceived(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 10;
        $type = $conditions['type'] ?? 'upvotes';

        $postsSum = ForumPost::where('user_id', $user->id)->sum($type);
        $repliesSum = ForumReply::where('user_id', $user->id)->sum($type);

        return ($postsSum + $repliesSum) >= $required;
    }

    protected function checkBootcampEnrollment(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = Enrollment::where('user_id', $user->id)
            ->where('purchasable_type', Bootcamp::class)
            ->count();

        return $count >= $required;
    }

    protected function checkBootcampCompleted(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = Completion::where('user_id', $user->id)
            ->where('completable_type', Bootcamp::class)
            ->count();

        return $count >= $required;
    }

    protected function checkReviewsWritten(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = CourseRating::where('user_id', $user->id)->count();

        return $count >= $required;
    }

    protected function checkEventRegistered(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = EventRegistration::where('user_id', $user->id)->count();

        return $count >= $required;
    }

    protected function checkEventAttended(User $user, array $conditions): bool
    {
        $required = $conditions['count'] ?? 1;
        $count = EventRegistration::where('user_id', $user->id)
            ->whereNotNull('attended_at')
            ->count();

        return $count >= $required;
    }

    protected function checkStreakDays(User $user, array $conditions): bool
    {
        $required = $conditions['days'] ?? 3;
        $actualStreak = $this->calculateCurrentStreak($user->id);

        return $actualStreak >= $required;
    }

    protected function calculateCurrentStreak(int $userId): int
    {
        $activities = UserActivityLog::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays(60))
            ->select(DB::raw('DATE(created_at) as date'))
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->toArray();

        if (empty($activities)) {
            return 0;
        }

        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        // Streak must include today or yesterday
        if ($activities[0] !== $today && $activities[0] !== $yesterday) {
            return 0;
        }

        $streak = 1;
        for ($i = 1; $i < count($activities); $i++) {
            $expected = date('Y-m-d', strtotime($activities[$i - 1].' -1 day'));
            if ($activities[$i] === $expected) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    protected function checkTotalXP(User $user, array $conditions): bool
    {
        $required = $conditions['xp'] ?? 100;
        $totalXP = $this->calculateUserXP($user->id);

        return $totalXP >= $required;
    }

    protected function calculateUserXP(int $userId): int
    {
        // Use the stored XP value from the user
        $user = User::find($userId);

        return $user ? $user->xp : 0;
    }

    protected function checkMultiType(User $user, array $conditions): bool
    {
        $requirements = $conditions['requirements'] ?? [];

        foreach ($requirements as $type => $threshold) {
            $count = match ($type) {
                'courses_enrolled' => Enrollment::where('user_id', $user->id)
                    ->where('purchasable_type', Course::class)->count(),
                'courses_completed' => Completion::where('user_id', $user->id)
                    ->where('completable_type', Course::class)->count(),
                'bootcamps_completed' => Completion::where('user_id', $user->id)
                    ->where('completable_type', Bootcamp::class)->count(),
                'quizzes_passed' => TestAttempt::where('user_id', $user->id)
                    ->where('passed', true)->count(),
                'forum_posts' => ForumPost::where('user_id', $user->id)->count(),
                'forum_replies' => ForumReply::where('user_id', $user->id)->count(),
                'reviews_written' => CourseRating::where('user_id', $user->id)->count(),
                default => 0,
            };

            if ($count < $threshold) {
                return false;
            }
        }

        return true;
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Get user statistics
     */
    public function getUserStats(User $user, string $triggerType, array $context = []): array
    {
        return [
            'enrolled_count' => Enrollment::where('user_id', $user->id)
                ->where('purchasable_type', Course::class)->count(),
            'completed_courses' => Completion::where('user_id', $user->id)
                ->where('completable_type', Course::class)->count(),
            'passed_quizzes' => TestAttempt::where('user_id', $user->id)
                ->where('passed', true)->count(),
            'forum_posts' => ForumPost::where('user_id', $user->id)->count(),
            'forum_replies' => ForumReply::where('user_id', $user->id)->count(),
            'reviews_written' => CourseRating::where('user_id', $user->id)->count(),
            'streak_days' => $this->calculateCurrentStreak($user->id),
            'total_xp' => $this->calculateUserXP($user->id),
        ];
    }

    /**
     * Award XP to a user
     */
    public function awardXp(User $user, int $amount): void
    {
        UserActivityLog::create([
            'user_id' => $user->id,
            'action' => 'achievement_xp',
            'description' => "Earned {$amount} XP from achievement",
            'loggable_type' => Achievement::class,
            'loggable_id' => 0,
        ]);
    }

    /**
     * Send notification for new achievement
     */
    private function sendNotification(User $user, Achievement $achievement): void
    {
        app(NotificationService::class)->achievementEarned(
            $user->id,
            $achievement->name,
            $achievement->icon ?? '🏆'
        );
    }

    /**
     * Get all available achievements
     */
    public function getAvailableAchievements(): Collection
    {
        return Achievement::active()
            ->orderBy('category')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get user's earned achievements
     */
    public function getUserAchievements(User $user): Collection
    {
        return UserAchievement::where('user_id', $user->id)
            ->with('achievement')
            ->orderBy('earned_at', 'desc')
            ->get();
    }

    /**
     * Get user's unearned achievements
     */
    public function getUnearnedAchievements(User $user): Collection
    {
        $earnedIds = UserAchievement::where('user_id', $user->id)
            ->pluck('achievement_id');

        return Achievement::active()
            ->whereNotIn('id', $earnedIds)
            ->orderBy('category')
            ->get();
    }

    /**
     * Get achievements by category
     */
    public function getByCategory(string $category): Collection
    {
        return Achievement::active()
            ->where('category', $category)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get achievement categories
     */
    public function getCategories(): array
    {
        return [
            'learning' => 'Learning',
            'social' => 'Social',
            'consistency' => 'Consistency',
            'milestone' => 'Milestone',
        ];
    }

    /**
     * Get achievement progress for a user
     */
    public function getProgress(User $user): array
    {
        $achievements = Achievement::active()->get();
        $stats = $this->getUserStats($user, '', []);
        $progress = [];

        foreach ($achievements as $achievement) {
            $earned = UserAchievement::userHasAchievement($user->id, $achievement->id);
            $conditions = $achievement->trigger_conditions ?? [];

            $current = match ($achievement->trigger_type) {
                self::TRIGGER_COURSE_ENROLLED => $stats['enrolled_count'],
                self::TRIGGER_COURSE_COMPLETED => $stats['completed_courses'],
                self::TRIGGER_QUIZ_PASSED => $stats['passed_quizzes'],
                self::TRIGGER_FORUM_POST => $stats['forum_posts'],
                self::TRIGGER_FORUM_REPLY => $stats['forum_replies'],
                self::TRIGGER_REVIEW_WRITTEN => $stats['reviews_written'],
                self::TRIGGER_STREAK_DAYS => $stats['streak_days'],
                self::TRIGGER_TOTAL_XP => $stats['total_xp'],
                default => 0,
            };

            $target = $conditions['count']
                ?? $conditions['days']
                ?? $conditions['xp']
                ?? $conditions['enrolled_count']
                ?? $conditions['completed_courses']
                ?? 1;

            $progress[] = [
                'achievement' => $achievement,
                'earned' => $earned,
                'current' => $current,
                'target' => $target,
                'percentage' => min(100, $target > 0 ? round(($current / $target) * 100) : 0),
            ];
        }

        return $progress;
    }
}
