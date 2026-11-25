<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\PaymentTransaction;
use App\Models\SiapAttempt;
use App\Models\SiapOrder;
use App\Models\TestAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SiapSimulationService
{
    public const SESSION_KEY = 'siap_simulation';

    public const EMAIL_PREFIX = 'siap-sim-';

    public const EMAIL_DOMAIN = '@sandbox.1langkah.test';

    /**
     * Check whether a SIAP simulation session is currently active.
     */
    public function isActive(): bool
    {
        return session()->has(self::SESSION_KEY);
    }

    /**
     * Start a temporary student simulation for an admin user and log in as the temporary student.
     */
    public function startSimulation(Request $request, User $admin): User
    {
        $this->cleanupStaleSimulations();

        if ($request->session()->has(self::SESSION_KEY)) {
            $existing = $request->session()->get(self::SESSION_KEY);
            if (! empty($existing['temp_user_id'])) {
                $this->purgeTemporaryUser((int) $existing['temp_user_id']);
            }
        }

        $randomSuffix = strtolower(Str::random(10));
        $tempUser = User::create([
            'name' => 'Simulasi Siswa',
            'username' => 'siap_sim_'.$randomSuffix,
            'email' => self::EMAIL_PREFIX.$randomSuffix.self::EMAIL_DOMAIN,
            'role' => 'student',
            'password' => Str::random(24),
        ]);

        $request->session()->put(self::SESSION_KEY, [
            'admin_id' => $admin->id,
            'admin_return_url' => route('admin.siap.index'),
            'temp_user_id' => $tempUser->id,
            'started_at' => now()->toIso8601String(),
        ]);

        $request->session()->put('url.intended', route('siap.index'));
        Auth::login($tempUser);

        return $tempUser;
    }

    /**
     * End the active simulation, delete all temporary data, and restore the admin session.
     */
    public function endSimulation(Request $request): ?string
    {
        $simulation = $request->session()->get(self::SESSION_KEY);
        if (! is_array($simulation)) {
            return null;
        }

        if (! empty($simulation['temp_user_id'])) {
            $this->purgeTemporaryUser((int) $simulation['temp_user_id']);
        }

        $request->session()->forget([self::SESSION_KEY, 'url.intended']);

        if (! empty($simulation['admin_id'])) {
            $admin = User::find((int) $simulation['admin_id']);
            if ($admin) {
                Auth::login($admin);
            } else {
                Auth::logout();
            }
        } else {
            Auth::logout();
        }

        return $simulation['admin_return_url'] ?? route('admin.siap.index');
    }

    /**
     * Remove abandoned simulation users older than the given threshold.
     */
    public function cleanupStaleSimulations(int $olderThanHours = 2): void
    {
        $staleIds = User::where('email', 'like', self::EMAIL_PREFIX.'%'.self::EMAIL_DOMAIN)
            ->where('created_at', '<', now()->subHours($olderThanHours))
            ->pluck('id');

        foreach ($staleIds as $userId) {
            $this->purgeTemporaryUser((int) $userId);
        }
    }

    /**
     * Permanently delete a temporary simulation user and all associated records.
     */
    public function purgeTemporaryUser(int $userId): void
    {
        $user = User::find($userId);
        if (! $user || ! $this->isSimulationUser($user)) {
            return;
        }

        DB::transaction(function () use ($user) {
            $attemptIds = SiapAttempt::where('user_id', $user->id)->pluck('id');

            Certificate::where('user_id', $user->id)->delete();
            if ($attemptIds->isNotEmpty()) {
                Certificate::where('certifiable_type', SiapAttempt::class)
                    ->whereIn('certifiable_id', $attemptIds)
                    ->delete();
            }

            $txIds = SiapOrder::where('user_id', $user->id)
                ->whereNotNull('payment_transaction_id')
                ->pluck('payment_transaction_id');

            SiapOrder::where('user_id', $user->id)->delete();
            PaymentTransaction::where('user_id', $user->id)->delete();
            if ($txIds->isNotEmpty()) {
                PaymentTransaction::whereIn('id', $txIds)->delete();
            }

            SiapAttempt::where('user_id', $user->id)->delete();
            TestAttempt::where('user_id', $user->id)->delete();

            $user->activityLogs()->delete();
            $user->xpTransactions()->delete();
            $user->achievements()->delete();
            $user->settings()->delete();

            if (Schema::hasTable('notifications')) {
                if (Schema::hasColumn('notifications', 'user_id')) {
                    DB::table('notifications')->where('user_id', $user->id)->delete();
                } elseif (Schema::hasColumn('notifications', 'notifiable_id')) {
                    DB::table('notifications')
                        ->where('notifiable_type', User::class)
                        ->where('notifiable_id', $user->id)
                        ->delete();
                }
            }

            $user->delete();
        });
    }

    public function isSimulationUser(?User $user): bool
    {
        return $user !== null
            && Str::startsWith($user->email, self::EMAIL_PREFIX)
            && Str::endsWith($user->email, self::EMAIL_DOMAIN);
    }
}
