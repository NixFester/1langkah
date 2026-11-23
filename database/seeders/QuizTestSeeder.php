<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Test Students with Google Accounts (@gmail.com)
        $student = User::withoutEvents(function () {
            User::updateOrCreate(
                ['email' => 'student@email.com'],
                [
                    'name' => 'Budi Santoso',
                    'phone' => '081234567890',
                    'birth_place' => 'Jakarta',
                    'birth_date' => '2001-05-14',
                    'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
                    'purpose' => 'Pengembangan Karir & Kepemimpinan',
                    'password' => bcrypt('password'),
                    'role' => 'student',
                ]
            );

            return User::updateOrCreate(
                ['email' => 'student@gmail.com'],
                [
                    'name' => 'Budi Santoso',
                    'phone' => '081234567890',
                    'birth_place' => 'Jakarta',
                    'birth_date' => '2001-05-14',
                    'address' => 'Jl. Sudirman No. 45, Jakarta Selatan',
                    'purpose' => 'Pengembangan Karir & Kepemimpinan',
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

        // 2. Create/Update Global Psychology Test (20 Questions, 10 Free Preview, 1 Minute Timer, Rp 30.000 Unlock)
        $quiz = Quiz::updateOrCreate(
            ['id' => 1],
            [
                'course_id' => $course->id,
                'is_global' => true,
                'title' => 'Tes Psikologi & Pemetaan 4 Kepribadian Global 1Langkah',
                'description' => 'Temukan arketipe kepribadian dominan Anda: The Strategic Architect, The Visionary Catalyst, The Empathetic Guardian, atau The Dynamic Explorer. Mode Preview membuka 10 dari 20 soal (Batas Waktu Testing: 1 Menit). Buka analisis superpower, blind spots, rekomendasi karir, dan seluruh 20 soal dengan pembayaran Rp 30.000.',
                'type' => 'pre_test',
                'passing_score' => 70,
                'time_limit_minutes' => 1,
                'free_question_limit' => 10,
                'unlock_price' => 30000,
                'is_active' => true,
                'order' => 1,
            ]
        );

        // 3. Rebuild 20 Psychology Questions — Each question has 4 choices mapped to the 4 Personality Archetypes:
        // Option 1 (order 1) -> The Strategic Architect (Logika & Sistem)
        // Option 2 (order 2) -> The Visionary Catalyst (Inovasi & Visi)
        // Option 3 (order 3) -> The Empathetic Guardian (Empati & Harmoni)
        // Option 4 (order 4) -> The Dynamic Explorer (Taktis & Eksekusi Cepat)
        $quiz->questions()->delete();

        $psychologyQuestions = [
            [
                'Saat menghadapi proyek baru dengan tenggat waktu (deadline) yang sangat mepet, reaksi pertama Anda adalah...',
                'Menyusun blueprint, memecah jadwal kerja secara sistematis, dan menetapkan urutan prioritas logis',
                'Mencari terobosan kreatif atau konsep segar yang bisa membuat hasil proyek tampil beda',
                'Memastikan seluruh anggota tim memahami peran masing-masing dan merasa didukung',
                'Langsung terjun mengeksekusi tugas paling krusial di lapangan sambil beradaptasi di jalan',
            ],
            [
                'Ketika terjadi perdebatan sengit di dalam rapat tim, peran yang paling sering Anda ambil adalah...',
                'Menyajikan data objektif dan fakta terukur untuk menguji argumen mana yang paling rasional',
                'Menawarkan sudut pandang baru (out-of-the-box) yang menyatukan visi jangka panjang',
                'Menjadi jembatan penengah agar setiap orang merasa didengar dan suasana tetap kondusif',
                'Mendorong tim segera mengambil keputusan praktis agar waktu tidak habis untuk berdebat',
            ],
            [
                'Apa yang paling membuat Anda merasa puas setelah menyelesaikan sebuah pekerjaan besar?',
                'Sistem yang saya rancang berjalan rapi, bebas dari celah kesalahan, dan sangat efisien',
                'Ide orisinal yang saya gagas berhasil membawa perubahan baru yang menginspirasi banyak orang',
                'Melihat rekan tim dan orang lain terbantu serta tumbuh bersama lewat kolaborasi tersebut',
                'Berhasil menaklukkan tantangan sulit secara cepat dengan hasil konkret yang langsung terlihat',
            ],
            [
                'Jika rencana utama tiba-tiba gagal karena faktor tak terduga, langkah Anda adalah...',
                'Menganalisis akar masalah (root cause) dan mengaktifkan skenario cadangan (Plan B) yang sudah disiapkan',
                'Melihat kegagalan tersebut sebagai pintu masuk untuk bereksperimen dengan pendekatan yang sama sekali baru',
                'Menenangkan anggota tim yang panik dan menjaga semangat kolektif agar tidak saling menyalahkan',
                'Bereaksi instan memanfaatkan sumber daya apa pun yang ada di depan mata untuk menyelamatkan situasi',
            ],
            [
                'Dalam memilih lingkungan belajar atau bekerja, suasana seperti apa yang paling mengeluarkan potensi terbaik Anda?',
                'Lingkungan yang terstruktur jelas, tenang, dan menghargai ketelitian serta kedalaman analisis',
                'Lingkungan yang dinamis, bebas bereksplorasi, dan sangat terbuka pada ide-ide liar',
                'Lingkungan yang hangat, suportif, penuh rasa percaya, dan mengutamakan kerja sama',
                'Lingkungan yang bergerak cepat (fast-paced), penuh aksi nyata, dan minim birokrasi berbelit',
            ],
            [
                'Bagaimana cara Anda mempelajari sebuah keahlian atau teknologi yang belum pernah Anda kuasai?',
                'Membaca dokumentasi secara menyeluruh dan mempelajari struktur fundamentalnya dari awal',
                'Membayangkan berbagai proyek inovatif yang bisa diciptakan dengan keahlian baru tersebut',
                'Berdiskusi dan belajar bersama mentor atau komunitas agar bisa saling bertukar pengalaman',
                'Langsung mempraktikkannya lewat trial-and-error (learning by doing) tanpa banyak teori',
            ],
            [
                'Saat seseorang meminta saran kepada Anda mengenai masalah pribadinya atau karirnya, Anda cenderung...',
                'Membantunya memetakan pro-kontra dan menyusun langkah solusi yang paling logis',
                'Memberikan perspektif segar dan membuka peluang-peluang masa depan yang belum terpikirkan olehnya',
                'Mendengarkan dengan penuh empati dan memvalidasi perasaannya terlebih dahulu sebelum memberi masukan',
                'Memberikan saran praktis yang bisa langsung ia lakukan hari itu juga untuk mengubah keadaan',
            ],
            [
                'Apa kelemahan (blind spot) yang terkadang Anda sadari saat sedang berada di bawah tekanan tinggi?',
                'Menjadi terlalu perfeksionis, kaku pada aturan sistem, dan kritis terhadap kesalahan kecil',
                'Terlalu banyak memikirkan ide baru hingga fokus pada detail penyelesaian akhir sedikit terpecah',
                'Terlalu memikirkan perasaan orang lain hingga sulit berkata "tidak" atau menegur secara tegas',
                'Bertindak terlalu cepat secara impulsif tanpa sempat mendokumentasikan prosesnya',
            ],
            [
                'Ketika harus mempresentasikan sebuah proposal di depan pengambil keputusan, kekuatan utama Anda adalah...',
                'Data yang akurat, alur logika yang tak terbantahkan, dan proyeksi risiko yang matang',
                'Storytelling yang memikat, visi masa depan yang besar, dan antusiasme yang menular',
                'Ketulusan komunikasi, pemahaman mendalam tentang dampak kemanusiaan, dan keterbukaan',
                'Contoh demo nyata, bukti hasil di lapangan, dan kesiapan menjawab pertanyaan taktis secara spontan',
            ],
            [
                'Jika Anda diberi kebebasan memimpin sebuah organisasi, budaya utama yang ingin Anda bangun adalah...',
                'Budaya keunggulan berbasis standar kualitas tinggi, kejelasan sistem, dan akuntabilitas data',
                'Budaya inovasi tanpa henti yang berani mendobrak batas dan menciptakan tren baru',
                'Budaya kekeluargaan yang empatik, inklusif, dan fokus pada kesejahteraan setiap individu',
                'Budaya ketangkasan (agility) yang cepat mengambil keputusan dan fokus pada pencapaian target nyata',
            ],
            [
                '[Soal Full Akses #11] Bagaimana cara Anda menilai keberhasilan sebuah keputusan sulit?',
                'Keputusan tersebut terbukti konsisten dengan kerangka logika dan menghasilkan efisiensi jangka panjang',
                'Keputusan tersebut membuka horison pertumbuhan baru yang belum pernah dicapai sebelumnya',
                'Keputusan tersebut membawa dampak positif bagi kesejahteraan bersama dan menjaga keutuhan tim',
                'Keputusan tersebut berhasil menyelesaikan krisis secara cepat dan tepat sasaran di lapangan',
            ],
            [
                '[Soal Full Akses #12] Saat jadwal harian Anda penuh dengan banyak agenda sekaligus, strategi Anda adalah...',
                'Menggunakan matriks prioritas ketat dan menjadwalkan blok waktu secara presisi',
                'Memfokuskan energi terbaik pada agenda yang paling strategis dan mendelegasikan sisanya',
                'Berkoordinasi dengan rekan terkait agar beban kerja terbagi secara adil dan selaras',
                'Menyelesaikan satu per satu dengan ritme cepat dan fleksibel mengikuti urgensi di lapangan',
            ],
            [
                '[Soal Full Akses #13] Ketika menerima kritik tajam atas karya atau pekerjaan Anda, respons batin Anda adalah...',
                'Membedah isi kritik tersebut secara objektif: bagian mana yang valid secara logika untuk diperbaiki',
                'Menggunakan kritik tersebut sebagai bahan bakar untuk menciptakan versi karya yang jauh lebih revolusioner',
                'Mererenungkan maksud baik di balik kritik tersebut sambil menjaga hubungan baik dengan pemberi kritik',
                'Langsung memperbaiki bagian yang dikritik saat itu juga tanpa berlarut-larut memikirkannya',
            ],
            [
                '[Soal Full Akses #14] Dalam sebuah tim lintas disiplin, kontribusi unik yang selalu dicari orang dari Anda adalah...',
                'Kemampuan merancang struktur sistem dan menemukan celah kesalahan yang terlewat oleh orang lain',
                'Kemampuan mencetuskan konsep kreatif dan menyalakan semangat ketika tim sedang buntu',
                'Kemampuan membaca dinamika psikologis tim dan menjaga komunikasi tetap cair',
                'Kemampuan mengeksekusi tugas sulit dengan cepat ketika orang lain masih ragu-ragu',
            ],
            [
                '[Soal Full Akses #15] Bagaimana pandangan Anda terhadap aturan dan prosedur standar (SOP)?',
                'SOP sangat penting sebagai fondasi keteraturan sistem agar kualitas selalu terjaga',
                'SOP adalah titik awal yang harus terus diperbarui dan ditantang agar tidak menghambat inovasi',
                'SOP harus diterapkan secara bijaksana dengan tetap mempertimbangkan kondisi manusiawi di dalamnya',
                'SOP berguna sebagai panduan dasar, namun di lapangan kita harus siap berimprovisasi secara taktis',
            ],
            [
                '[Soal Full Akses #16] Apa yang paling menguras energi mental Anda dalam bekerja?',
                'Kekacauan tanpa arah, keputusan yang tidak logis, dan kerja yang tidak terorganisir',
                'Rutinitas monoton yang berulang setiap hari tanpa ruang untuk berkreasi',
                'Lingkungan kerja yang penuh konflik politik, egois, dan tidak saling menghargai',
                'Rapat teoretis berjam-jam yang tidak menghasilkan tindakan nyata',
            ],
            [
                '[Soal Full Akses #17] Saat memilih partner kolaborasi, tipe rekan seperti apa yang paling melengkapi Anda?',
                'Rekan yang komunikatif dan fleksibel dalam menerjemahkan sistem saya ke lapangan',
                'Rekan yang teliti dan terstruktur dalam mengawal detail eksekusi dari ide-ide besar saya',
                'Rekan yang tegas dan strategis dalam menetapkan target bisnis',
                'Rekan yang sabar menyusun perencanaan jangka panjang dan dokumentasi',
            ],
            [
                '[Soal Full Akses #18] Ketika melihat sebuah tren perubahan besar di industri Anda, langkah Anda adalah...',
                'Menganalisis pola datanya terlebih dahulu untuk memastikan apakah tren tersebut berkelanjutan',
                'Menjadi pionir pertama yang mengadopsi dan menciptakan inovasi baru di atas tren tersebut',
                'Memikirkan bagaimana tren tersebut dapat membantu mempermudah kehidupan banyak orang',
                'Langsung menguji coba alat/tren tersebut secara praktis untuk melihat kegunaan nyatanya',
            ],
            [
                '[Soal Full Akses #19] Bagaimana cara Anda memulihkan fokus ketika merasa jenuh (burnout)?',
                'Menyendiri dengan tenang untuk merapikan pikiran, membaca, atau menganalisis hal yang menarik',
                'Mencari inspirasi baru, berdiskusi tentang ide masa depan, atau mencoba hobi kreatif',
                'Berbagi cerita secara hangat dengan orang-orang terdekat yang memberi dukungan emosional',
                'Melakukan aktivitas fisik, perjalanan singkat, atau tantangan baru yang menyegarkan adrenalin',
            ],
            [
                '[Soal Full Akses #20] Lima tahun dari sekarang, Anda ingin dikenal sebagai sosok profesional yang...',
                'Ahli strategi dan perancang sistem andal yang karyanya menjadi standar keunggulan industri',
                'Pemimpin visioner dan inovator yang melahirkan terobosan berdampak luas',
                'Mentor dan pemimpin berempati tinggi yang berhasil menumbuhkan banyak talenta sukses',
                'Eksekutor tangguh yang selalu berhasil menyelesaikan misi-misi paling menantang',
            ],
        ];

        $createdQuestionIds = [];
        $answersByQuestionAndArchetype = [];

        foreach ($psychologyQuestions as $idx => $item) {
            $q = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $item[0],
                'type' => 'multiple_choice',
                'points' => 5,
                'is_required' => true,
                'order' => $idx + 1,
            ]);

            $createdQuestionIds[] = $q->id;

            for ($arch = 1; $arch <= 4; $arch++) {
                $ans = QuizAnswer::create([
                    'question_id' => $q->id,
                    'answer_text' => $item[$arch],
                    'is_correct' => true, // Every personality response is valid
                    'order' => $arch,
                ]);
                $answersByQuestionAndArchetype[$q->id][$arch] = $ans->id;
            }
        }

        // 4. Seed 4 Sample Completed Attempts (One for each Personality Gimmick) so Admin & Student can immediately preview all 4!
        TestAttempt::where('quiz_id', $quiz->id)->delete();

        $sampleProfiles = [
            [
                'arch' => 1,
                'name' => 'Budi Santoso (Architect - Mode Preview)',
                'email' => 'budi.architect@gmail.com',
                'birth_place' => 'Jakarta',
                'birth_date' => '2000-05-14',
                'address' => 'Jl. Sudirman No. 45, Jakarta Pusat',
                'whatsapp' => '081234567801',
                'is_paid' => false,
                'limit' => 10,
                'score' => 88.0,
            ],
            [
                'arch' => 2,
                'name' => 'Siti Rahmawati (Catalyst - Full Lunas)',
                'email' => 'siti.catalyst@gmail.com',
                'birth_place' => 'Bandung',
                'birth_date' => '2001-08-22',
                'address' => 'Jl. Dago Asri No. 12, Bandung',
                'whatsapp' => '081234567802',
                'is_paid' => true,
                'limit' => 20,
                'score' => 92.0,
            ],
            [
                'arch' => 3,
                'name' => 'Andi Pratama (Guardian - Full Lunas)',
                'email' => 'andi.guardian@gmail.com',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '1999-11-03',
                'address' => 'Jl. Kaliurang Km 7, Sleman, Yogyakarta',
                'whatsapp' => '081234567803',
                'is_paid' => true,
                'limit' => 20,
                'score' => 85.0,
            ],
            [
                'arch' => 4,
                'name' => 'Rina Wijaya (Explorer - Mode Preview)',
                'email' => 'rina.explorer@gmail.com',
                'birth_place' => 'Surabaya',
                'birth_date' => '2002-02-19',
                'address' => 'Jl. Darmo Permai III No. 8, Surabaya',
                'whatsapp' => '081234567804',
                'is_paid' => false,
                'limit' => 10,
                'score' => 78.0,
            ],
        ];

        foreach ($sampleProfiles as $sample) {
            $sampleAnswers = [];
            foreach (array_slice($createdQuestionIds, 0, $sample['limit']) as $i => $qId) {
                // Pick dominant archetype for 80% of answers and a secondary one for 20% so dimension bars look realistic
                $chosenArch = ($i % 5 === 0) ? (($sample['arch'] % 4) + 1) : $sample['arch'];
                $sampleAnswers[$qId] = $answersByQuestionAndArchetype[$qId][$chosenArch];
            }

            $attempt = TestAttempt::withoutEvents(function () use ($student, $quiz, $course, $sample, $sampleAnswers) {
                return TestAttempt::create([
                    'user_id' => $student->id,
                    'quiz_id' => $quiz->id,
                    'participant_name' => $sample['name'],
                    'google_email' => $sample['email'],
                    'birth_place' => $sample['birth_place'],
                    'birth_date' => $sample['birth_date'],
                    'address' => $sample['address'],
                    'whatsapp' => $sample['whatsapp'],
                    'testable_type' => Course::class,
                    'testable_id' => $course->id,
                    'test_type' => $quiz->type,
                    'score' => $sample['score'],
                    'total_questions' => 20,
                    'correct_answers' => $sample['limit'],
                    'passed' => true,
                    'is_paid' => $sample['is_paid'],
                    'paid_at' => $sample['is_paid'] ? now() : null,
                    'payment_amount' => 30000,
                    'answers' => $sampleAnswers,
                    'email_sent_at' => now(),
                    'started_at' => now()->subMinute(),
                    'completed_at' => now(),
                ]);
            });

            $prof = $attempt->personality_profile;
            TestAttempt::withoutEvents(function () use ($attempt, $prof) {
                $attempt->update([
                    'personality_summary' => $prof['archetype'].' — '.$prof['full_summary'],
                ]);
            });
        }

        $this->command?->info('Berhasil seed Tes Psikologi Global (20 Soal, 4 Arketipe Kepribadian) + 4 Contoh Hasil Peserta!');
    }
}
