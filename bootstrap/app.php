<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsKeuangan;
use App\Http\Middleware\IsMarketing;
use App\Http\Middleware\IsMentor;
use App\Http\Middleware\IsSuperadmin;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->redirectGuestsTo('/login');
        // Redirect user based on role to their appropriate dashboard (matches role-flow-diagrams.md)
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();

            return $user?->getDashboardRoute() ?? '/dashboard';
        });

        // Register Role-specific middleware aliases
        $middleware->alias([
            'admin' => IsAdmin::class,
            'superadmin' => IsSuperadmin::class,
            'keuangan' => IsKeuangan::class,
            'marketing' => IsMarketing::class,
            'mentor' => IsMentor::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
