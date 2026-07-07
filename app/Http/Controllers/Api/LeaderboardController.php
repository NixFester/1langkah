<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\XpService;
use Illuminate\Http\JsonResponse;

class LeaderboardController extends Controller
{
    private XpService $xpService;

    public function __construct(XpService $xpService)
    {
        $this->xpService = $xpService;
    }

    /**
     * Get leaderboard data
     * GET /api/leaderboard
     */
    public function index(): JsonResponse
    {
        if (! auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $topUsers = $this->xpService->getLeaderboard(10);
        $userRank = $this->xpService->getUserRank(auth()->id());

        return response()->json([
            'success' => true,
            'top_users' => $topUsers,
            'user_rank' => $userRank,
        ]);
    }

    /**
     * Get user's XP details
     * GET /api/xp/details
     */
    public function details(): JsonResponse
    {
        if (! auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $userId = auth()->id();
        $breakdown = $this->xpService->getXpBreakdown($userId);
        $nextLevel = $this->xpService->getXpToNextLevel($userId);
        $history = $this->xpService->getXpHistory($userId, 10);

        return response()->json([
            'success' => true,
            'xp' => auth()->user()->xp,
            'level' => auth()->user()->level,
            'breakdown' => $breakdown,
            'next_level' => $nextLevel,
            'recent' => $history,
        ]);
    }
}
