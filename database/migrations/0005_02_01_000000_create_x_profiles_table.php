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
        Schema::create('x_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('x_id')->nullable();
            $table->string('name')->nullable();
            $table->string('screen_name')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->text('expanded_url')->nullable();
            $table->text('display_url')->nullable();
            $table->text('profile_image_url_https')->nullable();
            $table->text('profile_banner_url')->nullable();
            $table->unsignedInteger('followers_count')->default(0);
            $table->unsignedInteger('friends_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('x_profiles');
    }
};
