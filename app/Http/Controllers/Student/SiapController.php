<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\PaymentTransaction;
use App\Models\PromoCode;
use App\Models\SiapAssessment;
use App\Models\SiapAttempt;
use App\Models\SiapOrder;
use App\Models\TestAttempt;
use App\Models\User;
use App\Services\AchievementService;
use App\Services\Payment\PaymentService;
use App\Services\SiapAssessmentService;
use App\Services\XpService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SiapController extends Controller
{
    public function __construct(
        private SiapAssessmentService $siapService,
        private XpService $xpService
    ) {}

    private function getGuestToken(): string
    {
        $token = session('siap_guest_token');
        if (! is_string($token) || $token === '') {
            $token = (string) Str::uuid();
            session(['siap_guest_token' => $token]);
        }

        return $token;
    }

    private function scopeActor(Builder $query, ?User $user, string $guestToken): Builder
    {
        if ($user) {
            return $query->where('user_id', $user->id);
        }

        return $query->where('guest_token', $guestToken);
    }

    private function actorOwns(object $model, ?User $user, string $guestToken): bool
    {
        if ($user && $user->canAccessAdmin()) {
            return true;
        }

        if ($user && isset($model->user_id) && (int) $model->user_id === (int) $user->id) {
            return true;
        }

        return ! empty($model->guest_token) && hash_equals((string) $model->guest_token, $guestToken);
    }

    /**
     * SIAP Asesmen Hub (Multiple Intelligence Test + Psycho Geometric Test + Super Finger + Bundle)
     */
    public function index(): View
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        $assessments = SiapAssessment::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $latestAttempts = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->latest('completed_at')
            ->get()
            ->groupBy('siap_assessment_id')
            ->map(fn ($items) => $items->first());

        $inProgressAttempts = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('status', SiapAttempt::STATUS_IN_PROGRESS)
            ->latest('updated_at')
            ->get()
            ->groupBy('siap_assessment_id')
            ->map(fn ($items) => $items->first());

        $pricing = $this->siapService->getPricing();

        return view('pages.siap.index', compact(
            'user',
            'assessments',
            'latestAttempts',
            'inProgressAttempts',
            'pricing'
        ));
    }

    /**
     * Assessment Intro Page + Pre-Start Confirmation Dialog
     */
    public function intro(SiapAssessment $assessment): View
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();
        $assessment->load(['categories']);

        $inProgressAttempt = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_IN_PROGRESS)
            ->latest('updated_at')
            ->first();

        $latestCompleted = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->latest('completed_at')
            ->first();

        $isUnlocked = $this->siapService->userHasUnlockedAssessment($user, $assessment, $guestToken);

        return view('pages.siap.intro', compact(
            'user',
            'assessment',
            'inProgressAttempt',
            'latestCompleted',
            'isUnlocked'
        ));
    }

    /**
     * Assessment Taking Page (Biodata + 1-by-1 Interactive Questions, no timer, no retake)
     */
    public function take(SiapAssessment $assessment): View|RedirectResponse
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        $existingCompleted = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->latest('completed_at')
            ->first();

        if ($existingCompleted) {
            return redirect()->route('siap.results', $existingCompleted)
                ->with('info', 'Anda sudah menyelesaikan '.$assessment->title.'.');
        }

        $assessment->load([
            'categories',
            'questions' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ]);

        $draft = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_IN_PROGRESS)
            ->latest('updated_at')
            ->first();

        $lastPersonalInfo = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->whereNotNull('participant_name')
            ->latest()
            ->first();

        if (! $lastPersonalInfo && $user) {
            $lastPersonalInfo = TestAttempt::where('user_id', $user->id)
                ->whereNotNull('participant_name')
                ->latest()
                ->first();
        }

        if (! $draft) {
            $defaultGoogleEmail = ($user && Str::endsWith(strtolower((string) $user->email), ['@gmail.com', '@googlemail.com']))
                ? $user->email
                : ($lastPersonalInfo?->google_email ?? '');

            $draft = SiapAttempt::create([
                'user_id' => $user?->id,
                'guest_token' => $user ? null : $guestToken,
                'siap_assessment_id' => $assessment->id,
                'status' => SiapAttempt::STATUS_IN_PROGRESS,
                'participant_name' => $lastPersonalInfo?->participant_name ?? $user?->name,
                'google_email' => $defaultGoogleEmail,
                'birth_place' => null,
                'birth_date' => $lastPersonalInfo?->birth_date ?? $user?->birth_date,
                'address' => $lastPersonalInfo?->address ?? $user?->address,
                'whatsapp' => $lastPersonalInfo?->whatsapp ?? $user?->phone,
                'answers' => [],
                'started_at' => now(),
            ]);
        }

        $isUnlocked = $this->siapService->userHasUnlockedAssessment($user, $assessment, $guestToken);

        return view('pages.siap.take', compact(
            'user',
            'assessment',
            'draft',
            'lastPersonalInfo',
            'isUnlocked'
        ));
    }

    /**
     * AJAX Autosave for in-progress SIAP assessment answers & biodata.
     */
    public function saveProgress(Request $request, SiapAssessment $assessment): JsonResponse
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        $draft = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_IN_PROGRESS)
            ->latest('updated_at')
            ->first();

        if (! $draft) {
            $draft = SiapAttempt::create([
                'user_id' => $user?->id,
                'guest_token' => $user ? null : $guestToken,
                'siap_assessment_id' => $assessment->id,
                'status' => SiapAttempt::STATUS_IN_PROGRESS,
                'started_at' => now(),
            ]);
        }

        $answers = $draft->answers ?? [];
        $incomingAnswers = $request->input('answers', []);
        if (is_array($incomingAnswers)) {
            foreach ($incomingAnswers as $qId => $val) {
                if ($val !== null && $val !== '') {
                    $answers[(string) $qId] = $val;
                }
            }
        }

        $updateData = ['answers' => $answers];
        foreach (['participant_name', 'google_email', 'birth_date', 'address', 'whatsapp'] as $field) {
            if ($request->filled($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        $draft->update($updateData);

        return response()->json([
            'saved' => true,
            'answered_count' => count($answers),
        ]);
    }

    /**
     * Submit Assessment (requires biodata; prevents retake once completed).
     */
    public function submit(Request $request, SiapAssessment $assessment): RedirectResponse
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        $existingCompleted = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->latest('completed_at')
            ->first();

        if ($existingCompleted) {
            return redirect()->route('siap.results', $existingCompleted)
                ->with('info', 'Anda sudah menyelesaikan '.$assessment->title.'.');
        }

        $assessment->load([
            'categories',
            'questions' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ]);

        $validated = $request->validate([
            'participant_name' => 'required|string|max:255',
            'google_email' => [
                'required',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (! Str::endsWith(strtolower((string) $value), ['@gmail.com', '@googlemail.com'])) {
                        $fail('Email harus menggunakan akun Google (@gmail.com).');
                    }
                },
            ],
            'birth_date' => 'required|date',
            'address' => 'required|string|max:1000',
            'whatsapp' => 'required|string|max:30',
            'answers' => 'nullable|array',
        ]);

        if ($user) {
            $this->siapService->syncUserPersonalData($user, $validated);
        }

        $draft = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->where('siap_assessment_id', $assessment->id)
            ->where('status', SiapAttempt::STATUS_IN_PROGRESS)
            ->latest('updated_at')
            ->first();

        $mergedAnswers = is_array($draft?->answers) ? $draft->answers : [];
        $submittedAnswers = $request->input('answers', []);
        if (is_array($submittedAnswers)) {
            foreach ($submittedAnswers as $qId => $val) {
                if ($val !== null && $val !== '') {
                    $mergedAnswers[(string) $qId] = $val;
                }
            }
        }

        if (count($mergedAnswers) < $assessment->questions->count()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Harap jawab semua '.$assessment->questions->count().' pertanyaan sebelum melihat hasil.');
        }

        $computed = $this->siapService->calculateResults($assessment, $mergedAnswers);
        $isUnlocked = $this->siapService->userHasUnlockedAssessment($user, $assessment, $guestToken);
        $timeSpent = max(1, (int) $request->input('time_spent_seconds', 60));

        $attemptAttrs = [
            'user_id' => $user?->id,
            'guest_token' => $user ? null : $guestToken,
            'siap_assessment_id' => $assessment->id,
            'status' => SiapAttempt::STATUS_COMPLETED,
            'participant_name' => $validated['participant_name'],
            'google_email' => $validated['google_email'],
            'birth_place' => null,
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'whatsapp' => $validated['whatsapp'],
            'answers' => $mergedAnswers,
            'scores' => $computed['scores'],
            'dominant_profile' => $computed['dominant_profile'],
            'is_full_unlocked' => $isUnlocked,
            'unlocked_at' => $isUnlocked ? now() : null,
            'payment_amount' => $isUnlocked ? ($assessment->unlock_price ?: 35000) : 0,
            'time_spent_seconds' => $timeSpent,
            'auto_submitted' => false,
            'started_at' => $draft?->started_at ?? now()->subSeconds($timeSpent),
            'completed_at' => now(),
        ];

        if ($draft) {
            $draft->update($attemptAttrs);
            $attempt = $draft->fresh();
        } else {
            $attempt = SiapAttempt::create($attemptAttrs);
        }

        if ($isUnlocked) {
            $this->siapService->issueCertificateIfEligible($attempt);
        }

        $this->siapService->sendResultEmail($attempt, $isUnlocked);

        if ($user) {
            try {
                $this->xpService->awardXp($user, 'quiz_passed', SiapAttempt::class, $attempt->id);
                app(AchievementService::class)->checkAndAward($user, AchievementService::TRIGGER_QUIZ_PASSED);
            } catch (\Throwable $e) {
                // XP / achievements should never block assessment completion
            }
        }

        $flashMessage = $isUnlocked
            ? 'Asesmen selesai! Laporan lengkap dan Sertifikat SIAP telah dibuka serta dikirim ke '.$attempt->google_email.'.'
            : 'Asesmen selesai! Hasil Preview telah dikirim ke '.$attempt->google_email.'. Buka hasil lengkap untuk melihat seluruh analisis dan sertifikat.';

        return redirect()->route('siap.results', $attempt)->with('success', $flashMessage);
    }

    /**
     * Super Finger Product Detail Page
     */
    public function superFinger(): View
    {
        $pricing = $this->siapService->getPricing();

        return view('pages.siap.super-finger', compact('pricing'));
    }

    /**
     * Hasil Asesmen Page (Free blurred #2..N paywall vs Full unlocked report + Recommendations table).
     */
    public function results(?SiapAttempt $attempt = null): View
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        if ($attempt && ! $this->actorOwns($attempt, $user, $guestToken)) {
            abort(403);
        }

        $completedAttempts = SiapAttempt::with('assessment')
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->where(function ($q) use ($attempt, $user, $guestToken) {
                if ($attempt && $attempt->user_id) {
                    $q->where('user_id', $attempt->user_id);
                } elseif ($attempt && $attempt->guest_token) {
                    $q->where('guest_token', $attempt->guest_token);
                } elseif ($user) {
                    $q->where('user_id', $user->id);
                } else {
                    $q->where('guest_token', $guestToken);
                }
            })
            ->latest('completed_at')
            ->get();

        if (! $attempt) {
            $attempt = $completedAttempts->first();
        }

        if (! $attempt) {
            return view('pages.siap.results', [
                'attempt' => null,
                'completedAttempts' => $completedAttempts,
                'scores' => [],
                'categoriesBySlug' => collect(),
                'recommendations' => [],
                'certificate' => null,
                'pricing' => $this->siapService->getPricing(),
            ]);
        }

        $attempt->load(['assessment.categories', 'certificate']);
        $scores = $attempt->scores ?? [];
        $categoriesBySlug = $attempt->assessment?->categories?->keyBy('slug') ?? collect();

        $recommendations = $attempt->is_full_unlocked
            ? $this->siapService->buildRecommendations($attempt->assessment, $scores)
            : [];

        $certificate = $attempt->is_full_unlocked
            ? ($attempt->certificate ?: $this->siapService->issueCertificateIfEligible($attempt))
            : null;

        $pricing = $this->siapService->getPricing();

        return view('pages.siap.results', compact(
            'attempt',
            'completedAttempts',
            'scores',
            'categoriesBySlug',
            'recommendations',
            'certificate',
            'pricing'
        ));
    }

    /**
     * Checkout Page for SIAP Products (`assessment_full`, `super_finger`, `bundle_siap`).
     */
    public function checkout(Request $request, string $product): View|RedirectResponse
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();
        $resolved = $this->resolveCheckoutProduct($request, $product, $user, $guestToken);

        if (! $resolved) {
            return redirect()->route('siap.index')->with('error', 'Produk asesmen tidak ditemukan.');
        }

        $activePromos = PromoCode::available()->latest()->take(5)->get();

        $lastAttemptInfo = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->whereNotNull('participant_name')
            ->latest()
            ->first();

        return view('pages.siap.checkout', [
            'user' => $user,
            'productType' => $product,
            'productName' => $resolved['name'],
            'productSubtitle' => $resolved['subtitle'],
            'normalPrice' => $resolved['normal_price'],
            'promoPrice' => $resolved['promo_price'],
            'assessment' => $resolved['assessment'],
            'attempt' => $resolved['attempt'],
            'activePromos' => $activePromos,
            'lastAttemptInfo' => $lastAttemptInfo,
        ]);
    }

    /**
     * AJAX: Validate PromoCode for SIAP checkout.
     */
    public function applyPromo(Request $request, string $product): JsonResponse
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();
        $resolved = $this->resolveCheckoutProduct($request, $product, $user, $guestToken);
        if (! $resolved) {
            return response()->json(['valid' => false, 'message' => 'Produk tidak valid.'], 404);
        }

        $code = strtoupper(trim((string) $request->input('promo_code')));
        $basePrice = (float) $resolved['promo_price'];

        if ($code === '') {
            return response()->json([
                'valid' => false,
                'message' => 'Masukkan kode diskon terlebih dahulu.',
            ], 422);
        }

        $promo = PromoCode::whereRaw('UPPER(code) = ?', [$code])->first();
        if (! $promo || ! $promo->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode diskon tidak ditemukan atau sudah tidak berlaku.',
            ], 422);
        }

        if ($promo->min_purchase && $basePrice < (float) $promo->min_purchase) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal transaksi untuk kode ini adalah Rp'.number_format((float) $promo->min_purchase, 0, ',', '.').'.',
            ], 422);
        }

        $discount = (int) round($promo->calculateDiscount($basePrice));
        $finalPrice = max(0, (int) $basePrice - $discount);

        return response()->json([
            'valid' => true,
            'code' => $promo->code,
            'name' => $promo->name,
            'type_label' => $promo->type_label,
            'base_price' => (int) $basePrice,
            'discount' => $discount,
            'final_price' => $finalPrice,
            'formatted_discount' => '- Rp'.number_format($discount, 0, ',', '.'),
            'formatted_final' => 'Rp'.number_format($finalPrice, 0, ',', '.'),
            'message' => "Kode diskon {$promo->code} ({$promo->name}) berhasil diterapkan!",
        ]);
    }

    /**
     * Process SIAP Checkout (supports Mock instant fulfillment + Xendit gateway + PromoCode, for guests and auth users).
     */
    public function processCheckout(Request $request, string $product): RedirectResponse
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();
        $resolved = $this->resolveCheckoutProduct($request, $product, $user, $guestToken);

        if (! $resolved) {
            return redirect()->route('siap.index')->with('error', 'Produk asesmen tidak valid.');
        }

        $validated = $request->validate([
            'participant_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'parent_whatsapp' => 'required|string|max:30',
            'parental_consent' => 'accepted',
            'promo_code' => 'nullable|string|max:50',
        ], [
            'parental_consent.accepted' => 'Persetujuan peserta / orang tua atau wali wajib dicentang.',
        ]);

        $basePrice = (float) $resolved['promo_price'];
        $code = strtoupper(trim((string) ($validated['promo_code'] ?? '')));
        $promoDiscount = 0;
        $appliedPromo = null;

        if ($code !== '') {
            $promo = PromoCode::whereRaw('UPPER(code) = ?', [$code])->first();
            if ($promo && $promo->isValid() && (! $promo->min_purchase || $basePrice >= (float) $promo->min_purchase)) {
                $promoDiscount = (int) round($promo->calculateDiscount($basePrice));
                $appliedPromo = $promo;
                $promo->incrementUsage();
            }
        }

        $finalAmount = max(0, (int) $basePrice - $promoDiscount);
        $totalDiscount = max(0, (int) $resolved['normal_price'] - $finalAmount);

        $order = SiapOrder::create([
            'user_id' => $user?->id,
            'guest_token' => $user ? null : $guestToken,
            'siap_assessment_id' => $resolved['assessment']?->id,
            'siap_attempt_id' => $resolved['attempt']?->id,
            'promo_code_id' => $appliedPromo?->id,
            'product_type' => $product,
            'product_name' => $resolved['name'],
            'participant_name' => $validated['participant_name'],
            'email' => $validated['email'],
            'parent_whatsapp' => $validated['parent_whatsapp'],
            'parental_consent' => true,
            'payment_method' => $finalAmount === 0 ? 'promo' : config('payment.driver', 'xendit'),
            'normal_price' => $resolved['normal_price'],
            'discount_amount' => $totalDiscount,
            'final_price' => $finalAmount,
            'status' => SiapOrder::STATUS_PENDING,
        ]);

        $paymentService = app(PaymentService::class);
        $metadata = [
            'siap_order_id' => $order->id,
            'product_type' => $product,
            'assessment_id' => $resolved['assessment']?->id,
            'attempt_id' => $resolved['attempt']?->id,
            'payment_method' => $order->payment_method,
            'promo_code' => $appliedPromo?->code,
            'promo_discount' => $promoDiscount,
            'customer_name' => $order->participant_name,
            'customer_email' => $order->email,
            'customer_phone' => $order->parent_whatsapp,
        ];

        if ($finalAmount > 0 && ! $paymentService->isMockDriver()) {
            $paymentResult = $paymentService->createPayment(
                $user,
                'siap_order',
                $order->id,
                $finalAmount,
                $resolved['name'],
                $metadata
            );

            if (! $paymentResult['success']) {
                return redirect()->route('siap.checkout', array_filter([
                    'product' => $product,
                    'assessment' => $resolved['assessment']?->id,
                    'attempt' => $resolved['attempt']?->id,
                ]))->with('error', $paymentResult['error'] ?? 'Gagal membuat invoice pembayaran Xendit.');
            }

            if (! empty($paymentResult['external_id'])) {
                $tx = PaymentTransaction::where('external_id', $paymentResult['external_id'])->first();
                if ($tx) {
                    $order->update(['payment_transaction_id' => $tx->id]);
                }
            }

            if ($paymentService->requiresRedirect() && ! empty($paymentResult['checkout_url'])) {
                return redirect($paymentResult['checkout_url']);
            }

            return redirect()->route('siap.checkout', ['product' => $product])
                ->with('error', 'Unable to process payment.');
        }

        // Mock payment driver or 100% promo discount
        $tx = PaymentTransaction::create([
            'user_id' => $user?->id,
            'external_id' => ($finalAmount === 0 ? 'FREE-SIAP-' : 'MOCK-SIAP-').strtoupper(Str::random(8)),
            'payment_id' => 'PAY-'.strtoupper(Str::random(10)),
            'driver' => $finalAmount === 0 ? 'promo' : 'mock',
            'item_type' => SiapOrder::class,
            'item_id' => $order->id,
            'amount' => $finalAmount,
            'status' => 'PAID',
            'item_name' => $resolved['name'],
            'metadata' => $metadata,
            'paid_at' => now(),
        ]);

        $order->update(['payment_transaction_id' => $tx->id]);
        $this->siapService->fulfillPaidOrder($order);

        if ($product === SiapOrder::PRODUCT_ASSESSMENT_FULL && $resolved['attempt']) {
            return redirect()->route('siap.results', $resolved['attempt'])
                ->with('success', 'Pembayaran berhasil! Seluruh hasil analisis & Sertifikat Asesmen SIAP kini terbuka.');
        }

        return redirect()->route('siap.order.success', $order)
            ->with('success', 'Pembayaran '.$resolved['name'].' berhasil!');
    }

    /**
     * Payment Success & Redeem Code Display Page.
     */
    public function orderSuccess(SiapOrder $order): View
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        if (! $this->actorOwns($order, $user, $guestToken)) {
            abort(403);
        }

        $order->load(['assessment', 'attempt']);

        $hasCompletedAnyAssessment = $this->scopeActor(SiapAttempt::query(), $order->user, (string) ($order->guest_token ?: $guestToken))
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->exists();

        $firstAssessment = SiapAssessment::where('is_active', true)->orderBy('sort_order')->first();

        return view('pages.siap.success', compact(
            'order',
            'hasCompletedAnyAssessment',
            'firstAssessment'
        ));
    }

    /**
     * Sertifikat Asesmen & Purchase History Page.
     */
    public function certificates(): View
    {
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        $actorAttemptIds = $this->scopeActor(SiapAttempt::query(), $user, $guestToken)
            ->pluck('id');

        $certificates = $actorAttemptIds->isNotEmpty()
            ? Certificate::where('certifiable_type', SiapAttempt::class)
                ->whereIn('certifiable_id', $actorAttemptIds)
                ->with('certifiable.assessment')
                ->latest('issued_date')
                ->get()
            : collect();

        $orders = $this->scopeActor(SiapOrder::query(), $user, $guestToken)
            ->with(['assessment', 'attempt'])
            ->latest()
            ->get();

        $firstAssessment = SiapAssessment::where('is_active', true)->orderBy('sort_order')->first();

        return view('pages.siap.certificates', compact(
            'user',
            'certificates',
            'orders',
            'firstAssessment'
        ));
    }

    /**
     * Printable SIAP Assessment Certificate View.
     */
    public function printCertificate(Certificate $certificate): View
    {
        if ($certificate->certifiable_type !== SiapAttempt::class) {
            abort(404);
        }

        $certificate->load('certifiable.assessment', 'user');
        $attempt = $certificate->certifiable;
        $user = Auth::user();
        $guestToken = $this->getGuestToken();

        if (! $attempt || ! $this->actorOwns($attempt, $user, $guestToken)) {
            abort(403);
        }

        return view('pages.siap.certificate-print', compact('certificate'));
    }

    /**
     * Resolve product pricing & context for SIAP checkout.
     *
     * @return array{name: string, subtitle: string, normal_price: int, promo_price: int, assessment: ?SiapAssessment, attempt: ?SiapAttempt}|null
     */
    private function resolveCheckoutProduct(Request $request, string $product, ?User $user, string $guestToken): ?array
    {
        $pricing = $this->siapService->getPricing();

        if ($product === SiapOrder::PRODUCT_ASSESSMENT_FULL) {
            $attemptId = $request->query('attempt') ?? $request->input('attempt_id');
            $attempt = $attemptId
                ? $this->scopeActor(SiapAttempt::query(), $user, $guestToken)->with('assessment')->find($attemptId)
                : null;

            $assessmentId = $request->query('assessment') ?? $request->input('assessment_id') ?? $attempt?->siap_assessment_id;
            $assessment = $assessmentId
                ? SiapAssessment::find($assessmentId)
                : SiapAssessment::where('is_active', true)->orderBy('sort_order')->first();

            if (! $assessment) {
                return null;
            }

            return [
                'name' => $assessment->title.' – Full Result',
                'subtitle' => 'Buka seluruh diagram dominasi, analisis lengkap setiap dimensi, rekomendasi belajar-karier, dan Sertifikat Asesmen SIAP.',
                'normal_price' => (int) ($assessment->normal_price ?: 125000),
                'promo_price' => (int) ($assessment->unlock_price ?: 35000),
                'assessment' => $assessment,
                'attempt' => $attempt,
            ];
        }

        if ($product === SiapOrder::PRODUCT_SUPER_FINGER) {
            return [
                'name' => 'Super Finger Assessment',
                'subtitle' => 'Analisis sidik jari (Nature | Genotipe) di lokasi mitra resmi 1Langkah. Kode redeem diterbitkan otomatis setelah pembayaran.',
                'normal_price' => $pricing['super_finger_normal'],
                'promo_price' => $pricing['super_finger_promo'],
                'assessment' => null,
                'attempt' => null,
            ];
        }

        if ($product === SiapOrder::PRODUCT_BUNDLE) {
            $attemptId = $request->query('attempt') ?? $request->input('attempt_id');
            $attempt = $attemptId
                ? $this->scopeActor(SiapAttempt::query(), $user, $guestToken)->with('assessment')->find($attemptId)
                : null;

            return [
                'name' => 'Paket Lengkap SIAP',
                'subtitle' => 'Paket lengkap: Full Result asesmen, Super Finger redeem code, sertifikat & merchandise, 1 sesi mentoring (2 jam), mapping karier-akademisi, dan voucher kursus.',
                'normal_price' => $pricing['bundle_price'],
                'promo_price' => $pricing['bundle_price'],
                'assessment' => $attempt?->assessment,
                'attempt' => $attempt,
            ];
        }

        return null;
    }
}
