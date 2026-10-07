<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'mentor_name',
        'mentor_company',
        'category',
        'level',
        'badge',
        'rating',
        'students_count',
        'price',
        'progress',
        'color',
        'mentor_id',
        'description',
        'short_description',
        'benefits',
        'curriculum',
        'resources',
        'status',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'students_count' => 'integer',
        'progress' => 'integer',
        'mentor_id' => 'integer',
        'benefits' => 'array',
        'curriculum' => 'array',
        'resources' => 'array',
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

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    public function pictures(): MorphMany
    {
        return $this->morphMany(Picture::class, 'pictureable');
    }

    /** Shortcut: single thumbnail or null. */
    public function thumbnail(): ?Picture
    {
        return $this->pictures()->thumbnail()->first();
    }

    /** Shortcut: ordered gallery images. */
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
        return $this->hasMany(CourseRating::class);
    }

    public function enrollments(): MorphMany
    {
        return $this->morphMany(Enrollment::class, 'purchasable');
    }

    /**
     * Course-level resources (stored in resources table)
     */
    public function courseResources(): HasMany
    {
        return $this->hasMany(Resource::class)->where(function ($query) {
            $query->whereNull('chapter_id')
                ->orWhere('chapter_id', 0);
        })->orderBy('order');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Get average rating from all user ratings
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
     * Get user's rating for this course
     */
    public function userRating(?int $userId): ?int
    {
        if (! $userId) {
            return null;
        }

        return $this->ratings()->where('user_id', $userId)->value('rating');
    }

    /**
     * Check if user is enrolled
     */
    public function isEnrolled(?int $userId): bool
    {
        if (! $userId) {
            return false;
        }

        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    /**
     * Get benefits as array
     */
    public function getBenefitsListAttribute(): array
    {
        return $this->benefits ?? [];
    }

    /**
     * Get curriculum sections
     */
    public function getCurriculumSectionsAttribute(): array
    {
        return $this->curriculum ?? [];
    }

    /**
     * Get resources (paywall protected)
     */
    public function getResourcesAttribute($value): ?array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }

    /**
     * Skills associated with this course
     */
    public function getSkillsAttribute(): array
    {
        // Extract skills from category and title
        $skills = [];

        if ($this->category) {
            $skills[] = $this->category;
        }

        // Add title words as potential skills
        $words = explode(' ', str_replace(['Course', 'Kursus'], '', $this->title));
        foreach ($words as $word) {
            if (strlen($word) > 3) {
                $skills[] = $word;
            }
        }

        return array_unique($skills);
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
     * Get the user who reviewed this course
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope: Only pending courses
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope: Only approved courses
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Only rejected courses
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Scope: Visible courses (approved only for public display)
     */
    public function scopeVisible($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Check if course is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if course is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if course is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    /**
     * Check if course is visible (approved)
     */
    public function isVisible(): bool
    {
        return $this->isApproved();
    }

    /**
     * Approve this course
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
     * Reject this course
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
