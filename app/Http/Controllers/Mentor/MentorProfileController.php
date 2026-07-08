<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller untuk membuat dan mengedit profil mentor
 */
class MentorProfileController extends Controller
{
    /**
     * Tampilkan form edit profil mentor
     * Jika mentor profile belum ada, buat baru dengan defaults
     */
    public function edit(): View
    {
        $user = auth()->user();

        // Cek apakah user sudah punya Mentor profile
        $mentor = $user->mentor;

        if (! $mentor) {
            // Buat Mentor profile baru dengan defaults
            $mentor = Mentor::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => 'Mentor',
                'company' => '',
                'price' => '0',
                'rating' => 0,
                'sessions_count' => 0,
                'initials' => Str::upper(substr($user->name, 0, 2)),
                'color' => '#3b82f6',
                'expertise' => [],
                'bio' => '',
                'linkedin_url' => '',
                'phone' => '',
            ]);
        }

        // Get existing schedules as available_days array
        $schedules = $mentor->schedules()->get();
        $availableDays = $schedules->pluck('day_of_week')->toArray();

        return view('mentor.profile-edit', [
            'mentor' => $mentor,
            'availableDays' => $availableDays,
        ]);
    }

    /**
     * Simpan/update profil mentor
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $mentor = $user->mentor;

        if (! $mentor) {
            return redirect()->route('mentor.profile.edit')
                ->with('error', 'Profil mentor tidak ditemukan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:100',
            'company' => 'nullable|string|max:255',
            'price' => 'required|string|max:50',
            'expertise' => 'nullable|array',
            'expertise.*' => 'string|max:100',
            'bio' => 'nullable|string|max:2000',
            'linkedin_url' => 'nullable|url|max:500',
            'phone' => 'nullable|string|max:20',
            'available_days' => 'nullable|array',
            'available_days.*' => 'integer|min:0|max:6',
        ]);

        // Generate initials from name
        $initials = Str::upper(substr($validated['name'], 0, 2));

        // Update mentor profile
        $mentor->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'company' => $validated['company'] ?? '',
            'price' => $validated['price'],
            'expertise' => $validated['expertise'] ?? [],
            'bio' => $validated['bio'] ?? '',
            'linkedin_url' => $validated['linkedin_url'] ?? '',
            'phone' => $validated['phone'] ?? '',
            'initials' => $initials,
        ]);

        // Update schedules for available_days
        $mentor->schedules()->delete();
        if (! empty($validated['available_days'])) {
            foreach ($validated['available_days'] as $day) {
                $mentor->schedules()->create([
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'is_available' => true,
                ]);
            }
        }

        return redirect()->route('mentor.dashboard')
            ->with('success', 'Profil mentor berhasil disimpan.');
    }
}
