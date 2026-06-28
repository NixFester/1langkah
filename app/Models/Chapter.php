<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'lessons',
        'duration',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'lessons' => 'integer',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
