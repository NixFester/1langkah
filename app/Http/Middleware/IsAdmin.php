<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Role hierarchy: higher index = more privileges
     */
    private const ROLE_HIERARCHY = [
        'superadmin' => 6,
        'admin'      => 5,
        'keuangan'   => 4,
        'marketing'  => 3,
        'mentor'     => 2,
        'student'    => 1,
    ];

    /**
     * Roles that can access admin panel
     */
    private const ADMIN_ROLES = ['superadmin', 'admin', 'keuangan', 'marketing'];

    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized action. Please login first.');
        }

        $userRole = auth()->user()->role;

        // If specific role required, check exact match
        if ($role !== null) {
            if (!$this->hasRole($userRole, $role)) {
                abort(403, 'Unauthorized action. Insufficient permissions.');
            }
            return $next($request);
        }

        // Default: check if user has any admin panel access
        if (!in_array($userRole, self::ADMIN_ROLES)) {
            abort(403, 'Unauthorized action. Admin panel access required.');
        }

        return $next($request);
    }

    /**
     * Check if user has specific role or higher
     */
    public function hasRole(string $userRole, string $requiredRole): bool
    {
        $userLevel = self::ROLE_HIERARCHY[$userRole] ?? 0;
        $requiredLevel = self::ROLE_HIERARCHY[$requiredRole] ?? 0;

        return $userLevel >= $requiredLevel;
    }

    /**
     * Check if user can access admin panel
     */
    public function canAccessAdmin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return in_array(auth()->user()->role, self::ADMIN_ROLES);
    }
}