<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'chapter_id',
        'title',
        'type',
        'url',
        'file_size',
        'order',
        'description',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'chapter_id' => 'integer',
        'order' => 'integer',
        'file_size' => 'integer',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Get icon based on resource type
     */
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'pdf' => 'file-text',
            'zip' => 'archive',
            'video' => 'video',
            'link' => 'link',
            'github' => 'github',
            default => 'file',
        };
    }

    /**
     * Get human readable file size
     */
    public function getFormattedSizeAttribute(): ?string
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Scope: resources without chapter (course-level)
     */
    public function scopeCourseLevel($query)
    {
        return $query->whereNull('chapter_id');
    }

    /**
     * Scope: resources for specific chapter
     */
    public function scopeForChapter($query, $chapterId)
    {
        return $query->where('chapter_id', $chapterId);
    }
}
