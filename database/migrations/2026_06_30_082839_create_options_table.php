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
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->index(); // e.g., 'user_role', 'course_level', 'event_type', 'event_status', 'bootcamp_type'
            $table->string('key', 50)->index();      // the stored value, e.g., 'admin', 'Beginner', 'online'
            $table->string('label', 100);            // display label, e.g., 'Admin', 'Beginner', 'Online'
            $table->string('color', 20)->nullable(); // optional color hex for badges
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Prevent duplicate keys within a category
            $table->unique(['category', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
