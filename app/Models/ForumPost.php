<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_urls',
        'upvotes',
        'downvotes',
        'reply_count',
    ];

    protected $casts = [
        'image_urls' => 'array',
        'upvotes' => 'integer',
        'downvotes' => 'integer',
        'reply_count' => 'integer',
    ];

    /**
     * Get the author of the post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all replies for the post
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class);
    }

    /**
     * Get top-level replies (no parent)
     */
    public function topLevelReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class)->whereNull('parent_id');
    }

    /**
     * Get all votes for the post
     */
    public function votes(): MorphMany
    {
        return $this->morphMany(ForumVote::class, 'votable');
    }

    /**
     * Get the vote score (upvotes - downvotes)
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

    /**
     * Scope for search
     */
    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Increment reply count
     */
    public function incrementReplyCount(): void
    {
        $this->increment('reply_count');
    }

    /**
     * Decrement reply count
     */
    public function decrementReplyCount(): void
    {
        $this->decrement('reply_count');
    }
}
