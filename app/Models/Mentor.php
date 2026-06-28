<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mentor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'company',
        'price',
        'rating',
        'sessions_count',
        'initials',
        'color',
        'expertise',
        'bio',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'sessions_count' => 'integer',
        'expertise' => 'array',
    ];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function bootcamps(): HasMany
    {
        return $this->hasMany(Bootcamp::class);
    }
}
