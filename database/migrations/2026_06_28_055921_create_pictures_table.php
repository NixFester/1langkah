<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            // polymorphic: works for Course, Bootcamp, or anything else later
            $table->morphs('pictureable');          // pictureable_id + pictureable_type
            $table->enum('type', ['thumbnail', 'gallery']);
            $table->string('url');                  // public URL or storage path
            $table->string('alt')->nullable();      // accessibility / SEO text
            $table->string('description')->nullable(); // human-readable caption
            $table->unsignedSmallInteger('order')->default(0); // sort order for gallery type
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pictures');
    }
};