<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserSettingController extends Controller
{
    /**
     * Get user settings (for API)
     */
    public function show(): JsonResponse
    {
        $settings = UserSetting::findOrCreateForUser(auth()->id());

        return response()->json([
            'success' => true,
            'data' => [
                'avatar' => $settings->avatar_url,
                'notification_preferences' => $settings->notification_preferences,
                'privacy' => [
                    'show_profile_publicly' => $settings->show_profile_publicly,
                    'show_progress_publicly' => $settings->show_progress_publicly,
                    'allow_mentor_contact' => $settings->allow_mentor_contact,
                ],
                'preferences' => [
                    'preferred_language' => $settings->preferred_language,
                    'timezone' => $settings->timezone,
                ],
            ],
        ]);
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request): JsonResponse
    {
        $request->validate([
            'email_course_updates' => 'boolean',
            'email_bootcamp_reminders' => 'boolean',
            'email_event_announcements' => 'boolean',
            'email_forum_replies' => 'boolean',
            'email_achievements' => 'boolean',
            'email_weekly_progress' => 'boolean',
            'push_course_updates' => 'boolean',
            'push_bootcamp_reminders' => 'boolean',
            'push_forum_replies' => 'boolean',
        ]);

        $settings = UserSetting::findOrCreateForUser(auth()->id());
        $prefs = $settings->notification_preferences ?? [];

        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'email_') || str_starts_with($key, 'push_')) {
                $prefs[$key] = (bool) $value;
            }
        }

        $settings->notification_preferences = $prefs;
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated',
            'data' => $settings->notification_preferences,
        ]);
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request): JsonResponse
    {
        $request->validate([
            'show_profile_publicly' => 'boolean',
            'show_progress_publicly' => 'boolean',
            'allow_mentor_contact' => 'boolean',
        ]);

        $settings = UserSetting::findOrCreateForUser(auth()->id());

        $settings->show_profile_publicly = $request->boolean('show_profile_publicly');
        $settings->show_progress_publicly = $request->boolean('show_progress_publicly');
        $settings->allow_mentor_contact = $request->boolean('allow_mentor_contact');
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Privacy settings updated',
        ]);
    }

    /**
     * Upload avatar
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|max:20480',
        ]);

        $settings = UserSetting::findOrCreateForUser(auth()->id());

        // Delete old avatar if exists
        if ($settings->avatar) {
            Storage::disk('public')->delete($settings->avatar);
        }

        // Store new avatar
        $url = \App\Services\ImageService::uploadAndCompress($request->file('avatar'), 'avatars', 800, 80);
        $settings->avatar = str_replace('/storage/', '', $url);
        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Avatar uploaded successfully',
            'data' => [
                'avatar_url' => $settings->avatar_url,
            ],
        ]);
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar(): JsonResponse
    {
        $settings = UserSetting::findOrCreateForUser(auth()->id());

        if ($settings->avatar) {
            Storage::disk('public')->delete($settings->avatar);
            $settings->avatar = null;
            $settings->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Avatar deleted',
        ]);
    }

    /**
     * Update learning preferences
     */
    public function updatePreferences(Request $request): JsonResponse
    {
        $request->validate([
            'preferred_language' => 'string|in:id,en',
            'timezone' => 'string',
        ]);

        $settings = UserSetting::findOrCreateForUser(auth()->id());

        if ($request->has('preferred_language')) {
            $settings->preferred_language = $request->preferred_language;
        }

        if ($request->has('timezone')) {
            $settings->timezone = $request->timezone;
        }

        $settings->save();

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated',
        ]);
    }
}
