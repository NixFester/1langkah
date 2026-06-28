<?php

namespace Database\Seeders;

use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        $rickroll = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';

        $chapters = [
            // Course 1
            ['course_id' => 1, 'title' => 'Pengenalan & Setup Environment', 'lessons' => 5, 'duration' => '2h 30m', 'video_url' => $rickroll],
            ['course_id' => 1, 'title' => 'HTML & CSS Dasar', 'lessons' => 8, 'duration' => '3h 15m', 'video_url' => $rickroll],
            ['course_id' => 1, 'title' => 'JavaScript ES6', 'lessons' => 10, 'duration' => '4h 20m', 'video_url' => $rickroll],
            ['course_id' => 1, 'title' => 'React JS Fundamentals', 'lessons' => 12, 'duration' => '5h 10m', 'video_url' => $rickroll],
            ['course_id' => 1, 'title' => 'Node.js & Express', 'lessons' => 9, 'duration' => '4h 00m', 'video_url' => $rickroll],
            ['course_id' => 1, 'title' => 'Database (MongoDB & SQL)', 'lessons' => 7, 'duration' => '3h 45m', 'video_url' => $rickroll],
            ['course_id' => 1, 'title' => 'Final Project', 'lessons' => 6, 'duration' => '3h 00m', 'video_url' => $rickroll],
            // Course 2
            ['course_id' => 2, 'title' => 'Pengenalan Data Science', 'lessons' => 4, 'duration' => '1h 50m', 'video_url' => $rickroll],
            ['course_id' => 2, 'title' => 'Python & Pandas', 'lessons' => 10, 'duration' => '4h 30m', 'video_url' => $rickroll],
            ['course_id' => 2, 'title' => 'Machine Learning dengan Scikit‑Learn', 'lessons' => 12, 'duration' => '5h 00m', 'video_url' => $rickroll],
            ['course_id' => 2, 'title' => 'Data Visualisasi (Matplotlib, Seaborn)', 'lessons' => 6, 'duration' => '2h 45m', 'video_url' => $rickroll],
            ['course_id' => 2, 'title' => 'Deep Learning & Neural Networks', 'lessons' => 8, 'duration' => '4h 20m', 'video_url' => $rickroll],
            ['course_id' => 2, 'title' => 'Deploy Model ke Production', 'lessons' => 5, 'duration' => '2h 30m', 'video_url' => $rickroll],
            // Course 3
            ['course_id' => 3, 'title' => 'Intro Flutter & Dart', 'lessons' => 6, 'duration' => '2h 20m', 'video_url' => $rickroll],
            ['course_id' => 3, 'title' => 'Widget Dasar & Layout', 'lessons' => 8, 'duration' => '3h 10m', 'video_url' => $rickroll],
            ['course_id' => 3, 'title' => 'State Management (Provider, Riverpod)', 'lessons' => 10, 'duration' => '4h 40m', 'video_url' => $rickroll],
            ['course_id' => 3, 'title' => 'Firebase Auth & Firestore', 'lessons' => 7, 'duration' => '3h 20m', 'video_url' => $rickroll],
            ['course_id' => 3, 'title' => 'Deploy ke Play Store & App Store', 'lessons' => 4, 'duration' => '2h 00m', 'video_url' => $rickroll],
            // Course 4
            ['course_id' => 4, 'title' => 'Dasar Container & Docker', 'lessons' => 6, 'duration' => '2h 40m', 'video_url' => $rickroll],
            ['course_id' => 4, 'title' => 'Docker Compose & Networking', 'lessons' => 8, 'duration' => '3h 30m', 'video_url' => $rickroll],
            ['course_id' => 4, 'title' => 'Kubernetes Introduction', 'lessons' => 7, 'duration' => '3h 00m', 'video_url' => $rickroll],
            ['course_id' => 4, 'title' => 'Deployment & Scaling', 'lessons' => 9, 'duration' => '4h 15m', 'video_url' => $rickroll],
            ['course_id' => 4, 'title' => 'CI/CD dengan GitLab & Jenkins', 'lessons' => 5, 'duration' => '2h 50m', 'video_url' => $rickroll],
            // Course 5
            ['course_id' => 5, 'title' => 'Instalasi Laravel & MVC', 'lessons' => 5, 'duration' => '2h 10m', 'video_url' => $rickroll],
            ['course_id' => 5, 'title' => 'Routing & Controller', 'lessons' => 7, 'duration' => '3h 00m', 'video_url' => $rickroll],
            ['course_id' => 5, 'title' => 'Blade Templating & Components', 'lessons' => 8, 'duration' => '3h 20m', 'video_url' => $rickroll],
            ['course_id' => 5, 'title' => 'Eloquent ORM & Migrations', 'lessons' => 10, 'duration' => '4h 10m', 'video_url' => $rickroll],
            ['course_id' => 5, 'title' => 'Authentication & Authorization', 'lessons' => 6, 'duration' => '2h 40m', 'video_url' => $rickroll],
            ['course_id' => 5, 'title' => 'API Development', 'lessons' => 7, 'duration' => '3h 15m', 'video_url' => $rickroll],
            // Course 6
            ['course_id' => 6, 'title' => 'Prinsip Dasar UI/UX', 'lessons' => 4, 'duration' => '1h 40m', 'video_url' => $rickroll],
            ['course_id' => 6, 'title' => 'Wireframing & Prototyping (Figma)', 'lessons' => 8, 'duration' => '3h 30m', 'video_url' => $rickroll],
            ['course_id' => 6, 'title' => 'Design System & Component Library', 'lessons' => 6, 'duration' => '2h 50m', 'video_url' => $rickroll],
            ['course_id' => 6, 'title' => 'Usability Testing & Feedback', 'lessons' => 5, 'duration' => '2h 20m', 'video_url' => $rickroll],
            // Course 7
            ['course_id' => 7, 'title' => 'React Setup & JSX', 'lessons' => 6, 'duration' => '2h 30m', 'video_url' => $rickroll],
            ['course_id' => 7, 'title' => 'Component & Props', 'lessons' => 8, 'duration' => '3h 15m', 'video_url' => $rickroll],
            ['course_id' => 7, 'title' => 'State & Lifecycle', 'lessons' => 10, 'duration' => '4h 00m', 'video_url' => $rickroll],
            ['course_id' => 7, 'title' => 'Hooks (useState, useEffect, Custom)', 'lessons' => 12, 'duration' => '4h 45m', 'video_url' => $rickroll],
            ['course_id' => 7, 'title' => 'React Router & Redux', 'lessons' => 9, 'duration' => '3h 50m', 'video_url' => $rickroll],
            ['course_id' => 7, 'title' => 'Fetch API & Testing', 'lessons' => 7, 'duration' => '3h 00m', 'video_url' => $rickroll],
            // Course 8
            ['course_id' => 8, 'title' => 'Pengenalan SQL & Database', 'lessons' => 5, 'duration' => '2h 00m', 'video_url' => $rickroll],
            ['course_id' => 8, 'title' => 'SELECT, WHERE, JOIN', 'lessons' => 9, 'duration' => '3h 40m', 'video_url' => $rickroll],
            ['course_id' => 8, 'title' => 'Subqueries & Window Functions', 'lessons' => 7, 'duration' => '3h 10m', 'video_url' => $rickroll],
            ['course_id' => 8, 'title' => 'Database Design & Normalisasi', 'lessons' => 6, 'duration' => '2h 30m', 'video_url' => $rickroll],
            ['course_id' => 8, 'title' => 'Stored Procedures & Triggers', 'lessons' => 5, 'duration' => '2h 15m', 'video_url' => $rickroll],
            // Course 9
            ['course_id' => 9, 'title' => 'Python Dasar', 'lessons' => 7, 'duration' => '2h 50m', 'video_url' => $rickroll],
            ['course_id' => 9, 'title' => 'File I/O & Exception Handling', 'lessons' => 6, 'duration' => '2h 30m', 'video_url' => $rickroll],
            ['course_id' => 9, 'title' => 'Web Scraping dengan BeautifulSoup', 'lessons' => 8, 'duration' => '3h 20m', 'video_url' => $rickroll],
            ['course_id' => 9, 'title' => 'Otomatisasi Excel & Email', 'lessons' => 7, 'duration' => '3h 00m', 'video_url' => $rickroll],
            ['course_id' => 9, 'title' => 'Schedule Script dengan Cron', 'lessons' => 4, 'duration' => '1h 50m', 'video_url' => $rickroll],
            // Course 10
            ['course_id' => 10, 'title' => 'AWS Overview & IAM', 'lessons' => 5, 'duration' => '2h 10m', 'video_url' => $rickroll],
            ['course_id' => 10, 'title' => 'EC2, VPC, dan Networking', 'lessons' => 8, 'duration' => '3h 40m', 'video_url' => $rickroll],
            ['course_id' => 10, 'title' => 'S3, RDS, dan DynamoDB', 'lessons' => 9, 'duration' => '4h 00m', 'video_url' => $rickroll],
            ['course_id' => 10, 'title' => 'Lambda, API Gateway', 'lessons' => 7, 'duration' => '3h 15m', 'video_url' => $rickroll],
            ['course_id' => 10, 'title' => 'CloudFormation & Monitoring', 'lessons' => 6, 'duration' => '2h 50m', 'video_url' => $rickroll],
        ];

        foreach ($chapters as $ch) {
            Chapter::create($ch);
        }
    }
}