<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\MentorSession;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MentorSessionController extends Controller
{
    /**
     * Display student's mentor sessions
     */
    public function mySessions(): View
    {
        $user = Auth::user();

        // Active session (pending or active status)
        $activeSession = MentorSession::where('user_id', $user->id)
            ->whereIn('status', [MentorSession::STATUS_PENDING, MentorSession::STATUS_ACTIVE])
            ->with('mentor')
            ->first();

        // Session history (completed or cancelled)
        $history = MentorSession::where('user_id', $user->id)
            ->whereIn('status', [MentorSession::STATUS_COMPLETED, MentorSession::STATUS_CANCELLED])
            ->with('mentor')
            ->latest()
            ->paginate(10);

        return view('pages.my-sessions', [
            'activeSession' => $activeSession,
            'history' => $history,
        ]);
    }

    /**
     * Book a mentor session - store booking data and redirect to payment
     */
    public function book(Request $request, int $userId): RedirectResponse
    {
        $user = Auth::user();

        // Find mentor by user_id
        $mentor = Mentor::where('user_id', $userId)->firstOrFail();

        // Check if user already has an active session
        $existingSession = MentorSession::where('user_id', $user->id)
            ->whereIn('status', [MentorSession::STATUS_PENDING, MentorSession::STATUS_ACTIVE])
            ->first();

        if ($existingSession) {
            return redirect()->back()->with('error', __('app.msg_error_anda_masih_memiliki_sesi_mentoring_yang_'));
        }

        $validated = $request->validate([
            'booked_date' => 'required|date|after_or_equal:today',
            'booked_time' => 'required',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Store booking data in session for post-payment processing
        session([
            'pending_mentor_session' => [
                'mentor_id' => $mentor->id,
                'mentor_name' => $mentor->name,
                'booked_date' => $validated['booked_date'],
                'booked_time' => $validated['booked_time'],
                'notes' => $validated['notes'] ?? null,
                'price' => $mentor->price,
                'formatted_price' => $mentor->formatted_price,
            ],
        ]);

        // Redirect to payment page
        return redirect()->route('pembayaran');
    }

    /**
     * Cancel a session
     */
    public function cancel(MentorSession $session): RedirectResponse
    {
        $user = Auth::user();

        // Verify ownership
        if ($session->user_id !== $user->id) {
            abort(403, __('app.msg_abort_anda_tidak_memiliki_akses_ke_sesi_ini'));
        }

        // Can only cancel pending sessions
        if (! $session->isPending()) {
            return redirect()->back()->with('error', __('app.msg_error_sesi_tidak_dapat_dibatalkan'));
        }

        $session->update([
            'status' => MentorSession::STATUS_CANCELLED,
        ]);

        return redirect()->route('my-sessions')->with('success', __('app.msg_success_sesi_mentoring_berhasil_dibatalkan'));
    }

    /**
     * Notify mentor about new booking
     */
    private function notifyMentor(MentorSession $session): void
    {
        $mentor = $session->mentor;

        // Create in-app notification
        if ($mentor->user_id) {
            Notification::create([
                'user_id' => $mentor->user_id,
                'type' => 'mentor_booking',
                'title' => 'Booking Baru!',
                'message' => Auth::user()->name.' ingin melakukan sesi mentoring pada '.$session->formatted_date.' pukul '.$session->booked_time,
                'data' => json_encode([
                    'session_id' => $session->id,
                    'student_id' => Auth::user()->id,
                    'booked_date' => $session->booked_date->format('Y-m-d'),
                    'booked_time' => $session->booked_time,
                ]),
                'is_read' => false,
            ]);
        }
    }
}
