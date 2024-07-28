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
        Schema::create('twitch_users', function (Blueprint $table) {
            $table->id();
            $table->string('twitch_id')->nullable();
            $table->string('display_name')->nullable();
            $table->string('email')->nullable();
            $table->text('profile_image_url')->nullable();
            $table->boolean('logged_in')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twitch_users');
    }
};
