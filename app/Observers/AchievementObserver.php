<?php

namespace App\Observers;

use App\Models\Bootcamp;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\AchievementService;

class AchievementObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    /**
     * Handle Enrollment created
     */
    public function created(Enrollment $enrollment): void
    {
        $user = User::find($enrollment->user_id);
        if (! $user) {
            return;
        }

        if ($enrollment->purchasable_type === Course::class) {
            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_COURSE_ENROLLED
            );
            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_COURSE_CATEGORY_ENROLLED
            );
        }

        if ($enrollment->purchasable_type === Bootcamp::class) {
            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_BOOTCAMP_ENROLLED
            );
        }
    }
}
