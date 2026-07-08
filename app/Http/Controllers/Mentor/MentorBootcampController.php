<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Bootcamp;
use App\Models\BootcampSession;
use App\Models\Mentor as MentorModel;
use App\Models\Option;
use App\Services\XpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MentorBootcampController extends Controller
{
    public function __construct(
        private XpService $xpService
    ) {}

    /**
     * Get mentor profile from authenticated user
     */
    private function getMentorProfile(): ?MentorModel
    {
        return auth()->user()->mentor;
    }

    /**
     * Authorize that the current user owns this bootcamp
     */
    private function authorizeOwnership(Bootcamp $bootcamp): void
    {
        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        if ($bootcamp->mentor_name !== $user->name && $bootcamp->mentor_id !== $mentorProfile?->id) {
            abort(403, 'Anda bukan pengajar bootcamp ini.');
        }
    }

    /**
     * Display a listing of the mentor's bootcamps
     */
    public function index(): View
    {
        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        $bootcamps = Bootcamp::where('mentor_name', $user->name)
            ->orWhere('mentor_id', $mentorProfile?->id)
            ->withCount('enrollments')
            ->with('sessions')
            ->latest()
            ->paginate(12);

        return view('mentor.bootcamps.index', [
            'bootcamps' => $bootcamps,
        ]);
    }

    /**
     * Show the form for creating a new bootcamp
     */
    public function create(): View
    {
        $types = Option::getOptionsForSelect('bootcamp_type');

        return view('mentor.bootcamps.create', compact('types'));
    }

    /**
     * Store a newly created bootcamp
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|'.Option::getValidationRule('bootcamp_type'),
            'price' => 'required|string|max:50',
            'start_date' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'sessions_info' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'participants' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        // Set mentor info
        $data['mentor_name'] = $user->name;
        $data['mentor_id'] = $mentorProfile?->id;

        $bootcamp = Bootcamp::create($data);

        // Create sessions if provided
        if ($request->has('sessions')) {
            $sessions = $request->input('sessions');
            foreach ($sessions as $session) {
                if (! empty($session['topic'])) {
                    $bootcamp->sessions()->create([
                        'date' => $session['date'] ?? '',
                        'topic' => $session['topic'],
                        'time' => $session['time'] ?? '',
                        'meeting_url' => $session['meeting_url'] ?? null,
                        'description' => $session['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('mentor.bootcamps.edit', $bootcamp)
            ->with('success', 'Bootcamp berhasil dibuat.');
    }

    /**
     * Show the form for editing a bootcamp
     */
    public function edit(Bootcamp $bootcamp): View
    {
        $this->authorizeOwnership($bootcamp);
        $bootcamp->load('sessions');
        $types = Option::getOptionsForSelect('bootcamp_type');

        return view('mentor.bootcamps.edit', [
            'bootcamp' => $bootcamp,
            'types' => $types,
        ]);
    }

    /**
     * Update the specified bootcamp
     */
    public function update(Request $request, Bootcamp $bootcamp): RedirectResponse
    {
        $this->authorizeOwnership($bootcamp);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|'.Option::getValidationRule('bootcamp_type'),
            'price' => 'required|string|max:50',
            'start_date' => 'required|string|max:100',
            'location' => 'nullable|string|max:255',
            'sessions_info' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:20',
            'participants' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
        ]);

        $bootcamp->update($data);

        return redirect()->back()->with('success', 'Bootcamp berhasil diperbarui.');
    }

    /**
     * Remove the specified bootcamp
     */
    public function destroy(Bootcamp $bootcamp): RedirectResponse
    {
        $this->authorizeOwnership($bootcamp);
        $bootcamp->delete();

        return redirect()->route('mentor.bootcamps.index')
            ->with('success', 'Bootcamp berhasil dihapus.');
    }

    /* ── Session Management ─────────────────────────────────────────── */

    /**
     * Add a session to a bootcamp
     */
    public function storeSession(Request $request, Bootcamp $bootcamp): RedirectResponse
    {
        $this->authorizeOwnership($bootcamp);

        $data = $request->validate([
            'date' => 'required|string|max:100',
            'topic' => 'required|string|max:255',
            'time' => 'required|string|max:100',
            'meeting_url' => 'nullable|url|max:500',
            'description' => 'nullable|string|max:255',
        ]);

        $data['password'] = $bootcamp->isOnline() ? BootcampSession::generatePassword() : null;
        $bootcamp->sessions()->create($data);

        return redirect()->back()->with('success', 'Sesi berhasil ditambahkan.');
    }

    /**
     * Update a session
     */
    public function updateSession(Request $request, Bootcamp $bootcamp, BootcampSession $session): RedirectResponse
    {
        $this->authorizeOwnership($bootcamp);

        if ($session->bootcamp_id !== $bootcamp->id) {
            abort(404, 'Sesi tidak ditemukan.');
        }

        $data = $request->validate([
            'date' => 'required|string|max:100',
            'topic' => 'required|string|max:255',
            'time' => 'required|string|max:100',
            'meeting_url' => 'nullable|url|max:500',
            'description' => 'nullable|string|max:255',
        ]);

        $session->update($data);

        return redirect()->back()->with('success', 'Sesi berhasil diperbarui.');
    }

    /**
     * Delete a session
     */
    public function destroySession(Bootcamp $bootcamp, BootcampSession $session): RedirectResponse
    {
        $this->authorizeOwnership($bootcamp);

        if ($session->bootcamp_id !== $bootcamp->id) {
            abort(404, 'Sesi tidak ditemukan.');
        }

        $session->delete();

        return redirect()->back()->with('success', 'Sesi berhasil dihapus.');
    }

    /* ── Attendance Management ─────────────────────────────────────── */

    /**
     * View attendance for a bootcamp
     */
    public function attendance(Request $request): View
    {
        $bootcamp = Bootcamp::with('sessions')->findOrFail($request->route('bootcamp'));
        $this->authorizeOwnership($bootcamp);

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

        return view('mentor.bootcamps.attendance', [
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

        $bootcamp = Bootcamp::findOrFail($request->route('bootcamp'));
        $this->authorizeOwnership($bootcamp);

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
            ->with('success', "Kode absensi berhasil di-generate untuk {$validated['date']}")
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
            return redirect()->back()->with('error', 'Kode tidak valid atau sudah digunakan.');
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

        return redirect()->back()->with('success', "Absensi ditandai untuk {$user->name}. +XP awarded!");
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
