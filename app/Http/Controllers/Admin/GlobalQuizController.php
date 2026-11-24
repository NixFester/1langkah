<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\Quiz;
use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GlobalQuizController extends Controller
{
    /**
     * Manage Global Quiz Page
     * Default tab: Current Configuration + List of Discount Codes + Buat Global Quiz + Participant Results
     */
    public function index(Request $request): View
    {
        // All Global Quizzes (separate from course quizzes)
        $globalQuizzes = Quiz::where('is_global', true)
            ->withCount(['questions', 'attempts'])
            ->orderBy('order')
            ->latest()
            ->get();

        // Active / selected Global Quiz for Current Configuration
        $selectedQuizId = $request->query('quiz_id');
        $currentGlobalQuiz = $selectedQuizId
            ? $globalQuizzes->firstWhere('id', (int) $selectedQuizId)
            : $globalQuizzes->first();

        if ($currentGlobalQuiz) {
            $currentGlobalQuiz->load('questions.answers');
        }

        // Discount codes list
        $promoCodes = PromoCode::latest()->get();

        // Global Quiz Attempts (Results)
        $globalQuizIds = $globalQuizzes->pluck('id')->toArray();
        $attemptsQuery = TestAttempt::with(['user', 'quiz'])
            ->whereIn('quiz_id', $globalQuizIds)
            ->whereNotNull('completed_at')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->input('search'));
                $numericId = preg_replace('/^QUIZ-/i', '', $search);
                $q->where(function ($sub) use ($search, $numericId) {
                    $sub->where('participant_name', 'like', "%{$search}%")
                        ->orWhere('google_email', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('birth_place', 'like', "%{$search}%");
                    if (is_numeric($numericId)) {
                        $sub->orWhere('id', (int) $numericId)
                            ->orWhere('user_id', (int) $numericId);
                    }
                });
            })
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                if ($request->input('payment_status') === 'paid') {
                    $q->where('is_paid', true);
                } elseif ($request->input('payment_status') === 'preview') {
                    $q->where('is_paid', false);
                }
            })
            ->latest('completed_at');

        $attempts = $attemptsQuery->paginate(15)->withQueryString();

        return view('admin.global_quiz', compact(
            'globalQuizzes',
            'currentGlobalQuiz',
            'promoCodes',
            'attempts'
        ));
    }

    /**
     * Update Current Global Quiz Configuration
     */
    public function updateConfig(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unlock_price' => 'required|integer|min:0',
            'free_question_limit' => 'required|integer|min:1',
            'time_limit_minutes' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $quiz = Quiz::findOrFail($data['quiz_id']);

        $quiz->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_global' => true,
            'course_id' => null,
            'unlock_price' => $data['unlock_price'],
            'free_question_limit' => $data['free_question_limit'],
            'time_limit_minutes' => $data['time_limit_minutes'],
            'passing_score' => $data['passing_score'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.global-quiz', ['quiz_id' => $quiz->id])
            ->with('success', 'Konfigurasi Global Quiz berhasil diperbarui!');
    }

    /**
     * Buat Global Quiz Baru (Separate from Course Quizzes)
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unlock_price' => 'required|integer|min:0',
            'free_question_limit' => 'required|integer|min:1',
            'time_limit_minutes' => 'required|integer|min:1',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $quiz = Quiz::create([
            'course_id' => null,
            'is_global' => true,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'type' => 'pre_test',
            'passing_score' => $data['passing_score'] ?? 70,
            'time_limit_minutes' => $data['time_limit_minutes'],
            'free_question_limit' => $data['free_question_limit'],
            'unlock_price' => $data['unlock_price'],
            'is_active' => $request->boolean('is_active', true),
            'order' => Quiz::where('is_global', true)->max('order') + 1,
        ]);

        return redirect()->route('admin.quizzes.questions', $quiz)
            ->with('success', 'Global Quiz baru berhasil dibuat! Silakan tambahkan daftar soal psikologi.');
    }

    /**
     * Store a new Discount Code
     */
    public function storePromo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|integer|min:1',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = true;
        $data['created_by'] = auth()->id();

        PromoCode::create($data);

        return redirect()->route('admin.global-quiz', ['tab' => 'promos'])
            ->with('success', "Kode diskon {$data['code']} berhasil ditambahkan!");
    }

    /**
     * Toggle Discount Code status
     */
    public function togglePromo(PromoCode $promo): RedirectResponse
    {
        $promo->update(['is_active' => ! $promo->is_active]);

        return redirect()->route('admin.global-quiz', ['tab' => 'promos'])
            ->with('success', "Status kode diskon {$promo->code} berhasil diperbarui.");
    }

    /**
     * Delete Discount Code
     */
    public function destroyPromo(PromoCode $promo): RedirectResponse
    {
        $code = $promo->code;
        $promo->delete();

        return redirect()->route('admin.global-quiz', ['tab' => 'promos'])
            ->with('success', "Kode diskon {$code} berhasil dihapus.");
    }

    /**
     * Export Global Quiz Results to CSV
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $globalQuizIds = Quiz::where('is_global', true)->pluck('id')->toArray();
        $attempts = TestAttempt::with(['user', 'quiz'])
            ->whereIn('quiz_id', $globalQuizIds)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->get();

        $filename = 'hasil-global-quiz-'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($attempts) {
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
                'Global Quiz',
                'Skor (%)',
                'Soal Terjawab',
                'Total Soal',
                'Status Bayar',
                'Tipe Kepribadian',
                'Tanggal Selesai',
            ]);

            foreach ($attempts as $index => $attempt) {
                fputcsv($output, [
                    $index + 1,
                    'QUIZ-'.$attempt->id,
                    $attempt->user_id,
                    $attempt->participant_name ?? $attempt->user?->name ?? '-',
                    $attempt->google_email ?? $attempt->user?->email ?? '-',
                    $attempt->formatted_ttl,
                    $attempt->address ?? '-',
                    $attempt->whatsapp ?? '-',
                    $attempt->quiz?->title ?? 'Global Quiz',
                    number_format((float) $attempt->score, 2),
                    is_array($attempt->answers) ? count($attempt->answers) : $attempt->correct_answers,
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
     * Export Global Quiz Results to PDF
     */
    public function exportPdf(Request $request): View
    {
        $globalQuizIds = Quiz::where('is_global', true)->pluck('id')->toArray();
        $attempts = TestAttempt::with(['user', 'quiz'])
            ->whereIn('quiz_id', $globalQuizIds)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->get();

        $quiz = Quiz::where('is_global', true)->first();

        return view('exports.quiz-results-pdf', compact('quiz', 'attempts'));
    }
}
