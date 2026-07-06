<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('avatar')->nullable();

            // Notification preferences (JSON for flexibility)
            $table->json('notification_preferences')->default(json_encode([
                'email_course_updates' => true,
                'email_bootcamp_reminders' => true,
                'email_event_announcements' => true,
                'email_forum_replies' => true,
                'email_achievements' => true,
                'email_weekly_progress' => false,
                'push_course_updates' => true,
                'push_bootcamp_reminders' => true,
                'push_forum_replies' => true,
            ]));

            // Privacy settings
            $table->boolean('show_profile_publicly')->default(true);
            $table->boolean('show_progress_publicly')->default(true);
            $table->boolean('allow_mentor_contact')->default(true);

            // Learning preferences
            $table->string('preferred_language')->default('id');
            $table->string('timezone')->default('Asia/Jakarta');

            $table->timestamps();

            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
