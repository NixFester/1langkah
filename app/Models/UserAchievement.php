<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'achievement_id',
        'earned_at',
        'is_notified',
    ];

    protected function casts(): array
    {
        return [
            'earned_at' => 'datetime',
            'is_notified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    /**
     * Mark achievement as notified
     */
    public function markAsNotified(): void
    {
        $this->update(['is_notified' => true]);
    }

    /**
     * Check if user already has this achievement
     */
    public static function userHasAchievement(int $userId, int $achievementId): bool
    {
        return self::where('user_id', $userId)
            ->where('achievement_id', $achievementId)
            ->exists();
    }

    /**
     * Award an achievement to a user
     */
    public static function awardToUser(int $userId, int $achievementId): ?self
    {
        if (self::userHasAchievement($userId, $achievementId)) {
            return null;
        }

        return self::create([
            'user_id' => $userId,
            'achievement_id' => $achievementId,
            'earned_at' => now(),
            'is_notified' => false,
        ]);
    }
}
