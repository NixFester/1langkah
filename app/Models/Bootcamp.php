<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Bootcamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'mentor_name',
        'type',
        'participants',
        'start_date',
        'price',
        'color',
        'sessions_info',
        'location',
        'mentor_id',
        'jadwal_kelas',
        'benefits',
        'icon',
    ];

    protected $casts = [
        'participants' => 'integer',
        'mentor_id' => 'integer',
        'jadwal_kelas' => 'array',
        'benefits' => 'array',
    ];

    protected $appends = [
        'formatted_price',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(BootcampSession::class);
    }

    public function pictures(): MorphMany
    {
        return $this->morphMany(Picture::class, 'pictureable');
    }

    public function thumbnail(): ?Picture
    {
        return $this->pictures()->thumbnail()->first();
    }

    public function gallery()
    {
        return $this->pictures()->gallery();
    }

    public function testAttempts(): MorphMany
    {
        return $this->morphMany(TestAttempt::class, 'testable');
    }

    public function completions(): MorphMany
    {
        return $this->morphMany(Completion::class, 'completable');
    }

    public function activityLogs(): MorphMany
    {
        return $this->morphMany(UserActivityLog::class, 'loggable');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(BootcampRating::class);
    }

    public function enrollments(): MorphMany
    {
        return $this->morphMany(Enrollment::class, 'purchasable');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check if offline bootcamp
     */
    public function isOffline(): bool
    {
        return $this->type === 'offline';
    }

    /**
     * Check if online bootcamp
     */
    public function isOnline(): bool
    {
        return $this->type === 'online';
    }

    /**
     * Get average rating
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('rating') ?? $this->rating ?? 0;
    }

    /**
     * Get total rating count
     */
    public function getRatingCountAttribute(): int
    {
        return $this->ratings()->count();
    }

    /**
     * Get user's rating
     */
    public function userRating(?int $userId): ?int
    {
        if (!$userId) return null;
        return $this->ratings()->where('user_id', $userId)->value('rating');
    }

    /**
     * Check if enrolled
     */
    public function isEnrolled(?int $userId): bool
    {
        if (!$userId) return false;
        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    /**
     * Get benefits list
     */
    public function getBenefitsListAttribute(): array
    {
        return $this->attributes['benefits'] ?? [];
    }

    /**
     * Get icon class
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon ?? 'graduation-cap';
    }

    /**
     * Get skills associated with this bootcamp
     */
    public function getSkillsAttribute(): array
    {
        $skills = [];

        // Extract from title
        $words = explode(' ', str_replace(['Bootcamp', 'Kelas'], '', $this->title));
        foreach ($words as $word) {
            if (strlen($word) > 3) {
                $skills[] = $word;
            }
        }

        return array_unique($skills);
    }

    /**
     * Get user's attendance for this bootcamp
     */
    public function getUserAttendance(?int $userId)
    {
        if (!$userId) return null;
        return $this->attendanceRecords()->where('user_id', $userId)->get();
    }

    /**
     * Get verified attendance count for user
     */
    public function getUserVerifiedAttendanceCount(?int $userId): int
    {
        if (!$userId) return 0;
        return $this->attendanceRecords()
            ->where('user_id', $userId)
            ->where('verified', true)
            ->count();
    }

    /**
     * Get neatly formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        if (empty($this->price) || $this->price == 0 || strtolower(trim((string)$this->price)) === 'gratis') {
            return 'Gratis';
        }

        if (is_numeric($this->price)) {
            return 'Rp ' . number_format((float) $this->price, 0, ',', '.');
        }

        return (string) $this->price;
    }
}
