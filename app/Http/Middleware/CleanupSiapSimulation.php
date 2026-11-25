<?php

namespace App\Http\Middleware;

use App\Services\SiapSimulationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CleanupSiapSimulation
{
    public function __construct(private SiapSimulationService $simulationService) {}

    /**
     * Enforce personal-data completion before entering /siap during simulation,
     * and delete all temporary simulation data as soon as the user leaves simulation routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has(SiapSimulationService::SESSION_KEY)) {
            return $next($request);
        }

        $routeName = (string) ($request->route()?->getName() ?? '');

        $isAllowedRoute = $routeName !== '' && (
            in_array($routeName, [
                'complete-profile',
                'complete-profile.submit',
                'lang.switch',
                'webhook.xendit',
                'api.notifications',
                'api.notifications.count',
                'api.notifications.read',
                'api.notifications.read-all',
            ], true)
            || Str::startsWith($routeName, 'siap.')
        );

        if ($isAllowedRoute) {
            $user = Auth::user();
            if (
                Str::startsWith($routeName, 'siap.')
                && $routeName !== 'siap.simulation.exit'
                && $this->simulationService->isSimulationUser($user)
                && ! $user->hasCompletedPersonalData()
            ) {
                return redirect()->route('complete-profile')
                    ->with('info', __('app.siap_sim_complete_profile_notice'));
            }

            return $next($request);
        }

        $this->simulationService->endSimulation($request);
        $request->session()->flash('info', __('app.siap_sim_ended_flash'));

        if (Str::startsWith($routeName, ['admin.', 'superadmin.'])) {
            return $next($request);
        }

        return redirect()->route('admin.siap.index');
    }
}
