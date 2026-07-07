<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Mentor as MentorModel;
use App\Services\XpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller untuk Manajemen Event oleh Mentor
 * Memungkinkan mentor membuat dan mengelola event mereka sendiri
 * Termasuk fitur ticket scanning untuk event offline
 */
class MentorEventController extends Controller
{
    public function __construct(
        private XpService $xpService
    ) {}

    /**
     * Menampilkan daftar event milik mentor
     */
    public function index(): View
    {
        $mentorProfile = $this->getMentorProfile();

        $events = Event::where('mentor_id', $mentorProfile?->id)
            ->where('is_mentor_created', true)
            ->withCount('registrations')
            ->latest()
            ->paginate(12);

        return view('mentor.events.index', [
            'events' => $events,
        ]);
    }

    /**
     * Menampilkan form pembuatan event
     */
    public function create(): View
    {
        return view('mentor.events.create');
    }

    /**
     * Menyimpan event baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:300',
            'description' => 'nullable|string',
            'type' => 'required|in:online,offline,hybrid',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i',
            'timezone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url|max:500',
            'max_participants' => 'nullable|integer|min:1',
            'banner_url' => 'nullable|url|max:500',
            'color' => 'nullable|string|max:20',
        ]);

        $user = auth()->user();
        $mentorProfile = $this->getMentorProfile();

        if (! $mentorProfile) {
            return redirect()->back()->with('error', 'Profil mentor tidak ditemukan.');
        }

        // Combine date and time
        $startDateTime = $validated['start_date'].' '.$validated['start_time'].':00';
        $endDateTime = null;
        if (! empty($validated['end_date'])) {
            $endDateTime = $validated['end_date'].' '.($validated['end_time'] ?? '23:59').':00';
        }

        $event = Event::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']).'-'.Str::random(5),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'start_date' => $startDateTime,
            'end_date' => $endDateTime ?? $startDateTime,
            'timezone' => $validated['timezone'] ?? 'Asia/Jakarta',
            'location' => $validated['location'] ?? null,
            'meeting_url' => $validated['meeting_url'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'banner_url' => $validated['banner_url'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'status' => 'published',
            'created_by' => $user->id,
            'mentor_id' => $mentorProfile->id,
            'is_mentor_created' => true,
        ]);

        return redirect()->route('mentor.events.edit', $event)
            ->with('success', 'Event berhasil dibuat.');
    }

    /**
     * Menampilkan form edit event
     */
    public function edit(Event $event): View
    {
        $this->authorizeMentorOwnership($event);

        return view('mentor.events.edit', [
            'event' => $event,
        ]);
    }

    /**
     * Memperbarui event
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeMentorOwnership($event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:300',
            'description' => 'nullable|string',
            'type' => 'required|in:online,offline,hybrid',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_date' => 'nullable|date',
            'end_time' => 'nullable|date_format:H:i',
            'timezone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'meeting_url' => 'nullable|url|max:500',
            'max_participants' => 'nullable|integer|min:1',
            'banner_url' => 'nullable|url|max:500',
            'color' => 'nullable|string|max:20',
            'status' => 'required|in:draft,published,cancelled,completed',
        ]);

        // Combine date and time
        $startDateTime = $validated['start_date'].' '.$validated['start_time'].':00';
        $endDateTime = null;
        if (! empty($validated['end_date'])) {
            $endDateTime = $validated['end_date'].' '.($validated['end_time'] ?? '23:59').':00';
        }

        $event->update([
            'title' => $validated['title'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'start_date' => $startDateTime,
            'end_date' => $endDateTime ?? $startDateTime,
            'timezone' => $validated['timezone'] ?? 'Asia/Jakarta',
            'location' => $validated['location'] ?? null,
            'meeting_url' => $validated['meeting_url'] ?? null,
            'max_participants' => $validated['max_participants'] ?? null,
            'banner_url' => $validated['banner_url'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Menampilkan daftar registrasi event
     */
    public function registrations(Event $event): View
    {
        $this->authorizeMentorOwnership($event);

        $registrations = EventRegistration::where('event_id', $event->id)
            ->with('user')
            ->latest()
            ->paginate(25);

        return view('mentor.events.registrations', [
            'event' => $event,
            'registrations' => $registrations,
        ]);
    }

    /**
     * Menandai peserta hadir dan memberikan XP
     */
    public function markAttended(EventRegistration $registration): RedirectResponse
    {
        $event = $registration->event;

        $this->authorizeMentorOwnership($event);

        if ($registration->attended_at) {
            return redirect()->back()->with('warning', 'Peserta sudah ditandai hadir.');
        }

        $registration->update([
            'attended_at' => now(),
            'status' => 'attended',
        ]);

        // Award XP for attending event
        $this->xpService->awardXp(
            $registration->user,
            'event_attended',
            EventRegistration::class,
            $registration->id
        );

        // Check for event attendance achievements
        app(AchievementService::class)->checkAndAward(
            $registration->user,
            AchievementService::TRIGGER_EVENT_ATTENDED
        );

        return redirect()->back()->with('success', 'Peserta ditandai hadir dan mendapatkan 20 XP.');
    }

    /* ── Ticket Scanner ─────────────────────────────────────────────── */

    /**
     * Menampilkan halaman ticket scanner untuk event
     */
    public function ticketScanner(Event $event): View
    {
        $this->authorizeMentorOwnership($event);

        // Get recent attendance records
        $recentAttendances = EventRegistration::where('event_id', $event->id)
            ->whereNotNull('attended_at')
            ->with('user')
            ->latest('attended_at')
            ->take(50)
            ->get();

        // Get statistics
        $stats = [
            'total_registrations' => EventRegistration::where('event_id', $event->id)->count(),
            'attended_count' => EventRegistration::where('event_id', $event->id)->whereNotNull('attended_at')->count(),
            'pending_count' => EventRegistration::where('event_id', $event->id)->whereNull('attended_at')->count(),
        ];

        return view('mentor.events.ticket-scanner', [
            'event' => $event,
            'recentAttendances' => $recentAttendances,
            'stats' => $stats,
        ]);
    }

    /**
     * Scan ticket code untuk event
     */
    public function scanTicket(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeMentorOwnership($event);

        $validated = $request->validate([
            'ticket_code' => 'required|string|max:50',
        ]);

        // Find registration by ticket code
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('ticket_code', $validated['ticket_code'])
            ->first();

        // If no ticket_code match, try to find by user email or name
        if (! $registration) {
            $registration = EventRegistration::where('event_id', $event->id)
                ->whereHas('user', function ($query) use ($validated) {
                    $query->where('email', $validated['ticket_code'])
                        ->orWhere('name', 'LIKE', '%'.$validated['ticket_code'].'%');
                })
                ->first();
        }

        if (! $registration) {
            return redirect()->back()
                ->with('error', 'Tiket tidak ditemukan. Pastikan kode tiket benar.');
        }

        if ($registration->attended_at) {
            return redirect()->back()
                ->with('warning', "Peserta {$registration->user->name} sudah hadir pada ".$registration->attended_at->format('H:i:s'));
        }

        // Mark as attended and award XP
        $registration->update([
            'attended_at' => now(),
            'status' => 'attended',
        ]);

        $this->xpService->awardXp(
            $registration->user,
            'event_attended',
            EventRegistration::class,
            $registration->id
        );

        // Check for event attendance achievements
        app(AchievementService::class)->checkAndAward(
            $registration->user,
            AchievementService::TRIGGER_EVENT_ATTENDED
        );

        return redirect()->back()
            ->with('success', "Tiket {@$registration->user->name} berhasil discan! +XP awarded!");
    }

    /**
     * Mendapatkan profil mentor dari user yang login
     */
    private function getMentorProfile(): ?MentorModel
    {
        $user = auth()->user();

        // First try to find by user_id (most reliable)
        $mentor = MentorModel::where('user_id', $user->id)->first();

        // Fallback to name matching
        if (! $mentor) {
            $mentor = MentorModel::where('name', $user->name)->first();
        }

        // Fallback: if user is a mentor and there's only one mentor profile, use it
        // (useful for testing when names don't match)
        if (! $mentor && $user->role === 'mentor') {
            $mentor = MentorModel::first();
        }

        return $mentor;
    }

    /**
     * Memeriksa kepemilikan event oleh mentor
     */
    private function authorizeMentorOwnership(Event $event): void
    {
        $mentorProfile = $this->getMentorProfile();

        if (! $mentorProfile || $event->mentor_id !== $mentorProfile->id) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
