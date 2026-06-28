<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sesi extends Model
{
    use HasFactory;

    protected $table = 'sesi';

    protected $fillable = [
        'bootcamp_id',
        'date',
        'topic',
        'time',
    ];

    protected $casts = [
        'bootcamp_id' => 'integer',
    ];

    public function bootcamp(): BelongsTo
    {
        return $this->belongsTo(Bootcamp::class);
    }
}
