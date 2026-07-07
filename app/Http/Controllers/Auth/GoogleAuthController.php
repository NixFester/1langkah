<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\StringHelper;
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
     * If user exists with this email -> login
     * If no user -> register with Google data
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            // Get Google user data
            $googleUser = Socialite::driver('google')->user();

            // Check if user exists with this email
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // User exists -> login
                $this->loginUser($existingUser, $request);

                return $this->redirectAfterAuth($existingUser);
            }

            // No user exists -> register new user
            $newUser = $this->registerGoogleUser($googleUser);
            $this->loginUser($newUser, $request);

            return $this->redirectAfterAuth($newUser);

        } catch (\Exception $e) {
            // Log the error and redirect back with error message
            \Log::error('Google OAuth Error: '.$e->getMessage());

            return redirect()->route('login')
                ->withErrors(['email' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.']);
        }
    }

    /**
     * Register a new user from Google data
     */
    private function registerGoogleUser($googleUser): User
    {
        // Get full name from Google
        $fullName = $googleUser->name ?? $googleUser->nickname ?? 'User';

        // Generate romanized username (max 12 characters)
        $baseUsername = StringHelper::generateUsername($fullName);

        // Make username unique if it already exists
        $username = StringHelper::makeUsernameUnique(
            StringHelper::sanitizeUsername($baseUsername),
            fn ($u) => User::where('username', $u)->exists()
        );

        // Generate password from username
        $password = StringHelper::generatePassword($username);

        // Create the user
        $user = User::create([
            'name' => $fullName,
            'username' => $username,
            'email' => $googleUser->email,
            'password' => $password,
            'profile_photo' => $googleUser->avatar,
            'role' => 'student',
        ]);

        return $user;
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
