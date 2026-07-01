<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    private RatingService $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Rate a course
     * POST /api/ratings/course
     */
    public function rateCourse(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $result = $this->ratingService->rateCourse(
            auth()->id(),
            $validated['course_id'],
            $validated['rating'],
            $validated['review'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Rate a bootcamp
     * POST /api/ratings/bootcamp
     */
    public function rateBootcamp(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'bootcamp_id' => 'required|integer|exists:bootcamps,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $result = $this->ratingService->rateBootcamp(
            auth()->id(),
            $validated['bootcamp_id'],
            $validated['rating'],
            $validated['review'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Get course rating details
     * GET /api/ratings/course/{courseId}
     */
    public function getCourseRating(int $courseId): JsonResponse
    {
        $result = $this->ratingService->getCourseRatingDetails($courseId);

        return response()->json($result);
    }

    /**
     * Get bootcamp rating details
     * GET /api/ratings/bootcamp/{bootcampId}
     */
    public function getBootcampRating(int $bootcampId): JsonResponse
    {
        $result = $this->ratingService->getBootcampRatingDetails($bootcampId);

        return response()->json($result);
    }

    /**
     * Get user's rating for a course
     * GET /api/ratings/course/{courseId}/user
     */
    public function getUserCourseRating(int $courseId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['rating' => null]);
        }

        $rating = $this->ratingService->getCourseRating(auth()->id(), $courseId);

        return response()->json(['rating' => $rating]);
    }

    /**
     * Get user's rating for a bootcamp
     * GET /api/ratings/bootcamp/{bootcampId}/user
     */
    public function getUserBootcampRating(int $bootcampId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['rating' => null]);
        }

        $rating = $this->ratingService->getBootcampRating(auth()->id(), $bootcampId);

        return response()->json(['rating' => $rating]);
    }

    /**
     * Get top rated courses
     * GET /api/ratings/top-courses
     */
    public function getTopCourses(): JsonResponse
    {
        $courses = $this->ratingService->getTopRatedCourses();

        return response()->json($courses);
    }

    /**
     * Get top rated bootcamps
     * GET /api/ratings/top-bootcamps
     */
    public function getTopBootcamps(): JsonResponse
    {
        $bootcamps = $this->ratingService->getTopRatedBootcamps();

        return response()->json($bootcamps);
    }
}
