<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('testable'); // Course or Bootcamp
            $table->enum('test_type', ['pre_test', 'post_test']);
            $table->decimal('score', 5, 2)->nullable();          // e.g. 87.50
            $table->unsignedSmallInteger('total_questions')->nullable();
            $table->unsignedSmallInteger('correct_answers')->nullable();
            $table->boolean('passed')->default(false);
            $table->json('answers')->nullable();                 // user answers snapshot
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'test_type']);
            $table->index(['testable_type', 'testable_id', 'test_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_attempts');
    }
};