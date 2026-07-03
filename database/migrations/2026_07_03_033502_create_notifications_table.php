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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50); // video_completed, course_completed, session_joined, etc.
            $table->string('title', 255);
            $table->text('message');
            $table->string('icon', 50)->default('bell'); // icon name
            $table->string('color', 20)->default('blue'); // red, blue, green, yellow
            $table->string('link', 255)->nullable(); // URL to navigate to
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('data')->nullable(); // extra data
            $table->timestamps();

            $table->index(['user_id', 'is_read']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
