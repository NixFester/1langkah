<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['title' => 'React Hooks Live Session', 'start_date' => now()->startOfMonth()->addDays(2)->setTime(10, 0), 'end_date' => now()->startOfMonth()->addDays(2)->setTime(12, 0), 'type' => 'online', 'slug' => 'react-hooks', 'status' => 'upcoming'],
            ['title' => 'Assignment Due: UI Design', 'start_date' => now()->startOfMonth()->addDays(6)->setTime(23, 59), 'end_date' => now()->startOfMonth()->addDays(6)->setTime(23, 59), 'type' => 'online', 'slug' => 'ui-design-due', 'status' => 'upcoming'],
            ['title' => 'Mentor Session: Rudi', 'start_date' => now()->startOfMonth()->addDays(9)->setTime(14, 0), 'end_date' => now()->startOfMonth()->addDays(9)->setTime(15, 0), 'type' => 'online', 'slug' => 'mentor-rudi', 'status' => 'upcoming'],
            ['title' => 'Bootcamp: Full-Stack #4', 'start_date' => now()->startOfMonth()->addDays(17)->setTime(10, 0), 'end_date' => now()->startOfMonth()->addDays(17)->setTime(13, 0), 'type' => 'online', 'slug' => 'bootcamp-fs', 'status' => 'upcoming'],
            ['title' => 'Community Meetup', 'start_date' => now()->startOfMonth()->addDays(21)->setTime(19, 0), 'end_date' => now()->startOfMonth()->addDays(21)->setTime(21, 0), 'type' => 'offline', 'slug' => 'meetup', 'status' => 'upcoming'],
        ];

        foreach ($events as $e) {
            Event::create($e);
        }
    }
}