<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── Tier 1: No dependencies ──────────────────────────────────────
            OptionSeeder::class,
            UserSeeder::class,
            MentorSeeder::class,

            // ── Tier 1b: Depends on User ──────────────────────────────────────
            ForumSeeder::class,

            // ── Tier 2: Depends on Mentor ───────────────────────────────────
            CourseSeeder::class,

            // ── Tier 3: Depends on Course ───────────────────────────────────
            ChapterSeeder::class,
            ChapterVideoSeeder::class,
            ResourceSeeder::class,
            QuizSeeder::class,

            // ── Tier 4: Depends on Mentor ───────────────────────────────────
            BootcampSeeder::class,

            // ── Tier 5: Depends on Bootcamp ──────────────────────────────────
            BootcampSessionSeeder::class,

            // ── Tier 6: Standalone ───────────────────────────────────────────
            EventSeeder::class,

            // ── Tier 7: Depends on User, Course, Bootcamp, Chapter ──────────
            UserProgressSeeder::class,

            // ── Tier 8: Depends on User ─────────────────────────────────────
            UserActivityLogSeeder::class,

            // ── Tier 9: Depends on User, Bootcamp ───────────────────────────
            BootcampRatingSeeder::class,

            // ── Tier 10: Depends on Course, Bootcamp ────────────────────────
            PictureSeeder::class,

            // ── Tier 11: Self-contained (creates its own users/courses/etc.) ─
            FlowTestSeeder::class,

            // ── Tier 12: Standalone achievements ───────────────────────────────
            AchievementSeeder::class,
        ]);
    }
}
