<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiapCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'siap_assessment_id',
        'slug',
        'code',
        'name',
        'label',
        'symbol',
        'color',
        'icon',
        'description',
        'details',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(SiapAssessment::class, 'siap_assessment_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SiapQuestion::class, 'siap_category_id')->orderBy('sort_order');
    }
}
