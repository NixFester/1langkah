<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizTestSeeder extends Seeder
{
    public function run(): void
    {
        // Create Google email student & standard test student without triggering achievement observers
        $student = User::withoutEvents(function () {
            User::updateOrCreate(
                ['email' => 'student@email.com'],
                [
                    'name' => 'Budi Santoso',
                    'password' => bcrypt('password'),
                    'role' => 'student',
                ]
            );

            return User::updateOrCreate(
                ['email' => 'student@gmail.com'],
                [
                    'name' => 'Budi Santoso',
                    'password' => bcrypt('password'),
                    'role' => 'student',
                ]
            );
        });

        $course = Course::first() ?? Course::create([
            'title' => 'Kursus Pengembangan Diri & Karir',
            'mentor_name' => 'Mentor',
            'mentor_company' => '1Langkah',
            'category' => 'Psychology',
            'level' => 'Beginner',
            'price' => '0',
            'status' => 'approved',
        ]);

        Enrollment::withoutEvents(function () use ($student, $course) {
            Enrollment::firstOrCreate([
                'user_id' => $student->id,
                'purchasable_type' => Course::class,
                'purchasable_id' => $course->id,
            ]);
        });

        // Create/Update Global Psychology Test (20 Questions, 10 Free Preview, 1 Minute Timer, Rp 30.000 Unlock)
        $quiz = Quiz::updateOrCreate(
            ['id' => 1],
            [
                'course_id' => $course->id,
                'is_global' => true,
                'title' => 'Tes Psikologi & Kepribadian Global 1Langkah',
                'description' => 'Tes evaluasi profil kepribadian, gaya pengambilan keputusan, dan adaptabilitas untuk seluruh siswa. Mode Preview membuka 10 dari 20 soal (Batas Waktu Testing: 1 Menit). Buka rangkuman kepribadian penuh & 20 soal dengan pembayaran Rp 30.000.',
                'type' => 'pre_test',
                'passing_score' => 70,
                'time_limit_minutes' => 1,
                'free_question_limit' => 10,
                'unlock_price' => 30000,
                'is_active' => true,
                'order' => 1,
            ]
        );

        // Rebuild 20 psychology questions
        $quiz->questions()->delete();

        $psychologyQuestions = [
            ['Saat menghadapi tenggat waktu (deadline) yang sangat mendesak, langkah pertama yang Anda ambil adalah...', 'Membuat daftar prioritas utama dan langsung mengeksekusi tugas terpenting', 'Menunggu arahan detail dari rekan atau atasan terlebih dahulu'],
            ['Ketika terjadi perbedaan pendapat di dalam tim kerja, Anda cenderung...', 'Mencari titik tengah berbasis data dan tujuan bersama', 'Menghindari diskusi agar suasana tetap tenang'],
            ['Bagaimana cara Anda merespons perubahan rencana secara mendadak?', 'Segera menyesuaikan strategi dan mencari peluang baru dari perubahan tersebut', 'Merasa kurang nyaman dan butuh waktu lama untuk beradaptasi'],
            ['Dalam menyelesaikan masalah yang kompleks, pendekatan yang paling menggambarkan diri Anda adalah...', 'Memecah masalah menjadi bagian-bagian logis yang terukur', 'Mengandalkan perasaan sesaat tanpa membuat perencanaan'],
            ['Saat menerima kritik terhadap hasil pekerjaan Anda, reaksi Anda adalah...', 'Mengevaluasi poin kritik secara objektif untuk perbaikan', 'Merasa tersinggung dan mempertahankan cara lama'],
            ['Ketika bekerja dalam proyek baru yang belum pernah Anda tangani sebelumnya, Anda akan...', 'Aktif mempelajari referensi dan mencoba pendekatan terstruktur', 'Menunda pengerjaan sampai ada orang lain yang memberi contoh'],
            ['Bagaimana Anda mengelola energi saat harus menyelesaikan banyak tugas sekaligus?', 'Mengatur blok waktu fokus dan meminimalkan distraksi', 'Mengerjakan semuanya secara acak bersamaan'],
            ['Dalam mengambil keputusan penting, faktor utama yang Anda pertimbangkan adalah...', 'Kombinasi analisis dampak jangka panjang dan efisiensi eksekusi', 'Pilihan yang paling mudah dilakukan saat itu juga'],
            ['Saat melihat rekan tim mengalami kesulitan menyelesaikan tugasnya, Anda...', 'Menawarkan bantuan terarah tanpa mengabaikan tanggung jawab utama Anda', 'Membiarkannya karena bukan bagian dari tugas Anda'],
            ['Apa yang paling memotivasi Anda dalam mencapai target pribadi maupun profesional?', 'Pertumbuhan kompetensi diri dan dampak nyata dari hasil kerja', 'Sekadar menggugurkan kewajiban rutin'],
            ['[Soal Full Akses #11] Ketika rencana utama gagal di tengah jalan, seberapa cepat Anda menyusun Rencana B?', 'Langsung melakukan evaluasi cepat dan menyiapkan alternatif saat itu juga', 'Berhenti sejenak dalam waktu lama karena kehilangan motivasi'],
            ['[Soal Full Akses #12] Dalam lingkungan kerja dengan tekanan tinggi, bagaimana Anda menjaga stabilitas emosi?', 'Fokus pada hal yang bisa dikendalikan dan menjaga komunikasi tetap jernih', 'Mudah terbawa suasana panik di sekitar'],
            ['[Soal Full Akses #13] Bagaimana gaya komunikasi Anda saat harus menyampaikan ide baru kepada banyak orang?', 'Terstruktur, menggunakan contoh konkret, dan terbuka terhadap masukan', 'Menyampaikan secara singkat tanpa argumen pendukung'],
            ['[Soal Full Akses #14] Ketika dihadapkan pada dua pilihan berisiko, Anda cenderung...', 'Mengukur rasio risiko dan manfaat secara terukur sebelum melangkah', 'Menghindari pengambilan keputusan sama sekali'],
            ['[Soal Full Akses #15] Seberapa sering Anda melakukan refleksi terhadap pencapaian mingguan Anda?', 'Secara rutin untuk memastikan progres tetap selaras dengan tujuan', 'Jarang sekali melakukan evaluasi diri'],
            ['[Soal Full Akses #16] Saat memimpin sebuah inisiatif, gaya kepemimpinan yang Anda terapkan adalah...', 'Memberikan kejelasan visi sekaligus memberdayakan anggota tim', 'Mengatur seluruh detail sendiri tanpa percaya pada tim'],
            ['[Soal Full Akses #17] Bagaimana sikap Anda terhadap standar kualitas pekerjaan?', 'Memastikan kualitas optimal dengan tetap memperhatikan ketepatan waktu', 'Asal selesai cepat meskipun banyak kesalahan'],
            ['[Soal Full Akses #18] Ketika menemukan informasi baru yang bertentangan dengan asumsi awal Anda, Anda...', 'Bersedia memperbarui pandangan berdasarkan bukti yang lebih valid', 'Tetap bertahan pada asumsi lama'],
            ['[Soal Full Akses #19] Bagaimana cara Anda membangun kepercayaan dengan rekan atau klien baru?', 'Menjaga konsistensi antara komitmen yang diucapkan dan hasil nyata', 'Memberikan janji berlebihan di awal'],
            ['[Soal Full Akses #20] Dalam jangka panjang, bagaimana Anda melihat perkembangan potensi diri Anda?', 'Sebagai proses belajar berkelanjutan yang selalu terbuka pada tantangan baru', 'Merasa sudah cukup dengan kemampuan yang dimiliki saat ini'],
        ];

        foreach ($psychologyQuestions as $idx => $item) {
            $q = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $item[0],
                'type' => 'multiple_choice',
                'points' => 5,
                'is_required' => true,
                'order' => $idx + 1,
            ]);

            QuizAnswer::create([
                'question_id' => $q->id,
                'answer_text' => $item[1],
                'is_correct' => true,
                'order' => 1,
            ]);

            QuizAnswer::create([
                'question_id' => $q->id,
                'answer_text' => $item[2],
                'is_correct' => false,
                'order' => 2,
            ]);
        }

        $this->command?->info('Tes Psikologi Global (20 soal, Preview 10 soal, Timer 1 menit, Rp 30.000) berhasil disiapkan! Quiz ID: '.$quiz->id);
    }
}
