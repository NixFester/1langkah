<?php

namespace App\Models;

use App\Services\XpService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'ticket_code',
        'registered_at',
        'attended_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::created(function (self $registration): void {
            // Award XP for event registration
            app(XpService::class)->awardXp(
                $registration->user,
                'event_registered',
                self::class,
                $registration->id
            );
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
