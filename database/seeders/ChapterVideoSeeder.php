<?php

namespace Database\Seeders;

use App\Models\Chapter;
use App\Models\ChapterVideo;
use Illuminate\Database\Seeder;

class ChapterVideoSeeder extends Seeder
{
    public function run(): void
    {
        $baseThumbnail = 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=800&q=80';

        Chapter::with('course')->get()->each(function (Chapter $chapter) use ($baseThumbnail) {
            $lessons = $chapter->lessons ?? 3;
            $course = $chapter->course;
            $courseTitle = $course?->title ?? 'Kursus';
            $chapterTitle = $chapter->title;

            // Lesson name prefixes vary by course type
            $prefixes = $this->getPrefixes($courseTitle);
            $durations = $this->getDurations($lessons);

            foreach (range(1, $lessons) as $i) {
                $lessonNum = $i + 1;
                $title = sprintf('%s %s', $prefixes[$i % count($prefixes)], $lessonNum);
                $duration = $durations[$i % count($durations)];
                $thumbnail = $baseThumbnail;

                ChapterVideo::updateOrCreate(
                    [
                        'chapter_id' => $chapter->id,
                        'order' => $lessonNum,
                    ],
                    [
                        'title' => "$chapterTitle — $title",
                        'video_url' => "https://www.youtube.com/watch?v=E4WlUXrJgy4",
                        'thumbnail_url' => $thumbnail,
                        'duration' => $duration,
                        'description' => "Video pembelajaran $title untuk chapter \"$chapterTitle\" dalam kursus $courseTitle.",
                    ]
                );
            }
        });
    }

    /**
     * Get lesson title prefixes based on course category
     */
    private function getPrefixes(string $courseTitle): array
    {
        $lower = strtolower($courseTitle);

        // Full-Stack / Web Development
        if (str_contains($lower, 'full') || str_contains($lower, 'web')) {
            return [
                'Pengenalan',
                'Demo & Contoh',
                'Praktik Langsung',
                'Studi Kasus',
                'Tips & Best Practice',
                'Error Handling',
                'Refactoring',
                'Optimization',
            ];
        }

        // Data Science / ML / SQL
        if (str_contains($lower, 'data') || str_contains($lower, 'sql') || str_contains($lower, 'machine')) {
            return [
                'Teori & Konsep',
                'Setup Environment',
                'Hands-on Demo',
                'Analisis Data',
                'Visualisasi Hasil',
                'Interpretasi Output',
                'Studi Kasus Nyata',
                'Q&A & Review',
            ];
        }

        // Flutter / Mobile
        if (str_contains($lower, 'flutter') || str_contains($lower, 'mobile')) {
            return [
                'Widget Intro',
                'Coding Demo',
                'Build & Run',
                'State Management',
                'Navigation',
                'Testing',
                'Debugging',
                'Deployment',
            ];
        }

        // DevOps / Docker / Kubernetes / AWS / Cloud
        if (str_contains($lower, 'devops') || str_contains($lower, 'docker') ||
            str_contains($lower, 'kubernetes') || str_contains($lower, 'aws') ||
            str_contains($lower, 'cloud')) {
            return [
                'Konsep Dasar',
                'Setup & Config',
                'Demo Live',
                'Troubleshooting',
                'Production Tips',
                'Monitoring',
                'Security Checklist',
                'Automation',
            ];
        }

        // Laravel / Backend
        if (str_contains($lower, 'laravel') || str_contains($lower, 'api')) {
            return [
                'Routing & Controller',
                'Model & Migration',
                'View & Blade',
                'Form & Validation',
                'Authentication',
                'API Development',
                'Testing',
                'Deployment',
            ];
        }

        // UI/UX Design
        if (str_contains($lower, 'ui') || str_contains($lower, 'ux') || str_contains($lower, 'design')) {
            return [
                'Teori Desain',
                'Wireframing',
                'Mockup Demo',
                'Color & Typography',
                'Prototyping',
                'User Testing',
                'Iterasi Design',
                'Handoff to Dev',
            ];
        }

        // React / Frontend
        if (str_contains($lower, 'react') || str_contains($lower, 'javascript')) {
            return [
                'Setup Project',
                'Component Basics',
                'Props & State',
                'Hooks Deep Dive',
                'Event Handling',
                'API Integration',
                'Performance Tuning',
                'Project Walkthrough',
            ];
        }

        // Python / Automation
        if (str_contains($lower, 'python') || str_contains($lower, 'automation')) {
            return [
                'Dasar Pemrograman',
                'Script Walkthrough',
                'Demo Execution',
                'Error Handling',
                'File Processing',
                'Schedule Setup',
                'Logging & Monitoring',
                'Use Case Real',
            ];
        }

        // Default
        return [
            'Pengenalan',
            'Demo Praktik',
            'Latihan',
            'Studi Kasus',
            'Tips & Trick',
            'Review Materi',
            'Q&A Session',
            'Summary',
        ];
    }

    /**
     * Get realistic durations for lesson videos
     */
    private function getDurations(int $lessonIndex): array
    {
        $allDurations = [
            '8:30', '12:15', '15:45', '10:00', '18:20',
            '14:10', '20:30', '9:45', '11:00', '16:30',
            '7:50', '13:25', '22:10', '6:40', '17:05',
        ];

        // Rotate based on lesson index so adjacent lessons don't all have the same duration
        $offset = ($lessonIndex % 3) * 5;
        $rotated = array_merge(array_slice($allDurations, $offset), array_slice($allDurations, 0, $offset));

        return $rotated;
    }
}
