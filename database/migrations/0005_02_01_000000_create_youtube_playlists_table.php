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
        Schema::create('youtube_playlists', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('embed_url')->nullable();
            $table->unsignedInteger('item_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_playlists');
    }
};
