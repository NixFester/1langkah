<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('mentor_name');   // we'll link to mentors later
            $table->string('mentor_company');
            $table->string('category');
            $table->string('level');
            $table->string('badge')->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->integer('students_count')->default(0);
            $table->string('price');
            $table->integer('progress')->default(0); // for demo
            $table->string('color')->nullable();
            $table->foreignId('mentor_id')->nullable()->constrained('mentors')->nullOnDelete(); // when we create mentors
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};