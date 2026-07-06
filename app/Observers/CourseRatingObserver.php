<?php

namespace App\Observers;

use App\Models\CourseRating;
use App\Models\User;
use App\Services\AchievementService;

class CourseRatingObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    public function created(CourseRating $rating): void
    {
        $user = User::find($rating->user_id);
        if (! $user) {
            return;
        }

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_REVIEW_WRITTEN
        );

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_TOTAL_XP
        );
    }
}
