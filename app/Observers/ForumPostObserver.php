<?php

namespace App\Observers;

use App\Models\ForumPost;
use App\Models\User;
use App\Services\AchievementService;

class ForumPostObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    public function created(ForumPost $post): void
    {
        $user = User::find($post->user_id);
        if (! $user) {
            return;
        }

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_FORUM_POST
        );

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_TOTAL_XP
        );
    }
}
