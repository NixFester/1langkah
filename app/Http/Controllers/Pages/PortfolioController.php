<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\PortfolioService;
use App\Services\PexelsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioController extends Controller
{
    private PortfolioService $portfolioService;
    private PexelsService $pexelsService;

    public function __construct(PortfolioService $portfolioService, PexelsService $pexelsService)
    {
        $this->portfolioService = $portfolioService;
        $this->pexelsService = $pexelsService;
    }

    /**
     * Show user's own portfolio
     */
    public function index(): View
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $portfolio = $this->portfolioService->getPortfolio(auth()->id());

        return view('pages.portofolio', [
            'portfolio' => $portfolio,
        ]);
    }

    /**
     * Show public portfolio (for sharing)
     */
    public function public(int $userId): View
    {
        $portfolio = $this->portfolioService->getPublicPortfolio($userId);

        if (empty($portfolio)) {
            abort(404, 'Portfolio not found');
        }

        return view('pages.portofolio-public', [
            'portfolio' => $portfolio,
        ]);
    }

    /**
     * Share portfolio (generate shareable data)
     */
    public function share(Request $request): \Illuminate\Http\JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $shareData = $this->portfolioService->generateShareData(auth()->id());

        return response()->json([
            'success' => true,
            'data' => $shareData,
        ]);
    }

    /**
     * Get placeholder image using Pexels
     */
    public function placeholder(Request $request): \Illuminate\Http\JsonResponse
    {
        $query = $request->input('q', 'education');
        $width = $request->input('w', 800);
        $height = $request->input('h', 450);

        $url = $this->pexelsService->getRandomPhotoUrl($query, $width, $height);

        return response()->json([
            'url' => $url,
        ]);
    }
}
