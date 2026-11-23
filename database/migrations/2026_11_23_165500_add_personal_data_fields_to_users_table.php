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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('birth_place');
            }
            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('birth_date');
            }
            if (! Schema::hasColumn('users', 'purpose')) {
                $table->string('purpose')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = array_filter(
                ['phone', 'birth_place', 'birth_date', 'address', 'purpose'],
                fn ($col) => Schema::hasColumn('users', $col)
            );

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
