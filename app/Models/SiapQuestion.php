<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiapQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'siap_assessment_id',
        'siap_category_id',
        'question_text',
        'options',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(SiapAssessment::class, 'siap_assessment_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SiapCategory::class, 'siap_category_id');
    }
}
