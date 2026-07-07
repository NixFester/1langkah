<?php

namespace App\Models;

use App\Services\XpService;
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
        'ticket_code',
        'is_following',
        'followed_at',
        'completed_at',
    ];

    protected $casts = [
        'is_following' => 'boolean',
        'followed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $enrollment): void {
            if ($enrollment->purchasable_type === Bootcamp::class && $enrollment->purchasable_id && empty($enrollment->ticket_code)) {
                $enrollment->ticket_code = self::generateTicketCode();
            }
        });

        static::created(function (self $enrollment): void {
            // Award XP for enrollment
            $action = $enrollment->isCourse() ? 'enrolled_course' : 'enrolled_bootcamp';
            app(XpService::class)->awardXp(
                $enrollment->user,
                $action,
                self::class,
                $enrollment->id
            );
        });
    }

    public static function generateTicketCode(): string
    {
        do {
            $code = strtoupper(substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(8))), 0, 8));
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }

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
