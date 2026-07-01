<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Bootcamp;
use App\Models\Chapter;
use App\Models\Enrollment;
use App\Models\ChapterProgress;
use App\Models\SessionProgress;
use App\Models\UserSkill;
use App\Models\CourseRating;
use App\Models\Completion;
use App\Models\UserActivityLog;
use Illuminate\Database\Seeder;

class UserProgressSeeder extends Seeder
{
    public function run(): void
    {
        // Get test user
        $testUser = User::where('email', 'test@email.com')->first();
        if (!$testUser) {
            echo "Test user not found. Run UserSeeder first.\n";
            return;
        }

        // Clear existing user data
        Enrollment::where('user_id', $testUser->id)->delete();
        ChapterProgress::where('user_id', $testUser->id)->delete();
        SessionProgress::where('user_id', $testUser->id)->delete();
        UserSkill::where('user_id', $testUser->id)->delete();
        CourseRating::where('user_id', $testUser->id)->delete();
        Completion::where('user_id', $testUser->id)->delete();
        UserActivityLog::where('user_id', $testUser->id)->delete();

        // Get all courses and bootcamps
        $courses = Course::all();
        $bootcamps = Bootcamp::all();

        // ============================================
        // ENROLLMENT: Enroll in some courses
        // ============================================

        $enrolledCourses = $courses->take(4);
        foreach ($enrolledCourses as $course) {
            Enrollment::create([
                'user_id' => $testUser->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $course->id,
                'status' => 'active',
                'is_following' => false,
                'followed_at' => null,
                'completed_at' => null,
            ]);
        }

        // Enroll in one bootcamp
        $offlineBootcamp = $bootcamps->where('type', 'offline')->first();
        if ($offlineBootcamp) {
            Enrollment::create([
                'user_id' => $testUser->id,
                'purchasable_type' => Bootcamp::class,
                'purchasable_id' => $offlineBootcamp->id,
                'status' => 'active',
                'is_following' => false,
                'followed_at' => null,
                'completed_at' => null,
            ]);
        }

        // ============================================
        // CHAPTER PROGRESS: Mark some chapters as clicked/watched (external links)
        // ============================================

        foreach ($enrolledCourses as $course) {
            $chapters = Chapter::where('course_id', $course->id)->get();

            if ($chapters->isNotEmpty()) {
                // Progress varies by course (50%, 75%, 100%, 25%)
                $courseIndex = $enrolledCourses->search($course);
                $progressPercent = [50, 75, 100, 25][$courseIndex] ?? 50;
                $chaptersToComplete = ceil($chapters->count() * ($progressPercent / 100));

                for ($i = 0; $i < $chaptersToComplete && $i < $chapters->count(); $i++) {
                    $chapter = $chapters[$i];
                    $isComplete = $progressPercent >= 50 && $i === ($chaptersToComplete - 1);

                    ChapterProgress::create([
                        'user_id' => $testUser->id,
                        'chapter_id' => $chapter->id,
                        'is_completed' => $isComplete,
                        'started_at' => now()->subDays(rand(1, 30)),
                        'last_watched_at' => now()->subDays(rand(0, 5)),
                        'completed_at' => $isComplete ? now()->subDays(rand(0, 3)) : null,
                    ]);
                }

                // Mark course as completed if 100%
                if ($progressPercent >= 100) {
                    Completion::create([
                        'user_id' => $testUser->id,
                        'completable_type' => Course::class,
                        'completable_id' => $course->id,
                        'completed_at' => now()->subDays(rand(1, 10)),
                    ]);
                }
            }
        }

        // ============================================
        // SESSION PROGRESS: Mark some bootcamp sessions
        // ============================================

        if ($offlineBootcamp) {
            $sessions = $offlineBootcamp->sessions;

            if ($sessions->isNotEmpty()) {
                // Complete 3 out of 5 sessions
                $sessionsToComplete = min(3, $sessions->count());
                for ($i = 0; $i < $sessionsToComplete; $i++) {
                    SessionProgress::create([
                        'user_id' => $testUser->id,
                        'bootcamp_session_id' => $sessions[$i]->id,
                        'clicked_at' => now()->subDays(rand(1, 20)),
                        'completed' => true,
                    ]);
                }
            }
        }

        // ============================================
        // RATINGS: Rate some completed courses
        // ============================================

        // Get completed courses
        $completedCourses = Completion::where('user_id', $testUser->id)
            ->where('completable_type', Course::class)
            ->get();

        foreach ($completedCourses as $completion) {
            CourseRating::create([
                'user_id' => $testUser->id,
                'course_id' => $completion->completable_id,
                'rating' => rand(4, 5), // 4 or 5 stars
                'review_text' => 'Kursus yang sangat bermanfaat!',
            ]);
        }

        // ============================================
        // USER SKILLS: Track skills learned
        // ============================================

        $skills = [
            ['name' => 'JavaScript', 'rating' => 4.5],
            ['name' => 'React', 'rating' => 4.0],
            ['name' => 'Node.js', 'rating' => 3.5],
            ['name' => 'Python', 'rating' => 4.8],
            ['name' => 'Data Science', 'rating' => 4.2],
        ];

        $completedCourseIds = $completedCourses->pluck('completable_id')->toArray();

        foreach ($skills as $index => $skill) {
            $sourceType = $index < 3 ? 'course' : 'bootcamp';
            $sourceId = $sourceType === 'course'
                ? ($completedCourseIds[0] ?? $courses->first()->id)
                : ($offlineBootcamp->id ?? $bootcamps->first()->id);

            UserSkill::create([
                'user_id' => $testUser->id,
                'skill_name' => $skill['name'],
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'rating' => $skill['rating'],
            ]);
        }

        // ============================================
        // ACTIVITY LOGS: Log some activities
        // ============================================

        $activities = [
            ['type' => Course::class, 'action' => 'enrolled'],
            ['type' => Course::class, 'action' => 'started'],
            ['type' => Course::class, 'action' => 'watched'],
            ['type' => Course::class, 'action' => 'completed'],
            ['type' => Course::class, 'action' => 'rated'],
            ['type' => Bootcamp::class, 'action' => 'enrolled'],
            ['type' => Bootcamp::class, 'action' => 'attended'],
        ];

        foreach ($activities as $index => $activity) {
            $source = $activity['type'] === Course::class
                ? $courses->random()
                : $bootcamps->random();

            UserActivityLog::create([
                'user_id' => $testUser->id,
                'loggable_type' => $activity['type'],
                'loggable_id' => $source->id,
                'action' => $activity['action'],
                'metadata' => [
                    'source' => $activity['type'] === Course::class ? 'course' : 'bootcamp',
                    'action' => $activity['action'],
                ],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder/1.0',
                'created_at' => now()->subDays(rand(0, 14)),
            ]);
        }

        // Update user bio
        $testUser->bio = 'Full-Stack Developer enthusiast | Passionate about learning new technologies | Building web apps with React and Node.js';
        $testUser->save();

        echo "Test user progress seeded successfully!\n";
        echo "- Enrolled in {$enrolledCourses->count()} courses\n";
        echo "- Enrolled in 1 bootcamp\n";
        echo "- Completed {$completedCourses->count()} course(s)\n";
        echo "- Rated {$completedCourses->count()} course(s)\n";
        echo "- Learned " . count($skills) . " skills\n";
        echo "- Logged " . count($activities) . " activities\n";
    }
}
