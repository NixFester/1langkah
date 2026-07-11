<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorQuizController extends Controller
{
    /**
     * Get the mentor's courses (used for authorization)
     */
    private function getMentorCourseIds(): array
    {
        $user = auth()->user();
        $mentorProfile = Mentor::where('name', $user->name)->first();

        return Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->pluck('id')
            ->toArray();
    }

    /**
     * Check if user can manage the quiz
     */
    private function canManageQuiz(Quiz $quiz): bool
    {
        $courseIds = $this->getMentorCourseIds();

        return in_array($quiz->course_id, $courseIds);
    }

    /**
     * Display list of quizzes for mentor's courses
     */
    public function index(): View
    {
        $courseIds = $this->getMentorCourseIds();

        $quizzes = Quiz::with('course', 'questions')
            ->withCount('questions')
            ->whereIn('course_id', $courseIds)
            ->latest()
            ->paginate(15);

        return view('mentor.quizzes.index', compact('quizzes'));
    }

    /**
     * Show create quiz form
     */
    public function create(): View
    {
        $user = auth()->user();
        $mentorProfile = Mentor::where('name', $user->name)->first();

        $courses = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->get();

        return view('mentor.quizzes.create', compact('courses'));
    }

    /**
     * Store new quiz
     */
    public function store(Request $request): RedirectResponse
    {
        $courseIds = $this->getMentorCourseIds();

        $data = $request->validate([
            'course_id' => 'required|exists:courses,id|in:'.implode(',', $courseIds),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:pre_test,post_test,chapter_quiz',
            'chapter_id' => 'nullable|exists:chapters,id',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        Quiz::create($data);

        return redirect()->route('mentor.quizzes.index')->with('success', __('app.msg_success_quiz_berhasil_ditambahkan'));
    }

    /**
     * Show edit quiz form
     */
    public function edit(Quiz $quiz): View
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $user = auth()->user();
        $mentorProfile = Mentor::where('name', $user->name)->first();

        $courses = Course::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->get();

        $quiz->load('questions.answers');

        return view('mentor.quizzes.edit', compact('quiz', 'courses'));
    }

    /**
     * Update quiz
     */
    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $courseIds = $this->getMentorCourseIds();

        $data = $request->validate([
            'course_id' => 'required|exists:courses,id|in:'.implode(',', $courseIds),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:pre_test,post_test,chapter_quiz',
            'chapter_id' => 'nullable|exists:chapters,id',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        $quiz->update($data);

        return redirect()->route('mentor.quizzes.index')->with('success', __('app.msg_success_quiz_berhasil_diperbarui'));
    }

    /**
     * Delete quiz
     */
    public function destroy(Quiz $quiz): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $quiz->delete();

        return redirect()->route('mentor.quizzes.index')->with('success', __('app.msg_success_quiz_berhasil_dihapus'));
    }

    /**
     * Manage quiz questions
     */
    public function questions(Quiz $quiz): View
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $quiz->load('questions.answers');

        return view('mentor.quizzes.questions', compact('quiz'));
    }

    /**
     * Add question to quiz
     */
    public function addQuestion(Request $request, Quiz $quiz): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

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

        return redirect()->route('mentor.quizzes.questions', $quiz)->with('success', __('app.msg_success_question_berhasil_ditambahkan'));
    }

    /**
     * Update question
     */
    public function updateQuestion(Request $request, Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

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

        return redirect()->route('mentor.quizzes.questions', $quiz)->with('success', __('app.msg_success_question_berhasil_diperbarui'));
    }

    /**
     * Delete question
     */
    public function deleteQuestion(Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $question->delete();

        return redirect()->route('mentor.quizzes.questions', $quiz)->with('success', __('app.msg_success_question_berhasil_dihapus'));
    }

    /**
     * Update answer (for setting correct answer)
     */
    public function updateAnswer(Request $request, Quiz $quiz, QuizQuestion $question, QuizAnswer $answer): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

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

        return redirect()->route('mentor.quizzes.questions', $quiz)->with('success', __('app.msg_success_answer_berhasil_diperbarui'));
    }

    /**
     * Add answer to question
     */
    public function addAnswer(Request $request, Quiz $quiz, QuizQuestion $question): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $data = $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'nullable|boolean',
        ]);

        $data['question_id'] = $question->id;
        $data['is_correct'] = $request->boolean('is_correct', false);
        $data['order'] = $question->answers()->max('order') + 1;

        QuizAnswer::create($data);

        return redirect()->route('mentor.quizzes.questions', $quiz)->with('success', __('app.msg_success_answer_berhasil_ditambahkan'));
    }

    /**
     * Delete answer
     */
    public function deleteAnswer(Quiz $quiz, QuizQuestion $question, QuizAnswer $answer): RedirectResponse
    {
        if (! $this->canManageQuiz($quiz)) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_quiz_ini'));
        }

        $answer->delete();

        return redirect()->route('mentor.quizzes.questions', $quiz)->with('success', __('app.msg_success_answer_berhasil_dihapus'));
    }
}
