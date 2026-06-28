<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Picture extends Model
{
    protected $fillable = [
        'pictureable_id',
        'pictureable_type',
        'type',
        'url',
        'alt',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    // ── Relationship ─────────────────────────────────────────────────────────

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
        return $query->where('type', 'array')->orderBy('order');
    }

    // ── Helper: safely set a thumbnail (replaces any existing one) ───────────

    /**
     * Attach or replace the thumbnail for a given parent model.
     *
     * Usage: Picture::setThumbnail($course, 'https://...', 'Course cover')
     */
    public static function setThumbnail(Model $parent, string $url, string $alt = ''): self
    {
        // Delete existing thumbnail for this parent before creating a new one
        $parent->pictures()->thumbnail()->delete();

        return $parent->pictures()->create([
            'type'  => 'thumbnail',
            'url'   => $url,
            'alt'   => $alt,
            'order' => 0,
        ]);
    }

    /**
     * Add a gallery image. Pass $order to control sort position.
     */
    public static function addToGallery(Model $parent, string $url, string $alt = '', int $order = 0): self
    {
        return $parent->pictures()->create([
            'type'  => 'array',
            'url'   => $url,
            'alt'   => $alt,
            'order' => $order,
        ]);
    }
}