<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiapAssessment extends Model
{
    use HasFactory;

    public const SCORING_FORCED_CHOICE = 'forced_choice';
    public const SCORING_LIKERT = 'likert';

    protected $fillable = [
        'slug',
        'title',
        'badge',
        'subtitle',
        'description',
        'scoring_type',
        'question_count',
        'time_limit_minutes',
        'normal_price',
        'unlock_price',
        'disclaimer',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'question_count' => 'integer',
            'time_limit_minutes' => 'integer',
            'normal_price' => 'integer',
            'unlock_price' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories(): HasMany
    {
        return $this->hasMany(SiapCategory::class)->orderBy('sort_order');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SiapQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(SiapAttempt::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(SiapOrder::class);
    }

    public function isForcedChoice(): bool
    {
        return $this->scoring_type === self::SCORING_FORCED_CHOICE;
    }

    public function isLikert(): bool
    {
        return $this->scoring_type === self::SCORING_LIKERT;
    }
}
