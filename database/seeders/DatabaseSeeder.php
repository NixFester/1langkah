<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── Core System Options (Roles, Levels, Types, Statuses) ─────────
            OptionSeeder::class,

            // ── Default Production Staff / Admin Accounts ────────────────────
            ProductionReadyUserSeeder::class,

            // ── Gamification Defaults (XP Rewards & Achievements) ────────────
            XpRewardsSeeder::class,
            AchievementSeeder::class,
        ]);
    }
}
