<?php

namespace App\Observers;

use App\Models\ForumReply;
use App\Models\User;
use App\Services\AchievementService;

class ForumReplyObserver
{
    public function __construct(
        protected AchievementService $achievementService
    ) {}

    public function created(ForumReply $reply): void
    {
        $user = User::find($reply->user_id);
        if (! $user) {
            return;
        }

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_FORUM_REPLY
        );

        $this->achievementService->checkAndAward(
            $user,
            AchievementService::TRIGGER_TOTAL_XP
        );
    }
}
