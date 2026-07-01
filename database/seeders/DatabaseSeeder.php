<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BootcampRatingSeeder::class,
            BootcampSeeder::class,
            BootcampSessionSeeder::class,
            ChapterSeeder::class,
            CourseSeeder::class,
            EventSeeder::class,
            FlowTestSeeder::class,
            MentorSeeder::class,
            OptionSeeder::class,
            PictureSeeder::class,
            QuizSeeder::class,
            UserSeeder::class,
            UserActivityLogSeeder::class,
            UserProgressSeeder::class,
        ]);
    }
}