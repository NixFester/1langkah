<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionProgress extends Model
{
    use HasFactory;

    protected $table = 'session_progress';

    protected $fillable = [
        'user_id',
        'bootcamp_session_id',
        'clicked_at',
        'completed',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
        'completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(BootcampSession::class, 'bootcamp_session_id');
    }
}
