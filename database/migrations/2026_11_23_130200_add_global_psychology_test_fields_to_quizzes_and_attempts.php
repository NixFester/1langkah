<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->boolean('is_global')->default(false)->after('course_id');
            $table->unsignedSmallInteger('free_question_limit')->default(10)->after('time_limit_minutes');
            $table->unsignedInteger('unlock_price')->default(30000)->after('free_question_limit');
        });

        Schema::table('test_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('testable_id')->nullable()->change();
            $table->string('testable_type')->nullable()->change();
            $table->string('google_email')->nullable()->after('participant_name');
            $table->boolean('is_paid')->default(false)->after('passed');
            $table->timestamp('paid_at')->nullable()->after('is_paid');
            $table->unsignedInteger('payment_amount')->default(30000)->after('paid_at');
            $table->text('personality_summary')->nullable()->after('answers');
            $table->timestamp('email_sent_at')->nullable()->after('personality_summary');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['is_global', 'free_question_limit', 'unlock_price']);
        });

        Schema::table('test_attempts', function (Blueprint $table) {
            $table->dropColumn(['google_email', 'is_paid', 'paid_at', 'payment_amount', 'personality_summary', 'email_sent_at']);
        });
    }
};
