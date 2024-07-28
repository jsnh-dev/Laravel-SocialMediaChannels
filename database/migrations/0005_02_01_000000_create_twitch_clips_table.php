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
        Schema::create('twitch_clips', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id')->nullable();
            $table->string('title')->nullable();
            $table->string('game_id')->nullable();
            $table->text('url')->nullable();
            $table->text('embed_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->string('duration')->nullable();
            $table->string('twitch_creator_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twitch_clips');
    }
};
