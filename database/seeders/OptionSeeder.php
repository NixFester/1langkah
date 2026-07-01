<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            // User Roles
            [
                'category' => 'user_role',
                'key' => 'student',
                'label' => 'Student',
                'color' => '#10b981',
                'sort_order' => 1,
            ],
            [
                'category' => 'user_role',
                'key' => 'mentor',
                'label' => 'Mentor',
                'color' => '#3b82f6',
                'sort_order' => 2,
            ],
            [
                'category' => 'user_role',
                'key' => 'admin',
                'label' => 'Admin',
                'color' => '#ef4444',
                'sort_order' => 3,
            ],

            // Course Levels
            [
                'category' => 'course_level',
                'key' => 'Beginner',
                'label' => 'Beginner',
                'color' => '#22c55e',
                'sort_order' => 1,
            ],
            [
                'category' => 'course_level',
                'key' => 'Intermediate',
                'label' => 'Intermediate',
                'color' => '#f59e0b',
                'sort_order' => 2,
            ],
            [
                'category' => 'course_level',
                'key' => 'Advanced',
                'label' => 'Advanced',
                'color' => '#ef4444',
                'sort_order' => 3,
            ],

            // Event Types
            [
                'category' => 'event_type',
                'key' => 'online',
                'label' => 'Online',
                'color' => '#3b82f6',
                'sort_order' => 1,
            ],
            [
                'category' => 'event_type',
                'key' => 'offline',
                'label' => 'Offline',
                'color' => '#f59e0b',
                'sort_order' => 2,
            ],
            [
                'category' => 'event_type',
                'key' => 'hybrid',
                'label' => 'Hybrid',
                'color' => '#8b5cf6',
                'sort_order' => 3,
            ],

            // Event Statuses
            [
                'category' => 'event_status',
                'key' => 'draft',
                'label' => 'Draft',
                'color' => '#6b7280',
                'sort_order' => 1,
            ],
            [
                'category' => 'event_status',
                'key' => 'upcoming',
                'label' => 'Upcoming',
                'color' => '#3b82f6',
                'sort_order' => 2,
            ],
            [
                'category' => 'event_status',
                'key' => 'ongoing',
                'label' => 'Ongoing',
                'color' => '#22c55e',
                'sort_order' => 3,
            ],
            [
                'category' => 'event_status',
                'key' => 'completed',
                'label' => 'Completed',
                'color' => '#10b981',
                'sort_order' => 4,
            ],
            [
                'category' => 'event_status',
                'key' => 'cancelled',
                'label' => 'Cancelled',
                'color' => '#ef4444',
                'sort_order' => 5,
            ],

            // Bootcamp Types
            [
                'category' => 'bootcamp_type',
                'key' => 'online',
                'label' => 'Online',
                'color' => '#3b82f6',
                'sort_order' => 1,
            ],
            [
                'category' => 'bootcamp_type',
                'key' => 'offline',
                'label' => 'Offline',
                'color' => '#f59e0b',
                'sort_order' => 2,
            ],
        ];

        foreach ($options as &$option) {
            $option['is_active'] = $option['is_active'] ?? true;
        }

        foreach ($options as $option) {
            Option::updateOrCreate(
                ['category' => $option['category'], 'key' => $option['key']],
                $option
            );
        }
    }
}
