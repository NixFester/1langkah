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

            // Notification preferences (JSON for flexibility; defaults handled in UserSetting model as MySQL disallows literal defaults on JSON columns)
            $table->json('notification_preferences')->nullable();

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
