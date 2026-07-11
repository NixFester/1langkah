<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Option;
use App\Models\PaymentVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk Dashboard Superadmin
 * Level tertinggi, bisa akses semua fitur dan audit log
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama Superadmin
     */
    public function index(): View
    {
        // Statistik user per role
        $userStats = [
            'total' => User::count(),
            'superadmins' => User::where('role', 'superadmin')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'keuangans' => User::where('role', 'keuangan')->count(),
            'marketings' => User::where('role', 'marketing')->count(),
            'mentors' => User::where('role', 'mentor')->count(),
            'students' => User::where('role', 'student')->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
            'new_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        // Statistik sistem
        $systemStats = [
            'courses' => Course::count(),
            'enrollments' => Enrollment::count(),
            'revenue_this_month' => PaymentVerification::approved()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'pending_payments' => PaymentVerification::pending()->count(),
        ];

        // Aktivitas terbaru
        $recentActivity = AuditLog::with('user')
            ->latest()
            ->take(20)
            ->get();

        // User terbaru
        $recentUsers = User::latest()->take(10)->get();

        // Course terbaru
        $recentCourses = Course::latest()->take(5)->get();

        // Chart data - user growth
        $userGrowth = User::where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Role distribution
        $roleDistribution = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->role => $item->count]);

        return view('superadmin.dashboard', [
            'userStats' => $userStats,
            'systemStats' => $systemStats,
            'recentActivity' => $recentActivity,
            'recentUsers' => $recentUsers,
            'recentCourses' => $recentCourses,
            'userGrowth' => $userGrowth,
            'roleDistribution' => $roleDistribution,
        ]);
    }

    /**
     * Menampilkan daftar semua user
     */
    public function users(): View
    {
        $users = User::with('activityLogs')
            ->when(request('role'), function ($query, $role) {
                $query->where('role', $role);
            })
            ->when(request('search'), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(25);

        $roles = Option::getOptionsForSelect('user_role');

        return view('superadmin.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Form edit user
     */
    public function editUser(User $user): View
    {
        $roles = Option::getOptionsForSelect('user_role');

        return view('superadmin.user-form', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $oldValues = $user->toArray();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:superadmin,admin,keuangan,marketing,mentor,student',
            'profile_photo' => 'nullable|url|max:500',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update($data);

        AuditLog::log(
            AuditLog::ACTION_UPDATE,
            User::class,
            $user->id,
            array_intersect_key($oldValues, array_flip(['name', 'email', 'role'])),
            array_intersect_key($data, array_flip(['name', 'email', 'role'])),
            "Mengubah user: {$user->name}"
        );

        return redirect()->route('superadmin.users')
            ->with('success', __('app.msg_success_user_berhasil_diperbarui'));
    }

    /**
     * Ubah role user
     */
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:superadmin,admin,keuangan,marketing,mentor,student',
        ]);

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        AuditLog::log(
            AuditLog::ACTION_ROLE_CHANGE,
            User::class,
            $user->id,
            ['role' => $oldRole],
            ['role' => $request->role],
            "Mengubah role {$user->name} dari {$oldRole} menjadi {$request->role}"
        );

        return redirect()->back()->with('success', __('app.msg_success_role_berhasil_diubah'));
    }

    /**
     * Hapus user
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', __('app.msg_error_anda_tidak_bisa_menghapus_akun_sendiri'));
        }

        $name = $user->name;
        $user->delete();

        AuditLog::log(
            AuditLog::ACTION_DELETE,
            User::class,
            null,
            ['name' => $name],
            null,
            "Menghapus user: {$name}"
        );

        return redirect()->route('superadmin.users')
            ->with('success', __('app.msg_success_user_berhasil_dihapus'));
    }

    /**
     * Menampilkan audit log
     */
    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')
            ->when(request('action'), function ($query, $action) {
                $query->where('action', $action);
            })
            ->when(request('user_id'), function ($query, $userId) {
                $query->where('user_id', $userId);
            })
            ->when(request('model_type'), function ($query, $type) {
                $query->where('model_type', $type);
            })
            ->when(request('date_from'), function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when(request('date_to'), function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->latest()
            ->paginate(50);

        // Statistik ringkasan
        $summary = [
            'total_logs' => AuditLog::count(),
            'today_logs' => AuditLog::whereDate('created_at', today())->count(),
            'this_week' => AuditLog::where('created_at', '>=', now()->startOfWeek())->count(),
            'logins_today' => AuditLog::where('action', 'login')->whereDate('created_at', today())->count(),
        ];

        // User yang paling aktif
        $activeUsers = AuditLog::selectRaw('user_id, COUNT(*) as action_count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('user_id')
            ->orderByDesc('action_count')
            ->take(10)
            ->with('user')
            ->get();

        return view('superadmin.audit-logs', [
            'logs' => $logs,
            'summary' => $summary,
            'activeUsers' => $activeUsers,
        ]);
    }

    /**
     * Detail audit log
     */
    public function auditLogDetail(AuditLog $log): View
    {
        $log->load('user');

        return view('superadmin.audit-log-detail', [
            'log' => $log,
        ]);
    }

    /**
     * Statistik sistem
     */
    public function systemStats(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_courses' => Course::count(),
            'total_enrollments' => Enrollment::count(),
            'total_revenue' => PaymentVerification::approved()->sum('amount'),
            'pending_verifications' => PaymentVerification::pending()->count(),
        ];

        // User growth per bulan (12 bulan terakhir)
        $monthlyUsers = User::where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Revenue per bulan
        $monthlyRevenue = PaymentVerification::approved()
            ->where('created_at', '>=', now()->subMonths(12))
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return view('superadmin.system-stats', [
            'stats' => $stats,
            'monthlyUsers' => $monthlyUsers,
            'monthlyRevenue' => $monthlyRevenue,
        ]);
    }
}
