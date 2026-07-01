<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_name',
        'source_type',
        'source_id',
        'rating',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
