<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Bootcamp;
use App\Services\ProgressService;
use App\Services\XpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MentorAttendanceController extends Controller
{
    public function __construct(
        private ProgressService $progressService,
        private XpService $xpService
    ) {}

    /**
     * View attendance for a bootcamp
     */
    public function index(Request $request): View
    {
        $bootcamp = Bootcamp::with('sessions')->findOrFail($request->route('bootcampId'));

        $records = AttendanceRecord::where('bootcamp_id', $bootcamp->id)
            ->with('user')
            ->orderBy('attendance_date', 'desc')
            ->get()
            ->groupBy('attendance_date');

        // Get unique short codes for today
        $todayRecords = AttendanceRecord::where('bootcamp_id', $bootcamp->id)
            ->whereDate('attendance_date', today())
            ->get()
            ->map(fn ($r) => $r->short_code)
            ->filter()
            ->unique();

        return view('pages.mentor.attendance.index', [
            'bootcamp' => $bootcamp,
            'records' => $records,
            'todayCodes' => $todayRecords,
        ]);
    }

    /**
     * Generate attendance codes for a bootcamp session
     */
    public function generateCodes(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $bootcamp = Bootcamp::findOrFail($request->route('bootcampId'));

        // Generate a 4-character alphanumeric short code
        $shortCode = $this->generateShortCode();

        // Get all enrolled users
        $enrolledUsers = $bootcamp->enrollments()->with('user')->get()->pluck('user');

        foreach ($enrolledUsers as $user) {
            $userCode = $this->generateShortCode();

            // Create attendance record with unique QR and short code
            $uniqueQrCode = md5("bootcamp_{$bootcamp->id}_user_{$user->id}_date_{$validated['date']}_".now()->timestamp.Str::random(5));

            AttendanceRecord::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'bootcamp_id' => $bootcamp->id,
                    'attendance_date' => $validated['date'],
                ],
                [
                    'qr_code' => $uniqueQrCode,
                    'short_code' => $userCode,
                    'verified' => false,
                ]
            );
        }

        return redirect()->back()
            ->with('success', "Attendance codes generated for {$validated['date']}")
            ->with('short_code', $shortCode);
    }

    /**
     * Scan/Input code to mark attendance
     */
    public function scanCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'short_code' => 'required|string|size:4',
        ]);

        // Find attendance record by short code
        $record = AttendanceRecord::where('short_code', strtoupper($validated['short_code']))
            ->whereDate('attendance_date', today())
            ->where('verified', false)
            ->first();

        if (! $record) {
            return redirect()->back()->with('error', 'Invalid or already used code');
        }

        // Mark as verified
        $record->update([
            'verified' => true,
            'scanned_at' => now(),
        ]);

        // Award XP for attendance scanned
        $user = $record->user;
        if ($user) {
            $this->xpService->awardXp(
                $user,
                'attendance_scanned',
                AttendanceRecord::class,
                $record->id
            );
        }

        return redirect()->back()->with('success', "Attendance marked for {$user->name}. +XP awarded!");
    }

    /**
     * Generate a 4-character alphanumeric short code
     * Excludes ambiguous characters: I, O, 0, 1
     */
    private function generateShortCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // No I, O, 0, 1
        $code = '';

        for ($i = 0; $i < 4; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $code;
    }
}
