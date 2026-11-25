<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\PromoCode;
use App\Models\SiapAssessment;
use App\Models\SiapCategory;
use App\Models\SiapQuestion;
use App\Models\User;
use Illuminate\Database\Seeder;

class SiapAssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPricingOptions();
        $this->seedDefaultPromo();
        $this->seedPsychoGeometric();
        $this->seedMultipleIntelligence();
    }

    private function seedPricingOptions(): void
    {
        $defaults = [
            ['key' => 'siap_super_finger_normal_price', 'label' => '350000', 'sort_order' => 1],
            ['key' => 'siap_super_finger_price', 'label' => '99000', 'sort_order' => 2],
            ['key' => 'siap_bundle_price', 'label' => '500000', 'sort_order' => 3],
        ];

        foreach ($defaults as $opt) {
            Option::firstOrCreate(
                ['category' => 'siap_pricing', 'key' => $opt['key']],
                [
                    'label' => $opt['label'],
                    'sort_order' => $opt['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }

    private function seedDefaultPromo(): void
    {
        PromoCode::whereRaw('UPPER(code) = ?', ['CONTOH'])->delete();

        $creatorId = User::first()?->id ?? 1;
        $promo = PromoCode::firstOrCreate(
            ['code' => 'EDUCREATIVEFEST2026'],
            [
                'name' => 'Voucher Edu Creative Fest 2026',
                'type' => PromoCode::TYPE_PERCENTAGE,
                'value' => 50,
                'max_uses' => null,
                'used_count' => 0,
                'min_purchase' => 0,
                'is_active' => true,
                'created_by' => $creatorId,
                'description' => 'Kode voucher Edu Creative Fest 2026 untuk asesmen SIAP 1Langkah',
            ]
        );

        if (! $promo->wasRecentlyCreated) {
            $promo->update([
                'name' => 'Voucher Edu Creative Fest 2026',
                'type' => PromoCode::TYPE_PERCENTAGE,
                'value' => 50,
                'max_uses' => null,
                'min_purchase' => 0,
                'is_active' => true,
                'description' => 'Kode voucher Edu Creative Fest 2026 untuk asesmen SIAP 1Langkah',
            ]);
        }
    }

    private function seedPsychoGeometric(): void
    {
        $assessment = SiapAssessment::updateOrCreate(
            ['slug' => 'psycho-geometric'],
            [
                'title' => 'Psycho Geometric Test',
                'badge' => null,
                'subtitle' => 'Eksplorasi 5 kecenderungan kepribadian & gaya belajarmu berbasis lima bentuk geometri.',
                'description' => 'Adaptasi eksploratif berbasis lima bentuk geometri (Square, Triangle, Rectangle, Circle, Squiggle) untuk memahami kecenderungan perilaku, gaya belajar, komunikasi, dan potensi akademik-karier peserta.',
                'scoring_type' => SiapAssessment::SCORING_FORCED_CHOICE,
                'question_count' => 25,
                'time_limit_minutes' => 5,
                'normal_price' => 150000,
                'unlock_price' => 35000,
                'disclaimer' => 'Ini adalah materi adaptasi/pengembangan, bukan publikasi resmi atau interpretasi tervalidasi dari Susan Dellinger. Psycho Geometric bukan diagnosis dan tidak boleh digunakan sendirian untuk menentukan jurusan, pekerjaan, penerimaan, atau seleksi. Rekomendasi akademik/karier harus mempertimbangkan minat, kemampuan, nilai, pengalaman, kondisi, serta informasi lain.',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        $categoriesData = [
            [
                'slug' => 'square',
                'code' => 'A',
                'name' => 'Square (Kotak)',
                'label' => 'Terstruktur • Teliti • Sistematis',
                'symbol' => '■',
                'color' => '#4783C8',
                'icon' => 'grid',
                'description' => 'Cenderung menyukai keteraturan, kejelasan, konsistensi, dan langkah yang terencana. Biasanya nyaman ketika tujuan, aturan, serta ekspektasi dijelaskan dengan rinci. Dalam situasi baru, ia dapat mengamati dan mengumpulkan informasi sebelum bertindak.',
                'details' => [
                    'love_language' => 'Memberi dan menerima hadiah. Hadiah dapat dimaknai sebagai tanda perhatian, ingatan, dan penghargaan. Bukan semata nilai atau harga barang; perhatian pada pilihan dan makna hadiah dapat terasa penting.',
                    'personal_strength' => 'Teliti; terorganisasi; konsisten; dapat diandalkan; memperhatikan detail; kuat dalam mengikuti prosedur; mampu menjaga kualitas dan menyelesaikan tugas secara bertahap.',
                    'learning_style' => 'Cenderung terbantu oleh materi runtut, contoh konkret, catatan terstruktur, dan target belajar yang jelas. Strategi: gunakan agenda belajar, checklist, rangkuman bertingkat, tabel perbandingan, latihan berulang, serta pecah tugas besar menjadi tahapan kecil. Sisipkan latihan terbuka agar tidak terpaku pada satu cara.',
                    'communication_style' => 'Cenderung menyukai komunikasi jelas, spesifik, runtut, dan berbasis fakta. Sampaikan tujuan, konteks, tenggat, dan langkah yang diharapkan. Hindari instruksi terlalu samar; tetap latih fleksibilitas saat informasi belum lengkap.',
                    'teamwork_style' => 'Sering kuat sebagai perencana, pencatat, penjaga kualitas, atau pengelola detail. Membantu tim dengan pembagian tugas, jadwal, dokumentasi, dan tindak lanjut. Perlu memberi ruang bagi ide spontan serta menerima bahwa tidak semua proses berjalan persis sesuai rencana.',
                    'academic_exploration' => 'Matematika, Akuntansi, Administrasi, Sains Laboratorium, Pengelolaan Data, Dokumentasi, Teknik',
                    'career_exploration' => 'Analis data pemula, Akuntansi/Keuangan, Administrasi, Quality Control, Dokumentasi, Perencanaan Operasional, Laboratorium, Pengelolaan Arsip',
                    'entrepreneurship_potential' => 'Berpotensi kuat pada usaha yang membutuhkan konsistensi operasional, pencatatan, standar layanan/produk, pengelolaan stok, dan kontrol kualitas. Tantangan pengembangan: berani menguji ide sebelum semuanya sempurna, merespons perubahan pasar, dan mengambil keputusan saat data belum lengkap.',
                    'practice' => '(1) Susun rencana satu proyek dan checklist-nya; (2) Selesaikan satu tugas dengan standar kualitas; (3) Coba satu metode baru tanpa menunggu semua detail sempurna; (4) Berikan apresiasi melalui hadiah kecil yang bermakna; (5) Evaluasi apa yang perlu dipertahankan dan diubah.',
                ],
                'sort_order' => 1,
            ],
            [
                'slug' => 'triangle',
                'code' => 'B',
                'name' => 'Triangle (Segitiga)',
                'label' => 'Berorientasi tujuan • Tegas • Penggerak',
                'symbol' => '▲',
                'color' => '#DF2020',
                'icon' => 'target',
                'description' => 'Cenderung berorientasi pada tujuan, hasil, tantangan, dan kemajuan. Dapat menikmati situasi yang memberi kesempatan mengambil inisiatif, membuat keputusan, dan melihat dampak tindakan. Perlu menjaga keseimbangan antara kecepatan, proses, dan kebutuhan orang lain.',
                'details' => [
                    'love_language' => 'Memberi dan menerima pelayanan (Acts of Service). Perhatian dapat dirasakan melalui tindakan nyata yang membantu, meringankan beban, atau membuat sesuatu berjalan. Bentuk pelayanan sederhana dan tepat sasaran bisa lebih bermakna daripada janji.',
                    'personal_strength' => 'Berani mengambil inisiatif; fokus pada target; tegas; kompetitif secara konstruktif; cepat bertindak; berorientasi solusi; mampu menggerakkan orang menuju hasil.',
                    'learning_style' => 'Cenderung terbantu oleh target yang menantang, pembelajaran berbasis proyek, studi kasus, kompetisi sehat, dan penerapan langsung. Strategi: tetapkan target terukur, gunakan batas waktu realistis, kerjakan soal aplikasi, presentasikan hasil, dan lakukan refleksi agar proses belajar tidak hanya mengejar nilai.',
                    'communication_style' => 'Cenderung langsung, ringkas, tegas, dan berorientasi keputusan. Sampaikan inti, pilihan, risiko, serta rekomendasi. Latih mendengar sampai tuntas, memeriksa pemahaman, dan menyampaikan ketegasan tanpa merendahkan.',
                    'teamwork_style' => 'Sering mengambil peran pengarah, koordinator, atau pengambil keputusan. Membantu tim menjaga fokus dan momentum. Perlu membagi ruang bicara, mendelegasikan, menghargai proses, dan memastikan keputusan mempertimbangkan masukan anggota.',
                    'academic_exploration' => 'Kewirausahaan, Manajemen, Ekonomi, Debat, Olahraga, Teknik Terapan, Proyek Kompetitif',
                    'career_exploration' => 'Pengembangan bisnis, Manajemen proyek, Penjualan konsultatif, Operasi, Kepemimpinan organisasi, Bidang teknik berbasis target, Manajemen produk',
                    'entrepreneurship_potential' => 'Berpotensi pada aktivitas yang membutuhkan keberanian menawarkan solusi, menetapkan target, mengambil keputusan, dan mengembangkan pasar. Tantangan: memvalidasi kebutuhan pelanggan, mengelola risiko, mendengar umpan balik, serta tidak mengorbankan kualitas dan etika demi kecepatan.',
                    'practice' => '(1) Tetapkan satu target SMART; (2) Pimpin satu tugas kecil; (3) Lakukan satu tindakan pelayanan tanpa diminta; (4) Minta masukan dari anggota tim dan dengarkan tanpa menyela; (5) Evaluasi hasil sekaligus proses dan dampaknya pada orang lain.',
                ],
                'sort_order' => 2,
            ],
            [
                'slug' => 'rectangle',
                'code' => 'C',
                'name' => 'Rectangle (Persegi Panjang)',
                'label' => 'Eksploratif • Transisi • Mencari arah',
                'symbol' => '▭',
                'color' => '#D18637',
                'icon' => 'compass',
                'description' => 'Dalam kerangka ini, Rectangle dipahami sebagai fase transisi atau pencarian arah, bukan label kepribadian yang kaku. Seseorang mungkin sedang mencoba peran, minat, atau cara baru; merasa belum mantap; atau sedang menata ulang prioritas. Fase ini dapat menjadi kesempatan mengenali diri dan bertumbuh.',
                'details' => [
                    'love_language' => 'Memberi dan menerima sentuhan (Physical Touch), sesuai pemetaan yang digunakan dalam asesmen ini. Sentuhan yang aman, pantas, disepakati, dan sesuai konteks—misalnya tos atau tepukan dukungan—dapat menjadi tanda kedekatan. Hormati batas pribadi dan budaya setiap orang.',
                    'personal_strength' => 'Terbuka pada perubahan; mau mencoba; adaptif; peka terhadap pengalaman baru; berpotensi menemukan perspektif berbeda; mampu belajar dari proses pencarian. Kekuatan ini berkembang ketika eksplorasi disertai refleksi dan komitmen bertahap.',
                    'learning_style' => 'Dapat terbantu oleh variasi metode, percobaan singkat, pilihan topik, dan umpan balik berkala. Strategi: lakukan eksperimen belajar 1–2 minggu, catat energi dan kemajuan, gunakan portofolio, minta umpan balik, lalu pilih satu kebiasaan inti untuk dijaga. Hindari terlalu sering berganti strategi tanpa evaluasi.',
                    'communication_style' => 'Mungkin sedang mencari cara paling nyaman untuk menyampaikan pikiran. Beri waktu untuk merumuskan jawaban, gunakan pertanyaan terbuka, dan periksa apakah pesan dipahami. Latih menyatakan kebutuhan, batas, serta keputusan sementara dengan jelas.',
                    'teamwork_style' => 'Dapat berkontribusi sebagai anggota yang fleksibel, penguji pendekatan, atau pengamat proses. Membantu tim melihat alternatif dan menyesuaikan diri. Perlu memperjelas komitmen, tenggat, dan tanggung jawab agar eksplorasi tidak berubah menjadi ketidakpastian bagi tim.',
                    'academic_exploration' => 'Kelas Pilihan Lintas Rumpun, Klub Minat, Proyek Lintas Bidang, Riset Eksploratif Minat',
                    'career_exploration' => 'Job shadowing, Wawancara profesi, Magang singkat sesuai usia, Proyek sukarela, Simulasi pekerjaan lintas bidang',
                    'entrepreneurship_potential' => 'Dapat menemukan peluang melalui pengalaman mencoba dan melihat masalah dari sudut baru. Mulai dari eksperimen kecil: wawancara calon pengguna, prototipe sederhana, dan uji coba terbatas. Tantangan: memilih satu masalah prioritas, menyelesaikan siklus uji, dan tidak terlalu cepat berpindah ide.',
                    'practice' => '(1) Pilih satu minat untuk dieksplorasi; (2) Lakukan eksperimen kecil selama tujuh hari; (3) Catat apa yang memberi energi dan apa yang sulit; (4) Tetapkan satu komitmen yang akan diselesaikan; (5) Hormati batas sentuhan diri sendiri dan orang lain.',
                ],
                'sort_order' => 3,
            ],
            [
                'slug' => 'circle',
                'code' => 'D',
                'name' => 'Circle (Lingkaran)',
                'label' => 'Empatik • Kolaboratif • Relasional',
                'symbol' => '●',
                'color' => '#439D8A',
                'icon' => 'users',
                'description' => 'Cenderung memperhatikan hubungan, suasana, dan kebutuhan orang lain. Dapat menikmati kerja sama, mendengarkan, serta membangun rasa kebersamaan. Penting menjaga keseimbangan antara kepedulian kepada orang lain dan kemampuan menyampaikan kebutuhan diri.',
                'details' => [
                    'love_language' => 'Memberi dan menerima kalimat pujian (Words of Affirmation). Pengakuan yang tulus, spesifik, dan jujur dapat terasa bermakna. Apresiasi tidak harus berlebihan; menyebut usaha atau kontribusi secara konkret sering lebih kuat daripada pujian umum.',
                    'personal_strength' => 'Empatik; pendengar yang baik; suportif; mampu membangun relasi; kooperatif; peka terhadap suasana kelompok; dapat menjadi penghubung dan penjaga keharmonisan.',
                    'learning_style' => 'Cenderung terbantu oleh diskusi, belajar berpasangan/kelompok, tutor sebaya, dan mengaitkan materi dengan pengalaman manusia. Strategi: diskusi terarah, saling mengajar, kelompok belajar dengan peran jelas, refleksi tertulis, dan sesi belajar mandiri agar pemahaman tidak hanya bergantung pada kelompok.',
                    'communication_style' => 'Cenderung hangat, suportif, dan memperhatikan perasaan lawan bicara. Gunakan bahasa yang menghargai dan mendengarkan aktif. Latih menyampaikan ketidaksetujuan, kritik, dan batas secara jujur tanpa selalu menghindari konflik.',
                    'teamwork_style' => 'Sering kuat sebagai fasilitator, pendukung, mediator, atau penjaga kolaborasi. Membantu anggota merasa dilibatkan. Perlu berani mengambil keputusan sulit, membagi beban secara adil, dan tidak mengambil alih semua pekerjaan demi menjaga suasana.',
                    'academic_exploration' => 'Pendidikan, Psikologi, Komunikasi, Bahasa, Kesehatan Masyarakat, Layanan Sosial, Ilmu Sosial',
                    'career_exploration' => 'Pendidikan, Layanan pelanggan, Komunikasi, Hubungan komunitas, Sumber daya manusia, Fasilitasi, Pendampingan, Layanan kesehatan/sosial',
                    'entrepreneurship_potential' => 'Berpotensi membangun usaha berbasis komunitas, layanan, pengalaman pelanggan, edukasi, atau solusi kebutuhan sehari-hari. Kekuatan dapat muncul dari kemampuan memahami pelanggan dan membangun kepercayaan. Tantangan: menetapkan harga, membuat batas layanan, mengelola konflik, dan mengambil keputusan berbasis data.',
                    'practice' => '(1) Berikan satu pujian yang spesifik dan tulus; (2) Dengarkan teman tanpa buru-buru memberi solusi; (3) Sampaikan satu kebutuhan atau batas dengan sopan; (4) Ambil peran kolaboratif dalam tugas; (5) Sisihkan waktu untuk belajar mandiri.',
                ],
                'sort_order' => 4,
            ],
            [
                'slug' => 'squiggle',
                'code' => 'E',
                'name' => 'Squiggle (Garis Bergelombang)',
                'label' => 'Kreatif • Imajinatif • Eksperimental',
                'symbol' => '〰',
                'color' => '#8065CF',
                'icon' => 'sparkles',
                'description' => 'Cenderung tertarik pada gagasan, kemungkinan, kreativitas, dan cara pandang yang tidak biasa. Dapat menikmati kebebasan bereksperimen dan menghubungkan ide dari berbagai bidang. Perlu strategi untuk mengubah inspirasi menjadi langkah konkret dan hasil yang selesai.',
                'details' => [
                    'love_language' => 'Memberi dan menerima waktu berkualitas (Quality Time). Kehadiran penuh, perhatian tanpa terdistraksi, dan pengalaman bersama dapat terasa bermakna. Kualitas interaksi lebih penting daripada sekadar berada di tempat yang sama.',
                    'personal_strength' => 'Imajinatif; menghasilkan banyak ide; fleksibel; ingin tahu; melihat hubungan yang tidak biasa; berani bereksperimen; mampu menawarkan alternatif dan pendekatan kreatif.',
                    'learning_style' => 'Cenderung terbantu oleh proyek kreatif, eksplorasi, visualisasi, eksperimen, pertanyaan terbuka, dan hubungan lintas topik. Strategi: gunakan mind map, prototipe, tugas pilihan, brainstorming dengan batas waktu, lalu pilih satu ide dan selesaikan dengan tahapan serta tenggat yang jelas.',
                    'communication_style' => 'Cenderung ekspresif, asosiatif, dan suka membahas kemungkinan. Gunakan cerita, analogi, sketsa, atau contoh visual. Latih merangkum inti, menyusun urutan, dan membedakan ide yang menarik dari ide yang siap dijalankan.',
                    'teamwork_style' => 'Sering berkontribusi sebagai penggagas ide, kreator konsep, atau pemecah kebuntuan. Membantu tim melihat peluang baru. Perlu mendokumentasikan ide, menerima batasan, menyepakati prioritas, dan bekerja bersama anggota yang menjaga implementasi.',
                    'academic_exploration' => 'Seni, Desain, Media, Teknologi Kreatif, Arsitektur, Komunikasi, Penulisan, Inovasi, Riset Eksploratif',
                    'career_exploration' => 'Desain grafis/produk, Konten kreatif, Penulisan, Periklanan, Multimedia, Pengembangan produk, UX, Seni pertunjukan, Inovasi',
                    'entrepreneurship_potential' => 'Berpotensi menciptakan konsep, produk, pengalaman, atau cara promosi yang berbeda. Dapat unggul pada ideasi dan diferensiasi. Tantangan: menguji kebutuhan pasar, menghitung biaya, menentukan pelanggan sasaran, menjaga konsistensi, dan menuntaskan ide menjadi produk yang bernilai.',
                    'practice' => '(1) Catat 10 ide tanpa menilai; (2) Pilih satu ide paling realistis; (3) Buat prototipe atau contoh sederhana; (4) Luangkan waktu berkualitas tanpa gawai bersama orang terdekat; (5) Minta umpan balik dan selesaikan satu revisi.',
                ],
                'sort_order' => 5,
            ],
        ];

        foreach ($categoriesData as $cat) {
            SiapCategory::updateOrCreate(
                [
                    'siap_assessment_id' => $assessment->id,
                    'slug' => $cat['slug'],
                ],
                $cat
            );
        }

        $questionsData = [
            1 => [
                'text' => 'Ketika mendapatkan tugas kelompok, biasanya saya akan ...',
                'A' => 'Membuat pembagian tugas dan menyusun jadwal kerja.',
                'B' => 'Mengambil inisiatif untuk memimpin kelompok.',
                'C' => 'Mencari tahu terlebih dahulu apa yang sebenarnya harus dilakukan.',
                'D' => 'Memastikan semua anggota merasa nyaman dan dilibatkan.',
                'E' => 'Mencari ide menarik agar tugas menjadi lebih kreatif.',
            ],
            2 => [
                'text' => 'Ketika menghadapi masalah yang sulit, saya cenderung ...',
                'A' => 'Mengumpulkan informasi dan menganalisisnya secara runtut.',
                'B' => 'Segera menentukan langkah dan bergerak mencari solusi.',
                'C' => 'Memikirkan beberapa kemungkinan sebelum memilih tindakan.',
                'D' => 'Berdiskusi dengan orang yang saya percaya.',
                'E' => 'Mencoba pendekatan yang belum pernah dilakukan.',
            ],
            3 => [
                'text' => 'Aktivitas sekolah yang paling menarik bagi saya adalah ...',
                'A' => 'Menyusun laporan, merapikan data, atau membuat rangkuman.',
                'B' => 'Mengikuti lomba, memimpin kegiatan, atau mengejar target.',
                'C' => 'Mencoba berbagai kegiatan untuk mengetahui minat saya.',
                'D' => 'Menjadi bagian dari kegiatan sosial atau kerja kelompok.',
                'E' => 'Membuat karya, eksperimen, atau proyek kreatif.',
            ],
            4 => [
                'text' => 'Jika guru memberi tugas dengan instruksi yang kurang jelas, saya akan ...',
                'A' => 'Meminta penjelasan rinci agar langkah-langkahnya jelas.',
                'B' => 'Menentukan sendiri langkah yang paling efektif lalu mulai bekerja.',
                'C' => 'Mencoba memahami tugas sambil mencari arah yang paling sesuai.',
                'D' => 'Bertanya kepada guru atau teman agar tidak salah memahami.',
                'E' => 'Mengembangkan ide sendiri selama tetap sesuai tujuan tugas.',
            ],
            5 => [
                'text' => 'Saat belajar untuk ujian, saya paling nyaman ...',
                'A' => 'Membuat jadwal dan belajar secara bertahap.',
                'B' => 'Menetapkan target nilai dan fokus pada materi penting.',
                'C' => 'Mengubah cara belajar jika cara sebelumnya kurang cocok.',
                'D' => 'Belajar bersama teman dan saling menjelaskan.',
                'E' => 'Menggunakan cara kreatif seperti mind map, simulasi, atau cerita.',
            ],
            6 => [
                'text' => 'Dalam sebuah tim, peran yang paling sering saya ambil adalah ...',
                'A' => 'Mengatur detail, alur kerja, dan memastikan semuanya rapi.',
                'B' => 'Mengarahkan tim agar tujuan tercapai.',
                'C' => 'Menyesuaikan diri dengan kebutuhan tim yang sedang berkembang.',
                'D' => 'Menjaga komunikasi dan membantu anggota bekerja sama.',
                'E' => 'Mengusulkan gagasan baru dan alternatif yang berbeda.',
            ],
            7 => [
                'text' => 'Jika rencana akhir pekan tiba-tiba berubah, saya biasanya ...',
                'A' => 'Menyusun ulang jadwal agar kegiatan tetap teratur.',
                'B' => 'Segera menentukan rencana pengganti.',
                'C' => 'Mengamati pilihan yang ada sebelum memutuskan.',
                'D' => 'Mempertimbangkan keinginan orang-orang yang ikut.',
                'E' => 'Menganggapnya kesempatan mencoba hal spontan.',
            ],
            8 => [
                'text' => 'Ketika ada teman yang mengalami kesulitan, saya cenderung ...',
                'A' => 'Membantu menyusun langkah praktis untuk menyelesaikannya.',
                'B' => 'Mendorongnya segera mengambil tindakan.',
                'C' => 'Membantunya melihat pilihan-pilihan yang mungkin.',
                'D' => 'Mendengarkan dan membuatnya merasa didukung.',
                'E' => 'Mengajaknya melihat masalah dari sudut pandang baru.',
            ],
            9 => [
                'text' => 'Hal yang paling membuat saya puas setelah menyelesaikan tugas adalah ...',
                'A' => 'Semua bagian selesai dengan rapi dan sesuai standar.',
                'B' => 'Target tercapai dan hasilnya terlihat nyata.',
                'C' => 'Saya mendapat pemahaman baru tentang diri atau kemampuan saya.',
                'D' => 'Semua orang merasa terbantu dan bekerja dengan baik.',
                'E' => 'Hasilnya unik, orisinal, atau berbeda dari biasanya.',
            ],
            10 => [
                'text' => 'Saat harus mengambil keputusan penting, saya biasanya ...',
                'A' => 'Membandingkan fakta dan mempertimbangkan konsekuensinya.',
                'B' => 'Memilih opsi yang paling mendukung tujuan saya.',
                'C' => 'Memberi waktu untuk mengenali apa yang paling tepat bagi saya.',
                'D' => 'Mempertimbangkan dampaknya terhadap orang lain.',
                'E' => 'Memikirkan kemungkinan baru yang belum dipertimbangkan.',
            ],
            11 => [
                'text' => 'Jika diminta membuat presentasi, saya akan ...',
                'A' => 'Menyusun kerangka, data, dan urutan materi dengan rapi.',
                'B' => 'Menentukan pesan utama agar audiens terdorong bertindak.',
                'C' => 'Mencoba beberapa konsep sebelum memilih format.',
                'D' => 'Membuat materi yang mudah dipahami dan melibatkan audiens.',
                'E' => 'Menggunakan ide visual atau cara penyampaian yang tidak biasa.',
            ],
            12 => [
                'text' => 'Dalam pertemanan, saya paling menghargai ...',
                'A' => 'Kejujuran, konsistensi, dan dapat dipercaya.',
                'B' => 'Sikap berani, mandiri, dan saling mendukung untuk maju.',
                'C' => 'Ruang untuk berubah dan menjadi diri sendiri.',
                'D' => 'Kepedulian, kebersamaan, dan saling memahami.',
                'E' => 'Keterbukaan terhadap ide dan pengalaman baru.',
            ],
            13 => [
                'text' => 'Ketika menerima kritik, reaksi awal saya biasanya ...',
                'A' => 'Memeriksa bagian mana yang perlu diperbaiki secara spesifik.',
                'B' => 'Menjadikannya tantangan untuk menunjukkan kemampuan.',
                'C' => 'Memikirkan apakah kritik itu sesuai dengan kondisi saya.',
                'D' => 'Memperhatikan cara penyampaian dan maksud orang tersebut.',
                'E' => 'Mengambil inspirasi untuk mencoba cara yang berbeda.',
            ],
            14 => [
                'text' => 'Jika diberi kesempatan memilih proyek sekolah, saya tertarik pada proyek yang ...',
                'A' => 'Memiliki prosedur, data, dan hasil yang terukur.',
                'B' => 'Menantang dan memiliki target yang jelas.',
                'C' => 'Memberi kesempatan mencoba bidang baru.',
                'D' => 'Bermanfaat bagi banyak orang atau komunitas.',
                'E' => 'Membebaskan saya bereksperimen dan berkreasi.',
            ],
            15 => [
                'text' => 'Ketika bekerja dengan tenggat waktu yang dekat, saya akan ...',
                'A' => 'Membuat daftar prioritas dan mengerjakan satu per satu.',
                'B' => 'Fokus pada hasil utama dan bergerak cepat.',
                'C' => 'Menilai ulang cara kerja agar bisa menyesuaikan keadaan.',
                'D' => 'Berkoordinasi agar tidak ada anggota yang tertinggal.',
                'E' => 'Mencari jalan pintas kreatif tanpa mengabaikan tujuan.',
            ],
            16 => [
                'text' => 'Saya lebih mudah memahami sesuatu ketika ...',
                'A' => 'Penjelasannya runtut, jelas, dan disertai contoh.',
                'B' => 'Saya tahu tujuan dan manfaat yang ingin dicapai.',
                'C' => 'Saya diberi kesempatan mengeksplorasi dan mencoba.',
                'D' => 'Saya bisa berdiskusi dan bertukar pemahaman.',
                'E' => 'Saya melihat gambaran besar dan menghubungkan berbagai ide.',
            ],
            17 => [
                'text' => 'Jika terjadi perbedaan pendapat dalam kelompok, saya cenderung ...',
                'A' => 'Mengajak semua pihak kembali pada aturan dan fakta.',
                'B' => 'Mendorong kelompok memilih keputusan dan melanjutkan.',
                'C' => 'Membuka kemungkinan kompromi atau cara baru.',
                'D' => 'Menjembatani komunikasi agar semua pihak didengar.',
                'E' => 'Mengusulkan sudut pandang alternatif.',
            ],
            18 => [
                'text' => 'Kegiatan ekstrakurikuler yang mungkin paling saya nikmati adalah ...',
                'A' => 'Klub sains, jurnalistik data, atau kegiatan yang terorganisasi.',
                'B' => 'OSIS, olahraga kompetitif, atau kepanitiaan yang menantang.',
                'C' => 'Mencoba beberapa klub untuk menemukan yang cocok.',
                'D' => 'Relawan, kelompok sosial, atau kegiatan kebersamaan.',
                'E' => 'Seni, desain, musik, teater, atau inovasi.',
            ],
            19 => [
                'text' => 'Saat mempelajari keterampilan baru, saya biasanya ...',
                'A' => 'Mengikuti panduan dan berlatih secara bertahap.',
                'B' => 'Menentukan standar kemampuan lalu berlatih untuk mencapainya.',
                'C' => 'Bereksperimen sampai menemukan cara yang cocok.',
                'D' => 'Belajar bersama orang lain atau meminta umpan balik.',
                'E' => 'Mencoba berbagai teknik dan menggabungkannya secara kreatif.',
            ],
            20 => [
                'text' => 'Jika memiliki waktu luang untuk membuat sesuatu, saya lebih mungkin ...',
                'A' => 'Membuat sesuatu yang berguna dan tertata.',
                'B' => 'Membuat proyek yang punya target atau hasil nyata.',
                'C' => 'Mencoba hal yang sedang ingin saya kenali lebih jauh.',
                'D' => 'Membuat sesuatu yang bisa dinikmati atau membantu orang lain.',
                'E' => 'Membuat karya orisinal berdasarkan imajinasi.',
            ],
            21 => [
                'text' => 'Dalam mencapai cita-cita, hal yang paling membantu saya adalah ...',
                'A' => 'Rencana yang jelas dan kebiasaan yang konsisten.',
                'B' => 'Ambisi, keberanian, dan target yang menantang.',
                'C' => 'Kesempatan mengevaluasi dan menyesuaikan arah.',
                'D' => 'Dukungan, relasi, dan lingkungan yang positif.',
                'E' => 'Kebebasan berinovasi dan mencoba peluang baru.',
            ],
            22 => [
                'text' => 'Ketika membaca informasi baru, saya paling tertarik pada ...',
                'A' => 'Fakta, struktur, dan detail yang dapat diperiksa.',
                'B' => 'Kesimpulan dan cara menerapkannya untuk mencapai hasil.',
                'C' => 'Berbagai kemungkinan yang bisa saya pelajari lebih lanjut.',
                'D' => 'Kisah manusia dan dampaknya bagi kehidupan.',
                'E' => 'Ide unik dan hubungan yang tidak biasa.',
            ],
            23 => [
                'text' => 'Jika menjadi ketua kegiatan sekolah, prioritas saya adalah ...',
                'A' => 'Memastikan tugas, jadwal, dan prosedur berjalan baik.',
                'B' => 'Membawa tim mencapai target kegiatan.',
                'C' => 'Menyesuaikan rencana berdasarkan situasi yang berkembang.',
                'D' => 'Menjaga semangat, komunikasi, dan kebersamaan tim.',
                'E' => 'Membuat kegiatan menarik dengan konsep segar.',
            ],
            24 => [
                'text' => 'Saya merasa paling bersemangat ketika ...',
                'A' => 'Berhasil menyelesaikan sesuatu dengan teliti dan benar.',
                'B' => 'Berhasil mengatasi tantangan dan mencapai sasaran.',
                'C' => 'Menemukan hal baru tentang minat dan kemampuan saya.',
                'D' => 'Merasa terhubung dan bisa memberi manfaat bagi orang lain.',
                'E' => 'Menghasilkan ide atau karya yang belum pernah terpikirkan.',
            ],
            25 => [
                'text' => 'Jika membayangkan masa depan, saya ingin ...',
                'A' => 'Memiliki kehidupan yang terencana dan stabil.',
                'B' => 'Mencapai prestasi, memimpin, atau membangun sesuatu.',
                'C' => 'Tetap punya ruang untuk mengeksplorasi pilihan hidup.',
                'D' => 'Menjalani kehidupan yang bermakna bersama orang lain.',
                'E' => 'Menciptakan hal baru dan menjalani jalan yang khas.',
            ],
        ];

        $letterMap = [
            'A' => 'square',
            'B' => 'triangle',
            'C' => 'rectangle',
            'D' => 'circle',
            'E' => 'squiggle',
        ];

        foreach ($questionsData as $order => $q) {
            $options = [];
            foreach ($letterMap as $letter => $slug) {
                $options[] = [
                    'key' => $letter,
                    'category_slug' => $slug,
                    'text' => $q[$letter],
                ];
            }

            SiapQuestion::updateOrCreate(
                [
                    'siap_assessment_id' => $assessment->id,
                    'sort_order' => $order,
                ],
                [
                    'siap_category_id' => null,
                    'question_text' => $q['text'],
                    'options' => $options,
                    'is_active' => true,
                ]
            );
        }

        // Remove any stray questions beyond 25
        SiapQuestion::where('siap_assessment_id', $assessment->id)
            ->where('sort_order', '>', 25)
            ->delete();
    }

    private function seedMultipleIntelligence(): void
    {
        $assessment = SiapAssessment::updateOrCreate(
            ['slug' => 'multiple-intelligence'],
            [
                'title' => 'Multiple Intelligence Test',
                'badge' => null,
                'subtitle' => 'Kenali 8 jenis kecerdasan yang berkembang melalui pengalaman dan lingkunganmu.',
                'description' => 'Menurut teori Multiple Intelligences Howard Gardner, kecerdasan manusia tidak hanya diukur dari kemampuan akademik. Setiap individu memiliki kombinasi 8 jenis kecerdasan yang unik.',
                'scoring_type' => SiapAssessment::SCORING_LIKERT,
                'question_count' => 32,
                'time_limit_minutes' => 15,
                'normal_price' => 150000,
                'unlock_price' => 35000,
                'disclaimer' => 'Skor menunjukkan pola jawaban peserta pada setiap area kecerdasan sebagai bahan refleksi dan eksplorasi, bukan diagnosis psikologis, pengukuran IQ, label permanen, atau penentu mutlak jurusan dan profesi. Padukan hasil ini dengan minat, nilai, prestasi, pengalaman, serta diskusi bersama guru, orang tua, atau konselor.',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        $masterNote = 'Kecenderungan ini perlu dikonfirmasi melalui pengalaman nyata dan informasi lain; jangan digunakan sendirian untuk menetapkan jurusan atau profesi.';

        // 8 categories x 4 questions each = 32 questions total
        $categoriesData = [
            [
                'slug' => 'linguistic',
                'name' => 'Linguistic Intelligence',
                'label' => 'Kecerdasan Linguistik (Bahasa) • The Communicator',
                'color' => '#8065CF',
                'icon' => 'book',
                'description' => 'Kecenderungan menggunakan bahasa untuk memahami, mengolah, menyusun, dan menyampaikan gagasan. Area ini dapat terlihat melalui ketertarikan pada membaca, menulis, bercerita, berdiskusi, berargumentasi, atau menjelaskan sesuatu kepada orang lain. Ini bukan ukuran kemampuan bahasa atau nilai pelajaran secara langsung.',
                'details' => [
                    'short_code' => 'LI',
                    'indonesian_name' => 'Kecerdasan Linguistik (Bahasa)',
                    'archetype' => 'The Communicator',
                    'personal_strength' => [
                        'Menyampaikan ide secara lisan atau tertulis.',
                        'Memilih kata dan menyusun penjelasan agar mudah dipahami.',
                        'Menyukai cerita, bacaan, diskusi, atau permainan kata.',
                        'Mengembangkan argumen dan merangkum informasi.',
                        'Menggunakan komunikasi untuk berbagi pengetahuan atau membangun pemahaman.',
                    ],
                    'learning_style' => [
                        'Membaca materi lalu menuliskan ringkasan dengan kata-kata sendiri.',
                        'Menjelaskan kembali materi seolah sedang mengajar orang lain.',
                        'Berdiskusi, bertanya, mengikuti debat terarah, atau membuat catatan.',
                        'Membuat kartu istilah, peta konsep berbasis kata, atau daftar pertanyaan.',
                        'Menggabungkan teks dengan gambar, praktik, dan latihan soal; jangan membatasi belajar hanya melalui bacaan atau ceramah.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: bahasa dan sastra, ilmu komunikasi, jurnalistik, pendidikan, hukum, hubungan internasional, linguistik, penulisan kreatif, dan bidang lain yang memerlukan kemampuan mengolah informasi verbal. Pilihan jurusan tetap perlu mempertimbangkan minat, nilai akademik, persyaratan masuk, dan pengalaman siswa.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: penulis, editor, jurnalis, penerjemah, guru atau pengajar, presenter, penyiar, peneliti bahasa, spesialis komunikasi, public relations, dan content creator. Setiap profesi membutuhkan kompetensi tambahan, pendidikan, etika, serta pengalaman yang sesuai.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: jasa penulisan dan penyuntingan, penerbitan atau produk literasi, kelas bahasa, pelatihan komunikasi, layanan penerjemahan, produksi konten edukatif, podcast atau media niche, dan jasa copywriting. Mulai dengan menguji kebutuhan calon pengguna dan membangun portofolio.',
                    'daily_practice' => [
                        'Tulis jurnal atau refleksi 5–10 menit.',
                        'Baca artikel/bab buku lalu ceritakan kembali intinya.',
                        'Latih presentasi singkat tentang topik yang disukai.',
                        'Buat ulasan buku, film, atau isu secara bertanggung jawab.',
                        'Perkaya kosakata dan pelajari bahasa baru secara bertahap.',
                        'Dengarkan lawan bicara dan latih menyampaikan pendapat dengan santun.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Tulis jurnal atau refleksi 5–10 menit dan ceritakan kembali intinya.',
                ],
                'questions' => [
                    'Saya senang menjelaskan sesuatu kepada orang lain menggunakan kata-kata saya sendiri.',
                    'Saya senang membaca, menulis, atau membuat cerita dan tulisan.',
                    'Saya menikmati kegiatan presentasi, debat, atau menyampaikan pendapat.',
                    'Saya mampu menyusun penjelasan tertulis agar mudah dipahami orang lain.',
                ],
                'sort_order' => 1,
            ],
            [
                'slug' => 'logical-mathematical',
                'name' => 'Logical-Mathematical Intelligence',
                'label' => 'Kecerdasan Logis-Matematis • The Analytical Thinker',
                'color' => '#4783C8',
                'icon' => 'chart',
                'description' => 'Kecenderungan mengenali pola, menghubungkan sebab dan akibat, berpikir runtut, menggunakan angka atau data, serta menyusun langkah untuk memecahkan masalah. Area ini tidak sama dengan nilai matematika dan dapat muncul dalam banyak konteks, termasuk eksperimen, strategi, dan pengambilan keputusan.',
                'details' => [
                    'short_code' => 'LG',
                    'indonesian_name' => 'Kecerdasan Logis-Matematis',
                    'archetype' => 'The Analytical Thinker',
                    'personal_strength' => [
                        'Mengurai masalah menjadi bagian-bagian yang lebih kecil.',
                        'Mengenali pola, hubungan, dan ketidaksesuaian informasi.',
                        'Menyusun alasan dan langkah penyelesaian secara sistematis.',
                        'Tertarik pada angka, data, eksperimen, atau teka-teki.',
                        'Membandingkan pilihan berdasarkan bukti dan kriteria.',
                    ],
                    'learning_style' => [
                        'Kerjakan contoh soal bertahap dan jelaskan alasan di setiap langkah.',
                        'Gunakan tabel, grafik, simulasi, atau diagram sebab-akibat.',
                        'Pecah tugas besar menjadi urutan langkah yang terukur.',
                        'Uji hipotesis melalui eksperimen atau studi kasus.',
                        'Diskusikan konsep dengan bahasa sederhana, lalu praktikkan pada konteks nyata.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: matematika, statistika, ilmu komputer, teknologi informasi, teknik, fisika, kimia, ekonomi, akuntansi, aktuaria, analisis data, dan bidang riset. Pertimbangkan pula minat pada objek kajian, kesiapan akademik, dan prasyarat tiap program.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: analis data, programmer, pengembang perangkat lunak, peneliti, analis keuangan, akuntan, insinyur, analis bisnis, aktuaris, dan spesialis riset. Peran-peran ini memerlukan keahlian teknis dan komunikasi yang dikembangkan melalui pendidikan serta praktik.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: jasa analisis data sederhana, pengembangan aplikasi atau otomasi, bimbingan belajar STEM, layanan pembukuan, riset pasar, produk berbasis teknologi, dan konsultasi proses setelah memiliki kompetensi. Validasi masalah pelanggan sebelum membangun solusi.',
                    'daily_practice' => [
                        'Pecahkan teka-teki logika atau permainan strategi.',
                        'Biasakan bertanya “apa buktinya?” dan “apa kemungkinan penyebabnya?”.',
                        'Catat pengeluaran atau data sederhana lalu cari polanya.',
                        'Buat rencana kegiatan dengan prioritas dan evaluasi hasilnya.',
                        'Lakukan eksperimen kecil yang aman dan catat temuannya.',
                        'Periksa kembali kesimpulan agar tidak terburu-buru.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Pecahkan teka-teki logika atau permainan strategi dan cari polanya.',
                ],
                'questions' => [
                    'Saya menikmati tantangan memecahkan teka-teki, pola, atau persoalan yang membutuhkan logika.',
                    'Saya suka mencari tahu alasan dan hubungan sebab-akibat dari suatu kejadian.',
                    'Saya senang mengolah angka, data, atau informasi untuk menemukan kesimpulan.',
                    'Saya suka membuat strategi berdasarkan perhitungan dan pertimbangan yang masuk akal.',
                ],
                'sort_order' => 2,
            ],
            [
                'slug' => 'spatial',
                'name' => 'Visual-Spatial Intelligence',
                'label' => 'Kecerdasan Visual-Spasial • The Visual Creator',
                'color' => '#D18637',
                'icon' => 'grid',
                'description' => 'Kecenderungan memahami dan mengolah informasi melalui gambar, bentuk, posisi, ruang, peta, komposisi, dan visualisasi. Area ini dapat terlihat dalam kegiatan merancang, membayangkan bentuk, membaca diagram, atau menyusun elemen visual.',
                'details' => [
                    'short_code' => 'VS',
                    'indonesian_name' => 'Kecerdasan Visual-Spasial',
                    'archetype' => 'The Visual Creator',
                    'personal_strength' => [
                        'Membayangkan bentuk, susunan, atau tampilan sebelum dibuat.',
                        'Memahami peta, diagram, skema, dan hubungan ruang.',
                        'Memperhatikan komposisi, warna, proporsi, atau tata letak.',
                        'Mengomunikasikan gagasan melalui sketsa atau visual.',
                        'Melihat alternatif desain atau susunan dari sudut pandang berbeda.',
                    ],
                    'learning_style' => [
                        'Ubah konsep menjadi diagram, sketsa, peta konsep, atau infografik.',
                        'Gunakan garis waktu, bagan alur, peta, dan model visual.',
                        'Tandai hubungan antaride dengan simbol atau pengelompokan yang konsisten.',
                        'Praktikkan membaca visual sekaligus menjelaskan maknanya dengan kata-kata.',
                        'Gunakan visual sebagai pelengkap latihan, bacaan, diskusi, dan praktik.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: desain komunikasi visual, desain produk, arsitektur, perencanaan wilayah dan kota, seni rupa, animasi, film, fotografi, multimedia, geografi, dan bidang yang melibatkan pemodelan ruang. Telusuri portofolio dan persyaratan seleksi masing-masing program.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: desainer grafis, ilustrator, fotografer, animator, editor video, desainer produk, arsitek, drafter, visual merchandiser, dan spesialis visualisasi data. Kompetensi perangkat, teknis, dan portofolio biasanya diperlukan.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: studio desain, jasa fotografi atau video, ilustrasi, desain presentasi, produk dekorasi, percetakan kreatif, jasa visualisasi, dan produk digital. Bangun contoh karya, tentukan segmen pelanggan, lalu uji layanan dalam proyek kecil.',
                    'daily_practice' => [
                        'Buat satu sketsa atau doodle untuk menuangkan ide.',
                        'Ubah catatan pelajaran menjadi diagram atau peta konsep.',
                        'Amati tata ruang dan desain benda di sekitar, lalu catat alasannya.',
                        'Latih membaca peta atau mengikuti denah.',
                        'Buat proyek visual kecil dan minta umpan balik.',
                        'Pelajari prinsip dasar komposisi, proporsi, dan keterbacaan.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Ubah catatan pelajaran menjadi diagram, sketsa, atau peta konsep.',
                ],
                'questions' => [
                    'Saya mudah membayangkan bentuk atau tampilan suatu benda sebelum membuatnya.',
                    'Saya mudah memahami informasi melalui gambar, diagram, peta, atau desain.',
                    'Saya tertarik membuat desain, mengatur tata letak, atau menyusun tampilan visual.',
                    'Saya mudah mengenali arah, posisi, jarak, atau hubungan antarobjek dalam ruang.',
                ],
                'sort_order' => 3,
            ],
            [
                'slug' => 'bodily-kinesthetic',
                'name' => 'Bodily-Kinesthetic Intelligence',
                'label' => 'Kecerdasan Kinestetik-Jasmani • The Hands-on Practitioner',
                'color' => '#CF6A64',
                'icon' => 'award',
                'description' => 'Kecenderungan menggunakan gerakan tubuh, koordinasi, keterampilan motorik, dan aktivitas langsung untuk melakukan, membuat, atau mengekspresikan sesuatu. Area ini dapat muncul dalam olahraga, seni gerak, keterampilan tangan, praktik laboratorium, dan kegiatan vokasional.',
                'details' => [
                    'short_code' => 'BK',
                    'indonesian_name' => 'Kecerdasan Kinestetik-Jasmani',
                    'archetype' => 'The Hands-on Practitioner',
                    'personal_strength' => [
                        'Belajar melalui mencoba, mempraktikkan, dan memperbaiki.',
                        'Menggunakan koordinasi tubuh atau keterampilan tangan.',
                        'Menunjukkan ketekunan dalam latihan keterampilan.',
                        'Memahami urutan kerja melalui demonstrasi dan praktik.',
                        'Menghasilkan sesuatu melalui aktivitas fisik atau manipulasi benda.',
                    ],
                    'learning_style' => [
                        'Ikuti demonstrasi lalu praktikkan langkahnya.',
                        'Gunakan simulasi, model, eksperimen, atau proyek langsung.',
                        'Pecah keterampilan menjadi tahapan kecil dan ulangi dengan umpan balik.',
                        'Gunakan jeda gerak singkat saat belajar dalam waktu lama.',
                        'Padukan praktik dengan membaca instruksi dan refleksi agar pemahaman konseptual tetap kuat.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: pendidikan jasmani, ilmu keolahragaan, seni tari atau teater, pendidikan vokasi, tata boga, teknik terapan, kesehatan tertentu, dan program berbasis praktik. Periksa persyaratan fisik, keselamatan, sertifikasi, dan prasyarat akademik pada setiap bidang.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: atlet atau pelatih, instruktur kebugaran, chef, teknisi, perajin, penata rambut, performer, terapis okupasi atau fisioterapis (dengan pendidikan dan lisensi yang sesuai), serta teknisi laboratorium. Kesesuaian profesi harus dinilai bersama kompetensi dan persyaratan formal.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: usaha kuliner, kerajinan, jasa perbaikan, kelas keterampilan, pelatihan olahraga, produk buatan tangan, jasa produksi, dan layanan berbasis praktik. Utamakan keselamatan kerja, standar mutu, perhitungan biaya, dan uji pasar.',
                    'daily_practice' => [
                        'Latih keterampilan praktis secara rutin dan bertahap.',
                        'Ikuti kegiatan olahraga atau gerak yang aman dan disukai.',
                        'Buat atau perbaiki benda sederhana dengan pengawasan bila diperlukan.',
                        'Praktikkan resep, eksperimen, atau proyek kerajinan.',
                        'Catat proses, kesalahan, dan perbaikan setelah latihan.',
                        'Jaga pemanasan, istirahat, dan keselamatan saat beraktivitas.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Latih keterampilan praktis atau proyek kerajinan secara rutin dan bertahap.',
                ],
                'questions' => [
                    'Saya lebih mudah memahami sesuatu jika langsung mencoba atau mempraktikkannya.',
                    'Saya menikmati kegiatan yang melibatkan keterampilan tangan, gerakan, atau koordinasi tubuh.',
                    'Saya lebih cepat menguasai keterampilan baru ketika melihat contoh dan langsung berlatih.',
                    'Saya senang membuat atau memperbaiki sesuatu menggunakan keterampilan praktik.',
                ],
                'sort_order' => 4,
            ],
            [
                'slug' => 'musical',
                'name' => 'Musical Intelligence',
                'label' => 'Kecerdasan Musikal • The Musical Explorer',
                'color' => '#B365A1',
                'icon' => 'bell',
                'description' => 'Kecenderungan mengenali, membedakan, mengingat, dan mengolah nada, irama, melodi, tempo, serta pola suara. Area ini dapat terlihat dalam kepekaan terhadap musik dan ketertarikan untuk bernyanyi, memainkan instrumen, atau menciptakan bunyi.',
                'details' => [
                    'short_code' => 'MU',
                    'indonesian_name' => 'Kecerdasan Musikal',
                    'archetype' => 'The Musical Explorer',
                    'personal_strength' => [
                        'Mengingat melodi, ritme, atau pola suara.',
                        'Mengenali perubahan nada, tempo, dan irama.',
                        'Menikmati ekspresi atau penciptaan musikal.',
                        'Memperhatikan kualitas suara dan susunan bunyi.',
                        'Menggunakan musik sebagai salah satu bentuk ekspresi kreatif.',
                    ],
                    'learning_style' => [
                        'Gunakan audio atau rekaman penjelasan jika membantu.',
                        'Buat jingle atau ritme untuk mengingat urutan tertentu bila sesuai.',
                        'Dengarkan materi musik secara aktif dan identifikasi pola.',
                        'Pelajari konsep musik melalui notasi, praktik instrumen, dan teori dasar.',
                        'Untuk materi nonmusik, tetap kombinasikan audio dengan latihan, teks, diskusi, dan visual.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: pendidikan musik, komposisi, seni pertunjukan, produksi musik, teknologi audio, etnomusikologi, dan bidang kreatif terkait suara. Periksa kebutuhan kemampuan musikal, portofolio, audisi, atau ujian masuk.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: musisi, penyanyi, komposer, arranger, produser musik, sound engineer, pengajar musik, teknisi audio, dan pekerja produksi pertunjukan. Jalur ini memerlukan latihan, pengalaman, jejaring, serta pemahaman hak cipta dan industri.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: kursus musik, studio latihan atau rekaman, produksi jingle, layanan audio untuk acara, komposisi musik, kanal edukasi musik, dan penyewaan alat musik. Perhatikan investasi peralatan, lisensi, hak cipta, dan kebutuhan pasar.',
                    'daily_practice' => [
                        'Dengarkan satu karya musik secara aktif dan identifikasi instrumennya.',
                        'Latih ritme dengan tepukan atau alat sederhana.',
                        'Pelajari instrumen atau vokal secara bertahap.',
                        'Rekam latihan dan evaluasi perkembangan.',
                        'Ciptakan melodi atau pola ritme sederhana.',
                        'Eksplorasi berbagai genre dan budaya musik dengan terbuka.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Dengarkan satu karya musik secara aktif dan latih ritme atau instrumen secara bertahap.',
                ],
                'questions' => [
                    'Saya mudah mengingat lagu, irama, atau melodi yang pernah saya dengar.',
                    'Saya bisa membedakan perubahan nada atau irama dalam sebuah musik.',
                    'Musik sering membantu saya mengekspresikan perasaan atau suasana hati.',
                    'Saya tertarik mempelajari alat musik, menciptakan irama, atau memahami struktur lagu.',
                ],
                'sort_order' => 5,
            ],
            [
                'slug' => 'interpersonal',
                'name' => 'Interpersonal Intelligence',
                'label' => 'Kecerdasan Interpersonal • The People Connector',
                'color' => '#439D8A',
                'icon' => 'users',
                'description' => 'Kecenderungan memahami orang lain, memperhatikan sudut pandang dan dinamika hubungan, serta berinteraksi dan bekerja sama. Area ini dapat terlihat dalam komunikasi sosial, kolaborasi, membantu kelompok, atau mengelola perbedaan.',
                'details' => [
                    'short_code' => 'IN',
                    'indonesian_name' => 'Kecerdasan Interpersonal',
                    'archetype' => 'The People Connector',
                    'personal_strength' => [
                        'Mendengarkan dan mempertimbangkan perspektif orang lain.',
                        'Membangun relasi dan bekerja sama.',
                        'Berkomunikasi dalam kelompok dan berbagi gagasan.',
                        'Peka terhadap dinamika sosial dan kebutuhan tim.',
                        'Membantu koordinasi atau penyelesaian perbedaan secara konstruktif.',
                    ],
                    'learning_style' => [
                        'Diskusi kelompok dengan tujuan dan peran yang jelas.',
                        'Belajar berpasangan atau menjelaskan materi kepada teman.',
                        'Ikut proyek kolaboratif dan saling memberi umpan balik.',
                        'Berlatih mendengarkan aktif dan mengajukan pertanyaan.',
                        'Sediakan waktu belajar mandiri agar pemahaman pribadi tetap terukur.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: ilmu komunikasi, pendidikan, psikologi, sosiologi, hubungan masyarakat, ilmu sosial, manajemen, sumber daya manusia, dan bidang pelayanan. Pilihan akademik harus mempertimbangkan ketertarikan pada materi, tuntutan profesi, dan persyaratan pendidikan.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: pendidik, fasilitator, HR, customer relations, sales, public relations, pekerja sosial, konsultan, manajer komunitas, dan pengelola proyek. Kemampuan mendengarkan, etika, pengetahuan bidang, dan keterampilan profesional tetap penting.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: event organizer, layanan komunitas, pelatihan, jasa pelanggan, bisnis berbasis jaringan, marketplace niche, dan usaha kolaboratif. Bangun kepercayaan, pahami kebutuhan pelanggan, dan jangan mengandalkan jaringan sosial saja tanpa model bisnis yang sehat.',
                    'daily_practice' => [
                        'Latih mendengarkan tanpa memotong pembicaraan.',
                        'Ikut organisasi atau proyek kelompok dengan kontribusi nyata.',
                        'Minta dan berikan umpan balik secara sopan.',
                        'Coba menjadi fasilitator diskusi atau koordinator kegiatan.',
                        'Belajar menyelesaikan perbedaan dengan mencari kebutuhan bersama.',
                        'Jaga batas pribadi dan hormati perbedaan.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Ikut diskusi atau proyek kelompok dengan peran jelas dan latih mendengarkan aktif.',
                ],
                'questions' => [
                    'Saya senang bekerja sama dan bertukar ide dengan banyak orang.',
                    'Teman sering datang kepada saya untuk meminta pendapat atau berdiskusi.',
                    'Saya dapat memahami sudut pandang orang lain meskipun berbeda dengan pendapat saya.',
                    'Saya menikmati kegiatan mengatur kelompok, membangun kerja sama, atau membantu menyelesaikan konflik.',
                ],
                'sort_order' => 6,
            ],
            [
                'slug' => 'intrapersonal',
                'name' => 'Intrapersonal Intelligence',
                'label' => 'Kecerdasan Intrapersonal • The Self Explorer',
                'color' => '#5A80B5',
                'icon' => 'sparkles',
                'description' => 'Kecenderungan memahami kondisi diri, mengenali perasaan, nilai, motivasi, dan tujuan pribadi, serta melakukan refleksi dan evaluasi. Area ini berkaitan dengan kesadaran diri dan pengelolaan arah pengembangan, bukan berarti seseorang selalu mandiri atau tidak membutuhkan orang lain.',
                'details' => [
                    'short_code' => 'IA',
                    'indonesian_name' => 'Kecerdasan Intrapersonal',
                    'archetype' => 'The Self Explorer',
                    'personal_strength' => [
                        'Mengenali perasaan, kebutuhan, dan respons diri.',
                        'Melakukan refleksi atas pengalaman dan keputusan.',
                        'Menetapkan tujuan pribadi dan memantau kemajuan.',
                        'Menyadari hal yang dianggap penting atau bermakna.',
                        'Mengembangkan motivasi dan tanggung jawab terhadap proses belajar.',
                    ],
                    'learning_style' => [
                        'Tetapkan tujuan belajar yang spesifik dan realistis.',
                        'Gunakan jurnal belajar untuk mencatat pemahaman dan kesulitan.',
                        'Buat jadwal mandiri, lalu evaluasi secara berkala.',
                        'Hubungkan materi dengan pertanyaan atau tujuan pribadi.',
                        'Tetap cari diskusi, mentor, dan umpan balik; refleksi diri tidak harus dilakukan sendirian.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: psikologi, filsafat, pendidikan, studi agama atau humaniora sesuai minat, penulisan, riset, dan bidang yang memerlukan refleksi serta pengelolaan proyek mandiri. Tidak ada jurusan yang otomatis ditentukan oleh skor intrapersonal.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: peneliti, penulis, pendidik, analis, konsultan pada bidang yang dikuasai, wirausahawan, dan profesi berbasis keahlian. Pilihan perlu disesuaikan dengan kompetensi, pengalaman, kebutuhan pasar, dan kemampuan bekerja dengan orang lain.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: produk edukasi digital, penulisan atau penerbitan, layanan berbasis keahlian, kursus, produk refleksi atau perencanaan, dan usaha solopreneur. Pastikan keahlian benar-benar dikuasai dan klaim layanan tidak melampaui kompetensi.',
                    'daily_practice' => [
                        'Tulis jurnal singkat tentang pengalaman dan pelajaran hari ini.',
                        'Tetapkan satu tujuan mingguan dan tinjau kemajuannya.',
                        'Kenali situasi yang membuat fokus atau kehilangan motivasi.',
                        'Latih mengambil keputusan kecil dan mengevaluasi hasilnya.',
                        'Minta masukan dari orang tepercaya untuk membandingkan refleksi diri.',
                        'Seimbangkan waktu mandiri dengan kolaborasi dan istirahat.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Tulis jurnal singkat harian dan tetapkan satu tujuan mingguan untuk dievaluasi.',
                ],
                'questions' => [
                    'Saya sering mengevaluasi diri untuk memahami kelebihan dan kekurangan saya.',
                    'Saya mengetahui kondisi dan perasaan diri sendiri ketika menghadapi masalah.',
                    'Saya senang menentukan target pribadi dan memantau perkembangan diri.',
                    'Saya bisa bekerja mandiri dan tetap termotivasi meskipun tidak diawasi orang lain.',
                ],
                'sort_order' => 7,
            ],
            [
                'slug' => 'naturalistic',
                'name' => 'Naturalistic Intelligence',
                'label' => 'Kecerdasan Naturalis • The Nature Explorer',
                'color' => '#71934B',
                'icon' => 'globe',
                'description' => 'Kecenderungan mengamati, mengenali, membedakan, dan mengelompokkan unsur alam serta memperhatikan hubungan antara makhluk hidup dan lingkungannya. Area ini dapat muncul dalam ketertarikan pada tumbuhan, hewan, ekosistem, cuaca, atau sumber daya alam.',
                'details' => [
                    'short_code' => 'NA',
                    'indonesian_name' => 'Kecerdasan Naturalis',
                    'archetype' => 'The Nature Explorer',
                    'personal_strength' => [
                        'Mengamati detail dan perubahan di lingkungan alam.',
                        'Mengenali perbedaan dan kesamaan objek alam.',
                        'Mengelompokkan atau mengklasifikasikan makhluk hidup dan unsur lingkungan.',
                        'Tertarik memahami ekosistem dan hubungan antarunsur.',
                        'Memperhatikan isu lingkungan dan keberlanjutan.',
                    ],
                    'learning_style' => [
                        'Lakukan observasi lapangan dengan panduan dan tujuan jelas.',
                        'Gunakan jurnal pengamatan, foto, tabel klasifikasi, atau catatan perubahan.',
                        'Pelajari konsep melalui spesimen, kebun, lingkungan sekitar, atau simulasi yang aman.',
                        'Bandingkan hasil pengamatan dengan sumber ilmiah tepercaya.',
                        'Kombinasikan eksplorasi alam dengan membaca, analisis data, dan diskusi.',
                    ],
                    'academic_exploration' => 'Bidang untuk dieksplorasi: biologi, ilmu lingkungan, kehutanan, pertanian, peternakan, perikanan, kelautan, geografi, konservasi, dan ilmu pangan. Periksa fokus program, kegiatan lapangan, laboratorium, dan persyaratan akademiknya.',
                    'career_exploration' => 'Bidang pekerjaan untuk dieksplorasi: peneliti biologi atau lingkungan, agronom, teknisi laboratorium, konservasionis, pengelola sumber daya alam, ahli perikanan, edukator lingkungan, dan analis keberlanjutan. Sebagian profesi memerlukan pendidikan serta sertifikasi khusus.',
                    'entrepreneurship_potential' => 'Ide kewirausahaan untuk dieksplorasi: urban farming, pembibitan tanaman, produk pertanian atau pangan, budidaya, jasa lanskap, pengolahan limbah yang sesuai aturan, edukasi lingkungan, dan produk ramah lingkungan. Uji kelayakan, dampak lingkungan, keamanan produk, dan kebutuhan pasar.',
                    'daily_practice' => [
                        'Rawat tanaman atau amati satu jenis tumbuhan secara berkala.',
                        'Buat jurnal cuaca atau perubahan lingkungan sekitar.',
                        'Kunjungi ruang terbuka, kebun, atau tempat edukasi alam.',
                        'Pilah sampah dan pelajari pengelolaan yang benar.',
                        'Amati keanekaragaman hayati tanpa merusak habitat.',
                        'Verifikasi informasi alam dari sumber ilmiah tepercaya.',
                    ],
                    'note' => $masterNote,
                    'activity' => 'Rawat tanaman atau buat jurnal pengamatan perubahan lingkungan sekitar.',
                ],
                'questions' => [
                    'Saya tertarik mengamati tumbuhan, hewan, atau fenomena alam di sekitar.',
                    'Saya senang mengelompokkan atau membandingkan berbagai jenis benda, makhluk hidup, atau lingkungan.',
                    'Saya tertarik mempelajari bagaimana lingkungan alam bekerja dan saling berhubungan.',
                    'Saya senang mengamati perubahan cuaca, kondisi lingkungan, atau karakteristik alam.',
                ],
                'sort_order' => 8,
            ],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $cat) {
            $questions = $cat['questions'];
            unset($cat['questions']);

            $model = SiapCategory::updateOrCreate(
                [
                    'siap_assessment_id' => $assessment->id,
                    'slug' => $cat['slug'],
                ],
                $cat
            );
            $categoryModels[] = ['model' => $model, 'questions' => $questions];
        }

        // Interleave round-by-round (4 rounds x 8 categories = 32 questions)
        $order = 1;
        for ($round = 0; $round < 4; $round++) {
            foreach ($categoryModels as $item) {
                if (isset($item['questions'][$round])) {
                    SiapQuestion::updateOrCreate(
                        [
                            'siap_assessment_id' => $assessment->id,
                            'sort_order' => $order,
                        ],
                        [
                            'siap_category_id' => $item['model']->id,
                            'question_text' => $item['questions'][$round],
                            'options' => null,
                            'is_active' => true,
                        ]
                    );
                    $order++;
                }
            }
        }

        SiapQuestion::where('siap_assessment_id', $assessment->id)
            ->where('sort_order', '>', 32)
            ->delete();
    }
}
