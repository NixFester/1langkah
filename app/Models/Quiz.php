<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'type',
        'chapter_id',
        'passing_score',
        'time_limit_minutes',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'passing_score' => 'integer',
        'time_limit_minutes' => 'integer',
        'order' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check if quiz is a pre-test
     */
    public function isPreTest(): bool
    {
        return $this->type === 'pre_test';
    }

    /**
     * Check if quiz is a post-test
     */
    public function isPostTest(): bool
    {
        return $this->type === 'post_test';
    }

    /**
     * Check if quiz is a chapter quiz
     */
    public function isChapterQuiz(): bool
    {
        return $this->type === 'chapter_quiz';
    }

    /**
     * Get total points for this quiz
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->questions->sum('points');
    }

    /**
     * Get total questions count
     */
    public function getQuestionsCountAttribute(): int
    {
        return $this->questions->count();
    }

    /**
     * Calculate passing score based on points
     */
    public function calculatePassingPoints(): int
    {
        return (int) ceil($this->total_points * ($this->passing_score / 100));
    }
}
