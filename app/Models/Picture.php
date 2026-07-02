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

    // ── Static Helpers ─────────────────────────────────────────────────────────

    /**
     * Set thumbnail picture for a model
     */
    public static function setThumbnail($model, string $url, ?string $description = null): self
    {
        // Remove existing thumbnail
        self::where('pictureable_type', get_class($model))
            ->where('pictureable_id', $model->id)
            ->where('type', 'thumbnail')
            ->delete();

        return self::create([
            'pictureable_type' => get_class($model),
            'pictureable_id' => $model->id,
            'url' => $url,
            'type' => 'thumbnail',
            'description' => $description,
        ]);
    }

    /**
     * Add image to gallery for a model
     */
    public static function addToGallery($model, string $url, ?string $description = null, int $order = 0): self
    {
        return self::create([
            'pictureable_type' => get_class($model),
            'pictureable_id' => $model->id,
            'url' => $url,
            'type' => 'gallery',
            'description' => $description,
            'order' => $order,
        ]);
    }
}