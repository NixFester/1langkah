<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Bootcamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'mentor_name',
        'type',
        'participants',
        'start_date',
        'price',
        'color',
        'sessions_info',
        'location',
        'mentor_id',
    ];

    protected $casts = [
        'participants' => 'integer',
        'mentor_id' => 'integer',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function pictures(): MorphMany
    {
        return $this->morphMany(Picture::class, 'pictureable');
    }

    public function thumbnail(): ?Picture
    {
        return $this->pictures()->thumbnail()->first();
    }

    public function gallery()
    {
        return $this->pictures()->gallery();
    }
}
