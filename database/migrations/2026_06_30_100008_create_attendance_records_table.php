<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('attendance_records')) {
            return;
        }

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bootcamp_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->string('qr_code', 64)->unique();
            $table->boolean('verified')->default(false);
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'bootcamp_id', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
