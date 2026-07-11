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
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'students_count' => 'integer',
        'progress' => 'integer',
        'mentor_id' => 'integer',
        'benefits' => 'array',
        'curriculum' => 'array',
        'resources' => 'array',
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
}
