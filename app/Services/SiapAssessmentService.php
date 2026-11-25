<?php

namespace App\Services;

use App\Mail\SiapResultMail;
use App\Models\Certificate;
use App\Models\Option;
use App\Models\SiapAssessment;
use App\Models\SiapAttempt;
use App\Models\SiapOrder;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SiapAssessmentService
{
    /**
     * Get configurable pricing for Super Finger and Paket Lengkap SIAP.
     */
    public function getPricing(): array
    {
        $options = Option::where('category', 'siap_pricing')
            ->pluck('label', 'key')
            ->toArray();

        $sfNormal = (int) ($options['siap_super_finger_normal_price'] ?? 350000);
        $sfPromo = (int) ($options['siap_super_finger_price'] ?? 99000);
        $bundlePrice = (int) ($options['siap_bundle_price'] ?? 500000);

        $discountPercent = $sfNormal > 0
            ? max(0, (int) round((($sfNormal - $sfPromo) / $sfNormal) * 100))
            : 0;

        return [
            'super_finger_normal' => $sfNormal,
            'super_finger_promo' => $sfPromo,
            'super_finger_discount_percent' => $discountPercent,
            'bundle_price' => $bundlePrice,
        ];
    }

    /**
     * Calculate category scores and dominant profile for a SIAP attempt.
     *
     * @param  array<int|string, mixed>  $answers
     * @return array{scores: array<int, array<string, mixed>>, dominant_profile: array<string, mixed>}
     */
    public function calculateResults(SiapAssessment $assessment, array $answers): array
    {
        $assessment->loadMissing(['categories', 'questions']);

        if ($assessment->isForcedChoice()) {
            return $this->calculateForcedChoiceResults($assessment, $answers);
        }

        return $this->calculateLikertResults($assessment, $answers);
    }

    private function calculateForcedChoiceResults(SiapAssessment $assessment, array $answers): array
    {
        $counts = [];
        foreach ($assessment->categories as $category) {
            $counts[$category->slug] = 0;
        }

        $activeQuestions = $assessment->questions->where('is_active', true)->values();
        $totalQuestions = max(1, $activeQuestions->count());

        foreach ($activeQuestions as $question) {
            $chosen = strtoupper(trim((string) ($answers[$question->id] ?? $answers[(string) $question->id] ?? '')));
            if ($chosen === '') {
                continue;
            }

            $matchedOption = collect($question->options ?? [])
                ->first(fn ($opt) => strtoupper((string) ($opt['key'] ?? '')) === $chosen);

            $slug = $matchedOption['category_slug'] ?? null;
            if ($slug && array_key_exists($slug, $counts)) {
                $counts[$slug]++;
            }
        }

        $scores = $assessment->categories->map(function ($cat) use ($counts, $totalQuestions) {
            $raw = $counts[$cat->slug] ?? 0;
            $percentage = (int) round(($raw / $totalQuestions) * 100);

            return [
                'category_id' => $cat->id,
                'slug' => $cat->slug,
                'code' => $cat->code,
                'name' => $cat->name,
                'label' => $cat->label,
                'symbol' => $cat->symbol,
                'color' => $cat->color,
                'icon' => $cat->icon,
                'raw_score' => $raw,
                'max_score' => $totalQuestions,
                'percentage' => $percentage,
            ];
        })->sortByDesc('raw_score')->values()->all();

        $topScore = $scores[0]['percentage'] ?? 0;
        $topRaw = $scores[0]['raw_score'] ?? 0;

        // Combination check per docs: equal or within 4 percentage points (<= 1 question difference on 25 items)
        $primarySlugs = [];
        $primaryNames = [];
        if ($topRaw > 0) {
            foreach ($scores as $item) {
                if (($topScore - $item['percentage']) <= 4 && $item['raw_score'] > 0) {
                    $primarySlugs[] = $item['slug'];
                    $primaryNames[] = $item['name'];
                }
            }
        } else {
            $primarySlugs[] = $scores[0]['slug'] ?? 'square';
            $primaryNames[] = $scores[0]['name'] ?? 'Square (Kotak)';
        }

        $isCombination = count($primarySlugs) > 1;
        $dominantLabel = $isCombination
            ? 'Profil Kombinasi: '.implode(' + ', $primaryNames)
            : ($primaryNames[0] ?? 'Square (Kotak)');

        return [
            'scores' => $scores,
            'dominant_profile' => [
                'is_combination' => $isCombination,
                'primary_slugs' => $primarySlugs,
                'label' => $dominantLabel,
                'top_percentage' => $topScore,
            ],
        ];
    }

    private function calculateLikertResults(SiapAssessment $assessment, array $answers): array
    {
        $sums = [];
        $counts = [];
        foreach ($assessment->categories as $category) {
            $sums[$category->id] = 0;
            $counts[$category->id] = 0;
        }

        $activeQuestions = $assessment->questions->where('is_active', true)->values();
        foreach ($activeQuestions as $question) {
            if (! $question->siap_category_id) {
                continue;
            }
            $counts[$question->siap_category_id] = ($counts[$question->siap_category_id] ?? 0) + 1;
            $val = (int) ($answers[$question->id] ?? $answers[(string) $question->id] ?? 0);
            if ($val >= 1 && $val <= 4) {
                $sums[$question->siap_category_id] = ($sums[$question->siap_category_id] ?? 0) + $val;
            }
        }

        $scores = $assessment->categories->map(function ($cat) use ($sums, $counts) {
            $raw = $sums[$cat->id] ?? 0;
            $qCount = max(1, $counts[$cat->id] ?? 4);
            $max = $qCount * 4;
            $percentage = (int) round(($raw / $max) * 100);

            return [
                'category_id' => $cat->id,
                'slug' => $cat->slug,
                'code' => $cat->code,
                'name' => $cat->name,
                'label' => $cat->label,
                'symbol' => $cat->symbol,
                'color' => $cat->color,
                'icon' => $cat->icon,
                'raw_score' => $raw,
                'max_score' => $max,
                'percentage' => $percentage,
            ];
        })->sort(fn ($a, $b) => ($b['percentage'] <=> $a['percentage']) ?: ($b['raw_score'] <=> $a['raw_score']))->values()->all();

        $topScore = $scores[0]['percentage'] ?? 0;
        $primarySlugs = [];
        $primaryNames = [];
        foreach ($scores as $item) {
            if ($item['percentage'] === $topScore && $topScore > 0) {
                $primarySlugs[] = $item['slug'];
                $primaryNames[] = $item['name'];
            }
        }
        if (empty($primarySlugs) && ! empty($scores[0])) {
            $primarySlugs[] = $scores[0]['slug'];
            $primaryNames[] = $scores[0]['name'];
        }

        $topThreeCutoff = (int) ($scores[2]['percentage'] ?? 0);
        $hasTopTie = count($primarySlugs) > 1
            || ($topThreeCutoff > 0 && collect($scores)->slice(3)->contains(fn ($item) => (int) ($item['percentage'] ?? 0) === $topThreeCutoff));

        $isCombination = count($primarySlugs) > 1;
        $dominantLabel = $isCombination
            ? 'Profil Kombinasi: '.implode(' + ', $primaryNames)
            : ($primaryNames[0] ?? 'Linguistic Intelligence');

        return [
            'scores' => $scores,
            'dominant_profile' => [
                'is_combination' => $isCombination,
                'has_top_tie' => $hasTopTie,
                'primary_slugs' => $primarySlugs,
                'label' => $dominantLabel,
                'top_percentage' => $topScore,
            ],
        ];
    }

    /**
     * Build recommendation table rows for "Langkah Selanjutnya untuk Mengembangkan Potensimu".
     *
     * @param  array<int, array<string, mixed>>  $scores
     * @return array<int, array{focus: string, recommendation: string}>
     */
    public function buildRecommendations(SiapAssessment $assessment, array $scores): array
    {
        $assessment->loadMissing('categories');
        $categoriesBySlug = $assessment->categories->keyBy('slug');

        if ($assessment->isForcedChoice()) {
            $topTwo = array_slice($scores, 0, 2);
            $primary = $categoriesBySlug->get($topTwo[0]['slug'] ?? '');
            $secondary = $categoriesBySlug->get($topTwo[1]['slug'] ?? '');

            if (! $primary) {
                return [];
            }

            $pDetails = $primary->details ?? [];
            $sDetails = $secondary?->details ?? [];

            return [
                [
                    'focus' => 'Cara Belajar & Strategi',
                    'recommendation' => ($pDetails['learning_style'] ?? '-').($secondary ? ' Sebagai pendukung ('.$secondary->name.'): '.($sDetails['learning_style'] ?? '') : ''),
                ],
                [
                    'focus' => 'Eksplorasi Akademik',
                    'recommendation' => collect([$pDetails['academic_exploration'] ?? null, $sDetails['academic_exploration'] ?? null])
                        ->filter()
                        ->implode(' • '),
                ],
                [
                    'focus' => 'Eksplorasi Karier',
                    'recommendation' => collect([$pDetails['career_exploration'] ?? null, $sDetails['career_exploration'] ?? null])
                        ->filter()
                        ->implode(' • '),
                ],
                [
                    'focus' => 'Potensi Kewirausahaan',
                    'recommendation' => $pDetails['entrepreneurship_potential'] ?? '-',
                ],
                [
                    'focus' => 'Latihan Mingguan',
                    'recommendation' => $pDetails['practice'] ?? '-',
                ],
            ];
        }

        $topThreeCutoff = (int) ($scores[2]['percentage'] ?? 0);
        $topItems = collect($scores)->take(3);
        if ($topThreeCutoff > 0) {
            $tiedAtThird = collect($scores)->slice(3)->filter(fn ($s) => (int) ($s['percentage'] ?? 0) === $topThreeCutoff);
            $topItems = $topItems->concat($tiedAtThird);
        }

        $topCats = $topItems
            ->map(fn ($s) => $categoriesBySlug->get($s['slug'] ?? ''))
            ->filter()
            ->values();

        if ($topCats->isEmpty()) {
            return [];
        }

        $formatBulletList = function (string $key, ?string $suffix = null) use ($topCats): string {
            $lines = $topCats->map(function ($cat) use ($key) {
                $items = (array) ($cat->details[$key] ?? []);
                $picked = collect($items)->filter()->take(2)->implode('; ');

                return $picked !== '' ? "{$cat->name}: {$picked}" : null;
            })->filter();

            if ($suffix && $lines->isNotEmpty()) {
                $lines->push($suffix);
            }

            return $lines->isNotEmpty() ? $lines->implode("\n") : '-';
        };

        $formatTextField = function (string $key) use ($topCats): string {
            $lines = $topCats->map(function ($cat) use ($key) {
                $val = trim((string) ($cat->details[$key] ?? ''));

                return $val !== '' ? "{$cat->name}: {$val}" : null;
            })->filter();

            return $lines->isNotEmpty() ? $lines->implode("\n") : '-';
        };

        return [
            [
                'focus' => 'Personal Strength',
                'recommendation' => $formatBulletList('personal_strength'),
            ],
            [
                'focus' => 'Learning Style & Learning Strategy',
                'recommendation' => $formatBulletList(
                    'learning_style',
                    'Catatan: Strategi di atas adalah pilihan untuk dicoba, bukan tipe belajar permanen.'
                ),
            ],
            [
                'focus' => 'Academic Exploration',
                'recommendation' => $formatTextField('academic_exploration'),
            ],
            [
                'focus' => 'Career Exploration',
                'recommendation' => $formatTextField('career_exploration'),
            ],
            [
                'focus' => 'Entrepreneurship Potential',
                'recommendation' => $formatTextField('entrepreneurship_potential'),
            ],
            [
                'focus' => 'Praktik Sehari-hari',
                'recommendation' => $formatBulletList('daily_practice'),
            ],
        ];
    }

    /**
     * Check whether the actor (user or guest token) already owns full access for the given assessment.
     */
    public function userHasUnlockedAssessment(?User $user, SiapAssessment $assessment, ?string $guestToken = null): bool
    {
        if (! $user && ! $guestToken) {
            return false;
        }

        $actorFilter = function ($query) use ($user, $guestToken) {
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                $query->where('guest_token', $guestToken);
            }
        };

        $hasBundle = SiapOrder::where($actorFilter)
            ->where('product_type', SiapOrder::PRODUCT_BUNDLE)
            ->where('status', SiapOrder::STATUS_PAID)
            ->exists();

        if ($hasBundle) {
            return true;
        }

        $hasAssessmentOrder = SiapOrder::where($actorFilter)
            ->where('product_type', SiapOrder::PRODUCT_ASSESSMENT_FULL)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapOrder::STATUS_PAID)
            ->exists();

        if ($hasAssessmentOrder) {
            return true;
        }

        return SiapAttempt::where($actorFilter)
            ->where('siap_assessment_id', $assessment->id)
            ->where('is_full_unlocked', true)
            ->exists();
    }

    /**
     * Generate a unique single-use Super Finger redeem code (SF-XXXX-XXXX).
     */
    public function generateRedeemCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $part1 = '';
            $part2 = '';
            for ($i = 0; $i < 4; $i++) {
                $part1 .= $chars[random_int(0, strlen($chars) - 1)];
                $part2 .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $code = "SF-{$part1}-{$part2}";
        } while (SiapOrder::where('redeem_code', $code)->exists());

        return $code;
    }

    /**
     * Issue a SIAP assessment certificate for a completed, unlocked attempt (idempotent).
     */
    public function issueCertificateIfEligible(SiapAttempt $attempt): ?Certificate
    {
        if (! $attempt->is_full_unlocked || ! $attempt->isCompleted()) {
            return null;
        }

        $existing = Certificate::where('certifiable_type', SiapAttempt::class)
            ->where('certifiable_id', $attempt->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        $attempt->loadMissing(['user', 'assessment']);
        $dominantLabel = $attempt->dominant_profile['label'] ?? 'Profil Potensi Diri';

        return Certificate::createForUser(
            $attempt->user,
            'Sertifikat Asesmen SIAP — '.$attempt->assessment->title,
            SiapAttempt::class,
            $attempt->id,
            'Kecenderungan Dominan: '.$dominantLabel
        );
    }

    /**
     * Idempotently fulfill a paid SIAP order (from Mock payment or Xendit webhook).
     */
    public function fulfillPaidOrder(SiapOrder $order): void
    {
        $updates = [
            'status' => SiapOrder::STATUS_PAID,
            'paid_at' => $order->paid_at ?? now(),
        ];

        if ($order->includesRedeemCode() && empty($order->redeem_code)) {
            $updates['redeem_code'] = $this->generateRedeemCode();
            $updates['redeem_status'] = SiapOrder::REDEEM_UNUSED;
        }

        if ($order->isBundle() && empty($order->fulfillment_notes)) {
            $updates['fulfillment_notes'] = [
                'merchandise' => 'pending',
                'mentoring' => 'pending',
                'career_mapping' => 'pending',
                'voucher' => 'pending',
                'notes' => '',
            ];
        }

        $order->update($updates);
        $order->refresh();

        $user = $order->user;

        $actorAttemptQuery = function () use ($order) {
            return SiapAttempt::where('status', SiapAttempt::STATUS_COMPLETED)
                ->where(function ($q) use ($order) {
                    if ($order->user_id) {
                        $q->where('user_id', $order->user_id);
                    } elseif ($order->guest_token) {
                        $q->where('guest_token', $order->guest_token);
                    } elseif ($order->siap_attempt_id) {
                        $q->where('id', $order->siap_attempt_id);
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                });
        };

        if ($order->product_type === SiapOrder::PRODUCT_ASSESSMENT_FULL) {
            $attempts = $actorAttemptQuery()
                ->where('siap_assessment_id', $order->siap_assessment_id)
                ->get();

            foreach ($attempts as $attempt) {
                if (! $attempt->is_full_unlocked) {
                    $attempt->update([
                        'is_full_unlocked' => true,
                        'unlocked_at' => now(),
                        'payment_amount' => $order->final_price,
                    ]);
                }
                $this->issueCertificateIfEligible($attempt->fresh());
            }

            $targetAttempt = $order->attempt ?: $attempts->sortByDesc('completed_at')->first();
            if ($targetAttempt) {
                $this->sendResultEmail($targetAttempt->fresh(), true);
            }
        } elseif ($order->product_type === SiapOrder::PRODUCT_BUNDLE) {
            $attempts = $actorAttemptQuery()->get();

            foreach ($attempts as $attempt) {
                if (! $attempt->is_full_unlocked) {
                    $attempt->update([
                        'is_full_unlocked' => true,
                        'unlocked_at' => now(),
                        'payment_amount' => $order->final_price,
                    ]);
                }
                $this->issueCertificateIfEligible($attempt->fresh());
            }

            $targetAttempt = $order->attempt ?: $attempts->sortByDesc('completed_at')->first();
            if ($targetAttempt) {
                $this->sendResultEmail($targetAttempt->fresh(), true);
            }
        }

        if ($user) {
            try {
                $message = $order->redeem_code
                    ? "Pembayaran {$order->product_name} berhasil! Kode redeem Super Finger Anda: {$order->redeem_code}"
                    : "Pembayaran {$order->product_name} berhasil! Hasil lengkap & sertifikat SIAP kini terbuka.";

                app(NotificationService::class)->create(
                    $user->id,
                    'siap_order_paid',
                    'Pembayaran SIAP Berhasil',
                    $message,
                    [
                        'icon' => 'check-circle',
                        'color' => 'emerald',
                        'link' => route('siap.order.success', $order),
                    ]
                );
            } catch (\Throwable $e) {
                Log::warning('Failed to create SIAP notification: '.$e->getMessage());
            }
        }
    }

    /**
     * Send Preview or Full Report email to the participant's Google email.
     */
    public function sendResultEmail(SiapAttempt $attempt, bool $isFullReport): void
    {
        try {
            $attempt->loadMissing(['user', 'assessment.categories']);
            $recipient = $attempt->google_email ?: $attempt->user?->email;
            if (! $recipient) {
                return;
            }

            $recommendations = $isFullReport
                ? $this->buildRecommendations($attempt->assessment, $attempt->scores ?? [])
                : [];

            Mail::to($recipient)->send(new SiapResultMail($attempt, $isFullReport, $recommendations));
            $attempt->update(['email_sent_at' => now()]);
        } catch (\Throwable $e) {
            Log::warning('Failed to send SIAP result email: '.$e->getMessage());
        }
    }

    /**
     * Validate and consume a Super Finger redeem code once.
     *
     * @return array{success: bool, message: string, order?: SiapOrder}
     */
    public function redeemCode(string $rawCode, User $admin): array
    {
        $code = strtoupper(trim($rawCode));
        if ($code === '') {
            return ['success' => false, 'message' => 'Masukkan kode redeem terlebih dahulu.'];
        }

        $order = SiapOrder::with(['user', 'redeemedByUser'])
            ->where('redeem_code', $code)
            ->first();

        if (! $order) {
            return ['success' => false, 'message' => "Kode redeem {$code} tidak ditemukan."];
        }

        if (! $order->isPaid()) {
            return ['success' => false, 'message' => "Pesanan untuk kode {$code} belum berstatus LUNAS."];
        }

        if ($order->redeem_status === SiapOrder::REDEEM_USED) {
            $usedAt = $order->redeemed_at?->format('d/m/Y H:i') ?? '-';
            return [
                'success' => false,
                'message' => "Kode {$code} sudah pernah digunakan pada {$usedAt}. Satu kode hanya berlaku satu kali.",
                'order' => $order,
            ];
        }

        $order->update([
            'redeem_status' => SiapOrder::REDEEM_USED,
            'redeemed_at' => now(),
            'redeemed_by' => $admin->id,
        ]);

        return [
            'success' => true,
            'message' => "Kode {$code} ({$order->participant_name}) berhasil divalidasi dan ditandai SUDAH DIGUNAKAN.",
            'order' => $order->fresh(['user', 'redeemedByUser']),
        ];
    }

    /**
     * Sync missing profile fields back to User record (same pattern as legacy global quiz).
     *
     * @param  array<string, mixed>  $data
     */
    public function syncUserPersonalData(User $user, array $data): void
    {
        $updates = [];

        if (empty($user->phone) && ! empty($data['whatsapp']) && $data['whatsapp'] !== '-') {
            $updates['phone'] = $data['whatsapp'];
        }
        if (empty($user->birth_place) && ! empty($data['birth_place']) && $data['birth_place'] !== '-') {
            $updates['birth_place'] = $data['birth_place'];
        }
        if (empty($user->birth_date) && ! empty($data['birth_date'])) {
            $updates['birth_date'] = $data['birth_date'];
        }
        if (empty($user->address) && ! empty($data['address']) && $data['address'] !== '-') {
            $updates['address'] = $data['address'];
        }

        if (! empty($updates)) {
            $user->update($updates);
        }
    }
}
