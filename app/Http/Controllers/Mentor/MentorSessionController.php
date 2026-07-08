<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\MentorSession;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MentorSessionController extends Controller
{
    /**
     * Get the mentor's profile
     */
    private function getMentorProfile(): ?Mentor
    {
        return auth()->user()->mentor;
    }

    /**
     * Display mentor's session requests
     */
    public function index(): View
    {
        $mentorProfile = $this->getMentorProfile();

        if (! $mentorProfile) {
            abort(403, 'Profil mentor tidak ditemukan.');
        }

        // Pending sessions (new bookings)
        $pendingSessions = MentorSession::where('mentor_id', $mentorProfile->id)
            ->where('status', MentorSession::STATUS_PENDING)
            ->with('user')
            ->latest()
            ->get();

        // Active sessions (ongoing)
        $activeSessions = MentorSession::where('mentor_id', $mentorProfile->id)
            ->where('status', MentorSession::STATUS_ACTIVE)
            ->with('user')
            ->latest()
            ->get();

        // Completed sessions
        $completedSessions = MentorSession::where('mentor_id', $mentorProfile->id)
            ->where('status', MentorSession::STATUS_COMPLETED)
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('mentor.sessions.index', [
            'pendingSessions' => $pendingSessions,
            'activeSessions' => $activeSessions,
            'completedSessions' => $completedSessions,
            'mentor' => $mentorProfile,
        ]);
    }

    /**
     * Accept a session request
     */
    public function accept(MentorSession $session): RedirectResponse
    {
        $mentorProfile = $this->getMentorProfile();

        if ($session->mentor_id !== $mentorProfile->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi ini.');
        }

        if (! $session->isPending()) {
            return redirect()->back()->with('error', 'Sesi tidak dapat diterima.');
        }

        $session->update([
            'status' => MentorSession::STATUS_ACTIVE,
        ]);

        // Notify student
        Notification::create([
            'user_id' => $session->user_id,
            'type' => 'session_accepted',
            'title' => 'Booking Diterima!',
            'message' => 'Mentor telah menerima permintaan sesi mentoring Anda. Silakan hubungi mentor via WhatsApp.',
            'data' => json_encode([
                'session_id' => $session->id,
                'wa_link' => $session->wa_link,
            ]),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Sesi mentoring berhasil diterima!');
    }

    /**
     * Reject a session request
     */
    public function reject(Request $request, MentorSession $session): RedirectResponse
    {
        $mentorProfile = $this->getMentorProfile();

        if ($session->mentor_id !== $mentorProfile->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi ini.');
        }

        if (! $session->isPending()) {
            return redirect()->back()->with('error', 'Sesi tidak dapat ditolak.');
        }

        $session->update([
            'status' => MentorSession::STATUS_CANCELLED,
            'admin_notes' => $request->reason ?? 'Ditolak oleh mentor',
        ]);

        // Notify student
        Notification::create([
            'user_id' => $session->user_id,
            'type' => 'session_rejected',
            'title' => 'Booking Ditolak',
            'message' => 'Mohon maaf, mentor tidak dapat memenuhi permintaan sesi Anda. Silakan pilih waktu lain atau mentor lain.',
            'data' => json_encode([
                'session_id' => $session->id,
            ]),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Sesi mentoring berhasil ditolak.');
    }

    /**
     * Mark session as completed
     */
    public function complete(MentorSession $session): RedirectResponse
    {
        $mentorProfile = $this->getMentorProfile();

        if ($session->mentor_id !== $mentorProfile->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi ini.');
        }

        if (! $session->isActive()) {
            return redirect()->back()->with('error', 'Sesi tidak dapat diselesaikan.');
        }

        $session->update([
            'status' => MentorSession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        // Notify student
        Notification::create([
            'user_id' => $session->user_id,
            'type' => 'session_completed',
            'title' => 'Sesi Mentoring Selesai!',
            'message' => 'Terima kasih telah mengikuti sesi mentoring. Semoga ilmunya bermanfaat! Jangan ragu untuk booking sesi berikutnya.',
            'data' => json_encode([
                'session_id' => $session->id,
            ]),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Sesi mentoring berhasil diselesaikan!');
    }
}
