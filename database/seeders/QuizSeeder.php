<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizAnswer;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if quizzes don't exist yet
        if (Quiz::exists()) {
            $this->command->info('Quizzes already exist, skipping...');
            return;
        }

        $this->command->info('Creating quizzes and questions...');

        // ============================================
        // QUIZ 1: Full-Stack Web Development - Pre Test
        // ============================================
        $quiz1 = Quiz::create([
            'course_id' => 100,
            'title' => 'Pre-Test: Full-Stack Web Development',
            'description' => 'Tes pengetahuan awal sebelum memulai kursus Full-Stack Web Development.',
            'type' => 'pre_test',
            'passing_score' => 70,
            'time_limit_minutes' => 30,
            'is_active' => true,
            'order' => 1,
        ]);

        $this->createFullStackPreTestQuestions($quiz1);

        // ============================================
        // QUIZ 2: Full-Stack Web Development - Post Test
        // ============================================
        $quiz2 = Quiz::create([
            'course_id' => 100,
            'title' => 'Post-Test: Full-Stack Web Development',
            'description' => 'Tes pengetahuan setelah menyelesaikan kursus Full-Stack Web Development.',
            'type' => 'post_test',
            'passing_score' => 80,
            'time_limit_minutes' => 45,
            'is_active' => true,
            'order' => 2,
        ]);

        $this->createFullStackPostTestQuestions($quiz2);

        // ============================================
        // QUIZ 3: Data Science - Pre Test
        // ============================================
        $quiz3 = Quiz::create([
            'course_id' => 101,
            'title' => 'Pre-Test: Data Science & ML',
            'description' => 'Tes pengetahuan awal sebelum memulai kursus Data Science.',
            'type' => 'pre_test',
            'passing_score' => 70,
            'time_limit_minutes' => 30,
            'is_active' => true,
            'order' => 1,
        ]);

        $this->createDataSciencePreTestQuestions($quiz3);

        // ============================================
        // QUIZ 4: UI/UX Design - Pre Test
        // ============================================
        $quiz4 = Quiz::create([
            'course_id' => 102,
            'title' => 'Pre-Test: UI/UX Design',
            'description' => 'Tes pengetahuan awal sebelum memulai kursus UI/UX Design.',
            'type' => 'pre_test',
            'passing_score' => 70,
            'time_limit_minutes' => 20,
            'is_active' => true,
            'order' => 1,
        ]);

        $this->createUIUXPreTestQuestions($quiz4);

        $this->command->info('✓ Created ' . Quiz::count() . ' quizzes with questions');
    }

    /**
     * Create Full-Stack Pre Test Questions
     */
    private function createFullStackPreTestQuestions(Quiz $quiz): void
    {
        // Question 1
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa kepanjangan dari HTML?',
            'explanation' => 'HTML stands for HyperText Markup Language, yaitu bahasa markup standar untuk membuat halaman web.',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1,
        ]);

        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'HyperText Markup Language', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'High Tech Modern Language', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Home Tool Markup Language', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Hyper Transfer Markup Language', 'is_correct' => false, 'order' => 4]);

        // Question 2
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'JavaScript adalah bahasa pemrograman yang berjalan di sisi client (browser).',
            'explanation' => 'JavaScript memang berjalan di browser (client-side), tapi dengan Node.js JavaScript juga bisa berjalan di server-side.',
            'type' => 'true_false',
            'points' => 10,
            'order' => 2,
        ]);

        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);

        // Question 3
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa fungsi utama dari CSS?',
            'explanation' => 'CSS (Cascading Style Sheets) digunakan untuk mengatur tampilan dan layout halaman web.',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 3,
        ]);

        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Mengatur styling/tampilan web', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Membuat struktur halaman web', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Mengelola database', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Mengirim email', 'is_correct' => false, 'order' => 4]);

        // Question 4
        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Sebutkan 3 teknologi utama dalam Full-Stack Web Development!',
            'explanation' => 'Jawaban dapat meliputi HTML, CSS, JavaScript untuk frontend, dan berbagai backend technologies.',
            'type' => 'essay',
            'points' => 20,
            'order' => 4,
        ]);

        // Question 5
        $q5 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa perbedaan antara var, let, dan const dalam JavaScript?',
            'explanation' => 'var bersifat function-scoped, let/const bersifat block-scoped, dan const tidak bisa di-reassign.',
            'type' => 'essay',
            'points' => 20,
            'order' => 5,
        ]);
    }

    /**
     * Create Full-Stack Post Test Questions
     */
    private function createFullStackPostTestQuestions(Quiz $quiz): void
    {
        // Question 1
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa fungsi useEffect hook dalam React?',
            'explanation' => 'useEffect digunakan untuk menangani side effects seperti fetching data, subscriptions, atau modifying DOM.',
            'type' => 'multiple_choice',
            'points' => 15,
            'order' => 1,
        ]);

        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Mengelola side effects dalam komponen functional', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Mengubah state komponen', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Membuat komponen baru', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Mengatur routing', 'is_correct' => false, 'order' => 4]);

        // Question 2
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Express.js adalah framework backend untuk Node.js',
            'explanation' => 'Express.js memang adalah framework minimal dan fleksibel untuk Node.js yang digunakan untuk membangun API dan web applications.',
            'type' => 'true_false',
            'points' => 10,
            'order' => 2,
        ]);

        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);

        // Question 3
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa perbedaan antara SQL dan NoSQL database?',
            'explanation' => 'SQL databases menggunakan schema terstruktur dan query language, sementara NoSQL lebih fleksibel dengan document/key-value stores.',
            'type' => 'essay',
            'points' => 25,
            'order' => 3,
        ]);

        // Question 4
        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa itu RESTful API?',
            'explanation' => 'RESTful API adalah architectural style untuk membangun web services yang menggunakan HTTP methods.',
            'type' => 'multiple_choice',
            'points' => 15,
            'order' => 4,
        ]);

        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'API yang mengikuti REST architectural constraints', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'Database NoSQL', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'JavaScript framework', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'CSS preprocessor', 'is_correct' => false, 'order' => 4]);

        // Question 5
        $q5 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Jelaskan konsep Component-Driven Development dalam React!',
            'explanation' => 'Component-Driven Development adalah pendekatan membangun UI dari bawah ke atas, dimulai dari komponen individual.',
            'type' => 'essay',
            'points' => 25,
            'order' => 5,
        ]);
    }

    /**
     * Create Data Science Pre Test Questions
     */
    private function createDataSciencePreTestQuestions(Quiz $quiz): void
    {
        // Question 1
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa kepanjangan dari ML dalam context Data Science?',
            'explanation' => 'ML = Machine Learning, yaitu subset dari AI yang memungkinkan sistem belajar dari data.',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1,
        ]);

        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Machine Learning', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Microsoft Language', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Meta Learning', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Main Library', 'is_correct' => false, 'order' => 4]);

        // Question 2
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Python adalah bahasa pemrograman yang populer untuk Data Science.',
            'explanation' => 'Python memang sangat populer di Data Science karena ecosystem libraries seperti Pandas, NumPy, dan scikit-learn.',
            'type' => 'true_false',
            'points' => 10,
            'order' => 2,
        ]);

        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);

        // Question 3
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa library Python yang paling populer untuk manipulasi data?',
            'explanation' => 'Pandas adalah library Python yang populer untuk manipulasi dan analisis data tabular.',
            'type' => 'multiple_choice',
            'points' => 15,
            'order' => 3,
        ]);

        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Pandas', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Django', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Flask', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q3->id, 'answer_text' => 'Pygame', 'is_correct' => false, 'order' => 4]);

        // Question 4
        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Jelaskan perbedaan antara supervised dan unsupervised learning!',
            'explanation' => 'Supervised learning menggunakan labeled data untuk training, unsupervised menemukan pattern tanpa labels.',
            'type' => 'essay',
            'points' => 25,
            'order' => 4,
        ]);
    }

    /**
     * Create UI/UX Pre Test Questions
     */
    private function createUIUXPreTestQuestions(Quiz $quiz): void
    {
        // Question 1
        $q1 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa kepanjangan dari UX?',
            'explanation' => 'UX = User Experience, yaitu pengalaman pengguna saat menggunakan produk.',
            'type' => 'multiple_choice',
            'points' => 10,
            'order' => 1,
        ]);

        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'User Experience', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'User Extension', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Universal Exchange', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q1->id, 'answer_text' => 'Unique Experience', 'is_correct' => false, 'order' => 4]);

        // Question 2
        $q2 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Figma adalah tools untuk membuat wireframes dan prototypes.',
            'explanation' => 'Figma adalah collaborative interface design tool yang digunakan untuk membuat wireframes, prototypes, dan design systems.',
            'type' => 'true_false',
            'points' => 10,
            'order' => 2,
        ]);

        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q2->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);

        // Question 3
        $q3 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa perbedaan antara UI dan UX design?',
            'explanation' => 'UI fokus pada visual dan interaksi, UX mencakup keseluruhan pengalaman pengguna.',
            'type' => 'essay',
            'points' => 30,
            'order' => 3,
        ]);

        // Question 4
        $q4 = QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => 'Apa itu Design System?',
            'explanation' => 'Design System adalah kumpulan komponen dan guidelines yang dapat digunakan ulang untuk konsistensi desain.',
            'type' => 'multiple_choice',
            'points' => 20,
            'order' => 4,
        ]);

        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'Kumpulan reusable components dan guidelines', 'is_correct' => true, 'order' => 1]);
        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'Database untuk menyimpan desain', 'is_correct' => false, 'order' => 2]);
        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'Software untuk rendering 3D', 'is_correct' => false, 'order' => 3]);
        QuizAnswer::create(['question_id' => $q4->id, 'answer_text' => 'Framework untuk backend', 'is_correct' => false, 'order' => 4]);
    }
}
