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
        Schema::create('youtube_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->nullable();
            $table->string('title')->nullable();
            $table->string('custom_url')->nullable();
            $table->text('description')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('banner_external_url')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('subscriber_count')->default(0);
            $table->unsignedInteger('video_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_profiles');
    }
};
