<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bootcamps', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('mentor_name');
            $table->string('type'); // 'online' or 'offline'
            $table->integer('participants')->default(0);
            $table->string('start_date');
            $table->string('price');
            $table->string('color')->nullable();
            // online specific
            $table->string('sessions_info')->nullable(); // e.g. "7 sesi LIVE via Zoom"
            // offline specific
            $table->string('location')->nullable();
            $table->foreignId('mentor_id')->nullable()->constrained('mentors')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bootcamps');
    }
};