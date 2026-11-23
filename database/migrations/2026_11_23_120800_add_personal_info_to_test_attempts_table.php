<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_attempts', function (Blueprint $table) {
            $table->foreignId('quiz_id')->nullable()->after('user_id')->constrained('quizzes')->nullOnDelete();
            $table->string('participant_name')->nullable()->after('quiz_id');
            $table->string('birth_place')->nullable()->after('participant_name');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->text('address')->nullable()->after('birth_date');
            $table->string('whatsapp', 30)->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('test_attempts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('quiz_id');
            $table->dropColumn([
                'participant_name',
                'birth_place',
                'birth_date',
                'address',
                'whatsapp',
            ]);
        });
    }
};
