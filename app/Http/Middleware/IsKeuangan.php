<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memeriksa apakah user memiliki role Keuangan
 *
 * Digunakan untuk melindungi halaman dan route yang hanya bisa
 * diakses oleh role Keuangan
 */
class IsKeuangan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah user sudah login
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Periksa apakah role user adalah keuangan
        if (auth()->user()->role !== 'keuangan' && auth()->user()->role !== 'superadmin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Keuangan.');
        }

        return $next($request);
    }
}
