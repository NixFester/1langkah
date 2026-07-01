<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            OptionSeeder::class,
            UserSeeder::class,
            MentorSeeder::class,
            CourseSeeder::class,
            BootcampSeeder::class,
            ChapterSeeder::class,
            BootcampSessionSeeder::class,
            PictureSeeder::class,
            EventSeeder::class,
            UserActivityLogSeeder::class,
            UserProgressSeeder::class,
            BootcampRatingSeeder::class,
            FlowTestSeeder::class,
        ]);
    }
}