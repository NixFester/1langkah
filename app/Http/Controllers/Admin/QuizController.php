<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PromoCode;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\TestAttempt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuizController extends Controller
{
    /**
     * Display list of course quizzes (separate from Global Quiz)
     */
    public function index(Request $request): View
    {
        $courses = Course::orderBy('title')->get();

        $quizzes = Quiz::with('course', 'questions')
            ->withCount(['questions', 'attempts'])
            ->where('is_global', false)
            ->whereNotNull('course_id')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('course', fn ($c) => $c->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('course_id'), fn ($q) => $q->where('course_id', $request->input('course_id')))
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->input('type')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalAttempts = TestAttempt::whereNotNull('completed_at')
            ->whereHas('quiz', fn ($q) => $q->where('is_global', false))
            ->count();

        return view('admin.quizzes', compact('quizzes', 'courses', 'totalAttempts'));
    }

    /**
     * Show create quiz form
     */
    public function create(): View
    {
        $courses = Course::all();

        return view('admin.quiz_form', compact('courses'));
    }

    /**
     * Store new quiz
     */
    public function store(Request $request): RedirectResponse
    {
        $isGlobal = $request->boolean('is_global', false);

        $data = $request->validate([
            'course_id' => $isGlobal ? 'nullable|exists:courses,id' : 'required|exists:courses,id',
            'is_global' => 'nullable|boolean',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:pre_test,post_test,chapter_quiz',
            'chapter_id' => 'nullable|exists:chapters,id',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'free_question_limit' => 'nullable|integer|min:1',
            'unlock_price' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['is_global'] = $isGlobal;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['free_question_limit'] = $data['free_question_limit'] ?? 10;
        $data['unlock_price'] = $data['unlock_price'] ?? 30000;

        Quiz::create($data);

        return redirect()->route('admin.quizzes')->with('success', __('app.msg_success_quiz_berhasil_ditambahkan'));
    }

    /**
     * Show edit quiz form
     */
    public function edit(Quiz $quiz): View
    {
        $courses = Course::all();
        $quiz->load('questions.answers');

        return view('admin.quiz_form', compact('quiz', 'courses'));
    }

    /**
     * Update quiz
     */
    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $isGlobal = $request->boolean('is_global', false);

        $data = $request->validate([
            'course_id' => $isGlobal ? 'nullable|exists:courses,id' : 'required|exists:courses,id',
            'is_global' => 'nullable|boolean',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:pre_test,post_test,chapter_quiz',
            'chapter_id' => 'nullable|exists:chapters,id',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'free_question_limit' => 'nullable|integer|min:1',
            'unlock_price' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['is_global'] = $isGlobal;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['free_question_limit'] = $data['free_question_limit'] ?? 10;
        $data['unlock_price'] = $data['unlock_price'] ?? 30000;

        $quiz->update($data);

        return redirect()->route('admin.quizzes')->with('success', __('app.msg_success_quiz_berhasil_diperbarui'));
    }

    /**
     * Delete quiz
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes')->with('success', __('app.msg_success_quiz_berhasil_dihapus'));
    }

    /**
     * Manage quiz questions
     */
    public function questions(Quiz $quiz): View
    {
        $quiz->load('questions.answers');

        return view('admin.quiz_questions', compact('quiz'));
    }

    /**
     * Add question to quiz
     */
    public function addQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        $data = $request->validate([
            'question' => 'required|string',
            'explanation' => 'nullable|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'points' => 'nullable|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
        ]);

        $data['quiz_id'] = $quiz->id;
        $data['points'] = $data['points'] ?? 1;
        $data['is_required'] = $request->boolean('is_required', true);

        $question = QuizQuestion::create($data);

        // For multiple choice / true-false, create default answers
        if ($data['type'] === 'multiple_choice') {
            for ($i = 1; $i <= 4; $i++) {
                QuizAnswer::create([
                    'question_id' => $question->id,
                    'answer_text' => "Option {$i}",
                    'is_correct' => $i === 1,
                    'order' => $i,
                ]);
            }
        } elseif ($data['type'] === 'true_false') {
            QuizAnswer::create(['question_id' => $question->id, 'answer_text' => 'Benar', 'is_correct' => true, 'order' => 1]);
            QuizAnswer::create(['question_id' => $question->id, 'answer_text' => 'Salah', 'is_correct' => false, 'order' => 2]);
        }

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', __('app.msg_success_question_berhasil_ditambahkan'));
    }

    /**
     * Update question
     */
    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $data = $request->validate([
            'question' => 'required|string',
            'explanation' => 'nullable|string',
            'type' => 'required|in:multiple_choice,true_false,essay',
            'points' => 'nullable|integer|min:1',
            'order' => 'nullable|integer|min:0',
            'is_required' => 'nullable|boolean',
        ]);

        $data['points'] = $data['points'] ?? 1;
        $data['is_required'] = $request->boolean('is_required', true);

        $question->update($data);

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', __('app.msg_success_question_berhasil_diperbarui'));
    }

    /**
     * Delete question
     */
    public function deleteQuestion(Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', __('app.msg_success_question_berhasil_dihapus'));
    }

    /**
     * Update answer (for setting correct answer)
     */
    public function updateAnswer(Request $request, Quiz $quiz, QuizQuestion $question, QuizAnswer $answer): RedirectResponse
    {
        $data = $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'nullable|boolean',
        ]);

        // If setting correct, unset other correct answers first
        if ($request->boolean('is_correct')) {
            $question->answers()->where('id', '!=', $answer->id)->update(['is_correct' => false]);
        }

        $data['is_correct'] = $request->boolean('is_correct', false);
        $answer->update($data);

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', __('app.msg_success_answer_berhasil_diperbarui'));
    }

    /**
     * Add answer to question
     */
    public function addAnswer(Request $request, Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        $data = $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'nullable|boolean',
        ]);

        $data['question_id'] = $question->id;
        $data['is_correct'] = $request->boolean('is_correct', false);
        $data['order'] = $question->answers()->max('order') + 1;

        QuizAnswer::create($data);

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', __('app.msg_success_answer_berhasil_ditambahkan'));
    }

    /**
     * Delete answer
     */
    public function deleteAnswer(Quiz $quiz, QuizQuestion $question, QuizAnswer $answer): RedirectResponse
    {
        $answer->delete();

        return redirect()->route('admin.quizzes.questions', $quiz)->with('success', __('app.msg_success_answer_berhasil_dihapus'));
    }

    /**
     * Build query for quiz attempts (single quiz or all quizzes)
     */
    private function getQuizAttemptsQuery(?Quiz $quiz = null, ?Request $request = null)
    {
        $query = TestAttempt::with(['user', 'quiz.course', 'testable'])
            ->whereNotNull('completed_at');

        if ($quiz) {
            $query->where(function ($q) use ($quiz) {
                $q->where('quiz_id', $quiz->id)
                    ->orWhere(function ($sub) use ($quiz) {
                        $sub->whereNull('quiz_id')
                            ->where('testable_type', Course::class)
                            ->where('testable_id', $quiz->course_id)
                            ->where('test_type', $quiz->type);
                    });
            });
        } elseif ($request && $request->filled('quiz_id')) {
            $selectedQuiz = Quiz::find($request->input('quiz_id'));
            if ($selectedQuiz) {
                $query->where(function ($q) use ($selectedQuiz) {
                    $q->where('quiz_id', $selectedQuiz->id)
                        ->orWhere(function ($sub) use ($selectedQuiz) {
                            $sub->whereNull('quiz_id')
                                ->where('testable_type', Course::class)
                                ->where('testable_id', $selectedQuiz->course_id)
                                ->where('test_type', $selectedQuiz->type);
                        });
                });
            }
        }

        if ($request) {
            if ($request->filled('course_id')) {
                $courseId = $request->input('course_id');
                $query->where(function ($q) use ($courseId) {
                    $q->where(function ($sub) use ($courseId) {
                        $sub->where('testable_type', Course::class)
                            ->where('testable_id', $courseId);
                    })->orWhereHas('quiz', fn ($qz) => $qz->where('course_id', $courseId));
                });
            }

            if ($request->filled('status')) {
                if ($request->input('status') === 'passed') {
                    $query->where('passed', true);
                } elseif ($request->input('status') === 'failed') {
                    $query->where('passed', false);
                }
            }

            if ($request->filled('date_from')) {
                $query->whereDate('completed_at', '>=', $request->input('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('completed_at', '<=', $request->input('date_to'));
            }

            if ($request->filled('search')) {
                $search = trim($request->input('search'));
                $numericId = preg_replace('/^QUIZ-/i', '', $search);
                $query->where(function ($q) use ($search, $numericId) {
                    $q->where('participant_name', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('birth_place', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });

                    if (is_numeric($numericId)) {
                        $q->orWhere('id', (int) $numericId)
                            ->orWhere('user_id', (int) $numericId);
                    }
                });
            }
        }

        return $query->latest('completed_at');
    }

    /**
     * View all quiz results across all quizzes (central hub)
     */
    public function allResults(Request $request): View
    {
        $courses = Course::orderBy('title')->get();
        $quizzesList = Quiz::with('course')->orderBy('title')->get();

        $baseQuery = $this->getQuizAttemptsQuery(null, $request);
        $statsQuery = clone $baseQuery;
        $allMatching = $statsQuery->get();

        $stats = [
            'total' => $allMatching->count(),
            'passed' => $allMatching->where('passed', true)->count(),
            'failed' => $allMatching->where('passed', false)->count(),
            'avg_score' => $allMatching->count() > 0 ? round($allMatching->avg('score'), 1) : 0,
        ];

        $attempts = $baseQuery->paginate(20)->withQueryString();
        $quiz = $request->filled('quiz_id') ? Quiz::with('course')->find($request->input('quiz_id')) : null;

        return view('admin.quiz_results', compact('quiz', 'attempts', 'courses', 'quizzesList', 'stats'));
    }

    /**
     * View quiz results & participant personal data for a specific quiz
     */
    public function results(Request $request, Quiz $quiz): View
    {
        $quiz->load('course');
        $courses = Course::orderBy('title')->get();
        $quizzesList = Quiz::with('course')->orderBy('title')->get();

        $baseQuery = $this->getQuizAttemptsQuery($quiz, $request);
        $statsQuery = clone $baseQuery;
        $allMatching = $statsQuery->get();

        $stats = [
            'total' => $allMatching->count(),
            'passed' => $allMatching->where('passed', true)->count(),
            'failed' => $allMatching->where('passed', false)->count(),
            'avg_score' => $allMatching->count() > 0 ? round($allMatching->avg('score'), 1) : 0,
        ];

        $attempts = $baseQuery->paginate(20)->withQueryString();

        return view('admin.quiz_results', compact('quiz', 'attempts', 'courses', 'quizzesList', 'stats'));
    }

    /**
     * Stream CSV download for attempts
     */
    private function streamAttemptsCsv($attempts, ?Quiz $quiz = null): StreamedResponse
    {
        $slug = $quiz ? \Illuminate\Support\Str::slug($quiz->title) : 'semua-quiz';
        $filename = 'data-peserta-hasil-quiz-'.$slug.'-'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($attempts, $quiz) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'No',
                'ID Attempt',
                'ID User',
                'Nama Peserta',
                'Email Google',
                'Tempat, Tanggal Lahir (TTL)',
                'Alamat',
                'No. WhatsApp',
                'Quiz',
                'Kursus / Tipe',
                'Skor (%)',
                'Soal Terjawab',
                'Total Soal',
                'Status Bayar (Rp 30.000)',
                'Tipe Kepribadian',
                'Tanggal Selesai',
            ]);

            foreach ($attempts as $index => $attempt) {
                $quizTitle = $quiz?->title ?? $attempt->quiz?->title ?? ucfirst(str_replace('_', ' ', $attempt->test_type));
                $courseTitle = $quiz?->course?->title ?? $attempt->quiz?->course?->title ?? ($attempt->quiz?->is_global ? 'Tes Psikologi Global' : ($attempt->testable?->title ?? '-'));
                $answeredCount = is_array($attempt->answers) ? count($attempt->answers) : $attempt->correct_answers;

                fputcsv($output, [
                    $index + 1,
                    'QUIZ-'.$attempt->id,
                    $attempt->user_id,
                    $attempt->participant_name ?? $attempt->user?->name ?? '-',
                    $attempt->google_email ?? $attempt->user?->email ?? '-',
                    $attempt->formatted_ttl,
                    $attempt->address ?? '-',
                    $attempt->whatsapp ?? '-',
                    $quizTitle,
                    $courseTitle,
                    number_format((float) $attempt->score, 2),
                    $answeredCount,
                    $attempt->total_questions,
                    $attempt->is_paid ? 'LUNAS (Rp '.number_format($attempt->payment_amount ?: 30000, 0, ',', '.').')' : 'PREVIEW (Belum Bayar)',
                    $attempt->personality_profile['archetype'] ?? '-',
                    $attempt->completed_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export all/filtered quiz results to CSV
     */
    public function exportAllCsv(Request $request): StreamedResponse
    {
        $attempts = $this->getQuizAttemptsQuery(null, $request)->get();
        $quiz = $request->filled('quiz_id') ? Quiz::with('course')->find($request->input('quiz_id')) : null;

        return $this->streamAttemptsCsv($attempts, $quiz);
    }

    /**
     * Export single quiz results to CSV
     */
    public function exportCsv(Request $request, Quiz $quiz): StreamedResponse
    {
        $quiz->load('course');
        $attempts = $this->getQuizAttemptsQuery($quiz, $request)->get();

        return $this->streamAttemptsCsv($attempts, $quiz);
    }

    /**
     * Export all/filtered quiz results to printable PDF view
     */
    public function exportAllPdf(Request $request): View
    {
        $attempts = $this->getQuizAttemptsQuery(null, $request)->get();
        $quiz = $request->filled('quiz_id') ? Quiz::with('course')->find($request->input('quiz_id')) : null;

        return view('exports.quiz-results-pdf', compact('quiz', 'attempts'));
    }

    /**
     * Export single quiz results to printable PDF view
     */
    public function exportPdf(Request $request, Quiz $quiz): View
    {
        $quiz->load('course');
        $attempts = $this->getQuizAttemptsQuery($quiz, $request)->get();

        return view('exports.quiz-results-pdf', compact('quiz', 'attempts'));
    }
}
