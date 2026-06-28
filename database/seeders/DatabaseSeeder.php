<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MentorSeeder::class,
            CourseSeeder::class,
            BootcampSeeder::class,
            ChapterSeeder::class,
            BootcampSessionSeeder::class,
            PictureSeeder::class,
        ]);
    }
}