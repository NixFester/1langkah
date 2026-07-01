<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'lessons',
        'duration',
        'video_url',
        'thumbnail_url',
        'description',
        'order',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'lessons' => 'integer',
        'order' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ChapterProgress::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ChapterVideo::class)->orderBy('order');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function progressForUser(User $user)
    {
        return $this->progress()->where('user_id', $user->id)->first();
    }
}
