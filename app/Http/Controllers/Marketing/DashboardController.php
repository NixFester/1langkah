<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PaymentVerification;
use App\Models\PromoCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk Dashboard Marketing
 * Bertugas membuat promo, melacak kampanye, dan analisis
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama Marketing
     */
    public function index(): View
    {
        // Statistik promo
        $promoStats = [
            'total' => PromoCode::count(),
            'active' => PromoCode::active()->valid()->count(),
            'used_today' => PromoCode::whereDate('updated_at', today())->count(),
        ];

        // Statistik siswa baru
        $studentStats = [
            'new_today' => User::whereDate('created_at', today())->count(),
            'new_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
            'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'total' => User::count(),
        ];

        // Statistik enrollments
        $enrollmentStats = [
            'today' => Enrollment::whereDate('created_at', today())->count(),
            'this_week' => Enrollment::where('created_at', '>=', now()->startOfWeek())->count(),
            'this_month' => Enrollment::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        // Promo codes aktif
        $activePromos = PromoCode::active()->valid()->started()
            ->with('creator')
            ->latest()
            ->take(5)
            ->get();

        // Top promo codes (paling banyak digunakan)
        $topPromos = PromoCode::where('used_count', '>', 0)
            ->orderByDesc('used_count')
            ->take(5)
            ->get();

        // Top courses (paling banyak enrollment)
        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'purchasable'])
            ->latest()
            ->take(10)
            ->get();

        // Course registrations per day (7 hari)
        $weeklyEnrollments = Enrollment::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('marketing.dashboard', [
            'promoStats' => $promoStats,
            'studentStats' => $studentStats,
            'enrollmentStats' => $enrollmentStats,
            'activePromos' => $activePromos,
            'topPromos' => $topPromos,
            'topCourses' => $topCourses,
            'recentEnrollments' => $recentEnrollments,
            'weeklyEnrollments' => $weeklyEnrollments,
        ]);
    }

    /**
     * Menampilkan daftar promo codes
     */
    public function promoCodes(): View
    {
        $promos = PromoCode::with('creator')
            ->when(request('status'), function ($query, $status) {
                if ($status === 'active') {
                    $query->active()->valid()->started();
                } elseif ($status === 'expired') {
                    $query->where('expires_at', '<', now());
                } elseif ($status === 'maxed') {
                    $query->whereNotNull('max_uses')
                        ->whereColumn('used_count', '>=', 'max_uses');
                }
            })
            ->latest()
            ->paginate(20);

        return view('marketing.promo-codes', [
            'promos' => $promos,
        ]);
    }

    /**
     * Form untuk membuat promo baru
     */
    public function createPromoCode(): View
    {
        return view('marketing.promo-form');
    }

    /**
     * Simpan promo baru
     */
    public function storePromoCode(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:1',
            'max_uses' => 'nullable|integer|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'description' => 'nullable|string|max:500',
        ]);

        $data['created_by'] = auth()->id();
        $data['is_active'] = true;

        $promo = PromoCode::create($data);

        // Log aktivitas
        AuditLog::log(
            AuditLog::ACTION_CREATE,
            PromoCode::class,
            $promo->id,
            null,
            $data,
            "Membuat promo code: {$promo->code}"
        );

        return redirect()->route('marketing.promo-codes')
            ->with('success', __('app.msg_success_promo_code_berhasil_dibuat'));
    }

    /**
     * Edit promo code
     */
    public function editPromoCode(PromoCode $promo): View
    {
        return view('marketing.promo-form', [
            'promo' => $promo,
        ]);
    }

    /**
     * Update promo code
     */
    public function updatePromoCode(Request $request, PromoCode $promo)
    {
        $oldValues = $promo->toArray();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:promo_codes,code,'.$promo->id,
            'type' => 'required|in:percentage,fixed_amount',
            'value' => 'required|numeric|min:1',
            'max_uses' => 'nullable|integer|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:1',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:500',
        ]);

        $promo->update($data);

        AuditLog::log(
            AuditLog::ACTION_UPDATE,
            PromoCode::class,
            $promo->id,
            $oldValues,
            $data,
            "Mengubah promo code: {$promo->code}"
        );

        return redirect()->route('marketing.promo-codes')
            ->with('success', __('app.msg_success_promo_code_berhasil_diperbarui'));
    }

    /**
     * Hapus promo code
     */
    public function destroyPromoCode(PromoCode $promo)
    {
        $code = $promo->code;
        $promo->delete();

        AuditLog::log(
            AuditLog::ACTION_DELETE,
            PromoCode::class,
            null,
            ['code' => $code],
            null,
            "Menghapus promo code: {$code}"
        );

        return redirect()->back()->with('success', __('app.msg_success_promo_code_berhasil_dihapus'));
    }

    /**
     * Toggle status promo
     */
    public function togglePromoCode(PromoCode $promo)
    {
        $oldStatus = $promo->is_active;
        $promo->update(['is_active' => ! $promo->is_active]);

        AuditLog::log(
            AuditLog::ACTION_UPDATE,
            PromoCode::class,
            $promo->id,
            ['is_active' => $oldStatus],
            ['is_active' => ! $oldStatus],
            $promo->is_active ? "Mengaktifkan promo: {$promo->code}" : "Menonaktifkan promo: {$promo->code}"
        );

        return redirect()->back()->with('success', __('app.msg_success_status_promo_berhasil_diubah'));
    }

    /**
     * Menampilkan analytics
     */
    public function analytics(): View
    {
        // Enrollment trends (30 hari)
        $enrollmentTrend = Enrollment::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Revenue per course
        $revenueByCourse = PaymentVerification::approved()
            ->selectRaw('course_title, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('course_title')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // Promo usage stats
        $promoUsage = [
            'total_used' => PromoCode::sum('used_count'),
            'total_discount' => PaymentVerification::whereNotNull('promo_code')
                ->approved()
                ->sum('discount_amount'),
            'avg_discount' => PaymentVerification::whereNotNull('promo_code')
                ->approved()
                ->avg('discount_amount') ?? 0,
        ];

        // New users trend
        $userTrend = User::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('marketing.analytics', [
            'enrollmentTrend' => $enrollmentTrend,
            'revenueByCourse' => $revenueByCourse,
            'promoUsage' => $promoUsage,
            'userTrend' => $userTrend,
        ]);
    }

    /**
     * Generate random promo code
     */
    public function generateCode()
    {
        return response()->json([
            'code' => PromoCode::generateCode(8),
        ]);
    }
}
