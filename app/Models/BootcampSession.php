<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BootcampSession extends Model
{
    use HasFactory;

    protected $table = 'bootcamp_session';

    protected $fillable = [
        'bootcamp_id',
        'date',
        'topic',
        'time',
        'meeting_url',
        'description',
        'order',
        'password',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $session): void {
            if ($session->bootcamp_id && empty($session->password)) {
                $session->password = self::generatePassword();
            }
        });
    }

    public static function generatePassword(): string
    {
        do {
            $password = strtoupper(str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT));
        } while (self::where('password', $password)->exists());

        return $password;
    }

    protected $casts = [
        'bootcamp_id' => 'integer',
        'order' => 'integer',
    ];

    public function bootcamp(): BelongsTo
    {
        return $this->belongsTo(Bootcamp::class);
    }
}
