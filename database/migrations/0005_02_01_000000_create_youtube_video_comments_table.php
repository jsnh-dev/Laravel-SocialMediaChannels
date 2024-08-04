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
        Schema::create('youtube_video_comments', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->nullable();
            $table->string('youtube_parent_id')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->text('text_display')->nullable();
            $table->text('text_original')->nullable();
            $table->string('author_display_name')->nullable();
            $table->text('author_profile_image_url')->nullable();
            $table->string('author_channel_url')->nullable();
            $table->string('author_channel_id')->nullable();
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('reply_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_video_comments');
    }
};
