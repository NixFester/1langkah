<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserXpTransaction;
use App\Models\XpReward;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class XpService
{
    /**
     * Default XP values for each action
     */
    private const DEFAULT_XP_VALUES = [
        // Enrollment
        'enrolled_course' => 50,
        'enrolled_bootcamp' => 75,

        // Course progress
        'video_watched' => 10,
        'chapter_completed' => 25,

        // Quiz
        'quiz_passed' => 50,
        'quiz_failed' => 10,

        // Bootcamp
        'session_clicked' => 15,
        'attendance_scanned' => 30,

        // Forum
        'forum_post_created' => 10,
        'forum_reply_created' => 5,
        'forum_post_upvoted' => 3,
        'forum_reply_upvoted' => 3,

        // Reviews
        'review_submitted' => 15,

        // Events
        'event_registered' => 10,
        'event_attended' => 20,

        // Achievement
        'achievement_bonus' => 0, // XP reward is set in achievement.xp_reward
    ];

    /**
     * Award XP to a user
     */
    public function awardXp(User $user, string $action, string $sourceType, int $sourceId): ?UserXpTransaction
    {
        // Check for duplicate XP
        if (UserXpTransaction::alreadyAwarded($sourceType, $sourceId)) {
            return null;
        }

        $xpAmount = $this->getXpForAction($action);

        return $this->awardXpDirectly($user, $action, $sourceType, $sourceId, $xpAmount);
    }

    /**
     * Award XP directly with a specific amount without triggering achievement checks
     * (used by AchievementService for achievement bonuses)
     */
    public function awardXpDirectly(User $user, string $action, string $sourceType, int $sourceId, int $xpAmount): ?UserXpTransaction
    {
        if (UserXpTransaction::alreadyAwarded($sourceType, $sourceId)) {
            return null;
        }

        if ($xpAmount <= 0) {
            return null;
        }

        $transaction = UserXpTransaction::create([
            'user_id' => $user->id,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
            'action' => $action,
            'xp_amount' => $xpAmount,
        ]);

        // Update user's stored XP (without re-checking achievements to avoid infinite loop)
        $this->addXpDirectly($user, $xpAmount);

        return $transaction;
    }

    /**
     * Add XP directly without triggering achievement checks
     */
    private function addXpDirectly(User $user, int $additionalXp): void
    {
        $newXp = $user->xp + $additionalXp;
        $newLevel = $this->calculateLevel($newXp);

        $user->update([
            'xp' => $newXp,
            'level' => $newLevel,
        ]);
    }

    /**
     * Add XP directly to a user ID without triggering achievement checks
     */
    public function addXpToUserId(int $userId, int $xpAmount): void
    {
        $user = User::find($userId);
        if ($user) {
            $this->addXpDirectly($user, $xpAmount);
        }
    }

    /**
     * Award XP to a specific user ID (for cases where we don't have the model)
     */
    public function awardXpToUserId(int $userId, string $action, string $sourceType, int $sourceId): ?UserXpTransaction
    {
        $user = User::find($userId);
        if (! $user) {
            return null;
        }

        return $this->awardXp($user, $action, $sourceType, $sourceId);
    }

    /**
     * Get XP amount for an action
     */
    public function getXpForAction(string $action): int
    {
        // Try to get from database first
        $reward = XpReward::where('action', $action)->first();
        if ($reward) {
            return $reward->xp_amount;
        }

        // Fall back to default values
        return self::DEFAULT_XP_VALUES[$action] ?? 0;
    }

    /**
     * Update user's stored XP and level
     */
    public function updateUserXp(User $user, int $additionalXp): void
    {
        $newXp = $user->xp + $additionalXp;
        $newLevel = $this->calculateLevel($newXp);
        $oldLevel = $user->level;

        $user->update([
            'xp' => $newXp,
            'level' => $newLevel,
        ]);

        // Check for achievements after XP update
        $this->checkXpAchievements($user, $newXp, $newLevel, $oldLevel);
    }

    /**
     * Check and award XP/Level related achievements
     */
    public function checkXpAchievements(User $user, int $newXp, int $newLevel, int $oldLevel): void
    {
        // Check XP-based achievements via AchievementService
        $achievementService = app(AchievementService::class);

        // Check XP milestones
        $achievementService->checkAndAward($user, AchievementService::TRIGGER_TOTAL_XP);

        // Check level milestones
        if ($newLevel > $oldLevel) {
            $achievementService->checkAndAward($user, AchievementService::TRIGGER_LEVEL_REACHED);
        }
    }

    /**
     * Award an achievement to a user
     */
    private function awardAchievement(User $user, Achievement $achievement): void
    {
        // Check if already has achievement
        $alreadyHas = UserAchievement::where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->exists();

        if ($alreadyHas) {
            return;
        }

        // Award the achievement
        UserAchievement::create([
            'user_id' => $user->id,
            'achievement_id' => $achievement->id,
            'earned_at' => now(),
            'is_notified' => false,
        ]);

        // Award XP bonus if specified
        if ($achievement->xp_reward && $achievement->xp_reward > 0) {
            // Use a unique source to prevent duplicate XP
            $this->awardXp(
                $user,
                'achievement_bonus',
                Achievement::class,
                $achievement->id
            );
        }
    }

    /**
     * Get user's current XP
     */
    public function getUserXp(int $userId): int
    {
        return User::find($userId)?->xp ?? 0;
    }

    /**
     * Get user's current level
     */
    public function getUserLevel(int $userId): int
    {
        return User::find($userId)?->level ?? 1;
    }

    /**
     * Calculate level from XP using triangular numbers
     * Level N requires 100 * N * (N-1) / 2 XP
     */
    public function calculateLevel(int $xp): int
    {
        if ($xp <= 0) {
            return 1;
        }

        // Solve: 100 * N * (N-1) / 2 <= xp
        // N^2 - N - (2 * xp / 100) <= 0
        // Using quadratic formula: N = (1 + sqrt(1 + 8*xp/100)) / 2
        $n = (1 + sqrt(1 + 8 * $xp / 100)) / 2;

        return (int) floor($n);
    }

    /**
     * Get XP required for a specific level
     */
    public function getXpForLevel(int $level): int
    {
        if ($level <= 1) {
            return 0;
        }

        // Level N requires: 100 * N * (N-1) / 2
        return (int) (100 * $level * ($level - 1) / 2);
    }

    /**
     * Get XP needed to reach next level
     */
    public function getXpToNextLevel(int $userId): array
    {
        $user = User::find($userId);
        if (! $user) {
            return ['current' => 0, 'required' => 100, 'progress' => 0];
        }

        $currentXp = $user->xp;
        $currentLevel = $user->level;
        $nextLevelXp = $this->getXpForLevel($currentLevel + 1);
        $currentLevelXp = $this->getXpForLevel($currentLevel);

        $xpInLevel = $currentXp - $currentLevelXp;
        $xpNeeded = $nextLevelXp - $currentLevelXp;
        $progress = $xpNeeded > 0 ? round(($xpInLevel / $xpNeeded) * 100, 1) : 100;

        return [
            'current' => $currentXp,
            'current_level' => $currentLevel,
            'next_level' => $currentLevel + 1,
            'xp_in_level' => $xpInLevel,
            'xp_needed' => $xpNeeded,
            'xp_to_next' => $nextLevelXp - $currentXp,
            'progress' => $progress,
        ];
    }

    /**
     * Get user's XP history
     */
    public function getXpHistory(int $userId, int $limit = 20): Collection
    {
        return UserXpTransaction::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn ($t) => [
                'action' => $t->action,
                'xp' => $t->xp_amount,
                'created_at' => $t->created_at,
                'formatted_action' => $this->formatAction($t->action),
            ]);
    }

    /**
     * Get XP breakdown by category
     */
    public function getXpBreakdown(int $userId): array
    {
        $transactions = UserXpTransaction::where('user_id', $userId)->get();

        $breakdown = [
            'enrollment' => 0,
            'course_progress' => 0,
            'quiz' => 0,
            'bootcamp' => 0,
            'forum' => 0,
            'review' => 0,
            'event' => 0,
            'total' => 0,
        ];

        foreach ($transactions as $t) {
            $amount = $t->xp_amount;
            $breakdown['total'] += $amount;

            if (str_starts_with($t->action, 'enrolled_')) {
                $breakdown['enrollment'] += $amount;
            } elseif (in_array($t->action, ['video_watched', 'chapter_completed'])) {
                $breakdown['course_progress'] += $amount;
            } elseif (str_starts_with($t->action, 'quiz_')) {
                $breakdown['quiz'] += $amount;
            } elseif (str_starts_with($t->action, ['session_clicked', 'attendance_scanned'])) {
                $breakdown['bootcamp'] += $amount;
            } elseif (str_starts_with($t->action, 'forum_')) {
                $breakdown['forum'] += $amount;
            } elseif ($t->action === 'review_submitted') {
                $breakdown['review'] += $amount;
            } elseif (str_starts_with($t->action, 'event_')) {
                $breakdown['event'] += $amount;
            }
        }

        return $breakdown;
    }

    /**
     * Get leaderboard (top users by XP)
     */
    public function getLeaderboard(int $limit = 10): Collection
    {
        $cacheKey = 'leaderboard_top_'.$limit;

        return Cache::remember($cacheKey, 300, function () use ($limit) {
            return User::orderByDesc('xp')
                ->limit($limit)
                ->get()
                ->map(fn ($user, $index) => [
                    'rank' => $index + 1,
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->profile_photo,
                    'xp' => $user->xp,
                    'level' => $user->level,
                ]);
        });
    }

    /**
     * Get user's rank in the leaderboard
     */
    public function getUserRank(int $userId): ?array
    {
        $user = User::find($userId);
        if (! $user) {
            return null;
        }

        $rank = User::where('xp', '>', $user->xp)->count() + 1;

        return [
            'rank' => $rank,
            'xp' => $user->xp,
            'level' => $user->level,
        ];
    }

    /**
     * Clear leaderboard cache
     */
    public function clearLeaderboardCache(): void
    {
        Cache::forget('leaderboard_top_10');
        Cache::forget('leaderboard_top_25');
        Cache::forget('leaderboard_top_50');
    }

    /**
     * Format action for display
     */
    private function formatAction(string $action): string
    {
        $labels = [
            'enrolled_course' => 'Enrolled in course',
            'enrolled_bootcamp' => 'Enrolled in bootcamp',
            'video_watched' => 'Watched video',
            'chapter_completed' => 'Completed chapter',
            'quiz_passed' => 'Passed quiz',
            'quiz_failed' => 'Completed quiz',
            'session_clicked' => 'Joined session',
            'attendance_scanned' => 'Scanned attendance',
            'forum_post_created' => 'Created forum post',
            'forum_reply_created' => 'Replied in forum',
            'forum_post_upvoted' => 'Post upvoted',
            'forum_reply_upvoted' => 'Reply upvoted',
            'review_submitted' => 'Submitted review',
            'event_registered' => 'Registered for event',
            'event_attended' => 'Attended event',
        ];

        return $labels[$action] ?? str_replace('_', ' ', ucfirst($action));
    }

    /**
     * Get all available XP actions
     */
    public static function getAvailableActions(): array
    {
        return array_keys(self::DEFAULT_XP_VALUES);
    }


}
