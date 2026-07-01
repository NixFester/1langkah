<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('session_progress')) {
            return; // Skip if table already exists
        }

        Schema::create('session_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('bootcamp_session_id');
            $table->timestamp('clicked_at')->nullable();
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->unique(['user_id', 'bootcamp_session_id']);
            $table->foreign('bootcamp_session_id', 'session_progress_bootcamp_session_id_foreign')
                ->references('id')
                ->on('bootcamp_session')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_progress');
    }
};
