<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'start_date',
        'end_date',
        'timezone',
        'type',
        'location',
        'meeting_url',
        'status',
        'max_participants',
        'registered_count',
        'color',
        'banner_url',
        'created_by',
        'mentor_id',
        'is_mentor_created',
        'moderation_status',
        'moderation_notes',
        'moderation_reviewed_by',
        'moderation_reviewed_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'max_participants' => 'integer',
        'registered_count' => 'integer',
        'created_by' => 'integer',
        'mentor_id' => 'integer',
        'is_mentor_created' => 'boolean',
        'moderation_reviewed_at' => 'datetime',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registeredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_registrations');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    // ── Moderation Status ─────────────────────────────────────────────────────

    public const MODERATION_PENDING = 'pending';

    public const MODERATION_APPROVED = 'approved';

    public const MODERATION_REJECTED = 'rejected';

    /**
     * Get the user who reviewed this event
     */
    public function moderationReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderation_reviewed_by');
    }

    /**
     * Scope: Only pending moderation events
     */
    public function scopeModerationPending($query)
    {
        return $query->where('moderation_status', self::MODERATION_PENDING);
    }

    /**
     * Scope: Only approved moderation events
     */
    public function scopeModerationApproved($query)
    {
        return $query->where('moderation_status', self::MODERATION_APPROVED);
    }

    /**
     * Scope: Only rejected moderation events
     */
    public function scopeModerationRejected($query)
    {
        return $query->where('moderation_status', self::MODERATION_REJECTED);
    }

    /**
     * Scope: Visible events (approved moderation + active event status)
     */
    public function scopeVisible($query)
    {
        return $query->where('moderation_status', self::MODERATION_APPROVED)
            ->whereNotIn('status', ['draft', 'cancelled']);
    }

    /**
     * Check if event moderation is pending
     */
    public function isModerationPending(): bool
    {
        return $this->moderation_status === self::MODERATION_PENDING;
    }

    /**
     * Check if event moderation is approved
     */
    public function isModerationApproved(): bool
    {
        return $this->moderation_status === self::MODERATION_APPROVED;
    }

    /**
     * Check if event moderation is rejected
     */
    public function isModerationRejected(): bool
    {
        return $this->moderation_status === self::MODERATION_REJECTED;
    }

    /**
     * Check if event is visible (approved moderation)
     */
    public function isVisible(): bool
    {
        return $this->isModerationApproved();
    }

    /**
     * Approve this event
     */
    public function approve(?int $reviewerId = null, ?string $notes = null): bool
    {
        return $this->update([
            'moderation_status' => self::MODERATION_APPROVED,
            'moderation_reviewed_by' => $reviewerId,
            'moderation_reviewed_at' => now(),
            'moderation_notes' => $notes,
        ]);
    }

    /**
     * Reject this event
     */
    public function reject(?int $reviewerId = null, ?string $reason = null): bool
    {
        return $this->update([
            'moderation_status' => self::MODERATION_REJECTED,
            'moderation_reviewed_by' => $reviewerId,
            'moderation_reviewed_at' => now(),
            'moderation_notes' => $reason,
        ]);
    }

    /**
     * Submit for moderation review
     */
    public function submitForReview(): bool
    {
        return $this->update([
            'moderation_status' => self::MODERATION_PENDING,
        ]);
    }
}
