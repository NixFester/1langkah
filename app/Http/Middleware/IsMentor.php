<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa apakah user memiliki role Mentor
 *
 * Digunakan untuk melindungi halaman dan route yang hanya bisa
 * diakses oleh role Mentor
 */
class IsMentor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah user sudah login
        if (! auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth()->user();

        // Superadmin bisa akses semua route mentor
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Periksa apakah role user adalah mentor
        if ($user->role !== 'mentor') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Mentor atau Super Admin.');
        }

        // Periksa apakah user sudah membuat profil mentor
        // Exception: jangan redirect untuk route profile edit
        $isProfileRoute = $request->routeIs('mentor.profile.edit') || $request->routeIs('mentor.profile.update');

        if (! $isProfileRoute && $user->mentor === null) {
            return redirect()->route('mentor.profile.edit')
                ->with('info', 'Silakan lengkapi profil mentor Anda terlebih dahulu.');
        }

        return $next($request);
    }
}
