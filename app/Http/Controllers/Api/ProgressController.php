<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProgressController extends Controller
{
    private ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Mark a chapter as watched
     * POST /api/progress/chapter/{chapterId}
     */
    public function markChapterWatched(Request $request, int $chapterId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $progressSeconds = $request->input('progress_seconds', 0);
        $videoId = $request->input('video_id');
        $courseId = $request->input('course_id');

        $result = $this->progressService->markChapterWatched(auth()->id(), $chapterId, $progressSeconds, $videoId, $courseId);

        return response()->json($result);
    }

    /**
     * Get course progress
     * GET /api/progress/course/{courseId}
     */
    public function getCourseProgress(int $courseId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->getCourseProgress(auth()->id(), $courseId);

        return response()->json($result);
    }

    /**
     * Mark session clicked (online bootcamp)
     * POST /api/progress/session/{sessionId}
     */
    public function markSessionClicked(Request $request, int $sessionId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->markSessionClicked(auth()->id(), $sessionId);

        return response()->json($result);
    }

    /**
     * Mark session completed
     * POST /api/progress/session/{sessionId}/complete
     */
    public function markSessionCompleted(int $sessionId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->markSessionCompleted(auth()->id(), $sessionId);

        return response()->json($result);
    }

    /**
     * Get bootcamp session progress
     * GET /api/progress/bootcamp/{bootcampId}
     */
    public function getBootcampProgress(int $bootcampId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->getBootcampSessionProgress(auth()->id(), $bootcampId);

        return response()->json($result);
    }

    /**
     * Get dashboard stats
     * GET /api/progress/stats
     */
    public function getStats(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->getDashboardStats(auth()->id());

        return response()->json($result);
    }

    /**
     * Track session attendance (clicked meeting link)
     * POST /api/session-progress
     */
    public function trackSession(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $sessionId = $request->input('session_id');
        $bootcampId = $request->input('bootcamp_id');

        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'Session ID required'], 400);
        }

        $result = $this->progressService->markSessionClicked(auth()->id(), $sessionId);

        return response()->json($result);
    }

    /**
     * Get user skills summary
     * GET /api/progress/skills
     */
    public function getSkills(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->getUserSkillsSummary(auth()->id());

        return response()->json($result);
    }
}
