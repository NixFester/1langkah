<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel untuk menyimpan kode promo yang dibuat oleh Marketing
     */
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode promo (contoh: DISKON20)
            $table->string('name'); // Nama promo
            $table->string('type'); // percentage, fixed_amount
            $table->integer('value'); // Nilai diskon (persen atau jumlah)
            $table->integer('max_uses')->nullable(); // Batas penggunaan (null = unlimited)
            $table->integer('used_count')->default(0); // Jumlah yang sudah digunakan
            $table->decimal('min_purchase', 10, 2)->nullable(); // Minimal pembelian
            $table->decimal('max_discount', 10, 2)->nullable(); // Maximal diskon (untuk percentage)
            $table->date('starts_at')->nullable(); // Tanggal mulai
            $table->date('expires_at')->nullable(); // Tanggal kadaluarsa
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
    }
};
