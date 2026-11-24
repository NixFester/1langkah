<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class SiapAttempt extends Model
{
    use HasFactory;

    public const STATUS_IN_PROGRESS = 'in_progress';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'guest_token',
        'siap_assessment_id',
        'status',
        'participant_name',
        'google_email',
        'birth_place',
        'birth_date',
        'address',
        'whatsapp',
        'answers',
        'scores',
        'dominant_profile',
        'is_full_unlocked',
        'unlocked_at',
        'payment_amount',
        'time_spent_seconds',
        'auto_submitted',
        'email_sent_at',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'answers' => 'array',
            'scores' => 'array',
            'dominant_profile' => 'array',
            'is_full_unlocked' => 'boolean',
            'unlocked_at' => 'datetime',
            'payment_amount' => 'integer',
            'time_spent_seconds' => 'integer',
            'auto_submitted' => 'boolean',
            'email_sent_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(SiapAssessment::class, 'siap_assessment_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SiapOrder::class, 'siap_attempt_id');
    }

    public function certificate(): MorphOne
    {
        return $this->morphOne(Certificate::class, 'certifiable');
    }

    public function getFormattedTtlAttribute(): string
    {
        $parts = [];
        if ($this->birth_place) {
            $parts[] = $this->birth_place;
        }
        if ($this->birth_date) {
            $parts[] = $this->birth_date->format('d/m/Y');
        }

        return $parts ? implode(', ', $parts) : '-';
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED && $this->completed_at !== null;
    }
}
