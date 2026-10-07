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
        // Courses moderation fields
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('resources');
            $table->text('admin_notes')->nullable()->after('status');
            $table->foreignId('reviewed_by')->nullable()->after('admin_notes');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });

        // Bootcamps moderation fields
        Schema::table('bootcamps', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('benefits');
            $table->text('admin_notes')->nullable()->after('status');
            $table->foreignId('reviewed_by')->nullable()->after('admin_notes');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });

        // Events - modify existing status and add moderation fields
        Schema::table('events', function (Blueprint $table) {
            // Add moderation status column (keeping original status for event lifecycle)
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('approved')->after('is_mentor_created');
            $table->text('moderation_notes')->nullable()->after('moderation_status');
            $table->foreignId('moderation_reviewed_by')->nullable()->after('moderation_notes');
            $table->timestamp('moderation_reviewed_at')->nullable()->after('moderation_reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumns(['status', 'admin_notes', 'reviewed_by', 'reviewed_at']);
        });

        Schema::table('bootcamps', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumns(['status', 'admin_notes', 'reviewed_by', 'reviewed_at']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['moderation_reviewed_by']);
            $table->dropColumns(['moderation_status', 'moderation_notes', 'moderation_reviewed_by', 'moderation_reviewed_at']);
        });
    }
};
