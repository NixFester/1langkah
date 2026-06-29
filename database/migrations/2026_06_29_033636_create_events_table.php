<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('short_description')->nullable();

            // Scheduling
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');

            // Type & location
            $table->enum('type', ['online', 'offline', 'hybrid'])->default('online');
            $table->string('location')->nullable();          // offline venue or meeting link
            $table->string('meeting_url')->nullable();       // zoom/google meet

            // Status & limits
            $table->enum('status', ['draft', 'upcoming', 'ongoing', 'completed', 'cancelled'])->default('draft');
            $table->unsignedInteger('max_participants')->nullable();
            $table->unsignedInteger('registered_count')->default(0);

            // Misc
            $table->string('color')->nullable();
            $table->string('banner_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};