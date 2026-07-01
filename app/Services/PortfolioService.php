<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserSkill;
use App\Models\Course;
use App\Models\Bootcamp;
use App\Models\Completion;
use App\Models\CourseRating;
use App\Models\BootcampRating;
use Illuminate\Support\Facades\DB;

class PortfolioService
{
    private RatingService $ratingService;
    private ProgressService $progressService;

    public function __construct(RatingService $ratingService, ProgressService $progressService)
    {
        $this->ratingService = $ratingService;
        $this->progressService = $progressService;
    }

    /**
     * Generate user's portfolio data
     */
    public function getPortfolio(int $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [];
        }

        return [
            'user' => $this->getUserInfo($user),
            'skills' => $this->getSkills($userId),
            'courses' => $this->getCompletedCourses($userId),
            'bootcamps' => $this->getCompletedBootcamps($userId),
            'stats' => $this->getStats($userId),
            'achievements' => $this->getAchievements($userId),
        ];
    }

    /**
     * Get user info for portfolio
     */
    private function getUserInfo(User $user): array
    {
        return [
            'name' => $user->name,
            'bio' => $user->bio ?? '',
            'email' => $user->email,
            'profile_photo' => $user->profile_photo,
            'initials' => strtoupper(substr(implode('', array_map(fn($w) => $w[0] ?? '', explode(' ', $user->name))), 0, 2)),
            'joined_at' => $user->created_at->format('M Y'),
            'xp' => $user->xp,
            'streak' => $user->streak,
        ];
    }

    /**
     * Get user's skills sorted by rating (highest first)
     */
    private function getSkills(int $userId): array
    {
        $skills = UserSkill::where('user_id', $userId)
            ->orderBy('rating', 'desc')
            ->get()
            ->groupBy('skill_name')
            ->map(function ($grouped) {
                $bestRating = $grouped->max('rating');
                $source = $grouped->first();

                return [
                    'name' => $source->skill_name,
                    'rating' => $bestRating,
                    'source_type' => $source->source_type,
                    'count' => $grouped->count(),
                ];
            })
            ->values()
            ->sortByDesc('rating')
            ->take(15)
            ->toArray();

        return array_values($skills);
    }

    /**
     * Get completed courses sorted by rating (highest first)
     */
    private function getCompletedCourses(int $userId): array
    {
        $completions = Completion::where('user_id', $userId)
            ->where('completable_type', Course::class)
            ->with('completable:id,title,category,rating')
            ->get();

        return $completions
            ->map(function ($completion) {
                $course = $completion->completable;
                $userRating = CourseRating::where('user_id', $completion->user_id)
                    ->where('course_id', $course->id)
                    ->first();

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'category' => $course->category,
                    'rating' => $course->rating ?? $course->average_rating ?? 0,
                    'user_rating' => $userRating?->rating,
                    'thumbnail' => $course->thumbnail()?->url,
                    'completed_at' => $completion->completed_at?->format('M Y'),
                ];
            })
            ->sortByDesc('rating')
            ->values()
            ->toArray();
    }

    /**
     * Get completed bootcamps sorted by rating (highest first)
     */
    private function getCompletedBootcamps(int $userId): array
    {
        $completions = Completion::where('user_id', $userId)
            ->where('completable_type', Bootcamp::class)
            ->with('completable:id,title,type,rating')
            ->get();

        return $completions
            ->map(function ($completion) {
                $bootcamp = $completion->completable;
                $userRating = BootcampRating::where('user_id', $completion->user_id)
                    ->where('bootcamp_id', $bootcamp->id)
                    ->first();

                return [
                    'id' => $bootcamp->id,
                    'title' => $bootcamp->title,
                    'type' => $bootcamp->type,
                    'rating' => $bootcamp->rating ?? $bootcamp->average_rating ?? 0,
                    'user_rating' => $userRating?->rating,
                    'thumbnail' => $bootcamp->thumbnail()?->url,
                    'completed_at' => $completion->completed_at?->format('M Y'),
                ];
            })
            ->sortByDesc('rating')
            ->values()
            ->toArray();
    }

    /**
     * Get portfolio statistics
     */
    private function getStats(int $userId): array
    {
        $user = User::find($userId);

        $completedCourses = Completion::where('user_id', $userId)
            ->where('completable_type', Course::class)
            ->count();

        $completedBootcamps = Completion::where('user_id', $userId)
            ->where('completable_type', Bootcamp::class)
            ->count();

        $totalRatings = CourseRating::where('user_id', $userId)->count()
            + BootcampRating::where('user_id', $userId)->count();

        $totalSkills = UserSkill::where('user_id', $userId)->distinct('skill_name')->count('skill_name');

        return [
            'courses_completed' => $completedCourses,
            'bootcamps_completed' => $completedBootcamps,
            'total_programs' => $completedCourses + $completedBootcamps,
            'skills_acquired' => $totalSkills,
            'reviews_written' => $totalRatings,
            'xp_earned' => $user->xp,
            'streak_days' => $user->streak,
        ];
    }

    /**
     * Get achievements/badges
     */
    private function getAchievements(int $userId): array
    {
        $achievements = [];

        // Courses completed
        $coursesCompleted = Completion::where('user_id', $userId)
            ->where('completable_type', Course::class)
            ->count();

        if ($coursesCompleted >= 1) {
            $achievements[] = ['name' => 'First Course', 'icon' => '🎓', 'desc' => 'Completed first course'];
        }
        if ($coursesCompleted >= 5) {
            $achievements[] = ['name' => 'Course Collector', 'icon' => '📚', 'desc' => 'Completed 5 courses'];
        }
        if ($coursesCompleted >= 10) {
            $achievements[] = ['name' => 'Course Master', 'icon' => '🏆', 'desc' => 'Completed 10 courses'];
        }

        // Bootcamps completed
        $bootcampsCompleted = Completion::where('user_id', $userId)
            ->where('completable_type', Bootcamp::class)
            ->count();

        if ($bootcampsCompleted >= 1) {
            $achievements[] = ['name' => 'Bootcamp Graduate', 'icon' => '🎯', 'desc' => 'Completed first bootcamp'];
        }
        if ($bootcampsCompleted >= 3) {
            $achievements[] = ['name' => 'Bootcamp Pro', 'icon' => '⭐', 'desc' => 'Completed 3 bootcamps'];
        }

        // Skills
        $skillsCount = UserSkill::where('user_id', $userId)->distinct('skill_name')->count('skill_name');

        if ($skillsCount >= 5) {
            $achievements[] = ['name' => 'Skill Builder', 'icon' => '🛠️', 'desc' => 'Learned 5 different skills'];
        }
        if ($skillsCount >= 10) {
            $achievements[] = ['name' => 'Polymath', 'icon' => '🌟', 'desc' => 'Learned 10 different skills'];
        }

        // XP milestones
        $user = User::find($userId);
        $xp = $user->xp;

        if ($xp >= 100) {
            $achievements[] = ['name' => 'Getting Started', 'icon' => '🚀', 'desc' => 'Earned 100 XP'];
        }
        if ($xp >= 1000) {
            $achievements[] = ['name' => 'XP Hunter', 'icon' => '💎', 'desc' => 'Earned 1,000 XP'];
        }
        if ($xp >= 5000) {
            $achievements[] = ['name' => 'XP Legend', 'icon' => '👑', 'desc' => 'Earned 5,000 XP'];
        }

        // Ratings given
        $ratingsGiven = CourseRating::where('user_id', $userId)->count()
            + BootcampRating::where('user_id', $userId)->count();

        if ($ratingsGiven >= 3) {
            $achievements[] = ['name' => 'Reviewer', 'icon' => '📝', 'desc' => 'Wrote 3 reviews'];
        }

        return $achievements;
    }

    /**
     * Get public portfolio for sharing
     */
    public function getPublicPortfolio(int $userId): array
    {
        $portfolio = $this->getPortfolio($userId);

        // Remove sensitive data
        unset($portfolio['user']['email']);

        return $portfolio;
    }

    /**
     * Generate shareable portfolio link data
     */
    public function generateShareData(int $userId): array
    {
        $portfolio = $this->getPublicPortfolio($userId);

        return [
            'portfolio' => $portfolio,
            'share_url' => route('portfolio.public', $userId),
            'generated_at' => now()->toIso8601String(),
        ];
    }
}
