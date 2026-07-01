<?php

namespace Database\Seeders;

use App\Models\BootcampSession;
use Illuminate\Database\Seeder;

class BootcampSessionSeeder extends Seeder
{
    public function run(): void
    {
        $sesis = [
            // Bootcamp 101
            ['bootcamp_id' => 101, 'date' => '11 Agu 2025', 'topic' => 'Introduction & Environment Setup', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 101, 'date' => '13 Agu 2025', 'topic' => 'HTML/CSS & Responsive Design', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 101, 'date' => '15 Agu 2025', 'topic' => 'JavaScript & DOM Manipulation', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 101, 'date' => '18 Agu 2025', 'topic' => 'React JS Basic', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 101, 'date' => '20 Agu 2025', 'topic' => 'Node.js & Express', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 101, 'date' => '22 Agu 2025', 'topic' => 'Database & Authentication', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 101, 'date' => '25 Agu 2025', 'topic' => 'Final Project & Deployment', 'time' => '10:00 - 12:00 WIB'],
            // Bootcamp 102
            ['bootcamp_id' => 102, 'date' => '18 Agu 2025', 'topic' => 'Intro to Data Science', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '20 Agu 2025', 'topic' => 'Python for Data Analysis', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '22 Agu 2025', 'topic' => 'Pandas & Data Wrangling', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '25 Agu 2025', 'topic' => 'Machine Learning Algorithms', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '27 Agu 2025', 'topic' => 'Model Evaluation & Tuning', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '29 Agu 2025', 'topic' => 'Data Visualization', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '1 Sep 2025', 'topic' => 'Deployment & Capstone', 'time' => '14:00 - 16:00 WIB'],
            ['bootcamp_id' => 102, 'date' => '3 Sep 2025', 'topic' => 'Final Presentation', 'time' => '14:00 - 16:00 WIB'],
            // Bootcamp 103
            ['bootcamp_id' => 103, 'date' => '25 Agu 2025', 'topic' => 'Dart & Flutter Setup', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 103, 'date' => '27 Agu 2025', 'topic' => 'Widgets & Layout', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 103, 'date' => '29 Agu 2025', 'topic' => 'State Management', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 103, 'date' => '1 Sep 2025', 'topic' => 'Firebase Integration', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 103, 'date' => '3 Sep 2025', 'topic' => 'Deployment & Publishing', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 103, 'date' => '5 Sep 2025', 'topic' => 'Project Review', 'time' => '09:00 - 11:00 WIB'],
            // Bootcamp 104
            ['bootcamp_id' => 104, 'date' => '1 Sep 2025', 'topic' => 'DevOps Culture & Docker', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '3 Sep 2025', 'topic' => 'Kubernetes Fundamentals', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '5 Sep 2025', 'topic' => 'CI/CD with GitLab', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '8 Sep 2025', 'topic' => 'AWS Services for DevOps', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '10 Sep 2025', 'topic' => 'Monitoring & Logging', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '12 Sep 2025', 'topic' => 'Security & Best Practices', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '15 Sep 2025', 'topic' => 'Hands‑on Project', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '17 Sep 2025', 'topic' => 'Project Presentation', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '19 Sep 2025', 'topic' => 'Q&A and Career Tips', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 104, 'date' => '22 Sep 2025', 'topic' => 'Graduation', 'time' => '13:00 - 15:00 WIB'],
            // Offline bootcamps (only a few sesis for example)
            ['bootcamp_id' => 201, 'date' => '10 Agu 2025', 'topic' => 'Pengenalan Laravel & MVC', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 201, 'date' => '11 Agu 2025', 'topic' => 'Routing & Controllers', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 201, 'date' => '12 Agu 2025', 'topic' => 'Blade & Eloquent', 'time' => '09:00 - 11:00 WIB'],
            ['bootcamp_id' => 202, 'date' => '17 Agu 2025', 'topic' => 'Design Thinking', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 202, 'date' => '18 Agu 2025', 'topic' => 'Wireframing & Prototyping', 'time' => '10:00 - 12:00 WIB'],
            ['bootcamp_id' => 203, 'date' => '24 Agu 2025', 'topic' => 'Python & Jupyter', 'time' => '13:00 - 15:00 WIB'],
            ['bootcamp_id' => 203, 'date' => '25 Agu 2025', 'topic' => 'Data Cleaning with Pandas', 'time' => '13:00 - 15:00 WIB'],
        ];

        $sesis = array_map(function (array $session, int $index): array {
            $session['meeting_url'] = $session['meeting_url'] ?? 'https://meet.google.com/abc-defg-hij';
            $session['description'] = $session['description'] ?? 'Sesi ' . ($index + 1) . ' untuk program bootcamp ini.';
            $session['order'] = $session['order'] ?? ($index + 1);

            return $session;
        }, $sesis, array_keys($sesis));

        foreach ($sesis as $s) {
            BootcampSession::updateOrCreate(
                ['bootcamp_id' => $s['bootcamp_id'], 'topic' => $s['topic'], 'date' => $s['date']],
                $s
            );
        }
    }
}