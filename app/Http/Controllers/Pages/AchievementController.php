<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\UserAchievement;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AchievementController extends Controller
{
    /**
     * Display user's achievements
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get user's earned achievements
        $userAchievements = UserAchievement::where('user_id', $user->id)
            ->with('achievement')
            ->orderBy('earned_at', 'desc')
            ->get();

        // Get all achievements grouped by category
        $allAchievements = Achievement::all()->groupBy('category');

        // Calculate stats
        $totalEarned = $userAchievements->count();
        $totalAvailable = Achievement::count();

        return view('pages.achievement', [
            'userAchievements' => $userAchievements,
            'allAchievements' => $allAchievements,
            'totalEarned' => $totalEarned,
            'totalAvailable' => $totalAvailable,
        ]);
    }
}
