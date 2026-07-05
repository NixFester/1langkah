<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel untuk menyimpan data verifikasi pembayaran oleh Keuangan
     * Student upload bukti bayar -> Keuangan verifikasi -> Approved/Rejected
     */
    public function up(): void
    {
        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('course_title'); // Judul kursus/bootcamp
            $table->string('course_type')->default('course'); // course atau bootcamp
            $table->decimal('amount', 10, 2); // Jumlah yang dibayar
            $table->decimal('original_price', 10, 2)->nullable(); // Harga asli (jika pakai promo)
            $table->decimal('discount_amount', 10, 2)->nullable(); // Jumlah diskon
            $table->string('promo_code')->nullable(); // Kode promo yang digunakan
            $table->string('proof_image'); // Path ke gambar bukti bayar
            $table->string('payment_method')->nullable(); // Metode pembayaran (bank transfer, dll)
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('verification_notes')->nullable(); // Catatan dari Keuangan
            $table->text('rejection_reason')->nullable(); // Alasan penolakan
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('user_id');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_verifications');
    }
};
