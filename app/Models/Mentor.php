<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'company',
        'price',
        'rating',
        'sessions_count',
        'initials',
        'color',
        'expertise',
        'bio',
        'linkedin_url',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'sessions_count' => 'integer',
        'expertise' => 'array',
    ];

    protected $appends = [
        'formatted_price',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function bootcamps(): HasMany
    {
        return $this->hasMany(Bootcamp::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Get average rating across all courses and bootcamps
     */
    public function getAverageRatingAttribute(): float
    {
        $courseRatings = $this->courses->flatMap->ratings;
        $bootcampRatings = $this->bootcamps->flatMap->ratings;
        $allRatings = $courseRatings->concat($bootcampRatings);

        return $allRatings->count() > 0 ? $allRatings->avg('rating') : ($this->rating ?? 0);
    }

    /**
     * Get total students count
     */
    public function getTotalStudentsAttribute(): int
    {
        $courseStudents = $this->courses->sum('students_count');
        $bootcampParticipants = $this->bootcamps->sum('participants');

        return $courseStudents + $bootcampParticipants;
    }

    /**
     * Get linkedin iframe embed URL
     */
    public function getLinkedinEmbedUrlAttribute(): ?string
    {
        if (!$this->linkedin_url) return null;

        // Convert profile URL to embed URL
        // Example: https://linkedin.com/in/username -> https://linkedin.com/embed/username
        if (str_contains($this->linkedin_url, 'linkedin.com/in/')) {
            return str_replace('linkedin.com/in/', 'linkedin.com/embed/', $this->linkedin_url);
        }

        return $this->linkedin_url;
    }

    /**
     * Check if has LinkedIn
     */
    public function hasLinkedIn(): bool
    {
        return !empty($this->linkedin_url);
    }

    /**
     * Get expertise as formatted list
     */
    public function getExpertiseListAttribute(): array
    {
        return $this->expertise ?? [];
    }

    /**
     * Get neatly formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        $priceRaw = trim(str_replace('/sesi', '', (string)$this->price));
        if (empty($priceRaw) || $priceRaw == '0' || strtolower($priceRaw) === 'gratis') {
            return 'Gratis';
        }

        if (is_numeric($priceRaw)) {
            return 'Rp ' . number_format((float) $priceRaw, 0, ',', '.');
        }

        return (string) $this->price;
    }
}
