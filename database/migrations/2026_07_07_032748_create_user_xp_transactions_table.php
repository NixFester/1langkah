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
        Schema::create('user_xp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('source_type'); // App\Models\Enrollment, VideoProgress, etc.
            $table->unsignedBigInteger('source_id');
            $table->string('action'); // enrolled_course, video_watched, chapter_completed, etc.
            $table->unsignedInteger('xp_amount');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->unique(['source_type', 'source_id']); // Prevent duplicate XP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_xp_transactions');
    }
};
