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
        // Create a new table for video progress (separate from chapter progress)
        Schema::create('video_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained('chapter_videos')->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->unsignedInteger('watch_duration')->default(0); // seconds watched total
            $table->unsignedInteger('last_position')->default(0); // resume point (seconds)
            $table->timestamp('watched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'video_id']);
            $table->index(['video_id', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_progress');
    }
};
