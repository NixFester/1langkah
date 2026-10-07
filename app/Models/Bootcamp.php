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
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'participants' => 'integer',
        'mentor_id' => 'integer',
        'jadwal_kelas' => 'array',
        'benefits' => 'array',
        'reviewed_at' => 'datetime',
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
        if (! $userId) {
            return null;
        }

        return $this->ratings()->where('user_id', $userId)->value('rating');
    }

    /**
     * Check if enrolled
     */
    public function isEnrolled(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

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
        if (! $userId) {
            return null;
        }

        return $this->attendanceRecords()->where('user_id', $userId)->get();
    }

    /**
     * Get verified attendance count for user
     */
    public function getUserVerifiedAttendanceCount(?int $userId): int
    {
        if (! $userId) {
            return 0;
        }

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
        if (empty($this->price) || $this->price == 0 || strtolower(trim((string) $this->price)) === 'gratis') {
            return __('app.free');
        }

        if (is_numeric($this->price)) {
            return 'Rp '.number_format((float) $this->price, 0, ',', '.');
        }

        return (string) $this->price;
    }

    // ── Moderation Status ─────────────────────────────────────────────────────

    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    /**
     * Get the user who reviewed this bootcamp
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope: Only pending bootcamps
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Only approved bootcamps
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Only rejected bootcamps
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope: Visible bootcamps (approved only for public display)
     */
    public function scopeVisible($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Check if bootcamp is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if bootcamp is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if bootcamp is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if bootcamp is visible (approved)
     */
    public function isVisible(): bool
    {
        return $this->isApproved();
    }

    /**
     * Approve this bootcamp
     */
    public function approve(?int $reviewerId = null, ?string $notes = null): bool
    {
        return $this->update([
            'status' => self::STATUS_APPROVED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Reject this bootcamp
     */
    public function reject(?int $reviewerId = null, ?string $reason = null): bool
    {
        return $this->update([
            'status' => self::STATUS_REJECTED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Submit for review (set to pending)
     */
    public function submitForReview(): bool
    {
        return $this->update([
            'status' => self::STATUS_PENDING,
        ]);
    }
}
