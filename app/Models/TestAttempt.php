<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'participant_name',
        'google_email',
        'birth_place',
        'birth_date',
        'address',
        'whatsapp',
        'testable_type',
        'testable_id',
        'test_type',
        'score',
        'total_questions',
        'correct_answers',
        'passed',
        'is_paid',
        'paid_at',
        'payment_amount',
        'answers',
        'personality_summary',
        'email_sent_at',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'score' => 'decimal:2',
        'total_questions' => 'integer',
        'correct_answers' => 'integer',
        'passed' => 'boolean',
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
        'payment_amount' => 'integer',
        'answers' => 'array',
        'email_sent_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function testable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getFormattedTtlAttribute(): string
    {
        $parts = [];
        if ($this->birth_place) {
            $parts[] = $this->birth_place;
        }
        if ($this->birth_date) {
            $parts[] = $this->birth_date->format('d/m/Y');
        }

        return $parts ? implode(', ', $parts) : '-';
    }

    public function getPersonalityProfileAttribute(): array
    {
        $catalog = self::personalityCatalog();

        // Determine dominant archetype from user's selected answer order (1=Architect, 2=Catalyst, 3=Guardian, 4=Explorer)
        $counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

        if (is_array($this->answers) && count($this->answers) > 0) {
            $answerIds = array_filter(array_map('intval', array_values($this->answers)));
            if (! empty($answerIds)) {
                $orders = QuizAnswer::whereIn('id', $answerIds)->pluck('order');
                foreach ($orders as $ord) {
                    $bucket = ((int) $ord >= 1 && (int) $ord <= 4) ? (int) $ord : 1;
                    $counts[$bucket]++;
                }
            }
        }

        $totalCounted = array_sum($counts);

        if ($totalCounted > 0) {
            arsort($counts);
            $dominantKey = (int) array_key_first($counts);
            $dimensions = [
                'Logika & Strategi (Architect)' => round(($counts[1] / $totalCounted) * 100),
                'Inovasi & Visi (Catalyst)' => round(($counts[2] / $totalCounted) * 100),
                'Empati & Harmoni (Guardian)' => round(($counts[3] / $totalCounted) * 100),
                'Taktis & Adaptabilitas (Explorer)' => round(($counts[4] / $totalCounted) * 100),
            ];
        } else {
            // Fallback based on score range
            $score = (float) $this->score;
            if ($score >= 80) {
                $dominantKey = 1;
                $dimensions = [
                    'Logika & Strategi (Architect)' => 45,
                    'Inovasi & Visi (Catalyst)' => 25,
                    'Empati & Harmoni (Guardian)' => 15,
                    'Taktis & Adaptabilitas (Explorer)' => 15,
                ];
            } elseif ($score >= 60) {
                $dominantKey = 2;
                $dimensions = [
                    'Logika & Strategi (Architect)' => 20,
                    'Inovasi & Visi (Catalyst)' => 45,
                    'Empati & Harmoni (Guardian)' => 20,
                    'Taktis & Adaptabilitas (Explorer)' => 15,
                ];
            } elseif ($score >= 40) {
                $dominantKey = 3;
                $dimensions = [
                    'Logika & Strategi (Architect)' => 15,
                    'Inovasi & Visi (Catalyst)' => 20,
                    'Empati & Harmoni (Guardian)' => 45,
                    'Taktis & Adaptabilitas (Explorer)' => 20,
                ];
            } else {
                $dominantKey = 4;
                $dimensions = [
                    'Logika & Strategi (Architect)' => 15,
                    'Inovasi & Visi (Catalyst)' => 20,
                    'Empati & Harmoni (Guardian)' => 15,
                    'Taktis & Adaptabilitas (Explorer)' => 50,
                ];
            }
        }

        $selected = $catalog[$dominantKey] ?? $catalog[1];
        $selected['dimensions'] = $dimensions;

        return $selected;
    }

    public static function personalityCatalog(): array
    {
        return [
            1 => [
                'code' => 'THE STRATEGIC ARCHITECT',
                'archetype' => 'The Strategic Architect (Si Perancang Sistem & Logika)',
                'tagline' => '"Ketepatan strategi hari ini menentukan kemenangan esok hari."',
                'badge_color' => 'bg-indigo-100 text-indigo-800',
                'preview' => 'Anda memiliki ketajaman analisis struktural yang luar biasa. Di tengah kekacauan atau tekanan waktu, otak Anda otomatis mencari pola, menyusun kerangka logis, dan mengambil keputusan paling rasional.',
                'full_summary' => 'Sebagai seorang Strategic Architect, Anda adalah pemikir sistem alami yang memandang setiap masalah sebagai teka-teki logis yang bisa dipecahkan. Anda tidak mudah terpengaruh oleh kepanikan emosional di sekitar Anda; sebaliknya, Anda mengambil jarak untuk melihat gambaran besar (big picture) lalu merancang langkah-langkah eksekusi yang presisi. Dalam lingkungan profesional maupun akademik, Anda dikenal sebagai sosok yang dapat diandalkan untuk merumuskan strategi jangka panjang, mengoptimalkan efisiensi kerja, dan menjaga standar kualitas tertinggi.',
                'superpower' => 'Pattern Recognition & Sistemisasi — Mampu menyederhanakan masalah rumit menjadi blueprint kerja yang terukur dalam hitungan menit.',
                'strengths' => [
                    'Berpikir objektif, berbasis data, dan tahan terhadap bias emosional sesaat',
                    'Perencana jangka panjang yang selalu menyiapkan skenario cadangan (Plan B & C)',
                    'Mandiri, fokus tinggi, dan memiliki standar kualitas kerja di atas rata-rata',
                ],
                'blind_spots' => [
                    'Terkadang terlalu perfeksionis sehingga sulit mendelegasikan tugas ke orang lain',
                    'Cenderung terlihat dingin atau terlalu kritis saat berhadapan dengan rekan yang lambat',
                ],
                'work_style' => 'Bekerja paling optimal di lingkungan yang terstruktur, minim interupsi mikro, dengan target KPI yang jelas dan otonomi penuh untuk merancang solusi.',
                'careers' => ['System Architect / Software Engineer', 'Data Scientist & Strategist', 'Product Manager', 'Management Consultant'],
                'partner_match' => 'The Empathetic Guardian & The Dynamic Explorer',
            ],
            2 => [
                'code' => 'THE VISIONARY CATALYST',
                'archetype' => 'The Visionary Catalyst (Si Penggerak Inovasi & Ide)',
                'tagline' => '"Masa depan tidak ditunggu, melainkan diciptakan lewat keberanian berinovasi."',
                'badge_color' => 'bg-amber-100 text-amber-800',
                'preview' => 'Anda adalah sumber energi kreatif dan penggerak perubahan. Anda mampu melihat peluang baru di tempat yang dianggap buntu oleh orang lain, serta menginspirasi tim untuk bergerak cepat.',
                'full_summary' => 'Sebagai seorang Visionary Catalyst, pikiran Anda selalu bergerak dua langkah lebih maju ke masa depan. Anda memiliki kombinasi langka antara intuisi kreatif, keberanian mengambil risiko terukur, dan karisma komunikasi yang mampu menyalakan antusiasme orang-orang di sekitar Anda. Anda paling bersinar ketika diberi tantangan untuk membangun sesuatu dari nol, mendobrak cara-cara lama yang tidak lagi relevan, dan memimpin inisiatif perubahan yang berdampak besar.',
                'superpower' => 'Creative Disruption & Persuasi — Mampu mengubah ide abstrak menjadi visi nyata yang menggerakkan banyak orang.',
                'strengths' => [
                    'Sangat adaptif, penuh ide segar, dan berani keluar dari zona nyaman',
                    'Komunikator persuasif yang mampu membangun antusiasme dan visi bersama',
                    'Cepat menangkap tren baru dan menghubungkan berbagai konsep lintas bidang',
                ],
                'blind_spots' => [
                    'Mudah bosan dengan rutinitas administratif atau detail teknis yang berulang',
                    'Terkadang terlalu banyak memulai ide baru sekaligus sebelum proyek lama tuntas 100%',
                ],
                'work_style' => 'Berkembang pesat di lingkungan yang dinamis, kolaboratif, terbuka pada eksperimen baru, dan tidak dikekang oleh birokrasi kaku.',
                'careers' => ['Creative Director / Founder', 'Growth & Marketing Strategist', 'UI/UX & Product Designer', 'Business Development Lead'],
                'partner_match' => 'The Strategic Architect (sebagai penyeimbang eksekusi detail)',
            ],
            3 => [
                'code' => 'THE EMPATHETIC GUARDIAN',
                'archetype' => 'The Empathetic Guardian (Si Penjaga Harmoni & Kolaborasi)',
                'tagline' => '"Kekuatan terbesar sebuah tim lahir ketika setiap orang merasa didengar dan dihargai."',
                'badge_color' => 'bg-emerald-100 text-emerald-800',
                'preview' => 'Anda memiliki kecerdasan emosional (EQ) dan kepekaan sosial yang sangat tinggi. Anda adalah perekat utama dalam tim yang memastikan kolaborasi berjalan harmonis dan penuh kepercayaan.',
                'full_summary' => 'Sebagai seorang Empathetic Guardian, Anda memiliki radar sosial yang tajam untuk membaca dinamika emosi, motivasi, dan kebutuhan orang-orang di sekitar Anda. Anda memimpin bukan dengan paksaan, melainkan dengan keteladanan, empati, dan kemampuan mendengarkan secara mendalam. Kehadiran Anda menciptakan rasa aman psikologis (psychological safety) di dalam tim, sehingga konflik dapat diselesaikan secara dewasa dan setiap anggota tim dapat mengeluarkan potensi terbaiknya.',
                'superpower' => 'Emotional Resonance & Mediasi — Mampu meredakan ketegangan konflik dan menyatukan karakter-karakter berbeda menjadi tim yang solid.',
                'strengths' => [
                    'Pendengar aktif dengan empati tinggi yang mampu membangun kepercayaan jangka panjang',
                    'Sangat loyal, suportif, dan konsisten dalam menjaga komitmen terhadap tim',
                    'Peka terhadap detail kebutuhan pengguna (user-centric) maupun kesejahteraan rekan kerja',
                ],
                'blind_spots' => [
                    'Sering mendahulukan kepentingan orang lain hingga mengorbankan energi diri sendiri',
                    'Terkadang ragu memberikan teguran keras karena tidak ingin merusak suasana hubungan',
                ],
                'work_style' => 'Bekerja paling produktif dalam budaya kerja yang suportif, menjunjung nilai kolaborasi, memiliki misi sosial yang jelas, dan menghargai kontribusi manusia.',
                'careers' => ['People Operations / HR Specialist', 'Customer Success & Community Lead', 'UX Researcher', 'Psychologist / Mentor & Coach'],
                'partner_match' => 'The Strategic Architect & The Visionary Catalyst',
            ],
            4 => [
                'code' => 'THE DYNAMIC EXPLORER',
                'archetype' => 'The Dynamic Explorer (Si Eksekutor Taktis & Adaptif)',
                'tagline' => '"Tindakan nyata di lapangan lebih berharga daripada seribu teori di atas kertas."',
                'badge_color' => 'bg-purple-100 text-purple-800',
                'preview' => 'Anda adalah pemecah masalah taktis yang sangat tangguh di situasi krisis. Saat rencana berubah mendadak, Anda langsung bereaksi cepat, praktis, dan menemukan jalan keluar di lapangan.',
                'full_summary' => 'Sebagai seorang Dynamic Explorer, Anda memiliki refleks adaptasi dan ketangguhan mental yang luar biasa saat berhadapan dengan ketidakpastian. Anda tidak suka terjebak dalam rapat panjang tanpa ujung; Anda lebih memilih langsung terjun ke lapangan, menguji solusi secara nyata, dan melakukan penyesuaian cepat berdasarkan kondisi riil. Di saat orang lain membeku karena tekanan waktu atau perubahan mendadak, Anda justru tampil paling tenang dan taktis.',
                'superpower' => 'Rapid Crisis Execution — Mampu mengambil keputusan taktis secara instan dan mengubah hambatan mendadak menjadi kemenangan praktis.',
                'strengths' => [
                    'Responsif, pragmatis, dan sangat tenang saat menghadapi situasi darurat atau deadline ketat',
                    'Cepat belajar dari praktik langsung (learning by doing) dan fleksibel terhadap perubahan alat/metode',
                    'Berorientasi pada hasil konkret yang langsung terasa dampaknya',
                ],
                'blind_spots' => [
                    'Kurang menyukai pembuatan dokumentasi panjang atau perencanaan teoretis yang terlalu lama',
                    'Terkadang mengambil jalan pintas taktis yang perlu dirapikan kembali untuk skala jangka panjang',
                ],
                'work_style' => 'Sangat menyukai tantangan lapangan yang bergerak cepat (fast-paced), berorientasi pada aksi nyata, dengan birokrasi minimal dan umpan balik instan.',
                'careers' => ['Operations & Crisis Manager', 'Full-Stack Developer / DevOps', 'Project & Event Lead', 'Tactical Sales & Field Specialist'],
                'partner_match' => 'The Strategic Architect & The Empathetic Guardian',
            ],
        ];
    }
}
