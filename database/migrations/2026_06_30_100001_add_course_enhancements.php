<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'benefits')) {
                $table->json('benefits')->nullable()->after('description');
            }
            if (!Schema::hasColumn('courses', 'curriculum')) {
                $table->json('curriculum')->nullable()->after('benefits');
            }
            if (!Schema::hasColumn('courses', 'resources')) {
                $table->json('resources')->nullable()->after('curriculum');
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $columns = ['benefits', 'curriculum', 'resources'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('courses', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
