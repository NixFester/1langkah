<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'votable_id',
        'votable_type',
        'is_upvote',
    ];

    protected $casts = [
        'is_upvote' => 'boolean',
    ];

    /**
     * Get the user who voted
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent model (post or reply)
     */
    public function votable(): MorphTo
    {
        return $this->morphTo();
    }
}
