<?php

namespace App\Helpers;

use App\Models\Option;

class OptionsHelper
{
    /**
     * Get options for a category as key-value array for select dropdowns
     * Usage in Blade: \Options::get('user_role')
     */
    public static function get(string $category): array
    {
        return Option::getOptionsForSelect($category);
    }

    /**
     * Get all categories
     */
    public static function getCategories(): array
    {
        return Option::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }

    /**
     * Get validation rule for a category
     * Usage: 'in:' . \Options::rule('user_role')
     */
    public static function rule(string $category): string
    {
        return Option::getValidationRule($category);
    }

    /**
     * Check if a key is valid for a category
     */
    public static function isValid(string $category, string $key): bool
    {
        return Option::category($category)->where('key', $key)->active()->exists();
    }
}
