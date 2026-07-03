<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoProgress extends Model
{
    use HasFactory;

    protected $table = 'video_progress';

    protected $fillable = [
        'user_id',
        'video_id',
        'is_completed',
        'watch_duration',
        'last_position',
        'watched_at',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'watch_duration' => 'integer',
        'last_position' => 'integer',
        'watched_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(ChapterVideo::class, 'video_id');
    }
}
