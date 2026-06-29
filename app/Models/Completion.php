<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Completion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'completable_type',
        'completable_id',
        'final_score',
        'certificate_url',
        'completed_at',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function completable(): MorphTo
    {
        return $this->morphTo();
    }
}
