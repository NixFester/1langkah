<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'avatar',
        'notification_preferences',
        'show_profile_publicly',
        'show_progress_publicly',
        'allow_mentor_contact',
        'preferred_language',
        'timezone',
    ];

    protected function casts(): array
    {
        return [
            'notification_preferences' => 'array',
            'show_profile_publicly' => 'boolean',
            'show_progress_publicly' => 'boolean',
            'allow_mentor_contact' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get a specific notification preference
     */
    public function getNotificationPreference(string $key, bool $default = false): bool
    {
        $prefs = $this->notification_preferences ?? [];

        return $prefs[$key] ?? $default;
    }

    /**
     * Update a single notification preference
     */
    public function setNotificationPreference(string $key, bool $value): void
    {
        $prefs = $this->notification_preferences ?? [];
        $prefs[$key] = $value;
        $this->notification_preferences = $prefs;
        $this->save();
    }

    /**
     * Get avatar URL or default
     */
    public function getAvatarUrl(): ?string
    {
        if ($this->avatar) {
            return asset('storage/'.$this->avatar);
        }

        return null;
    }

    /**
     * Create or update settings for a user
     */
    public static function findOrCreateForUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'notification_preferences' => [
                    'email_course_updates' => true,
                    'email_bootcamp_reminders' => true,
                    'email_event_announcements' => true,
                    'email_forum_replies' => true,
                    'email_achievements' => true,
                    'email_weekly_progress' => false,
                    'push_course_updates' => true,
                    'push_bootcamp_reminders' => true,
                    'push_forum_replies' => true,
                ],
                'show_profile_publicly' => true,
                'show_progress_publicly' => true,
                'allow_mentor_contact' => true,
                'preferred_language' => 'id',
                'timezone' => 'Asia/Jakarta',
            ]
        );
    }
}
