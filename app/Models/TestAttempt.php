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
        $score = (float) $this->score;

        if ($score >= 80) {
            return [
                'archetype' => 'Analytical Visionary & Strategic Leader',
                'badge_color' => 'bg-emerald-100 text-emerald-800',
                'preview' => 'Anda memiliki ketajaman analisis tinggi, berorientasi pada solusi sistematis, serta mampu mengambil keputusan cepat di bawah tekanan waktu.',
                'full_summary' => 'Berdasarkan pola jawaban dan kecepatan respons Anda, Anda termasuk dalam profil Analytical Visionary & Strategic Leader. Anda memproses informasi secara terstruktur, memiliki regulasi emosi yang stabil saat menghadapi batas waktu ketat, dan mampu memprioritaskan ketepatan logika. Kekuatan utama Anda terletak pada perencanaan strategis, pemecahan masalah kompleks, serta kemampuan beradaptasi dengan cepat pada situasi baru. Area pengembangan yang disarankan adalah memberikan ruang delegasi lebih luas kepada tim dan menjaga keseimbangan antara target perfeksionisme dengan fleksibilitas eksekusi.',
            ];
        }

        if ($score >= 55) {
            return [
                'archetype' => 'Adaptive Collaborator & Balanced Thinker',
                'badge_color' => 'bg-blue-100 text-blue-800',
                'preview' => 'Anda memiliki keseimbangan antara intuisi dan logika, fleksibel dalam menghadapi perubahan, serta kooperatif dalam lingkungan tim.',
                'full_summary' => 'Berdasarkan pola jawaban Anda, Anda memiliki profil Adaptive Collaborator & Balanced Thinker. Anda cenderung mempertimbangkan berbagai sudut pandang sebelum mengambil keputusan, mampu bekerja sama secara harmonis, dan tetap tenang ketika menghadapi situasi yang dinamis. Kekuatan utama Anda meliputi empati komunikasi, daya tahan terhadap perubahan, dan pemikiran praktis yang aplikatif. Area pengembangan yang disarankan adalah meningkatkan ketegasan saat harus mengambil keputusan cepat di bawah tekanan waktu yang sempit.',
            ];
        }

        return [
            'archetype' => 'Reflective Explorer & Creative Intuitive',
            'badge_color' => 'bg-purple-100 text-purple-800',
            'preview' => 'Anda mengutamakan kedalaman pengamatan, pendekatan intuitif-kreatif, serta kehati-hatian sebelum menarik kesimpulan.',
            'full_summary' => 'Berdasarkan pola jawaban Anda, Anda termasuk dalam profil Reflective Explorer & Creative Intuitive. Anda lebih nyaman bekerja dengan kedalaman observasi dan tidak suka terburu-buru dalam menyimpulkan sesuatu tanpa eksplorasi menyeluruh. Kekuatan utama Anda ada pada kepekaan melihat detail yang sering terlewatkan orang lain, imajinasi solusi kreatif, serta ketulusan dalam berinteraksi. Area pengembangan yang disarankan adalah melatih manajemen waktu dan kecepatan eksekusi ketika berhadapan dengan tenggat waktu (deadline) yang singkat.',
        ];
    }
}
