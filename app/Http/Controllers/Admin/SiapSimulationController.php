<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiapSimulationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiapSimulationController extends Controller
{
    public function __construct(private SiapSimulationService $simulationService) {}

    /**
     * Show bilingual warning popup before launching the temporary student SIAP simulation.
     */
    public function entry(): View
    {
        $sandboxConfigured = ! empty(config('payment.xendit.sandbox_api_key'));
        $sandboxWebhookConfigured = ! empty(config('payment.xendit.sandbox_callback_token'));

        return view('admin.siap.simulate', compact(
            'sandboxConfigured',
            'sandboxWebhookConfigured'
        ));
    }

    /**
     * Start temporary student simulation, force Data Diri completion first, then redirect to /siap.
     */
    public function start(Request $request): RedirectResponse
    {
        $admin = Auth::user();
        $this->simulationService->startSimulation($request, $admin);

        return redirect()->route('complete-profile')
            ->with('info', __('app.siap_sim_complete_profile_notice'));
    }

    /**
     * Exit simulation explicitly, purge all temporary data, and restore admin session.
     */
    public function exit(Request $request): RedirectResponse
    {
        $returnUrl = $this->simulationService->endSimulation($request);

        return redirect()->to($returnUrl ?: route('admin.siap.index'))
            ->with('info', __('app.siap_sim_ended_flash'));
    }
}
