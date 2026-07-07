<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserXpTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source_type',
        'source_id',
        'action',
        'xp_amount',
    ];

    protected $casts = [
        'xp_amount' => 'integer',
    ];

    /**
     * Get the user who earned the XP
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if XP was already awarded for this source
     */
    public static function alreadyAwarded(string $sourceType, int $sourceId): bool
    {
        return static::where('source_type', $sourceType)
            ->where('source_id', $sourceId)
            ->exists();
    }

    /**
     * Get the source model instance
     */
    public function getSourceModelAttribute(): ?Model
    {
        return $sourceType::find($this->source_id);
    }
}
