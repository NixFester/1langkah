<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bootcamp_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bootcamp_id')->constrained()->cascadeOnDelete();
            $table->string('date');
            $table->string('topic');
            $table->string('time');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bootcamp_session');
    }
};