<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('title');
            $table->string('short_description')->nullable()->after('description');
        });

        Schema::table('bootcamps', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('title');
            $table->string('short_description')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['description', 'short_description']);
        });

        Schema::table('bootcamps', function (Blueprint $table) {
            $table->dropColumn(['description', 'short_description']);
        });
    }
};