<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            if (!Schema::hasColumn('enrollments', 'ticket_code')) {
                $table->string('ticket_code', 24)->nullable()->unique()->after('status');
            }
        });

        Schema::table('bootcamp_session', function (Blueprint $table) {
            if (!Schema::hasColumn('bootcamp_session', 'password')) {
                $table->string('password', 20)->nullable()->after('meeting_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bootcamp_session', function (Blueprint $table) {
            if (Schema::hasColumn('bootcamp_session', 'password')) {
                $table->dropColumn('password');
            }
        });

        Schema::table('enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('enrollments', 'ticket_code')) {
                $table->dropColumn('ticket_code');
            }
        });
    }
};
