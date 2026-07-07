<?php

namespace Database\Seeders;

use App\Models\XpReward;
use Illuminate\Database\Seeder;

class XpRewardsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            // Enrollment
            [
                'action' => 'enrolled_course',
                'xp_amount' => 50,
                'description' => 'XP earned when enrolling in a course',
            ],
            [
                'action' => 'enrolled_bootcamp',
                'xp_amount' => 75,
                'description' => 'XP earned when enrolling in a bootcamp',
            ],

            // Course progress
            [
                'action' => 'video_watched',
                'xp_amount' => 10,
                'description' => 'XP earned when completing a video (first time only)',
            ],
            [
                'action' => 'chapter_completed',
                'xp_amount' => 25,
                'description' => 'XP earned when completing all videos in a chapter',
            ],

            // Quiz
            [
                'action' => 'quiz_passed',
                'xp_amount' => 50,
                'description' => 'XP earned when passing a quiz',
            ],
            [
                'action' => 'quiz_failed',
                'xp_amount' => 10,
                'description' => 'XP earned for participating in a quiz (even if failed)',
            ],

            // Bootcamp
            [
                'action' => 'session_clicked',
                'xp_amount' => 15,
                'description' => 'XP earned when joining an online bootcamp session (first click only)',
            ],
            [
                'action' => 'attendance_scanned',
                'xp_amount' => 30,
                'description' => 'XP earned when scanning attendance QR for offline bootcamp',
            ],

            // Forum
            [
                'action' => 'forum_post_created',
                'xp_amount' => 10,
                'description' => 'XP earned when creating a forum post',
            ],
            [
                'action' => 'forum_reply_created',
                'xp_amount' => 5,
                'description' => 'XP earned when replying to a forum post',
            ],
            [
                'action' => 'forum_post_upvoted',
                'xp_amount' => 3,
                'description' => 'XP earned when your forum post receives an upvote',
            ],
            [
                'action' => 'forum_reply_upvoted',
                'xp_amount' => 3,
                'description' => 'XP earned when your forum reply receives an upvote',
            ],

            // Reviews
            [
                'action' => 'review_submitted',
                'xp_amount' => 15,
                'description' => 'XP earned when submitting a review with text (course or bootcamp)',
            ],

            // Events
            [
                'action' => 'event_registered',
                'xp_amount' => 10,
                'description' => 'XP earned when registering for an event',
            ],
            [
                'action' => 'event_attended',
                'xp_amount' => 20,
                'description' => 'XP earned when attending an event (marked by mentor/admin)',
            ],
        ];

        foreach ($rewards as $reward) {
            XpReward::updateOrCreate(
                ['action' => $reward['action']],
                $reward
            );
        }
    }
}
