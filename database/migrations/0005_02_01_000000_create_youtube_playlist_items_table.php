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
        Schema::create('youtube_playlist_items', function (Blueprint $table) {
            $table->id();
            $table->string('youtube_id')->nullable();
            $table->string('youtube_playlist_id')->nullable();
            $table->string('youtube_video_id')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_playlist_items');
    }
};
