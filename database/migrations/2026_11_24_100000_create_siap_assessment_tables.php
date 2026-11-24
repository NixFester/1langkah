<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siap_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('badge')->default('NURTURE | FENOTIPE');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('scoring_type', 32)->default('forced_choice'); // forced_choice | likert
            $table->unsignedSmallInteger('question_count')->default(25);
            $table->unsignedSmallInteger('time_limit_minutes')->default(5);
            $table->unsignedInteger('normal_price')->default(125000);
            $table->unsignedInteger('unlock_price')->default(35000);
            $table->text('disclaimer')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();
        });

        Schema::create('siap_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siap_assessment_id')->constrained('siap_assessments')->cascadeOnDelete();
            $table->string('slug');
            $table->string('code', 10)->nullable(); // A..E for Psycho Geometric
            $table->string('name');
            $table->string('label')->nullable();
            $table->string('symbol', 16)->nullable();
            $table->string('color', 32)->default('#4783C8');
            $table->string('icon', 32)->default('spark');
            $table->text('description')->nullable();
            $table->json('details')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->timestamps();

            $table->unique(['siap_assessment_id', 'slug']);
        });

        Schema::create('siap_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siap_assessment_id')->constrained('siap_assessments')->cascadeOnDelete();
            $table->foreignId('siap_category_id')->nullable()->constrained('siap_categories')->nullOnDelete();
            $table->text('question_text');
            $table->json('options')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('siap_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('siap_assessment_id')->constrained('siap_assessments')->cascadeOnDelete();
            $table->string('status', 32)->default('in_progress'); // in_progress | completed
            $table->string('participant_name')->nullable();
            $table->string('google_email')->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('whatsapp', 32)->nullable();
            $table->json('answers')->nullable();
            $table->json('scores')->nullable();
            $table->json('dominant_profile')->nullable();
            $table->boolean('is_full_unlocked')->default(false);
            $table->timestamp('unlocked_at')->nullable();
            $table->unsignedInteger('payment_amount')->default(0);
            $table->unsignedInteger('time_spent_seconds')->default(0);
            $table->boolean('auto_submitted')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'siap_assessment_id', 'status']);
        });

        Schema::create('siap_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('siap_assessment_id')->nullable()->constrained('siap_assessments')->nullOnDelete();
            $table->foreignId('siap_attempt_id')->nullable()->constrained('siap_attempts')->nullOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->constrained('payment_transactions')->nullOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->string('product_type', 32); // assessment_full | super_finger | bundle_siap
            $table->string('product_name');
            $table->string('participant_name');
            $table->string('email');
            $table->string('parent_whatsapp', 32);
            $table->boolean('parental_consent')->default(false);
            $table->string('payment_method', 32)->default('xendit');
            $table->unsignedInteger('normal_price')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('final_price')->default(0);
            $table->string('status', 32)->default('pending'); // pending | paid | failed | expired
            $table->string('redeem_code', 32)->nullable()->unique();
            $table->string('redeem_status', 32)->nullable(); // unused | used
            $table->timestamp('redeemed_at')->nullable();
            $table->foreignId('redeemed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('fulfillment_notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'product_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siap_orders');
        Schema::dropIfExists('siap_attempts');
        Schema::dropIfExists('siap_questions');
        Schema::dropIfExists('siap_categories');
        Schema::dropIfExists('siap_assessments');
    }
};
