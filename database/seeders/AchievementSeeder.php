<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // ============================================================
            // COURSE ACHIEVEMENTS (course_enrolled, course_completed)
            // ============================================================
            [
                'slug' => 'first-course',
                'name' => 'First Step',
                'description' => 'Enrolled in your first course',
                'icon' => '<svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path
  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168
  5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477
  4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                'category' => 'learning',
                'xp_reward' => 10,
                'trigger_type' => 'course_enrolled',
                'trigger_conditions' => ['enrolled_count' => 1],
            ],
            [
                'slug' => 'course-explorer',
                'name' => 'Course Explorer',
                'description' => 'Enrolled in 5 different courses',
                'icon' => '...',
                'category' => 'learning',
                'xp_reward' => 50,
                'trigger_type' => 'course_enrolled',
                'trigger_conditions' => ['enrolled_count' => 5],
            ],
            [
                'slug' => 'first-completion',
                'name' => 'First Graduate',
                'description' => 'Completed your first course',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 25,
                'trigger_type' => 'course_completed',
                'trigger_conditions' => ['completed_courses' => 1],
            ],

            // ============================================================
            // CATEGORY ACHIEVEMENTS (course_category_enrolled)
            // ============================================================
            [
                'slug' => 'programming-starter',
                'name' => 'Programming Starter',
                'description' => 'Enrolled in 2 programming courses',
                'icon' => '...',
                'category' => 'learning',
                'xp_reward' => 30,
                'trigger_type' => 'course_category_enrolled',
                'trigger_conditions' => ['category' => 'programming', 'count' => 2],
            ],
            [
                'slug' => 'design-enthusiast',
                'name' => 'Design Enthusiast',
                'description' => 'Enrolled in 2 design courses',
                'icon' => '...',
                'category' => 'learning',
                'xp_reward' => 30,
                'trigger_type' => 'course_category_enrolled',
                'trigger_conditions' => ['category' => 'design', 'count' => 2],
            ],

            // ============================================================
            // QUIZ ACHIEVEMENTS (quiz_passed, quiz_score_above)
            // ============================================================
            [
                'slug' => 'quiz-starter',
                'name' => 'Quiz Starter',
                'description' => 'Passed your first quiz',
                'icon' => '...',
                'category' => 'learning',
                'xp_reward' => 15,
                'trigger_type' => 'quiz_passed',
                'trigger_conditions' => ['count' => 1],
            ],
            [
                'slug' => 'quiz-perfectionist',
                'name' => 'Quiz Perfectionist',
                'description' => 'Achieved 90% or above in 3 quizzes',
                'icon' => '...',
                'category' => 'learning',
                'xp_reward' => 75,
                'trigger_type' => 'quiz_score_above',
                'trigger_conditions' => ['score' => 90, 'count' => 3],
            ],

            // ============================================================
            // FORUM ACHIEVEMENTS (forum_post, forum_reply, forum_vote_received)
            // ============================================================
            [
                'slug' => 'first-post',
                'name' => 'Community Voice',
                'description' => 'Made your first forum post',
                'icon' => '...',
                'category' => 'social',
                'xp_reward' => 10,
                'trigger_type' => 'forum_post',
                'trigger_conditions' => ['count' => 1],
            ],
            [
                'slug' => 'helpful-replier',
                'name' => 'Helpful Helper',
                'description' => 'Replied to 5 forum posts',
                'icon' => '...',
                'category' => 'social',
                'xp_reward' => 25,
                'trigger_type' => 'forum_reply',
                'trigger_conditions' => ['count' => 5],
            ],
            [
                'slug' => 'popular-poster',
                'name' => 'Popular Poster',
                'description' => 'Received 20 upvotes on your posts/replies',
                'icon' => '...',
                'category' => 'social',
                'xp_reward' => 50,
                'trigger_type' => 'forum_vote_received',
                'trigger_conditions' => ['count' => 20, 'type' => 'upvotes'],
            ],

            // ============================================================
            // BOOTCAMP ACHIEVEMENTS (bootcamp_enrolled, bootcamp_completed)
            // ============================================================
            [
                'slug' => 'bootcamp-starter',
                'name' => 'Bootcamp Beginner',
                'description' => 'Enrolled in your first bootcamp',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 20,
                'trigger_type' => 'bootcamp_enrolled',
                'trigger_conditions' => ['count' => 1],
            ],
            [
                'slug' => 'bootcamp-graduate',
                'name' => 'Bootcamp Graduate',
                'description' => 'Completed your first bootcamp',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 50,
                'trigger_type' => 'bootcamp_completed',
                'trigger_conditions' => ['count' => 1],
            ],

            // ============================================================
            // STREAK ACHIEVEMENTS (streak_days)
            // ============================================================
            [
                'slug' => 'streak-3',
                'name' => '3-Day Streak',
                'description' => 'Maintained a 3-day learning streak',
                'icon' => '...',
                'category' => 'consistency',
                'xp_reward' => 15,
                'trigger_type' => 'streak_days',
                'trigger_conditions' => ['days' => 3],
            ],
            [
                'slug' => 'streak-7',
                'name' => 'Week Warrior',
                'description' => 'Maintained a 7-day learning streak',
                'icon' => '...',
                'category' => 'consistency',
                'xp_reward' => 50,
                'trigger_type' => 'streak_days',
                'trigger_conditions' => ['days' => 7],
            ],

            // ============================================================
            // REVIEW ACHIEVEMENTS (review_written)
            // ============================================================
            [
                'slug' => 'first-review',
                'name' => 'Reviewer',
                'description' => 'Wrote your first course review',
                'icon' => '...',
                'category' => 'social',
                'xp_reward' => 10,
                'trigger_type' => 'review_written',
                'trigger_conditions' => ['count' => 1],
            ],

            // ============================================================
            // XP ACHIEVEMENTS (total_xp)
            // ============================================================
            [
                'slug' => 'xp-100',
                'name' => 'XP Hunter',
                'description' => 'Earned 100 XP',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 0,
                'trigger_type' => 'total_xp',
                'trigger_conditions' => ['xp' => 100],
            ],
            [
                'slug' => 'xp-500',
                'name' => 'XP Master',
                'description' => 'Earned 500 XP',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 0,
                'trigger_type' => 'total_xp',
                'trigger_conditions' => ['xp' => 500],
            ],

            // ============================================================
            // MULTI-TYPE COMBINATION ACHIEVEMENTS (multi_type)
            // ============================================================
            [
                'slug' => 'dedicated-learner',
                'name' => 'Dedicated Learner',
                'description' => 'Enrolled in 3 courses AND completed 1 course',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 75,
                'trigger_type' => 'multi_type',
                'trigger_conditions' => [
                    'requirements' => [
                        'courses_enrolled' => 3,
                        'courses_completed' => 1,
                    ],
                ],
            ],
            [
                'slug' => 'all-rounder',
                'name' => 'All-Rounder',
                'description' => 'Completed 1 course, 1 quiz, 1 forum post, and 1 review',
                'icon' => '...',
                'category' => 'milestone',
                'xp_reward' => 100,
                'trigger_type' => 'multi_type',
                'trigger_conditions' => [
                    'requirements' => [
                        'courses_completed' => 1,
                        'quizzes_passed' => 1,
                        'forum_posts' => 1,
                        'reviews_written' => 1,
                    ],
                ],
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::updateOrCreate(
                ['slug' => $achievement['slug']],
                $achievement + ['is_active' => true]
            );
        }
    }
}
