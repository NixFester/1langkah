<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('external_id')->unique();
            $table->string('payment_id')->nullable();
            $table->string('driver')->default('mock'); // mock, xendit
            $table->string('item_type'); // course, bootcamp, event, mentor_session
            $table->unsignedBigInteger('item_id');
            $table->bigInteger('amount');
            $table->string('status')->default('PENDING'); // PENDING, PAID, EXPIRED, FAILED, CANCELLED
            $table->string('item_name');
            $table->json('metadata')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'item_type', 'item_id']);
            $table->index(['external_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
