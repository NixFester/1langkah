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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('certificate_number')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('certifiable_type'); // Course, Bootcamp
            $table->unsignedBigInteger('certifiable_id');
            $table->date('issued_date');
            $table->date('valid_until')->nullable();
            $table->string('verification_code')->unique();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();

            $table->index(['certifiable_type', 'certifiable_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
