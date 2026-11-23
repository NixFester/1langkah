<?php

namespace App\Http\Controllers;

use App\Mail\QuizResultMail;
use App\Models\Course;
use App\Models\PaymentTransaction;
use App\Models\PromoCode;
use App\Models\Quiz;
use App\Models\TestAttempt;
use App\Services\AchievementService;
use App\Services\XpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizController extends Controller
{
    private XpService $xpService;

    public function __construct(XpService $xpService)
    {
        $this->xpService = $xpService;
    }

    /**
     * Check if user can access this quiz (global quizzes are open to all students)
     */
    private function canAccessQuiz($user, Quiz $quiz): bool
    {
        if ($quiz->is_global || ! $quiz->course_id) {
            return true;
        }

        return $user->enrollments()
            ->where('purchasable_type', Course::class)
            ->where('purchasable_id', $quiz->course_id)
            ->exists();
    }

    /**
     * Check if user already has a paid access for this quiz (via session pre-pay or paid attempt)
     */
    private function hasPaidForQuiz($user, Quiz $quiz): bool
    {
        if (session()->get("quiz_prepaid_{$quiz->id}_{$user->id}", false)) {
            return true;
        }

        return TestAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->where('is_paid', true)
            ->exists();
    }

    /**
     * Show available quizzes (Global Psychology Tests + Course Quizzes)
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        $globalQuizzes = Quiz::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_global', true)->orWhereNull('course_id');
            })
            ->withCount('questions')
            ->orderBy('order')
            ->get();

        $quizzes = Quiz::where('is_active', true)
            ->where('is_global', false)
            ->whereNotNull('course_id')
            ->with('course')
            ->withCount('questions')
            ->get()
            ->groupBy('course_id');

        return view('pages.quizzes', [
            'globalQuizzes' => $globalQuizzes,
            'quizzesByCourse' => $quizzes,
            'user' => $user,
        ]);
    }

    /**
     * Start a quiz
     */
    public function start(Quiz $quiz): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $this->canAccessQuiz($user, $quiz)) {
            return redirect()->route('kursus')
                ->with('error', __('app.msg_error_kamu_harus_terdaftar_di_kursus_ini_untuk'));
        }

        $quiz->load(['questions.answers']);

        $existingAttempt = TestAttempt::where('user_id', $user->id)
            ->where(function ($query) use ($quiz) {
                $query->where('quiz_id', $quiz->id)
                    ->orWhere(function ($q) use ($quiz) {
                        if ($quiz->course_id) {
                            $q->whereNull('quiz_id')
                                ->where('testable_type', Course::class)
                                ->where('testable_id', $quiz->course_id)
                                ->where('test_type', $quiz->type);
                        }
                    });
            })
            ->whereNotNull('completed_at')
            ->latest()
            ->first();

        $lastPersonalInfo = TestAttempt::where('user_id', $user->id)
            ->whereNotNull('participant_name')
            ->latest()
            ->first();

        $isPaidAccess = $this->hasPaidForQuiz($user, $quiz);
        $freeLimit = ($quiz->is_global && ! $isPaidAccess)
            ? ($quiz->free_question_limit ?: 10)
            : $quiz->questions->count();

        return view('pages.quiz-take', [
            'quiz' => $quiz,
            'existingAttempt' => $existingAttempt,
            'lastPersonalInfo' => $lastPersonalInfo,
            'user' => $user,
            'isPaidAccess' => $isPaidAccess,
            'freeLimit' => $freeLimit,
        ]);
    }

    /**
     * Show dedicated Checkout Page for Quiz Payment (with discount/promo code support like "CONTOH")
     */
    public function checkout(Request $request, Quiz $quiz): View
    {
        $user = Auth::user();
        $attemptId = $request->query('attempt');
        $attempt = $attemptId
            ? TestAttempt::where('user_id', $user->id)->find($attemptId)
            : null;

        $basePrice = $quiz->unlock_price ?: 30000;
        $activePromos = PromoCode::available()->latest()->take(5)->get();

        return view('pages.quiz-checkout', [
            'quiz' => $quiz,
            'attempt' => $attempt,
            'basePrice' => $basePrice,
            'activePromos' => $activePromos,
            'user' => $user,
        ]);
    }

    /**
     * AJAX: Validate & calculate promo/discount code on Quiz Checkout page
     */
    public function applyPromo(Request $request, Quiz $quiz): JsonResponse
    {
        $code = strtoupper(trim((string) $request->input('promo_code')));
        $basePrice = (float) ($quiz->unlock_price ?: 30000);

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
                'message' => 'Minimal transaksi untuk kode ini adalah Rp '.number_format((float) $promo->min_purchase, 0, ',', '.').'.',
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
            'formatted_discount' => '- Rp '.number_format($discount, 0, ',', '.'),
            'formatted_final' => 'Rp '.number_format($finalPrice, 0, ',', '.'),
            'message' => "Kode diskon {$promo->code} ({$promo->name}) berhasil diterapkan!",
        ]);
    }

    /**
     * Process Mock Payment from Quiz Checkout page (supports both pre-test unlock and result unlock)
     */
    public function processCheckout(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();
        $basePrice = (float) ($quiz->unlock_price ?: 30000);
        $code = strtoupper(trim((string) $request->input('promo_code')));
        $paymentMethod = $request->input('payment_method', 'qris');
        $attemptId = $request->input('attempt_id');

        $discount = 0;
        $appliedPromo = null;

        if ($code !== '') {
            $promo = PromoCode::whereRaw('UPPER(code) = ?', [$code])->first();
            if ($promo && $promo->isValid()) {
                $discount = (int) round($promo->calculateDiscount($basePrice));
                $appliedPromo = $promo;
                $promo->incrementUsage();
            }
        }

        $finalAmount = max(0, (int) $basePrice - $discount);

        $attempt = $attemptId
            ? TestAttempt::where('user_id', $user->id)->find($attemptId)
            : null;

        PaymentTransaction::create([
            'user_id' => $user->id,
            'external_id' => 'MOCK-QUIZ-CHK-'.strtoupper(Str::random(8)),
            'payment_id' => 'PAY-'.strtoupper(Str::random(10)),
            'driver' => 'mock',
            'item_type' => $attempt ? TestAttempt::class : Quiz::class,
            'item_id' => $attempt ? $attempt->id : $quiz->id,
            'amount' => $finalAmount,
            'status' => 'PAID',
            'item_name' => 'Akses Penuh '.$quiz->title,
            'metadata' => [
                'quiz_id' => $quiz->id,
                'attempt_id' => $attempt?->id,
                'payment_method' => $paymentMethod,
                'original_price' => (int) $basePrice,
                'discount_amount' => $discount,
                'promo_code' => $appliedPromo?->code,
            ],
            'paid_at' => now(),
        ]);

        // Unlock all existing attempts for this user & quiz
        TestAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->update([
                'is_paid' => true,
                'paid_at' => now(),
                'payment_amount' => $finalAmount,
            ]);

        // Mark session prepaid so student can answer all 20 questions
        session()->put("quiz_prepaid_{$quiz->id}_{$user->id}", true);

        $promoNote = $appliedPromo
            ? " (Diskon {$appliedPromo->code}: -Rp ".number_format($discount, 0, ',', '.').')'
            : '';

        if ($attempt) {
            $attempt->refresh();
            $quiz->load('questions.answers');

            try {
                $recipient = $attempt->google_email ?: $user->email;
                Mail::to($recipient)->send(new QuizResultMail($attempt, $quiz, true));
                $attempt->update(['email_sent_at' => now()]);
            } catch (\Throwable $e) {
                \Log::warning('Failed to send full quiz result email after checkout: '.$e->getMessage());
            }

            return redirect()->route('quiz.result', $attempt)
                ->with('success', 'Pembayaran Rp '.number_format($finalAmount, 0, ',', '.').$promoNote.' berhasil! Rangkuman kepribadian penuh & seluruh rincian jawaban kini terbuka dan telah dikirim ke email Google Anda.');
        }

        return redirect()->route('quiz.start', ['quiz' => $quiz->id, 'started' => 1])
            ->with('success', 'Pembayaran Rp '.number_format($finalAmount, 0, ',', '.').$promoNote.' berhasil! Seluruh soal dan laporan kepribadian penuh telah dibuka.');
    }

    /**
     * Pre-pay (Mock Payment Rp 30.000) from the pre-test notification modal before starting the test
     */
    public function prePay(Request $request, Quiz $quiz): RedirectResponse
    {
        return redirect()->route('quiz.checkout', $quiz);
    }

    /**
     * Submit quiz answers (supports manual submit & auto-submit when timer expires)
     */
    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();

        if (! $this->canAccessQuiz($user, $quiz)) {
            return redirect()->route('kursus')
                ->with('error', __('app.msg_error_kamu_harus_terdaftar_di_kursus_ini_untuk'));
        }

        $isAutoSubmit = $request->boolean('auto_submitted', false);
        $defaultGoogleEmail = Str::endsWith(strtolower($user->email), ['@gmail.com', '@googlemail.com'])
            ? $user->email
            : ($request->input('google_email') ?: $user->email);

        if ($isAutoSubmit) {
            // When time expires, guarantee submission succeeds even if some fields were left blank
            $validated = [
                'participant_name' => $request->input('participant_name') ?: $user->name,
                'google_email' => $request->input('google_email') ?: $defaultGoogleEmail,
                'birth_place' => $request->input('birth_place') ?: ($user->birth_place ?: '-'),
                'birth_date' => $request->input('birth_date') ?: ($user->birth_date ? $user->birth_date->format('Y-m-d') : null),
                'address' => $request->input('address') ?: ($user->address ?: '-'),
                'whatsapp' => $request->input('whatsapp') ?: ($user->phone ?: '-'),
            ];
        } else {
            $validated = $request->validate([
                'participant_name' => 'required|string|max:255',
                'google_email' => [
                    'required',
                    'email',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        if (! Str::endsWith(strtolower($value), ['@gmail.com', '@googlemail.com'])) {
                            $fail('Email harus menggunakan akun Google (@gmail.com).');
                        }
                    },
                ],
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'address' => 'required|string|max:1000',
                'whatsapp' => 'required|string|max:30',
                'answers' => 'nullable|array',
            ]);
        }

        // Sync personal data back to user profile if not yet set
        $userUpdates = [];
        if (empty($user->phone) && ! empty($validated['whatsapp']) && $validated['whatsapp'] !== '-') {
            $userUpdates['phone'] = $validated['whatsapp'];
        }
        if (empty($user->birth_place) && ! empty($validated['birth_place']) && $validated['birth_place'] !== '-') {
            $userUpdates['birth_place'] = $validated['birth_place'];
        }
        if (empty($user->birth_date) && ! empty($validated['birth_date'])) {
            $userUpdates['birth_date'] = $validated['birth_date'];
        }
        if (empty($user->address) && ! empty($validated['address']) && $validated['address'] !== '-') {
            $userUpdates['address'] = $validated['address'];
        }
        if (! empty($userUpdates)) {
            $user->update($userUpdates);
        }

        $quiz->load(['questions.answers']);
        $isPaidAccess = $this->hasPaidForQuiz($user, $quiz);
        $freeLimit = ($quiz->is_global && ! $isPaidAccess)
            ? ($quiz->free_question_limit ?: 10)
            : $quiz->questions->count();

        // Filter answers: if unpaid on a global test, only accept up to $freeLimit questions
        $rawAnswers = $request->input('answers', []);
        $allowedQuestions = $quiz->questions->sortBy('order')->values();
        $acceptedAnswers = [];
        $correctCount = 0;
        $totalQuestions = $quiz->questions->count();

        foreach ($allowedQuestions as $index => $question) {
            $isLockedQuestion = ($index >= $freeLimit);
            if ($isLockedQuestion && ! $isPaidAccess) {
                continue;
            }

            $userAnswerId = $rawAnswers[$question->id] ?? null;
            if ($userAnswerId !== null && $userAnswerId !== '') {
                $acceptedAnswers[$question->id] = $userAnswerId;
            }

            if ($question->type === 'essay') {
                continue;
            }

            if ($userAnswerId) {
                $isCorrect = $question->answers()
                    ->where('id', $userAnswerId)
                    ->where('is_correct', true)
                    ->exists();

                if ($isCorrect) {
                    $correctCount++;
                }
            }
        }

        $scoredQuestions = $quiz->questions->where('type', '!=', 'essay')->count();
        $score = $scoredQuestions > 0
            ? round(($correctCount / $scoredQuestions) * 100, 2)
            : 0;
        $passed = $score >= $quiz->passing_score;
        $amount = $quiz->unlock_price ?: 30000;

        $attempt = TestAttempt::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'participant_name' => $validated['participant_name'],
            'google_email' => $validated['google_email'],
            'birth_place' => $validated['birth_place'],
            'birth_date' => $validated['birth_date'],
            'address' => $validated['address'],
            'whatsapp' => $validated['whatsapp'],
            'testable_type' => $quiz->course_id ? Course::class : Quiz::class,
            'testable_id' => $quiz->course_id ?: $quiz->id,
            'test_type' => $quiz->type,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'passed' => $passed,
            'is_paid' => $isPaidAccess,
            'paid_at' => $isPaidAccess ? now() : null,
            'payment_amount' => $amount,
            'answers' => $acceptedAnswers,
            'started_at' => now()->subSeconds((int) $request->input('time_spent_seconds', 60)),
            'completed_at' => now(),
        ]);

        // Generate & store personality summary
        $profile = $attempt->personality_profile;
        $attempt->update([
            'personality_summary' => $profile['archetype'].' — '.$profile['full_summary'],
        ]);

        // Send email to student's Google account:
        // - If unpaid: send PREVIEW ONLY email
        // - If already pre-paid: send FULL REPORT email
        try {
            $recipient = $attempt->google_email ?: $user->email;
            Mail::to($recipient)->send(new QuizResultMail($attempt, $quiz, $isPaidAccess));
            $attempt->update(['email_sent_at' => now()]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to send quiz result email: '.$e->getMessage());
        }

        // Clear one-time prepaid session flag after attempt is saved
        session()->forget("quiz_prepaid_{$quiz->id}_{$user->id}");

        $this->xpService->awardXp(
            $user,
            $passed ? 'quiz_passed' : 'quiz_failed',
            TestAttempt::class,
            $attempt->id
        );
        app(AchievementService::class)->checkAndAward($user, AchievementService::TRIGGER_QUIZ_PASSED);

        $flashMessage = $isPaidAccess
            ? 'Tes selesai! Hasil rangkuman kepribadian penuh & rincian jawaban telah dikirim ke email Google Anda ('.$attempt->google_email.').'
            : 'Tes berakhir! Hasil Preview telah dikirim ke email Google Anda ('.$attempt->google_email.'). Bayar Rp '.number_format($amount, 0, ',', '.').' untuk membuka rangkuman kepribadian penuh & seluruh jawaban.';

        return redirect()->route('quiz.result', $attempt)->with('success', $flashMessage);
    }

    /**
     * Show quiz result (Preview mode if unpaid, Full Personality Summary & All Answers if paid)
     */
    public function result(TestAttempt $attempt): View|RedirectResponse
    {
        $user = Auth::user();

        if ($attempt->user_id !== $user->id && ! $user->canAccessAdmin()) {
            abort(403);
        }

        $quiz = $attempt->quiz_id
            ? Quiz::with(['course', 'questions.answers'])->find($attempt->quiz_id)
            : Quiz::with(['course', 'questions.answers'])
                ->where('course_id', $attempt->testable_id)
                ->where('type', $attempt->test_type)
                ->first();

        if (! $quiz) {
            $quiz = Quiz::with(['course', 'questions.answers'])->find($attempt->testable_id);
        }

        return view('pages.quiz-result', [
            'attempt' => $attempt,
            'quiz' => $quiz,
        ]);
    }

    /**
     * Redirect to Quiz Checkout Page from Result Page
     */
    public function payResult(Request $request, TestAttempt $attempt): RedirectResponse
    {
        $user = Auth::user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $quizId = $attempt->quiz_id ?: $attempt->testable_id;

        return redirect()->route('quiz.checkout', ['quiz' => $quizId, 'attempt' => $attempt->id]);
    }

    /**
     * API: Get quiz questions (for AJAX loading)
     */
    public function apiQuestions(Quiz $quiz): JsonResponse
    {
        $user = Auth::user();

        if (! $this->canAccessQuiz($user, $quiz)) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $quiz->load(['questions.answers']);

        $questions = $quiz->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question' => $q->question,
                'type' => $q->type,
                'points' => $q->points,
                'answers' => $q->answers->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'text' => $a->answer_text,
                    ];
                })->values(),
            ];
        });

        return response()->json([
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'type' => $quiz->type,
                'passing_score' => $quiz->passing_score,
                'time_limit_minutes' => $quiz->time_limit_minutes,
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * Get quiz history for user
     */
    public function history(): View|RedirectResponse
    {
        $user = Auth::user();

        $attempts = TestAttempt::where('user_id', $user->id)
            ->whereNotNull('completed_at')
            ->with(['testable', 'quiz'])
            ->latest('completed_at')
            ->paginate(10);

        return view('pages.quiz-history', [
            'attempts' => $attempts,
        ]);
    }
}
