<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MentorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mentor_id',
        'booked_date',
        'booked_time',
        'status',
        'wa_link',
        'notes',
        'admin_notes',
        'completed_at',
    ];

    protected $casts = [
        'booked_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // ── Status Constants ────────────────────────────────────────────────────────

    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    // ── Relationships ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    // ── Accessors & Helpers ────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getWaLinkAttribute(): ?string
    {
        if ($this->attributes['wa_link']) {
            return $this->attributes['wa_link'];
        }

        // Generate from mentor phone
        $phone = $this->mentor?->phone ?? '08123456789';
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);

        return 'https://wa.me/'.$cleanPhone;
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->booked_date->format('d M Y');
    }

    public function getFormattedDateTimeAttribute(): string
    {
        return $this->booked_date->format('d M Y').' at '.$this->booked_time;
    }
}
