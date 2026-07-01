<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChapterVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'title',
        'video_url',
        'thumbnail_url',
        'duration',
        'order',
        'description',
    ];

    protected $casts = [
        'chapter_id' => 'integer',
        'order' => 'integer',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Get embeddable video URL (YouTube, Vimeo, etc.)
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) {
            return null;
        }

        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        // Return as-is if not recognized
        return $this->video_url;
    }

    /**
     * Check if video is from YouTube
     */
    public function isYoutube(): bool
    {
        return str_contains($this->video_url ?? '', 'youtube.com') || str_contains($this->video_url ?? '', 'youtu.be');
    }

    /**
     * Check if video is from Vimeo
     */
    public function isVimeo(): bool
    {
        return str_contains($this->video_url ?? '', 'vimeo.com');
    }
}
