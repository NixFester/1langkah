<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Achievement extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'description',
        'icon',
        'category',
        'xp_reward',
        'trigger_type',
        'trigger_conditions',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'trigger_conditions' => 'array',
            'is_active' => 'boolean',
            'xp_reward' => 'integer',
        ];
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * Get active achievements only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get achievements by category
     */
    public function scopeOfCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get achievements by trigger type
     */
    public function scopeOfTriggerType($query, string $type)
    {
        return $query->where('trigger_type', $type);
    }

    /**
     * Check if conditions are met
     */
    public function checkConditions(array $userStats): bool
    {
        $conditions = $this->trigger_conditions ?? [];

        foreach ($conditions as $key => $value) {
            $userValue = $userStats[$key] ?? 0;
            if ($userValue < $value) {
                return false;
            }
        }

        return true;
    }
}
