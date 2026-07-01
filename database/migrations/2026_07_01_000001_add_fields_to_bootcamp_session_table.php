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
        Schema::table('bootcamp_session', function (Blueprint $table) {
            $table->string('meeting_url', 500)->nullable()->after('time');
            $table->string('description')->nullable()->after('meeting_url');
            $table->integer('order')->default(0)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bootcamp_session', function (Blueprint $table) {
            $table->dropColumn(['meeting_url', 'description', 'order']);
        });
    }
};
