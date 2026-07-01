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
    ];

    protected $casts = [
        'bootcamp_id' => 'integer',
        'order' => 'integer',
    ];

    public function bootcamp(): BelongsTo
    {
        return $this->belongsTo(Bootcamp::class);
    }
}
