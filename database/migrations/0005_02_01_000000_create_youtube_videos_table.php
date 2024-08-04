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
        Schema::create('youtube_videos', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('embed_url')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('favorite_count')->default(0);
            $table->unsignedInteger('comment_count')->default(0);
            $table->string('duration')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_videos');
    }
};
