<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('completable'); // Course or Bootcamp
            $table->decimal('final_score', 5, 2)->nullable();
            $table->string('certificate_url')->nullable();
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->unique(['user_id', 'completable_type', 'completable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('completions');
    }
};