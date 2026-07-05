<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to redirect users away from /dashboard if they have a role-specific dashboard
 */
class RedirectNonStudentsToRoleDashboard
{
    /**
     * Handle an incoming request.
     *
     * Redirects users with role-specific dashboards (superadmin, admin, keuangan, marketing, mentor)
     * away from the generic /dashboard route to their appropriate role dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role !== 'student') {
            return redirect($user->getDashboardRoute());
        }

        return $next($request);
    }
}
