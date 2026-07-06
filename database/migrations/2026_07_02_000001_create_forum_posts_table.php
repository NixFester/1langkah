<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->json('image_urls')->nullable()->comment('Array of image URLs');
            $table->unsignedInteger('upvotes')->default(0);
            $table->unsignedInteger('downvotes')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index('created_at');
            // Fulltext search - only for MySQL
            if (config('database.default') !== 'sqlite') {
                $table->fullText(['title', 'content']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
