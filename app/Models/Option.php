<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'key',
        'label',
        'color',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope: filter by category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: only active options
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('label');
    }

    /**
     * Get all options for a category as key-value array for select dropdowns
     * Returns: ['key' => 'Label', ...]
     */
    public static function getOptionsForSelect(string $category): array
    {
        return static::category($category)
            ->active()
            ->ordered()
            ->pluck('label', 'key')
            ->toArray();
    }

    /**
     * Get all active option keys for a category (for validation rules)
     * Returns: ['admin', 'mentor', 'student']
     */
    public static function getKeysForCategory(string $category): array
    {
        return static::category($category)
            ->active()
            ->ordered()
            ->pluck('key')
            ->toArray();
    }

    /**
     * Build validation rule string for a category (for 'in:val1,val2')
     */
    public static function getValidationRule(string $category): string
    {
        $keys = static::getKeysForCategory($category);
        return 'in:' . implode(',', $keys);
    }
}
