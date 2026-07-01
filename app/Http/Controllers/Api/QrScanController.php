<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProgressService;
use App\Models\AttendanceRecord;
use App\Models\Bootcamp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QrScanController extends Controller
{
    private ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Verify QR code attendance
     * POST /api/qr/verify
     */
    public function verify(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to mark attendance'
            ], 401);
        }

        $validated = $request->validate([
            'qr_code' => 'required|string',
            'bootcamp_id' => 'required|integer|exists:bootcamps,id',
        ]);

        $result = $this->progressService->verifyAttendance(
            auth()->id(),
            $validated['bootcamp_id'],
            $validated['qr_code']
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance verified! 🎉',
                'verified' => true,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
            'verified' => false,
        ], 400);
    }

    /**
     * Check if user already scanned
     * GET /api/qr/check/{bootcampId}
     */
    public function checkAttendance(int $bootcampId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $bootcamp = Bootcamp::find($bootcampId);

        if (!$bootcamp) {
            return response()->json(['success' => false, 'message' => 'Bootcamp not found'], 404);
        }

        $records = AttendanceRecord::where('user_id', auth()->id())
            ->where('bootcamp_id', $bootcampId)
            ->where('attendance_date', now()->toDateString())
            ->first();

        return response()->json([
            'success' => true,
            'already_scanned' => $records?->verified ?? false,
            'attendance_date' => $records?->attendance_date,
        ]);
    }

    /**
     * Get user's attendance history for a bootcamp
     * GET /api/qr/history/{bootcampId}
     */
    public function getHistory(int $bootcampId): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $result = $this->progressService->getAttendanceRecords(auth()->id(), $bootcampId);

        return response()->json($result);
    }

    /**
     * Generate QR code data for admin
     * POST /api/qr/generate (Admin only)
     */
    public function generate(Request $request): JsonResponse
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'bootcamp_id' => 'required|integer|exists:bootcamps,id',
            'date' => 'required|date',
        ]);

        $qrCode = $this->progressService->generateAttendanceQrCode(
            $validated['bootcamp_id'],
            $validated['date']
        );

        return response()->json([
            'success' => true,
            'qr_code' => $qrCode,
            'qr_url' => route('scan.qr', ['code' => $qrCode]),
        ]);
    }
}
