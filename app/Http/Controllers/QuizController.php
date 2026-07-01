<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\TestAttempt;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * Show available quizzes for enrolled courses
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        // Get quizzes with active status, grouped by course
        $quizzes = Quiz::where('is_active', true)
            ->with('course')
            ->get()
            ->groupBy('course_id');

        return view('pages.quizzes', [
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

        // Check if user is enrolled in this course
        $isEnrolled = $user->enrollments()
            ->where('purchasable_type', Course::class)
            ->where('purchasable_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('kursus')
                ->with('error', 'Kamu harus terdaftar di kursus ini untuk mengikuti quiz.');
        }

        // Load quiz with questions and answers (only correct answer NOT shown for fairness)
        $quiz->load(['questions.answers']);

        // Check for existing attempt
        $existingAttempt = TestAttempt::where('user_id', $user->id)
            ->where('testable_type', Course::class)
            ->where('testable_id', $quiz->course_id)
            ->where('test_type', $quiz->type)
            ->whereNotNull('completed_at')
            ->latest()
            ->first();

        return view('pages.quiz-take', [
            'quiz' => $quiz,
            'existingAttempt' => $existingAttempt,
        ]);
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $user = Auth::user();

        // Check if user is enrolled
        $isEnrolled = $user->enrollments()
            ->where('purchasable_type', Course::class)
            ->where('purchasable_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('kursus')
                ->with('error', 'Kamu harus terdaftar di kursus ini untuk mengikuti quiz.');
        }

        $quiz->load(['questions.answers']);

        // Calculate score
        $answers = $request->input('answers', []);
        $correctCount = 0;
        $totalQuestions = $quiz->questions->count();
        $totalScored = 0;

        foreach ($quiz->questions as $question) {
            $userAnswerId = $answers[$question->id] ?? null;

            // Essay questions don't count towards score (manual grading)
            if ($question->type === 'essay') {
                continue;
            }

            $totalScored += $question->points;

            if ($userAnswerId) {
                // Check if answer is correct
                $isCorrect = $question->answers()
                    ->where('id', $userAnswerId)
                    ->where('is_correct', true)
                    ->exists();

                if ($isCorrect) {
                    $correctCount++;
                }
            }
        }

        // Calculate percentage
        // Only count questions that have correct answers (not essays)
        $scoredQuestions = $quiz->questions->where('type', '!=', 'essay')->count();
        $score = $scoredQuestions > 0
            ? round(($correctCount / $scoredQuestions) * 100, 2)
            : 0;
        $passed = $score >= $quiz->passing_score;

        // Save attempt
        $attempt = TestAttempt::create([
            'user_id' => $user->id,
            'testable_type' => Course::class,
            'testable_id' => $quiz->course_id,
            'test_type' => $quiz->type,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'passed' => $passed,
            'answers' => $answers,
            'started_at' => now()->subMinutes(rand(5, 30)),
            'completed_at' => now(),
        ]);

        // If passed post-test and this is the final course completion requirement
        if ($passed && $quiz->type === 'post_test') {
            // Could mark course as completed here
        }

        return redirect()->route('quiz.result', $attempt);
    }

    /**
     * Show quiz result
     */
    public function result(TestAttempt $attempt): View|RedirectResponse
    {
        $user = Auth::user();

        // Ensure user owns this attempt
        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $quiz = Quiz::with('course')->find($attempt->testable_id);

        return view('pages.quiz-result', [
            'attempt' => $attempt,
            'quiz' => $quiz,
        ]);
    }

    /**
     * API: Get quiz questions (for AJAX loading)
     */
    public function apiQuestions(Quiz $quiz): JsonResponse
    {
        $user = Auth::user();

        // Check enrollment
        $isEnrolled = $user->enrollments()
            ->where('purchasable_type', Course::class)
            ->where('purchasable_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $quiz->load(['questions.answers']);

        // Return questions without correct answer indicator
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
            ->with(['testable'])
            ->latest('completed_at')
            ->paginate(10);

        return view('pages.quiz-history', [
            'attempts' => $attempts,
        ]);
    }
}
