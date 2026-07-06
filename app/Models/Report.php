<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'description',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public const REASON_SPAM = 'spam';

    public const REASON_HARASSMENT = 'harassment';

    public const REASON_INAPPROPRIATE = 'inappropriate_content';

    public const REASON_MISINFORMATION = 'misinformation';

    public const REASON_COPYRIGHT = 'copyright';

    public const REASON_OTHER = 'other';

    public const STATUS_PENDING = 'pending';

    public const STATUS_REVIEWED = 'reviewed';

    public const STATUS_RESOLVED = 'resolved';

    public const STATUS_DISMISSED = 'dismissed';

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Report reasons available
     */
    public static function getReasonOptions(): array
    {
        return [
            self::REASON_SPAM => 'Spam',
            self::REASON_HARASSMENT => 'Harassment / Bullying',
            self::REASON_INAPPROPRIATE => 'Inappropriate Content',
            self::REASON_MISINFORMATION => 'Misinformation',
            self::REASON_COPYRIGHT => 'Copyright Violation',
            self::REASON_OTHER => 'Other',
        ];
    }

    /**
     * Submit a new report
     */
    public static function submit(
        int $reporterId,
        string $reportableType,
        int $reportableId,
        string $reason,
        ?string $description = null
    ): self {
        return self::create([
            'reporter_id' => $reporterId,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportableId,
            'reason' => $reason,
            'description' => $description,
            'status' => self::STATUS_PENDING,
        ]);
    }

    /**
     * Mark report as reviewed
     */
    public function markAsReviewed(int $reviewerId, string $status, ?string $notes = null): void
    {
        $this->update([
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'status' => $status,
            'admin_notes' => $notes,
        ]);
    }
}
