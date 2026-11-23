<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     * - If user exists and has completed personal data -> login & redirect to dashboard
     * - If user exists (student) but has not filled personal data / phone -> login & redirect to complete-profile form
     * - If no user exists -> store Google profile in session and redirect to signup form to fill mandatory personal data & phone number
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                if (! $existingUser->profile_photo && $googleUser->avatar) {
                    $existingUser->update(['profile_photo' => $googleUser->avatar]);
                }

                $this->loginUser($existingUser, $request);

                if ($existingUser->isStudent() && ! $existingUser->hasCompletedPersonalData()) {
                    return redirect()->route('complete-profile')
                        ->with('google_notice', 'Silakan lengkapi formulir data diri dan nomor HP Anda terlebih dahulu sebelum melanjutkan.');
                }

                return $this->redirectAfterAuth($existingUser);
            }

            // New user registering with Google -> must fill personal data & phone number on signup form
            $fullName = trim($googleUser->name ?? $googleUser->nickname ?? 'User');
            $nameParts = preg_split('/\s+/', $fullName, 2);
            $firstName = $nameParts[0] ?? $fullName;
            $lastName = $nameParts[1] ?? '';

            $request->session()->put('google_signup_data', [
                'name' => $fullName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
                'google_id' => $googleUser->id,
            ]);

            return redirect()->route('signup')
                ->with('google_notice', 'Akun Google ('.$googleUser->email.') berhasil terhubung! Silakan lengkapi formulir data diri dan nomor HP Anda untuk menyelesaikan pendaftaran.');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: '.$e->getMessage());

            return redirect()->route('login')
                ->withErrors(['email' => 'Terjadi kesalahan saat autentikasi dengan Google. Silakan coba lagi.']);
        }
    }

    /**
     * Cancel Google signup session and return to normal signup form
     */
    public function cancelGoogleSignup(Request $request): RedirectResponse
    {
        $request->session()->forget('google_signup_data');

        return redirect()->route('signup');
    }

    /**
     * Login the user and regenerate session
     */
    private function loginUser(User $user, Request $request): void
    {
        Auth::login($user);
        $request->session()->regenerate();
    }

    /**
     * Redirect user to their appropriate dashboard after auth
     */
    private function redirectAfterAuth(User $user): RedirectResponse
    {
        return redirect()->intended($user->getDashboardRoute());
    }
}
