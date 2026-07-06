<?php

namespace App\Observers;

use App\Models\TestAttempt;
use App\Models\User;
use App\Services\AchievementService;

class TestAttemptObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    public function created(TestAttempt $attempt): void
    {
        $user = User::find($attempt->user_id);
        if (! $user) {
            return;
        }

        if ($attempt->passed) {
            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_QUIZ_PASSED
            );

            if ($attempt->score >= 90) {
                $this->achievementService->checkAndAward(
                    $user,
                    AchievementService::TRIGGER_QUIZ_SCORE_ABOVE
                );
            }
        }

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_TOTAL_XP
        );
    }
}
