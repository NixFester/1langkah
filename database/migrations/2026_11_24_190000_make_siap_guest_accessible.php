<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siap_assessments', function (Blueprint $table) {
            $table->string('badge')->nullable()->change();
        });

        Schema::table('siap_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('guest_token', 64)->nullable()->index()->after('user_id');
        });

        Schema::table('siap_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('guest_token', 64)->nullable()->index()->after('user_id');
        });

        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('siap_attempts', function (Blueprint $table) {
            $table->dropColumn('guest_token');
        });

        Schema::table('siap_orders', function (Blueprint $table) {
            $table->dropColumn('guest_token');
        });
    }
};
