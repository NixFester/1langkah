<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'pictureable_type',
        'pictureable_id',
        'url',
        'type',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // ── Relationships ───────────────────────────────────────────────────────

    public function pictureable(): MorphTo
    {
        return $this->morphTo();
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeThumbnail($query)
    {
        return $query->where('type', 'thumbnail');
    }

    public function scopeGallery($query)
    {
        return $query->where('type', 'gallery')->orderBy('order');
    }
}