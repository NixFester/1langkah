<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Bootcamp;
use App\Models\BootcampSession;
use App\Models\Chapter;
use App\Models\Enrollment;
use App\Models\ChapterProgress;
use App\Models\SessionProgress;
use App\Models\AttendanceRecord;
use App\Models\UserSkill;
use App\Models\CourseRating;
use App\Models\BootcampRating;
use App\Models\Completion;
use App\Models\UserActivityLog;
use App\Models\Option;
use Illuminate\Database\Seeder;

/**
 * FlowTestSeeder - Seeds data for testing the complete user flow
 *
 * Test Scenarios:
 * 1. Guest browsing (courses/bootcamps visible)
 * 2. Login required for payment
 * 3. Payment creates enrollment
 * 4. Course: Enrolled sees full resources, non-enrolled sees first 2
 * 5. Online Bootcamp: Enrolled sees passwords, clicks sessions
 * 6. Offline Bootcamp: Enrolled gets ticket code, scanned attendance
 * 7. Progress tracking in dashboard
 * 8. Portfolio shows completed items
 */
class FlowTestSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting Flow Test Seeder...');

        // Create test users
        $testUsers = $this->createTestUsers();

        // Create courses with resources (for testing resource preview)
        $courses = $this->createCoursesWithResources();

        // Create bootcamps (online & offline)
        $bootcamps = $this->createBootcamps();

        // Create chapters with video URLs
        $this->createChapters($courses);

        // Create bootcamp sessions with meeting URLs & passwords
        $this->createBootcampSessions($bootcamps);

        // ============================================
        // TEST USER 1: Active learner with progress
        // Tests: Dashboard stats, progress tracking, portfolio
        // ============================================
        $activeUser = $testUsers['active_learner'];
        $this->seedActiveLearner($activeUser, $courses, $bootcamps);

        // ============================================
        // TEST USER 2: New user, just enrolled
        // Tests: Payment flow, enrollment, first steps
        // ============================================
        $newUser = $testUsers['new_user'];
        $this->seedNewUser($newUser, $courses, $bootcamps);

        // ============================================
        // TEST USER 3: Guest-like user (not enrolled anywhere)
        // Tests: Preview mode (first 2 resources visible)
        // ============================================
        $guestLikeUser = $testUsers['guest_user'];

        // ============================================
        // Update options for demo data
        // ============================================
        $this->updateOptions();

        $this->command->info('');
        $this->command->info('✅ Flow Test Seeder completed!');
        $this->command->info('');
        $this->printTestCredentials();
    }

    /**
     * Create test users
     */
    private function createTestUsers(): array
    {
        $users = [
            'active_learner' => [
                'name' => 'Budi Santoso',
                'email' => 'budi@test.com',
                'password' => bcrypt('test'),
                'role' => 'student',
                'bio' => 'Software Developer | Learning Full-Stack Development | Based in Jakarta',
            ],
            'new_user' => [
                'name' => 'Siti Rahayu',
                'email' => 'siti@test.com',
                'password' => bcrypt('test'),
                'role' => 'student',
                'bio' => 'Fresh graduate interested in Data Science',
            ],
            'guest_user' => [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@test.com',
                'password' => bcrypt('test'),
                'role' => 'student',
                'bio' => 'Exploring different tech courses',
            ],
        ];

        $created = [];
        foreach ($users as $key => $data) {
            $created[$key] = User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
            $this->command->info("  ✓ Created user: {$data['email']}");
        }

        return $created;
    }

    /**
     * Create courses with resources (including external URLs)
     */
    private function createCoursesWithResources(): \Illuminate\Database\Eloquent\Collection
    {
        $coursesData = [
            [
                'title' => 'Full-Stack Web Development',
                'description' => 'Kuasai skill Full-Stack dari HTML hingga deployment. Include React, Node.js, dan database.',
                'short_description' => 'Jadi Full-Stack Developer dalam 8 minggu',
                'mentor_name' => 'Rudi Yesaya',
                'mentor_company' => 'Google',
                'category' => 'Programming',
                'level' => 'Intermediate',
                'badge' => 'Bestseller',
                'rating' => 4.9,
                'students_count' => 12400,
                'price' => 'Rp 799.000',
                'color' => '#667eea',
                'resources' => [
                    ['name' => '📺 Video Tutorial: Intro to Full-Stack', 'type' => 'YouTube', 'url' => 'https://www.youtube.com/watch?v=E4WlUXrJgy4'],
                    ['name' => '📖 Materi: Web Development Guide', 'type' => 'Wikipedia', 'url' => 'https://en.wikipedia.org/wiki/Web_development'],
                    ['name' => '📚 E-Book: JavaScript Fundamentals', 'type' => 'PDF', 'url' => 'https://storage.1langkah.id/ebooks/fullstack-webdev.pdf'],
                    ['name' => '🔗 Cheat Sheet: React & Node.js', 'type' => 'External Link', 'url' => 'https://github.com/1langkah/cheatsheets'],
                ],
            ],
            [
                'title' => 'Data Science & Machine Learning',
                'description' => 'Dari nol sampai bisa bikin model ML yang siap production. Studi kasus dari industri e-commerce.',
                'short_description' => 'Jadilah Data Scientist dalam 10 minggu',
                'mentor_name' => 'Dewi Lestari',
                'mentor_company' => 'Tokopedia',
                'category' => 'Data Science',
                'level' => 'Intermediate',
                'badge' => 'Popular',
                'rating' => 4.8,
                'students_count' => 8900,
                'price' => 'Rp 899.000',
                'color' => '#f093fb',
                'resources' => [
                    ['name' => '📺 Video Tutorial: Data Science Intro', 'type' => 'YouTube', 'url' => 'https://www.youtube.com/watch?v=E4WlUXrJgy4'],
                    ['name' => '📖 Materi: Machine Learning Guide', 'type' => 'Wikipedia', 'url' => 'https://en.wikipedia.org/wiki/Machine_learning'],
                    ['name' => '📊 Dataset Latihan untuk Praktik', 'type' => 'ZIP', 'url' => 'https://storage.1langkah.id/datasets/ml-practice.zip'],
                    ['name' => '📓 Notebook: Jupyter Lab Starter', 'type' => 'Colab', 'url' => 'https://colab.research.google.com/github/example'],
                ],
            ],
            [
                'title' => 'UI/UX Design Mastery',
                'description' => 'Pelajari prinsip desain, Figma, dan buat portofolio desain yang impressive.',
                'short_description' => 'Desain UI/UX profesional',
                'mentor_name' => 'Nadya Ramadhani',
                'mentor_company' => 'Shopee',
                'category' => 'Design',
                'level' => 'Beginner',
                'badge' => 'New',
                'rating' => 4.7,
                'students_count' => 4200,
                'price' => 'Rp 549.000',
                'color' => '#ffecd2',
                'resources' => [
                    ['name' => '📺 Video Tutorial: UI/UX Design Basics', 'type' => 'YouTube', 'url' => 'https://www.youtube.com/watch?v=E4WlUXrJgy4'],
                    ['name' => '📖 Materi: User Experience Design', 'type' => 'Wikipedia', 'url' => 'https://en.wikipedia.org/wiki/User_experience_design'],
                    ['name' => '🎨 Figma Starter Kit Template', 'type' => 'Figma', 'url' => 'https://figma.com/community/file/design-starter'],
                    ['name' => '🖼️ Design System Components', 'type' => 'Figma', 'url' => 'https://figma.com/community/file/design-system'],
                ],
            ],
        ];

        $courses = new \Illuminate\Database\Eloquent\Collection();
        foreach ($coursesData as $index => $data) {
            $course = Course::updateOrCreate(
                ['id' => $index + 100],
                array_merge($data, [
                    'benefits' => json_encode([
                        'Sertifikat completion',
                        'Akses seumur hidup',
                        '10+ proyek nyata',
                        'Mentoring 1-on-1',
                    ]),
                    'curriculum' => json_encode([
                        'Modul 1: Pengenalan',
                        'Modul 2: Konsep Dasar',
                        'Modul 3: Studi Kasus',
                        'Modul 4: Project Akhir',
                    ]),
                    'resources' => json_encode($data['resources']),
                ])
            );
            $courses->push($course);
            $this->command->info("  ✓ Created course: {$course->title}");
        }

        return $courses;
    }

    /**
     * Create bootcamps (online & offline)
     */
    private function createBootcamps(): \Illuminate\Database\Eloquent\Collection
    {
        $bootcampsData = [
            // Online Bootcamps
            [
                'id' => 301,
                'title' => 'Full-Stack Web Development Bootcamp',
                'type' => 'online',
                'mentor_name' => 'Rudi Yesaya',
                'participants' => 35,
                'start_date' => '2026-07-15 10:00:00',
                'sessions_info' => '8 sesi LIVE via Zoom',
                'price' => 'Rp 6.500.000',
                'color' => '#667eea',
            ],
            [
                'id' => 302,
                'title' => 'Data Science Bootcamp',
                'type' => 'online',
                'mentor_name' => 'Dewi Lestari',
                'participants' => 25,
                'start_date' => '2026-07-20 14:00:00',
                'sessions_info' => '10 sesi LIVE via Zoom',
                'price' => 'Rp 7.200.000',
                'color' => '#f093fb',
            ],
            // Offline Bootcamps
            [
                'id' => 401,
                'title' => 'Laravel Web Developer (Jakarta)',
                'type' => 'offline',
                'mentor_name' => 'Ahmad Fauzi',
                'participants' => 15,
                'start_date' => '2026-07-10 09:00:00',
                'sessions_info' => '8x pertemuan',
                'price' => 'Rp 4.500.000',
                'color' => '#fa709a',
                'location' => 'Jakarta Selatan, Co-working Space',
                'benefits' => json_encode([
                    'Sertifikat completion',
                    'Materi lengkap (PDF)',
                    'Lunch & coffee break',
                    'Networking dengan peserta lain',
                ]),
                'jadwal_kelas' => json_encode([
                    ['hari' => 'Senin', 'waktu' => '18:00 - 21:00', 'topik' => 'Pengenalan Laravel'],
                    ['hari' => 'Rabu', 'waktu' => '18:00 - 21:00', 'topik' => 'Routing & Controllers'],
                    ['hari' => 'Jumat', 'waktu' => '18:00 - 21:00', 'topik' => 'Blade Templating'],
                ]),
            ],
        ];

        $bootcamps = new \Illuminate\Database\Eloquent\Collection();
        foreach ($bootcampsData as $data) {
            $bootcamp = Bootcamp::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            $bootcamps->push($bootcamp);
            $this->command->info("  ✓ Created bootcamp: {$bootcamp->title} ({$bootcamp->type})");
        }

        return $bootcamps;
    }

    /**
     * Create chapters for courses
     */
    private function createChapters($courses): void
    {
        $chapterTemplates = [
            [
                'title' => 'Pengenalan & Setup Environment',
                'lessons' => 5,
                'duration' => '2h 30m',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Install semua tools yang diperlukan untuk development',
            ],
            [
                'title' => 'Konsep Dasar & Fundamental',
                'lessons' => 8,
                'duration' => '3h 15m',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Pelajari konsep dasar yang akan digunakan throughout the course',
            ],
            [
                'title' => 'Studi Kasus & Praktik',
                'lessons' => 10,
                'duration' => '4h 20m',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Apply pengetahuan dengan studi kasus nyata',
            ],
            [
                'title' => 'Project Akhir & Deployment',
                'lessons' => 6,
                'duration' => '3h 00m',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'description' => 'Build dan deploy project akhir kamu',
            ],
        ];

        foreach ($courses as $courseIndex => $course) {
            foreach ($chapterTemplates as $index => $template) {
                Chapter::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'title' => $template['title'],
                    ],
                    array_merge($template, [
                        'order' => $index + 1,
                        'thumbnail_url' => 'https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=800&q=80',
                    ])
                );
            }
            $this->command->info("  ✓ Created chapters for course: {$course->title}");
        }
    }

    /**
     * Create bootcamp sessions with meeting URLs & passwords
     */
    private function createBootcampSessions($bootcamps): void
    {
        $onlineBootcamps = $bootcamps->where('type', 'online');
        $offlineBootcamps = $bootcamps->where('type', 'offline');

        // Online bootcamp sessions
        foreach ($onlineBootcamps as $bootcamp) {
            $sessionCount = $bootcamp->id == 301 ? 8 : 10;
            for ($i = 1; $i <= $sessionCount; $i++) {
                $week = ceil($i / 2);
                $day = $i % 2 == 0 ? 'Kamis' : 'Selasa';

                BootcampSession::updateOrCreate(
                    [
                        'bootcamp_id' => $bootcamp->id,
                        'topic' => "Week {$week} - Session {$i}",
                        'date' => "2026-07-" . str_pad(($bootcamp->id == 301 ? 15 : 18) + ($i * 2), 2, '0', STR_PAD_LEFT),
                    ],
                    [
                        'time' => $bootcamp->id == 301 ? '10:00 - 12:00 WIB' : '14:00 - 16:00 WIB',
                        'meeting_url' => "https://zoom.us/j/{$bootcamp->id}{$i}" . str_repeat('1', 6),
                        'description' => "Sesi LIVE {$i} dari {$sessionCount}",
                        'order' => $i,
                    ]
                );
            }
            $this->command->info("  ✓ Created {$sessionCount} sessions for online bootcamp: {$bootcamp->title}");
        }

        // Offline bootcamp sessions
        foreach ($offlineBootcamps as $bootcamp) {
            $sessions = json_decode($bootcamp->jadwal_kelas ?? '[]', true) ?: [];
            $dateStart = 10;
            foreach ($sessions as $index => $session) {
                $sessionTopic = $session['topik'] ?? "Session " . ($index + 1);
                BootcampSession::updateOrCreate(
                    [
                        'bootcamp_id' => $bootcamp->id,
                        'topic' => $sessionTopic,
                    ],
                    [
                        'date' => "2026-07-" . str_pad((string) ($dateStart + ($index * 2)), 2, '0', STR_PAD_LEFT),
                        'time' => $session['waktu'] ?? '09:00 - 12:00 WIB',
                        'description' => "Pertemuan " . ($index + 1),
                        'order' => $index + 1,
                    ]
                );
            }
            $this->command->info("  ✓ Created sessions for offline bootcamp: {$bootcamp->title}");
        }
    }

    /**
     * Seed active learner with full progress
     */
    private function seedActiveLearner(User $user, $courses, $bootcamps): void
    {
        $this->command->info('');
        $this->command->info('📚 Seeding Active Learner: ' . $user->email);

        // Clear existing progress
        $this->clearUserProgress($user);

        // ============================================
        // ENROLLMENTS
        // ============================================

        // Enroll in courses
        $enrolledCourses = $courses->take(2);
        foreach ($enrolledCourses as $course) {
            $enrollment = Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'purchasable_type' => Course::class,
                    'purchasable_id' => $course->id,
                ],
                ['status' => 'active']
            );
            $this->command->info("    ✓ Enrolled in course: {$course->title} (ticket: {$enrollment->ticket_code})");
        }

        // Enroll in online bootcamp
        $onlineBootcamp = $bootcamps->where('type', 'online')->first();
        if ($onlineBootcamp) {
            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'purchasable_type' => Bootcamp::class,
                    'purchasable_id' => $onlineBootcamp->id,
                ],
                ['status' => 'active']
            );
            $this->command->info("    ✓ Enrolled in online bootcamp: {$onlineBootcamp->title}");
        }

        // Enroll in offline bootcamp (will auto-generate ticket_code)
        $offlineBootcamp = $bootcamps->where('type', 'offline')->first();
        if ($offlineBootcamp) {
            $enrollment = Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'purchasable_type' => Bootcamp::class,
                    'purchasable_id' => $offlineBootcamp->id,
                ],
                ['status' => 'active']
            );
            $this->command->info("    ✓ Enrolled in offline bootcamp: {$offlineBootcamp->title}");
            $this->command->info("      → Ticket Code: {$enrollment->ticket_code}");
        }

        // ============================================
        // CHAPTER PROGRESS (External link clicks)
        // ============================================
        foreach ($enrolledCourses as $course) {
            $chapters = Chapter::where('course_id', $course->id)->get();

            // Mark first 2 chapters as watched
            foreach ($chapters->take(2) as $index => $chapter) {
                ChapterProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'chapter_id' => $chapter->id,
                    ],
                    [
                        'is_completed' => $index === 0, // First chapter is completed
                        'watch_duration' => rand(300, 3600),
                        'last_position' => rand(0, 1800),
                        'started_at' => now()->subDays(rand(3, 10)),
                        'completed_at' => $index === 0 ? now()->subDays(rand(1, 5)) : null,
                        'last_watched_at' => now()->subDays(rand(0, 2)),
                    ]
                );
                $this->command->info("    ✓ Watched chapter: {$chapter->title}");
            }

            // Mark course as completed for first course
            if ($course->id === $enrolledCourses->first()->id) {
                Completion::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'completable_type' => Course::class,
                        'completable_id' => $course->id,
                    ],
                    ['completed_at' => now()->subDays(3)]
                );
                $this->command->info("    ✓ Course COMPLETED: {$course->title}");

                // Rate the completed course
                CourseRating::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'course_id' => $course->id,
                    ],
                    [
                        'rating' => 5,
                        'review_text' => 'Kursus yang sangat lengkap dan praktis! Mentor juga sangat helpful.',
                    ]
                );
                $this->command->info("    ✓ Rated course: {$course->title} (5 stars)");
            }
        }

        // ============================================
        // SESSION PROGRESS (Online bootcamp - clicked meeting links)
        // ============================================
        if ($onlineBootcamp) {
            $sessions = BootcampSession::where('bootcamp_id', $onlineBootcamp->id)
                ->orderBy('order')
                ->get();

            // Mark first 3 sessions as clicked (accessed meeting)
            foreach ($sessions->take(3) as $index => $session) {
                SessionProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'bootcamp_session_id' => $session->id,
                    ],
                    [
                        'clicked_at' => now()->subDays(rand(1, 14)),
                        'completed' => $index < 2, // First 2 are completed
                    ]
                );
                $this->command->info("    ✓ Session clicked: {$session->topic}");
            }
        }

        // ============================================
        // ATTENDANCE RECORDS (Offline bootcamp - scanned QR)
        // ============================================
        if ($offlineBootcamp) {
            $offlineSessions = BootcampSession::where('bootcamp_id', $offlineBootcamp->id)->get();

            // Create attendance records for first 2 sessions
            foreach ($offlineSessions->take(2) as $index => $session) {
                $qrCode = "OFFLINE_{$offlineBootcamp->id}_SESSION_{$session->id}_USER_{$user->id}";

                AttendanceRecord::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'bootcamp_id' => $offlineBootcamp->id,
                        'attendance_date' => now()->subDays(2 - $index)->toDateString(),
                    ],
                    [
                        'qr_code' => $qrCode,
                        'verified' => true,
                        'scanned_at' => now()->subDays(2 - $index),
                    ]
                );
                $this->command->info("    ✓ Attendance scanned: {$session->topic}");
            }
        }

        // ============================================
        // USER SKILLS
        // ============================================
        $skills = [
            ['name' => 'JavaScript', 'rating' => 4.5],
            ['name' => 'React', 'rating' => 4.0],
            ['name' => 'Node.js', 'rating' => 3.5],
            ['name' => 'Laravel', 'rating' => 4.2],
        ];

        foreach ($skills as $skill) {
            UserSkill::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'skill_name' => $skill['name'],
                ],
                [
                    'source_type' => 'course',
                    'source_id' => $enrolledCourses->first()->id ?? 100,
                    'rating' => $skill['rating'],
                ]
            );
        }
        $this->command->info("    ✓ Learned " . count($skills) . " skills");

        // ============================================
        // ACTIVITY LOGS
        // ============================================
        $activities = [
            ['type' => Course::class, 'id' => $enrolledCourses->first()->id ?? 100, 'action' => 'enrolled'],
            ['type' => Course::class, 'id' => $enrolledCourses->first()->id ?? 100, 'action' => 'started'],
            ['type' => Course::class, 'id' => $enrolledCourses->first()->id ?? 100, 'action' => 'watched'],
            ['type' => Course::class, 'id' => $enrolledCourses->first()->id ?? 100, 'action' => 'completed'],
            ['type' => Bootcamp::class, 'id' => $onlineBootcamp->id ?? 301, 'action' => 'enrolled'],
        ];

        foreach ($activities as $activity) {
            UserActivityLog::create([
                'user_id' => $user->id,
                'loggable_type' => $activity['type'],
                'loggable_id' => $activity['id'],
                'action' => $activity['action'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'FlowTestSeeder/1.0',
                'created_at' => now()->subDays(rand(0, 14)),
            ]);
        }
        $this->command->info("    ✓ Logged " . count($activities) . " activities");
    }

    /**
     * Seed new user with minimal progress
     */
    private function seedNewUser(User $user, $courses, $bootcamps): void
    {
        $this->command->info('');
        $this->command->info('🆕 Seeding New User: ' . $user->email);

        // Clear existing progress
        $this->clearUserProgress($user);

        // Just enrolled in one course
        $course = $courses->first();
        if ($course) {
            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'purchasable_type' => Course::class,
                    'purchasable_id' => $course->id,
                ],
                ['status' => 'active']
            );
            $this->command->info("    ✓ Just enrolled in: {$course->title}");

            // Started first chapter
            $firstChapter = Chapter::where('course_id', $course->id)->first();
            if ($firstChapter) {
                ChapterProgress::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'chapter_id' => $firstChapter->id,
                    ],
                    [
                        'is_completed' => false,
                        'watch_duration' => rand(60, 600),
                        'last_position' => rand(0, 300),
                        'started_at' => now()->subHours(rand(2, 48)),
                        'last_watched_at' => now()->subHours(rand(1, 24)),
                    ]
                );
                $this->command->info("    ✓ Started first chapter: {$firstChapter->title}");
            }
        }

        // Log enrollment activity
        UserActivityLog::create([
            'user_id' => $user->id,
            'loggable_type' => Course::class,
            'loggable_id' => $course->id ?? 100,
            'action' => 'enrolled',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'FlowTestSeeder/1.0',
        ]);
    }

    /**
     * Clear user progress data
     */
    private function clearUserProgress(User $user): void
    {
        Enrollment::where('user_id', $user->id)->delete();
        ChapterProgress::where('user_id', $user->id)->delete();
        SessionProgress::where('user_id', $user->id)->delete();
        AttendanceRecord::where('user_id', $user->id)->delete();
        UserSkill::where('user_id', $user->id)->delete();
        CourseRating::where('user_id', $user->id)->delete();
        BootcampRating::where('user_id', $user->id)->delete();
        Completion::where('user_id', $user->id)->delete();
        UserActivityLog::where('user_id', $user->id)->delete();
    }

    /**
     * Update options for demo
     */
    private function updateOptions(): void
    {
        // Option model uses key/label/category instead of key/value
        // Skipping for flow test - options are seeded by OptionSeeder
    }

    /**
     * Print test credentials
     */
    private function printTestCredentials(): void
    {
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('                    🧪 TEST CREDENTIALS');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('  📧 Active Learner (Full Progress):');
        $this->command->info('     Email: budi@test.com');
        $this->command->info('     Pass:  password123');
        $this->command->info('     → Has enrollments, progress, completed courses');
        $this->command->info('');
        $this->command->info('  📧 New User (Just Enrolled):');
        $this->command->info('     Email: siti@test.com');
        $this->command->info('     Pass:  password123');
        $this->command->info('     → Just enrolled, started first chapter');
        $this->command->info('');
        $this->command->info('  📧 Guest-like User (No Enrollments):');
        $this->command->info('     Email: ahmad@test.com');
        $this->command->info('     Pass:  password123');
        $this->command->info('     → Can preview first 2 resources in courses');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('  🔗 Test URLs:');
        $this->command->info('     http://localhost:8000/kursus');
        $this->command->info('     http://localhost:8000/bootcamp/online');
        $this->command->info('     http://localhost:8000/bootcamp/offline');
        $this->command->info('     http://localhost:8000/portofolio (after login)');
        $this->command->info('');
    }
}
