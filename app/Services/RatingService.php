<?php

namespace App\Services;

use App\Models\BootcampRating;
use App\Models\CourseRating;
use App\Models\Course;
use App\Models\Bootcamp;
use Illuminate\Support\Facades\DB;

class RatingService
{
    /**
     * Rate a course
     */
    public function rateCourse(int $userId, int $courseId, int $rating, ?string $reviewText = null): array
    {
        $course = Course::find($courseId);

        if (!$course) {
            return ['success' => false, 'message' => 'Course not found'];
        }

        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'Rating must be between 1 and 5'];
        }

        // Update or create rating
        $courseRating = CourseRating::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $courseId],
            ['rating' => $rating, 'review_text' => $reviewText]
        );

        // Update course's average rating
        $this->updateCourseAverageRating($courseId);

        // Add XP for rating
        $this->addXpForRating($userId, 'course');

        return [
            'success' => true,
            'message' => 'Rating submitted successfully',
            'rating' => $courseRating,
        ];
    }

    /**
     * Rate a bootcamp
     */
    public function rateBootcamp(int $userId, int $bootcampId, int $rating, ?string $reviewText = null): array
    {
        $bootcamp = Bootcamp::find($bootcampId);

        if (!$bootcamp) {
            return ['success' => false, 'message' => 'Bootcamp not found'];
        }

        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'Rating must be between 1 and 5'];
        }

        // Update or create rating
        $bootcampRating = BootcampRating::updateOrCreate(
            ['user_id' => $userId, 'bootcamp_id' => $bootcampId],
            ['rating' => $rating, 'review_text' => $reviewText]
        );

        // Update bootcamp's average rating
        $this->updateBootcampAverageRating($bootcampId);

        // Add XP for rating
        $this->addXpForRating($userId, 'bootcamp');

        return [
            'success' => true,
            'message' => 'Rating submitted successfully',
            'rating' => $bootcampRating,
        ];
    }

    /**
     * Get user's rating for a course
     */
    public function getCourseRating(int $userId, int $courseId): ?int
    {
        return CourseRating::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->value('rating');
    }

    /**
     * Get user's rating for a bootcamp
     */
    public function getBootcampRating(int $userId, int $bootcampId): ?int
    {
        return BootcampRating::where('user_id', $userId)
            ->where('bootcamp_id', $bootcampId)
            ->value('rating');
    }

    /**
     * Get course rating details
     */
    public function getCourseRatingDetails(int $courseId): array
    {
        $ratings = CourseRating::where('course_id', $courseId)
            ->with('user:id,name,profile_photo')
            ->orderBy('created_at', 'desc')
            ->get();

        $avgRating = $ratings->avg('rating') ?? 0;
        $totalRatings = $ratings->count();

        // Rating distribution
        $distribution = [
            5 => $ratings->where('rating', 5)->count(),
            4 => $ratings->where('rating', 4)->count(),
            3 => $ratings->where('rating', 3)->count(),
            2 => $ratings->where('rating', 2)->count(),
            1 => $ratings->where('rating', 1)->count(),
        ];

        return [
            'average' => round($avgRating, 1),
            'total' => $totalRatings,
            'distribution' => $distribution,
            'reviews' => $ratings->map(fn($r) => [
                'rating' => $r->rating,
                'review' => $r->review_text,
                'user' => $r->user ? [
                    'name' => $r->user->name,
                    'photo' => $r->user->profile_photo,
                ] : null,
                'created_at' => $r->created_at,
            ]),
        ];
    }

    /**
     * Get bootcamp rating details
     */
    public function getBootcampRatingDetails(int $bootcampId): array
    {
        $ratings = BootcampRating::where('bootcamp_id', $bootcampId)
            ->with('user:id,name,profile_photo')
            ->orderBy('created_at', 'desc')
            ->get();

        $avgRating = $ratings->avg('rating') ?? 0;
        $totalRatings = $ratings->count();

        $distribution = [
            5 => $ratings->where('rating', 5)->count(),
            4 => $ratings->where('rating', 4)->count(),
            3 => $ratings->where('rating', 3)->count(),
            2 => $ratings->where('rating', 2)->count(),
            1 => $ratings->where('rating', 1)->count(),
        ];

        return [
            'average' => round($avgRating, 1),
            'total' => $totalRatings,
            'distribution' => $distribution,
            'reviews' => $ratings->map(fn($r) => [
                'rating' => $r->rating,
                'review' => $r->review_text,
                'user' => $r->user ? [
                    'name' => $r->user->name,
                    'photo' => $r->user->profile_photo,
                ] : null,
                'created_at' => $r->created_at,
            ]),
        ];
    }

    /**
     * Get top rated courses
     */
    public function getTopRatedCourses(int $limit = 10): array
    {
        return Course::with('ratings')
            ->get()
            ->map(fn($course) => [
                'id' => $course->id,
                'title' => $course->title,
                'average_rating' => $course->average_rating,
                'rating_count' => $course->rating_count,
                'thumbnail' => $course->thumbnail()?->url,
            ])
            ->sortByDesc('average_rating')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Get top rated bootcamps
     */
    public function getTopRatedBootcamps(int $limit = 10): array
    {
        return Bootcamp::with('ratings')
            ->get()
            ->map(fn($bootcamp) => [
                'id' => $bootcamp->id,
                'title' => $bootcamp->title,
                'type' => $bootcamp->type,
                'average_rating' => $bootcamp->average_rating,
                'rating_count' => $bootcamp->rating_count,
                'thumbnail' => $bootcamp->thumbnail()?->url,
            ])
            ->sortByDesc('average_rating')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Update course's average rating in the courses table
     */
    private function updateCourseAverageRating(int $courseId): void
    {
        $avgRating = CourseRating::where('course_id', $courseId)->avg('rating');

        Course::where('id', $courseId)->update([
            'rating' => round($avgRating ?? 0, 1),
        ]);
    }

    /**
     * Update bootcamp's average rating
     */
    private function updateBootcampAverageRating(int $bootcampId): void
    {
        $avgRating = BootcampRating::where('bootcamp_id', $bootcampId)->avg('rating');

        Bootcamp::where('id', $bootcampId)->update([
            'rating' => round($avgRating ?? 0, 1),
        ]);
    }

    /**
     * Add XP when user rates something
     */
    private function addXpForRating(int $userId, string $type): void
    {
        // XP is handled by the model's accessor in User model
        // This method can be used for additional logic if needed
        \Log::info("User {$userId} rated a {$type} and earned XP");
    }
}
