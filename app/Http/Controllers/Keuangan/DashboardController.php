<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PaymentVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk Dashboard Keuangan
 * Bertugas memverifikasi pembayaran dan melihat laporan keuangan
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama Keuangan
     */
    public function index(): View
    {
        // Statistik untuk cards
        $stats = [
            'pending' => PaymentVerification::pending()->count(),
            'approved_today' => PaymentVerification::approved()
                ->whereDate('verified_at', today())->count(),
            'rejected_today' => PaymentVerification::rejected()
                ->whereDate('verified_at', today())->count(),
            'total_users' => User::count(),
        ];

        // Pendapatan hari ini
        $todayRevenue = PaymentVerification::approved()
            ->whereDate('verified_at', today())
            ->sum('amount');

        // Pendapatan bulan ini
        $monthRevenue = PaymentVerification::approved()
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->sum('amount');

        // Pembayaran pending terbaru
        $recentPending = PaymentVerification::pending()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Pembayaran terbaru (approved/rejected)
        $recentVerified = PaymentVerification::where('status', '!=', 'pending')
            ->with(['user', 'verifier'])
            ->latest()
            ->take(10)
            ->get();

        // Chart data - pembayaran per hari (7 hari terakhir)
        $weeklyPayments = PaymentVerification::where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, status, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                $result = ['date' => $items->first()->date];
                foreach ($items as $item) {
                    $result[$item->status] = $item->count;
                }

                return $result;
            })
            ->values();

        // Aktivitas terbaru (audit log)
        $recentActivity = AuditLog::latest()->take(10)->get();

        return view('keuangan.dashboard', [
            'stats' => $stats,
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'recentPending' => $recentPending,
            'recentVerified' => $recentVerified,
            'weeklyPayments' => $weeklyPayments,
            'recentActivity' => $recentActivity,
        ]);
    }

    /**
     * Menampilkan daftar pembayaran yang perlu diverifikasi
     */
    public function verifications(): View
    {
        $verifications = PaymentVerification::with('user')
            ->when(request('status'), function ($query, $status) {
                if ($status === 'pending') {
                    $query->pending();
                } elseif ($status === 'approved') {
                    $query->approved();
                } elseif ($status === 'rejected') {
                    $query->rejected();
                }
            })
            ->when(request('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when(request('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(20);

        return view('keuangan.verifications', [
            'verifications' => $verifications,
        ]);
    }

    /**
     * Menampilkan detail pembayaran
     */
    public function showVerification(PaymentVerification $verification): View
    {
        $verification->load(['user', 'verifier']);

        return view('keuangan.verification-detail', [
            'verification' => $verification,
        ]);
    }

    /**
     * Menyetujui pembayaran
     */
    public function approveVerification(Request $request, PaymentVerification $verification)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $verification->approve($request->notes);

        return redirect()->back()->with('success', __('app.msg_success_pembayaran_berhasil_disetujui_siswa_akan'));
    }

    /**
     * Menolak pembayaran
     */
    public function rejectVerification(Request $request, PaymentVerification $verification)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $verification->reject($request->reason);

        return redirect()->back()->with('success', __('app.msg_success_pembayaran_berhasil_ditolak'));
    }

    /**
     * Menampilkan laporan revenue
     */
    public function reports(): View
    {
        // Filter berdasarkan tanggal
        $startDate = request('date_from', now()->startOfMonth()->toDateString());
        $endDate = request('date_to', now()->endOfMonth()->toDateString());

        // Total statistik
        $totalApproved = PaymentVerification::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalRevenue = PaymentVerification::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');

        $totalDiscount = PaymentVerification::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('promo_code')
            ->sum('discount_amount');

        // Revenue per hari
        $dailyRevenue = PaymentVerification::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(verified_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top courses
        $topCourses = PaymentVerification::approved()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('course_title, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('course_title')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('keuangan.reports', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalApproved' => $totalApproved,
            'totalRevenue' => $totalRevenue,
            'totalDiscount' => $totalDiscount,
            'dailyRevenue' => $dailyRevenue,
            'topCourses' => $topCourses,
        ]);
    }

    /**
     * Export laporan ke CSV
     */
    public function exportReport()
    {
        $startDate = request('date_from', now()->startOfMonth()->toDateString());
        $endDate = request('date_to', now()->endOfMonth()->toDateString());

        $verifications = PaymentVerification::with(['user', 'verifier'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'laporan-pembayaran-'.date('Y-m-d').'.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Tanggal', 'Siswa', 'Kursus', 'Jumlah', 'Status', 'Diverifikasi Oleh', 'Metode Bayar']);

        foreach ($verifications as $i => $v) {
            fputcsv($output, [
                $i + 1,
                $v->created_at->format('d/m/Y H:i'),
                $v->user->name ?? '-',
                $v->course_title,
                'Rp '.number_format($v->amount),
                ucfirst($v->status),
                $v->verifier->name ?? '-',
                $v->payment_method ?? '-',
            ]);
        }

        fclose($output);
    }

    /**
     * Menampilkan enrollments
     */
    public function enrollments(): View
    {
        $enrollments = PaymentVerification::with('user')
            ->approved()
            ->latest('verified_at')
            ->paginate(25);

        return view('keuangan.enrollments', [
            'enrollments' => $enrollments,
        ]);
    }
}
