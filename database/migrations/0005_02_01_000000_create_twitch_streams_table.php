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
        Schema::create('twitch_streams', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id')->nullable();
            $table->string('game_id')->nullable();
            $table->string('game_name')->nullable();
            $table->string('title')->nullable();
            $table->string('tags')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->unsignedInteger('viewer_count')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twitch_streams');
    }
};
