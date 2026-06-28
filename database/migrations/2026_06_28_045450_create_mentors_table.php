<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->string('company');
            $table->string('price');
            $table->decimal('rating', 2, 1);
            $table->integer('sessions_count')->default(0);
            $table->string('initials');
            $table->string('color')->nullable();
            $table->json('expertise')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mentors');
    }
};