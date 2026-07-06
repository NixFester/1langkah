<?php

namespace App\Observers;

use App\Models\Bootcamp;
use App\Models\Completion;
use App\Models\Course;
use App\Models\User;
use App\Services\AchievementService;

class CompletionObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    public function created(Completion $completion): void
    {
        $user = User::find($completion->user_id);
        if (! $user) {
            return;
        }

        if ($completion->completable_type === Course::class) {
            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_COURSE_COMPLETED
            );
        }

        if ($completion->completable_type === Bootcamp::class) {
            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_BOOTCAMP_COMPLETED
            );
        }
    }
}
