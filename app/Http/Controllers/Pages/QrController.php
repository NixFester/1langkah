<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Services\ProgressService;
use App\Models\AttendanceRecord;
use App\Models\Bootcamp;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class QrController extends Controller
{
    private ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Show QR scanning page
     */
    public function scan(?int $bootcampId = null): View
    {
        $bootcamp = null;
        $userBootcamps = [];

        if ($bootcampId) {
            $bootcamp = Bootcamp::find($bootcampId);
        }

        // Get user's registered bootcamps (offline bootcamps only for attendance)
        if (auth()->check()) {
            $userBootcamps = auth()->user()->enrollments()
                ->where('purchasable_type', Bootcamp::class)
                ->where('status', 'active')
                ->with('purchasable')
                ->get()
                ->map(function ($enrollment) {
                    $bootcamp = $enrollment->purchasable;
                    if ($bootcamp && $bootcamp->type === 'offline') {
                        return [
                            'id' => $bootcamp->id,
                            'title' => $bootcamp->title,
                        ];
                    }
                    return null;
                })
                ->filter()
                ->values()
                ->toArray();
        }

        return view('pages.scan-qr', [
            'bootcamp' => $bootcamp,
            'userBootcamps' => $userBootcamps,
        ]);
    }

    /**
     * Show QR code display page (for admin to show to students)
     */
    public function display(string $code): View
    {
        $attendance = AttendanceRecord::where('qr_code', $code)->first();

        if (!$attendance) {
            abort(404, 'QR Code not found');
        }

        $bootcamp = Bootcamp::find($attendance->bootcamp_id);

        return view('pages.qr-display', [
            'attendance' => $attendance,
            'bootcamp' => $bootcamp,
            'qrCode' => $code,
        ]);
    }

    /**
     * Process QR scan
     */
    public function processScan(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to mark attendance'
            ], 401);
        }

        $validated = $request->validate([
            'qr_code' => 'required|string',
            'bootcamp_id' => 'nullable|integer|exists:bootcamps,id',
        ]);

        $bootcampId = $validated['bootcamp_id'] ?? null;
        $bootcamp = $bootcampId ? Bootcamp::find($bootcampId) : null;

        if ($bootcamp && $bootcamp->type === 'offline') {
            $enrollment = Enrollment::where('purchasable_type', Bootcamp::class)
                ->where('purchasable_id', $bootcamp->id)
                ->where('ticket_code', $validated['qr_code'])
                ->where('status', 'active')
                ->first();

            if ($enrollment) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tiket valid. Pemegang tiket terverifikasi.',
                    'verified' => true,
                    'mode' => 'ticket',
                ]);
            }
        }

        // Find the attendance record
        $attendance = AttendanceRecord::where('qr_code', $validated['qr_code'])->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR Code'
            ], 404);
        }

        // Check if already verified
        if ($attendance->verified) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance already marked for this session'
            ], 400);
        }

        // Check if user matches
        if ($attendance->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'This QR code is not for your account'
            ], 403);
        }

        // Verify attendance
        $result = $this->progressService->verifyAttendance(
            auth()->id(),
            $attendance->bootcamp_id,
            $validated['qr_code']
        );

        return response()->json($result);
    }
}
