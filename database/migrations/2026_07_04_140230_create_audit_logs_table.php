<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel audit_logs untuk mencatat semua aktivitas user
     * yang dilakukan di sistem (create, update, delete, verify, dll)
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // created, updated, deleted, verified, login, logout
            $table->string('model_type')->nullable(); // App\Models\User, App\Models\Course, dll
            $table->unsignedBigInteger('model_id')->nullable(); // ID dari record yang dimodifikasi
            $table->json('old_values')->nullable(); // Data sebelum diubah
            $table->json('new_values')->nullable(); // Data setelah diubah
            $table->string('description')->nullable(); // Deskripsi singkat aktivitas
            $table->string('ip_address', 45)->nullable(); // Alamat IP user
            $table->string('user_agent')->nullable(); // Browser/device info
            $table->timestamp('created_at')->useCurrent();

            // Index untuk query yang sering digunakan
            $table->index(['user_id', 'action']);
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
