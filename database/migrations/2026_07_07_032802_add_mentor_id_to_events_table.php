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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('mentor_id')->nullable()->after('created_by')->constrained('mentors')->nullOnDelete();
            $table->boolean('is_mentor_created')->default(false)->after('mentor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['mentor_id']);
            $table->dropColumn(['mentor_id', 'is_mentor_created']);
        });
    }
};
