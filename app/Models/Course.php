<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'mentor_name',
        'mentor_company',
        'category',
        'level',
        'badge',
        'rating',
        'students_count',
        'price',
        'progress',
        'color',
        'mentor_id',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'students_count' => 'integer',
        'progress' => 'integer',
        'mentor_id' => 'integer',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }
    public function pictures(): MorphMany
    {
        return $this->morphMany(Picture::class, 'pictureable');
    }

    /** Shortcut: single thumbnail or null. */
    public function thumbnail(): ?Picture
    {
        return $this->pictures()->thumbnail()->first();
    }

    /** Shortcut: ordered gallery images. */
    public function gallery()
    {
        return $this->pictures()->gallery();
    }
}
