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
        Schema::table('chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('chapters', 'video_url')) {
                $table->string('video_url', 500)->nullable()->after('duration');
            }
            if (!Schema::hasColumn('chapters', 'thumbnail_url')) {
                $table->string('thumbnail_url', 500)->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('chapters', 'description')) {
                $table->text('description')->nullable()->after('thumbnail_url');
            }
            if (!Schema::hasColumn('chapters', 'order')) {
                $table->integer('order')->default(0)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            if (Schema::hasColumn('chapters', 'order')) {
                $table->dropColumn(['order']);
            }
            if (Schema::hasColumn('chapters', 'description')) {
                $table->dropColumn(['description']);
            }
            if (Schema::hasColumn('chapters', 'thumbnail_url')) {
                $table->dropColumn(['thumbnail_url']);
            }
            if (Schema::hasColumn('chapters', 'video_url')) {
                $table->dropColumn(['video_url']);
            }
        });
    }
};
