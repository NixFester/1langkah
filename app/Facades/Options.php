<?php

namespace App\Facades;

use App\Helpers\OptionsHelper;
use Illuminate\Support\Facades\Facade;

class Options extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'options';
    }

    /**
     * Get options for a category
     */
    public static function get(string $category): array
    {
        return OptionsHelper::get($category);
    }

    /**
     * Get categories
     */
    public static function categories(): array
    {
        return OptionsHelper::getCategories();
    }

    /**
     * Get validation rule for a category
     */
    public static function rule(string $category): string
    {
        return OptionsHelper::rule($category);
    }

    /**
     * Check if a key is valid
     */
    public static function isValid(string $category, string $key): bool
    {
        return OptionsHelper::isValid($category, $key);
    }
}
