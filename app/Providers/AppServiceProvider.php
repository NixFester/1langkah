<?php

namespace App\Providers;

use App\Facades\Options;
use App\Models\Completion;
use App\Models\CourseRating;
use App\Models\Enrollment;
use App\Models\ForumPost;
use App\Models\ForumReply;
use App\Models\Option;
use App\Models\TestAttempt;
use App\Models\UserActivityLog;
use App\Observers\AchievementObserver;
use App\Observers\CompletionObserver;
use App\Observers\CourseRatingObserver;
use App\Observers\ForumPostObserver;
use App\Observers\ForumReplyObserver;
use App\Observers\TestAttemptObserver;
use App\Observers\UserActivityLogObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('options', function () {
            return new class
            {
                public function get(string $category): array
                {
                    return Option::getOptionsForSelect($category);
                }

                public function rule(string $category): string
                {
                    return Option::getValidationRule($category);
                }

                public function categories(): array
                {
                    return Option::select('category')
                        ->distinct()
                        ->orderBy('category')
                        ->pluck('category')
                        ->toArray();
                }

                public function isValid(string $category, string $key): bool
                {
                    return Option::category($category)
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

        // Register Achievement Observers
        Enrollment::observe(AchievementObserver::class);
        Completion::observe(CompletionObserver::class);
        TestAttempt::observe(TestAttemptObserver::class);
        ForumPost::observe(ForumPostObserver::class);
        ForumReply::observe(ForumReplyObserver::class);
        CourseRating::observe(CourseRatingObserver::class);
        UserActivityLog::observe(UserActivityLogObserver::class);
    }
}
