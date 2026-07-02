<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_post_id',
        'user_id',
        'parent_id',
        'content',
        'image_urls',
        'upvotes',
        'downvotes',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
    ];

    /**
     * Get the parent post
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(ForumPost::class, 'forum_post_id');
    }

    /**
     * Get the author of the reply
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reply (for nested replies)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ForumReply::class, 'parent_id');
    }

    /**
     * Get child replies (nested)
     */
    public function children(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'parent_id');
    }

    /**
     * Get all votes for the reply
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'votable');
    }

    /**
     * Get the vote score
     */
    public function getScoreAttribute(): int
    {
        return $this->upvotes - $this->downvotes;
    }

    /**
     * Check if user has voted
     */
    public function getUserVote(?int $userId): ?bool
    {
        if (!$userId) {
            return null;
        }

        $vote = $this->votes()->where('user_id', $userId)->first();
        return $vote?->is_upvote;
    }
}
