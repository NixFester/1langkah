<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add foreign keys for course_id and bootcamp_id to enable auto-enrollment
     */
    public function up(): void
    {
        Schema::table('payment_verifications', function (Blueprint $table) {
            // Add foreign keys for easier enrollment creation
            $table->foreignId('course_id')->nullable()->after('user_id')
                ->constrained('courses')->onDelete('set null');
            $table->foreignId('bootcamp_id')->nullable()->after('course_id')
                ->constrained('bootcamps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_verifications', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['bootcamp_id']);
            $table->dropColumn(['course_id', 'bootcamp_id']);
        });
    }
};
