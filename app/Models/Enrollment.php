<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'purchasable_type',
        'purchasable_id',
        'status',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check if enrollment is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if related to a course
     */
    public function isCourse(): bool
    {
        return $this->purchasable_type === Course::class;
    }

    /**
     * Check if related to a bootcamp
     */
    public function isBootcamp(): bool
    {
        return $this->purchasable_type === Bootcamp::class;
    }

    /**
     * Get the course if this is a course enrollment
     */
    public function getCourseAttribute(): ?Course
    {
        return $this->purchasable_type === Course::class ? $this->purchasable : null;
    }

    /**
     * Get the bootcamp if this is a bootcamp enrollment
     */
    public function getBootcampAttribute(): ?Bootcamp
    {
        return $this->purchasable_type === Bootcamp::class ? $this->purchasable : null;
    }
}
