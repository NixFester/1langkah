<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_skills')) {
            return;
        }

        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('skill_name');
            $table->string('source_type');
            $table->unsignedBigInteger('source_id');
            $table->decimal('rating', 3, 1)->default(0);
            $table->timestamps();
            $table->index(['user_id', 'source_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
