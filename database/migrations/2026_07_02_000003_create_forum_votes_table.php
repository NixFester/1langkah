<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('votable_id');
            $table->string('votable_type'); // 'forum_post' or 'forum_reply'
            $table->boolean('is_upvote');
            $table->timestamps();

            // Unique constraint: one vote per user per item
            $table->unique(['user_id', 'votable_id', 'votable_type']);

            // Indexes
            $table->index(['votable_id', 'votable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_votes');
    }
};
