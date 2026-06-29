<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // For bootcamp: marks if user actually follows the bootcamp (logic TBD later)
            $table->boolean('is_following')->default(false)->after('status');
            $table->timestamp('followed_at')->nullable()->after('is_following');
            $table->timestamp('completed_at')->nullable()->after('followed_at');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['is_following', 'followed_at', 'completed_at']);
        });
    }
};