<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserActivityLog;
use App\Services\AchievementService;

class UserActivityLogObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    public function created(UserActivityLog $log): void
    {
        if ($log->action === 'login' || $log->action === 'daily_login') {
            $user = User::find($log->user_id);
            if (! $user) {
                return;
            }

            $this->achievementService->checkAndAward(
                $user,
                AchievementService::TRIGGER_STREAK_DAYS
            );
        }
    }
}
