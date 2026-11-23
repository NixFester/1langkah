<?php

namespace App\Http\Controllers;

use App\Mail\QuizResultMail;
use App\Models\Course;
use App\Models\PaymentTransaction;
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
     * Pre-pay (Mock Payment Rp 30.000) from the pre-test notification modal before starting the test
     */
    public function prePay(Request $request, Quiz $quiz): RedirectResponse
    {
        $user = Auth::user();
        $amount = $quiz->unlock_price ?: 30000;

        PaymentTransaction::create([
            'user_id' => $user->id,
            'external_id' => 'MOCK-QUIZ-PRE-'.strtoupper(Str::random(8)),
            'payment_id' => 'PAY-'.strtoupper(Str::random(10)),
            'driver' => 'mock',
            'item_type' => Quiz::class,
            'item_id' => $quiz->id,
            'amount' => $amount,
            'status' => 'PAID',
            'item_name' => 'Akses Penuh '.$quiz->title,
            'metadata' => ['quiz_id' => $quiz->id, 'type' => 'pre_test_unlock'],
            'paid_at' => now(),
        ]);

        // Also mark any previous attempts for this quiz as paid
        TestAttempt::where('user_id', $user->id)
            ->where('quiz_id', $quiz->id)
            ->update([
                'is_paid' => true,
                'paid_at' => now(),
                'payment_amount' => $amount,
            ]);

        session()->put("quiz_prepaid_{$quiz->id}_{$user->id}", true);

        return redirect()->route('quiz.start', ['quiz' => $quiz->id, 'started' => 1])
            ->with('success', 'Pembayaran Mock Rp '.number_format($amount, 0, ',', '.').' berhasil! Seluruh soal dan laporan kepribadian penuh telah dibuka.');
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
                'birth_place' => $request->input('birth_place') ?: '-',
                'birth_date' => $request->input('birth_date') ?: null,
                'address' => $request->input('address') ?: '-',
                'whatsapp' => $request->input('whatsapp') ?: '-',
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
     * Mock Payment (Rp 30.000) on Result Page to unlock full personality summary, all answers, and send Full Email
     */
    public function payResult(Request $request, TestAttempt $attempt): RedirectResponse
    {
        $user = Auth::user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $quiz = $attempt->quiz_id ? Quiz::with('questions.answers')->find($attempt->quiz_id) : null;
        $amount = $quiz?->unlock_price ?: ($attempt->payment_amount ?: 30000);

        if (! $attempt->is_paid) {
            PaymentTransaction::create([
                'user_id' => $user->id,
                'external_id' => 'MOCK-QUIZ-RES-'.strtoupper(Str::random(8)),
                'payment_id' => 'PAY-'.strtoupper(Str::random(10)),
                'driver' => 'mock',
                'item_type' => TestAttempt::class,
                'item_id' => $attempt->id,
                'amount' => $amount,
                'status' => 'PAID',
                'item_name' => 'Buka Hasil Penuh '.($quiz?->title ?? 'Tes Psikologi'),
                'metadata' => ['attempt_id' => $attempt->id, 'quiz_id' => $quiz?->id],
                'paid_at' => now(),
            ]);

            $attempt->update([
                'is_paid' => true,
                'paid_at' => now(),
                'payment_amount' => $amount,
            ]);
        }

        // Also grant prepaid flag in case they immediately retake to answer all 20 questions
        if ($quiz) {
            session()->put("quiz_prepaid_{$quiz->id}_{$user->id}", true);
        }

        // Send FULL REPORT email to student's Google account upon payment
        try {
            $recipient = $attempt->google_email ?: $user->email;
            Mail::to($recipient)->send(new QuizResultMail($attempt, $quiz, true));
            $attempt->update(['email_sent_at' => now()]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to send full quiz result email: '.$e->getMessage());
        }

        return redirect()->route('quiz.result', $attempt)
            ->with('success', 'Pembayaran Mock Rp '.number_format($amount, 0, ',', '.').' berhasil! Rangkuman kepribadian penuh & seluruh rincian jawaban kini terbuka dan telah dikirim ke email Google Anda ('.($attempt->google_email ?: $user->email).').');
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
