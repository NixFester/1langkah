<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        QuizQuestion::truncate();
        QuizAnswer::truncate();
        Quiz::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('Creating quizzes and questions...');

        $courses = Course::pluck('id', 'title')->toArray();

        $this->command->info('Found ' . count($courses) . ' courses');

        // Quiz templates for each course type
        $quizTemplates = [
            'Full‑Stack Web Development Bootcamp' => [
                'pre' => [
                    'title' => 'Pre-Test: Full-Stack Web Development',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus Full-Stack Web Development.',
                    'passing_score' => 70,
                    'time_limit_minutes' => 30,
                ],
                'post' => [
                    'title' => 'Post-Test: Full-Stack Web Development',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus Full-Stack Web Development.',
                    'passing_score' => 80,
                    'time_limit_minutes' => 45,
                ],
            ],
            'Data Science & Machine Learning Masterclass' => [
                'pre' => [
                    'title' => 'Pre-Test: Data Science & ML',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus Data Science.',
                    'passing_score' => 70,
                    'time_limit_minutes' => 30,
                ],
                'post' => [
                    'title' => 'Post-Test: Data Science & ML',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus Data Science.',
                    'passing_score' => 80,
                    'time_limit_minutes' => 45,
                ],
            ],
            'Flutter & Firebase – Build Real Apps' => [
                'pre' => [
                    'title' => 'Pre-Test: Flutter & Firebase',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus Flutter.',
                    'passing_score' => 60,
                    'time_limit_minutes' => 25,
                ],
                'post' => [
                    'title' => 'Post-Test: Flutter & Firebase',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus Flutter.',
                    'passing_score' => 75,
                    'time_limit_minutes' => 40,
                ],
            ],
            'DevOps with Docker & Kubernetes' => [
                'pre' => [
                    'title' => 'Pre-Test: DevOps',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus DevOps.',
                    'passing_score' => 65,
                    'time_limit_minutes' => 30,
                ],
                'post' => [
                    'title' => 'Post-Test: DevOps',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus DevOps.',
                    'passing_score' => 80,
                    'time_limit_minutes' => 45,
                ],
            ],
            'Laravel 11 – Web Development Mudah' => [
                'pre' => [
                    'title' => 'Pre-Test: Laravel',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus Laravel.',
                    'passing_score' => 60,
                    'time_limit_minutes' => 25,
                ],
                'post' => [
                    'title' => 'Post-Test: Laravel',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus Laravel.',
                    'passing_score' => 75,
                    'time_limit_minutes' => 40,
                ],
            ],
            'UI/UX Design for Mobile & Web' => [
                'pre' => [
                    'title' => 'Pre-Test: UI/UX Design',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus UI/UX Design.',
                    'passing_score' => 60,
                    'time_limit_minutes' => 20,
                ],
                'post' => [
                    'title' => 'Post-Test: UI/UX Design',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus UI/UX Design.',
                    'passing_score' => 75,
                    'time_limit_minutes' => 35,
                ],
            ],
            'React JS – Dari Nol hingga Expert' => [
                'pre' => [
                    'title' => 'Pre-Test: React JS',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus React.',
                    'passing_score' => 65,
                    'time_limit_minutes' => 25,
                ],
                'post' => [
                    'title' => 'Post-Test: React JS',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus React.',
                    'passing_score' => 80,
                    'time_limit_minutes' => 40,
                ],
            ],
            'SQL for Data Analysis' => [
                'pre' => [
                    'title' => 'Pre-Test: SQL for Data Analysis',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus SQL.',
                    'passing_score' => 60,
                    'time_limit_minutes' => 20,
                ],
                'post' => [
                    'title' => 'Post-Test: SQL for Data Analysis',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus SQL.',
                    'passing_score' => 75,
                    'time_limit_minutes' => 35,
                ],
            ],
            'Python for Automation & Scripting' => [
                'pre' => [
                    'title' => 'Pre-Test: Python Automation',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus Python.',
                    'passing_score' => 60,
                    'time_limit_minutes' => 20,
                ],
                'post' => [
                    'title' => 'Post-Test: Python Automation',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus Python.',
                    'passing_score' => 75,
                    'time_limit_minutes' => 35,
                ],
            ],
            'Cloud Computing with AWS' => [
                'pre' => [
                    'title' => 'Pre-Test: Cloud Computing',
                    'description' => 'Tes pengetahuan awal sebelum memulai kursus AWS.',
                    'passing_score' => 65,
                    'time_limit_minutes' => 30,
                ],
                'post' => [
                    'title' => 'Post-Test: Cloud Computing',
                    'description' => 'Tes pengetahuan setelah menyelesaikan kursus AWS.',
                    'passing_score' => 80,
                    'time_limit_minutes' => 45,
                ],
            ],
        ];

        $quizCount = 0;
        foreach ($courses as $courseTitle => $courseId) {
            if (!isset($quizTemplates[$courseTitle])) {
                $this->command->info("Skipping: '$courseTitle' not in templates");
                continue;
            }

            $this->command->info("Creating quiz for: '$courseTitle' (ID: $courseId)");

            $template = $quizTemplates[$courseTitle];

            // Create Pre-Test
            $preQuiz = Quiz::create([
                'course_id' => $courseId,
                'title' => $template['pre']['title'],
                'description' => $template['pre']['description'],
                'type' => 'pre_test',
                'passing_score' => $template['pre']['passing_score'],
                'time_limit_minutes' => $template['pre']['time_limit_minutes'],
                'is_active' => true,
                'order' => 1,
            ]);
            $this->createGenericPreTestQuestions($preQuiz, $courseTitle);
            $quizCount++;

            // Create Post-Test
            $postQuiz = Quiz::create([
                'course_id' => $courseId,
                'title' => $template['post']['title'],
                'description' => $template['post']['description'],
                'type' => 'post_test',
                'passing_score' => $template['post']['passing_score'],
                'time_limit_minutes' => $template['post']['time_limit_minutes'],
                'is_active' => true,
                'order' => 2,
            ]);
            $this->createGenericPostTestQuestions($postQuiz, $courseTitle);
            $quizCount++;
        }

        $this->command->info('✓ Created ' . $quizCount . ' quizzes with questions');
    }

    private function createGenericPreTestQuestions(Quiz $quiz, string $courseTitle): void
    {
        // Question 1 - Multiple Choice
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa pemahaman Anda tentang topik kursus ini?',
            'explanation' => 'Pre-test ini untuk mengukur pengetahuan awal Anda.',
            'type' => 'multiple_choice',
            'points' => 25,
            'order' => 1,
        ]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Sangat baik, sudah punya pengalaman', 'is_correct' => false, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Cukup baik, pernah belajar sedikit', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Kurang, baru pertama kali belajar', 'is_correct' => true, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Tidak tahu sama sekali', 'is_correct' => false, 'order' => 4]);

        // Question 2 - True/False
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Saya sudah memiliki pengetahuan dasar tentang topik ini.',
            'explanation' => 'Pertanyaan ini untuk mengukur pengalaman awal.',
            'type' => 'true_false',
            'points' => 25,
            'order' => 2,
        ]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);

        // Question 3 - Multiple Choice
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa tujuan utama Anda mengikuti kursus ini?',
            'explanation' => 'Untuk memahami motivasi belajar Anda.',
            'type' => 'multiple_choice',
            'points' => 25,
            'order' => 3,
        ]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Untuk karir profesional', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Untuk hobi/personal', 'is_correct' => true, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Untuk tugas sekolah/kuliah', 'is_correct' => true, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Lainnya', 'is_correct' => true, 'order' => 4]);

        // Question 4 - Essay
        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Ceritakan pengalaman Anda sebelumnya dengan topik kursus ini (jika ada).',
            'explanation' => 'Jawaban akan membantu mentor memahami level Anda.',
            'type' => 'essay',
            'points' => 25,
            'order' => 4,
        ]);
    }

    private function createGenericPostTestQuestions(Quiz $quiz, string $courseTitle): void
    {
        // Question 1 - Multiple Choice
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa konsep utama yang telah Anda pelajari di kursus ini?',
            'explanation' => 'Post-test untuk mengukur pemahaman konsep kursus.',
            'type' => 'multiple_choice',
            'points' => 20,
            'order' => 1,
        ]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Konsep dasar dan fundamental', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Teknik lanjutan', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Best practices industri', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Semua jawaban benar', 'is_correct' => true, 'order' => 4]);

        // Question 2 - True/False
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Kursus ini telah memberikan pengetahuan yang saya harapkan.',
            'explanation' => 'Untuk evaluasi kepuasan belajar.',
            'type' => 'true_false',
            'points' => 20,
            'order' => 2,
        ]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);

        // Question 3 - Multiple Choice
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa skill praktis yang dapat Anda terapkan setelah kursus ini?',
            'explanation' => 'Untuk mengukur kesiapan implementasi.',
            'type' => 'multiple_choice',
            'points' => 20,
            'order' => 3,
        ]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Mampu membuat project sendiri', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Bisa memecahkan masalah terkait topik', 'is_correct' => true, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Siap untuk interview kerja', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Semua jawaban benar', 'is_correct' => true, 'order' => 4]);

        // Question 4 - Essay
        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Jelaskan bagaimana Anda akan menerapkan ilmu dari kursus ini dalam proyek nyata.',
            'explanation' => 'Untuk mengukur kemampuan aplikasi ilmu.',
            'type' => 'essay',
            'points' => 20,
            'order' => 4,
        ]);

        // Question 5 - Essay
        $q5 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa topik atau area yang menurut Anda perlu dipelajari lebih lanjut?',
            'explanation' => 'Untuk membantu pengembangan kurikulum di masa depan.',
            'type' => 'essay',
            'points' => 20,
            'order' => 5,
        ]);
    }
}
