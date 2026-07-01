<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BootcampRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bootcamp_id',
        'rating',
        'review_text',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bootcamp(): BelongsTo
    {
        return $this->belongsTo(Bootcamp::class);
    }
}
