<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question',
        'explanation',
        'type',
        'points',
        'order',
        'is_required',
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
        'is_required' => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class)->orderBy('order');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Check if question is multiple choice
     */
    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple_choice';
    }

    /**
     * Check if question is true/false
     */
    public function isTrueFalse(): bool
    {
        return $this->type === 'true_false';
    }

    /**
     * Check if question is essay
     */
    public function isEssay(): bool
    {
        return $this->type === 'essay';
    }

    /**
     * Get correct answer(s) for this question
     */
    public function getCorrectAnswers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->answers()->where('is_correct', true)->get();
    }

    /**
     * Check if an answer ID is correct
     */
    public function isAnswerCorrect(int $answerId): bool
    {
        return $this->answers()->where('id', $answerId)->where('is_correct', true)->exists();
    }

    /**
     * Get answer by ID
     */
    public function getAnswer(int $answerId): ?QuizAnswer
    {
        return $this->answers()->find($answerId);
    }
}
