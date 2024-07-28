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
        Schema::create('twitch_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id')->nullable();
            $table->string('login')->nullable();
            $table->string('display_name')->nullable();
            $table->string('type')->nullable();
            $table->string('broadcaster_type')->nullable();
            $table->text('description')->nullable();
            $table->text('profile_image_url')->nullable();
            $table->text('offline_image_url')->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('followers_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twitch_profiles');
    }
};
