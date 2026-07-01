<?php

namespace App\Providers;

use App\Facades\Options;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('options', function () {
            return new class {
                public function get(string $category): array
                {
                    return \App\Models\Option::getOptionsForSelect($category);
                }

                public function rule(string $category): string
                {
                    return \App\Models\Option::getValidationRule($category);
                }

                public function categories(): array
                {
                    return \App\Models\Option::select('category')
                        ->distinct()
                        ->orderBy('category')
                        ->pluck('category')
                        ->toArray();
                }

                public function isValid(string $category, string $key): bool
                {
                    return \App\Models\Option::category($category)
                        ->where('key', $key)
                        ->active()
                        ->exists();
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register Options facade alias
        $this->app->alias('options', Options::class);
    }
}
