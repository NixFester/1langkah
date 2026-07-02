<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('forum_replies')->onDelete('cascade');
            $table->text('content');
            $table->json('image_urls')->nullable()->comment('Array of image URLs');
            $table->unsignedInteger('upvotes')->default(0);
            $table->unsignedInteger('downvotes')->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['forum_post_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_replies');
    }
};
