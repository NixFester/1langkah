<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\PromoCode;
use App\Models\SiapAssessment;
use App\Models\SiapAttempt;
use App\Models\SiapOrder;
use App\Models\SiapQuestion;
use App\Models\User;
use App\Services\SiapAssessmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiapAdminController extends Controller
{
    public function __construct(private SiapAssessmentService $siapService) {}

    /**
     * Manage SIAP Dashboard (Config & Pricing, Participant Results, Redeem & Orders, Promo Codes)
     */
    public function index(Request $request): View
    {
        if (! PromoCode::whereRaw('UPPER(code) = ?', ['CONTOH'])->exists()) {
            $creatorId = auth()->id() ?? User::first()?->id ?? 1;
            PromoCode::create([
                'code' => 'CONTOH',
                'name' => 'Diskon Testing SIAP 50%',
                'type' => PromoCode::TYPE_PERCENTAGE,
                'value' => 50,
                'max_uses' => null,
                'used_count' => 0,
                'min_purchase' => 0,
                'is_active' => true,
                'created_by' => $creatorId,
                'description' => 'Kode diskon contoh untuk testing checkout asesmen SIAP 1Langkah',
            ]);
        }

        $assessments = SiapAssessment::withCount(['questions', 'attempts'])
            ->orderBy('sort_order')
            ->get();

        $selectedAssessmentId = $request->query('assessment_id');
        $currentAssessment = $selectedAssessmentId
            ? $assessments->firstWhere('id', (int) $selectedAssessmentId)
            : $assessments->first();

        $pricing = $this->siapService->getPricing();
        $promoCodes = PromoCode::latest()->get();

        $attemptsQuery = SiapAttempt::with(['user', 'assessment'])
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->when($request->filled('assessment_filter'), function ($q) use ($request) {
                $q->where('siap_assessment_id', (int) $request->input('assessment_filter'));
            })
            ->when($request->filled('payment_status'), function ($q) use ($request) {
                if ($request->input('payment_status') === 'paid') {
                    $q->where('is_full_unlocked', true);
                } elseif ($request->input('payment_status') === 'preview') {
                    $q->where('is_full_unlocked', false);
                }
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim((string) $request->input('search'));
                $numericId = preg_replace('/^SIAP-/i', '', $search);
                $q->where(function ($sub) use ($search, $numericId) {
                    $sub->where('participant_name', 'like', "%{$search}%")
                        ->orWhere('google_email', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                    if (is_numeric($numericId)) {
                        $sub->orWhere('id', (int) $numericId)
                            ->orWhere('user_id', (int) $numericId);
                    }
                });
            })
            ->latest('completed_at');

        $attempts = $attemptsQuery->paginate(15)->withQueryString();

        $orders = SiapOrder::with(['user', 'assessment', 'redeemedByUser'])
            ->when($request->filled('order_search'), function ($q) use ($request) {
                $s = trim((string) $request->input('order_search'));
                $q->where(function ($sub) use ($s) {
                    $sub->where('redeem_code', 'like', "%{$s}%")
                        ->orWhere('participant_name', 'like', "%{$s}%")
                        ->orWhere('email', 'like', "%{$s}%");
                });
            })
            ->latest()
            ->paginate(15, ['*'], 'orders_page')
            ->withQueryString();

        $paidBundleOrders = SiapOrder::where('product_type', SiapOrder::PRODUCT_BUNDLE)
            ->where('status', SiapOrder::STATUS_PAID)
            ->get(['fulfillment_notes']);

        $pendingBundles = $paidBundleOrders->filter(function ($o) {
            $notes = $o->fulfillment_notes ?? [];

            return ($notes['merchandise'] ?? 'pending') !== 'done'
                || ($notes['mentoring'] ?? 'pending') !== 'done'
                || ($notes['career_mapping'] ?? 'pending') !== 'done'
                || ($notes['voucher'] ?? 'pending') !== 'done';
        })->count();

        $stats = [
            'total_attempts' => SiapAttempt::where('status', SiapAttempt::STATUS_COMPLETED)->count(),
            'unlocked_attempts' => SiapAttempt::where('status', SiapAttempt::STATUS_COMPLETED)->where('is_full_unlocked', true)->count(),
            'total_revenue' => (int) SiapOrder::where('status', SiapOrder::STATUS_PAID)->sum('final_price'),
            'unused_redeems' => SiapOrder::where('status', SiapOrder::STATUS_PAID)->whereNotNull('redeem_code')->where('redeem_status', SiapOrder::REDEEM_UNUSED)->count(),
            'used_redeems' => SiapOrder::where('status', SiapOrder::STATUS_PAID)->whereNotNull('redeem_code')->where('redeem_status', SiapOrder::REDEEM_USED)->count(),
            'pending_bundles' => $pendingBundles,
        ];

        return view('admin.siap.index', compact(
            'assessments',
            'currentAssessment',
            'pricing',
            'promoCodes',
            'attempts',
            'orders',
            'stats'
        ));
    }

    /**
     * Update SIAP Assessment Configuration.
     */
    public function updateConfig(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'assessment_id' => 'required|exists:siap_assessments,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'time_limit_minutes' => 'required|integer|min:1|max:180',
            'normal_price' => 'required|integer|min:0',
            'unlock_price' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $assessment = SiapAssessment::findOrFail($data['assessment_id']);
        $assessment->update([
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'description' => $data['description'] ?? null,
            'time_limit_minutes' => $data['time_limit_minutes'],
            'normal_price' => $data['normal_price'],
            'unlock_price' => $data['unlock_price'],
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.siap.index', ['assessment_id' => $assessment->id])
            ->with('success', "Konfigurasi {$assessment->title} berhasil diperbarui.");
    }

    /**
     * Update Super Finger & Paket Lengkap SIAP pricing.
     */
    public function updatePricing(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'siap_super_finger_normal_price' => 'required|integer|min:0',
            'siap_super_finger_price' => 'required|integer|min:0',
            'siap_bundle_price' => 'required|integer|min:0',
        ]);

        foreach ($data as $key => $value) {
            Option::updateOrCreate(
                ['category' => 'siap_pricing', 'key' => $key],
                ['label' => (string) $value, 'is_active' => true]
            );
        }

        return redirect()->route('admin.siap.index')
            ->with('success', 'Harga Super Finger & Paket Lengkap SIAP berhasil diperbarui.');
    }

    /**
     * Manage Questions for a SIAP Assessment.
     */
    public function questions(SiapAssessment $assessment): View
    {
        $assessment->load(['categories', 'questions.category']);

        return view('admin.siap.questions', compact('assessment'));
    }

    public function storeQuestion(Request $request, SiapAssessment $assessment): RedirectResponse
    {
        $rules = ['question_text' => 'required|string|max:1000'];
        if ($assessment->isForcedChoice()) {
            $rules['option_a'] = 'required|string|max:500';
            $rules['option_b'] = 'required|string|max:500';
            $rules['option_c'] = 'required|string|max:500';
            $rules['option_d'] = 'required|string|max:500';
            $rules['option_e'] = 'required|string|max:500';
        } else {
            $rules['siap_category_id'] = 'required|exists:siap_categories,id';
        }

        $data = $request->validate($rules);

        $options = null;
        if ($assessment->isForcedChoice()) {
            $options = [
                ['key' => 'A', 'category_slug' => 'square', 'text' => $data['option_a']],
                ['key' => 'B', 'category_slug' => 'triangle', 'text' => $data['option_b']],
                ['key' => 'C', 'category_slug' => 'rectangle', 'text' => $data['option_c']],
                ['key' => 'D', 'category_slug' => 'circle', 'text' => $data['option_d']],
                ['key' => 'E', 'category_slug' => 'squiggle', 'text' => $data['option_e']],
            ];
        }

        $nextOrder = ($assessment->questions()->max('sort_order') ?? 0) + 1;

        SiapQuestion::create([
            'siap_assessment_id' => $assessment->id,
            'siap_category_id' => $data['siap_category_id'] ?? null,
            'question_text' => $data['question_text'],
            'options' => $options,
            'sort_order' => $nextOrder,
            'is_active' => true,
        ]);

        $assessment->update(['question_count' => $assessment->questions()->where('is_active', true)->count()]);

        return redirect()->route('admin.siap.questions', $assessment)
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function updateQuestion(Request $request, SiapAssessment $assessment, SiapQuestion $question): RedirectResponse
    {
        if ($question->siap_assessment_id !== $assessment->id) {
            abort(404);
        }

        $rules = ['question_text' => 'required|string|max:1000'];
        if ($assessment->isForcedChoice()) {
            $rules['option_a'] = 'required|string|max:500';
            $rules['option_b'] = 'required|string|max:500';
            $rules['option_c'] = 'required|string|max:500';
            $rules['option_d'] = 'required|string|max:500';
            $rules['option_e'] = 'required|string|max:500';
        } else {
            $rules['siap_category_id'] = 'required|exists:siap_categories,id';
        }

        $data = $request->validate($rules);

        $options = $question->options;
        if ($assessment->isForcedChoice()) {
            $options = [
                ['key' => 'A', 'category_slug' => 'square', 'text' => $data['option_a']],
                ['key' => 'B', 'category_slug' => 'triangle', 'text' => $data['option_b']],
                ['key' => 'C', 'category_slug' => 'rectangle', 'text' => $data['option_c']],
                ['key' => 'D', 'category_slug' => 'circle', 'text' => $data['option_d']],
                ['key' => 'E', 'category_slug' => 'squiggle', 'text' => $data['option_e']],
            ];
        }

        $question->update([
            'siap_category_id' => $data['siap_category_id'] ?? null,
            'question_text' => $data['question_text'],
            'options' => $options,
        ]);

        return redirect()->route('admin.siap.questions', $assessment)
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function deleteQuestion(SiapAssessment $assessment, SiapQuestion $question): RedirectResponse
    {
        if ($question->siap_assessment_id !== $assessment->id) {
            abort(404);
        }

        $question->delete();
        $assessment->update(['question_count' => $assessment->questions()->where('is_active', true)->count()]);

        return redirect()->route('admin.siap.questions', $assessment)
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    /**
     * Validate & Consume Super Finger Redeem Code (single-use).
     */
    public function redeemCode(Request $request): RedirectResponse
    {
        $request->validate(['redeem_code' => 'required|string|max:32']);

        $result = $this->siapService->redeemCode($request->input('redeem_code'), Auth::user());

        return redirect()->route('admin.siap.index', ['tab' => 'orders'])
            ->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    /**
     * Update Manual Fulfillment Checklist for Paket Lengkap SIAP.
     */
    public function updateFulfillment(Request $request, SiapOrder $order): RedirectResponse
    {
        $data = $request->validate([
            'merchandise' => 'required|in:pending,done',
            'mentoring' => 'required|in:pending,done',
            'career_mapping' => 'required|in:pending,done',
            'voucher' => 'required|in:pending,done',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update(['fulfillment_notes' => $data]);

        return redirect()->route('admin.siap.index', ['tab' => 'orders'])
            ->with('success', "Status fulfillment untuk {$order->participant_name} (#ORDER-{$order->id}) berhasil diperbarui.");
    }

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

        return redirect()->route('admin.siap.index', ['tab' => 'promos'])
            ->with('success', "Kode diskon {$data['code']} berhasil ditambahkan!");
    }

    public function togglePromo(PromoCode $promo): RedirectResponse
    {
        $promo->update(['is_active' => ! $promo->is_active]);

        return redirect()->route('admin.siap.index', ['tab' => 'promos'])
            ->with('success', "Status kode diskon {$promo->code} berhasil diperbarui.");
    }

    public function destroyPromo(PromoCode $promo): RedirectResponse
    {
        $code = $promo->code;
        $promo->delete();

        return redirect()->route('admin.siap.index', ['tab' => 'promos'])
            ->with('success', "Kode diskon {$code} berhasil dihapus.");
    }

    public function exportCsv(): StreamedResponse
    {
        $attempts = SiapAttempt::with(['user', 'assessment'])
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->latest('completed_at')
            ->get();

        $filename = 'hasil-asesmen-siap-'.date('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($attempts) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'No',
                'ID Attempt',
                'User ID',
                'Nama Peserta',
                'Email Google',
                'TTL',
                'Alamat',
                'No. WhatsApp',
                'Jenis Asesmen',
                'Profil Dominan',
                'Skor Tertinggi (%)',
                'Status Akses',
                'Tanggal Selesai',
            ]);

            foreach ($attempts as $idx => $attempt) {
                $topPct = $attempt->scores[0]['percentage'] ?? 0;
                fputcsv($output, [
                    $idx + 1,
                    'SIAP-'.$attempt->id,
                    $attempt->user_id,
                    $attempt->participant_name ?? $attempt->user?->name ?? '-',
                    $attempt->google_email ?? $attempt->user?->email ?? '-',
                    $attempt->formatted_ttl,
                    $attempt->address ?? '-',
                    $attempt->whatsapp ?? '-',
                    $attempt->assessment?->title ?? '-',
                    $attempt->dominant_profile['label'] ?? '-',
                    $topPct.'%',
                    $attempt->is_full_unlocked ? 'FULL AKSES (LUNAS)' : 'PREVIEW',
                    $attempt->completed_at?->format('d/m/Y H:i') ?? '-',
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPdf(): View
    {
        $attempts = SiapAttempt::with(['user', 'assessment'])
            ->where('status', SiapAttempt::STATUS_COMPLETED)
            ->latest('completed_at')
            ->get();

        return view('exports.siap-results-pdf', compact('attempts'));
    }
}
