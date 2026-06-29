<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\UserActivityLog;
use Illuminate\Database\Seeder;

class UserActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $logs = [
            ['user_id' => 1, 'action' => 'completed lesson in', 'loggable_type' => Course::class, 'loggable_id' => 1, 'created_at' => now()->subHours(2)],
            ['user_id' => 1, 'action' => 'earned badge in', 'loggable_type' => Course::class, 'loggable_id' => 1, 'created_at' => now()->subDay()],
            ['user_id' => 1, 'action' => 'submitted assignment for', 'loggable_type' => Course::class, 'loggable_id' => 2, 'created_at' => now()->subDays(2)],
        ];

        foreach ($logs as $log) {
            UserActivityLog::create($log);
        }
    }
}