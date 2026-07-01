<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bootcamps', function (Blueprint $table) {
            if (!Schema::hasColumn('bootcamps', 'jadwal_kelas')) {
                $table->json('jadwal_kelas')->nullable()->after('sessions_info');
            }
            if (!Schema::hasColumn('bootcamps', 'benefits')) {
                $table->json('benefits')->nullable()->after('jadwal_kelas');
            }
            if (!Schema::hasColumn('bootcamps', 'icon')) {
                $table->string('icon', 100)->default('graduation-cap')->after('benefits');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bootcamps', function (Blueprint $table) {
            $columns = ['jadwal_kelas', 'benefits', 'icon'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('bootcamps', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
