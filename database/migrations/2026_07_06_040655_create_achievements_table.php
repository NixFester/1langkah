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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description');
            $table->string('icon')->default('🏆');
            $table->string('category'); // learning, social, consistency, milestone
            $table->integer('xp_reward')->default(10);
            $table->string('trigger_type'); // course_completed, quiz_passed, streak_days, etc.
            $table->json('trigger_conditions')->nullable(); // {course_count: 5, streak_days: 7}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
