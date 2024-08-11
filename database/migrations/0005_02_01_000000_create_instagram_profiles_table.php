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
        Schema::create('instagram_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instagram_id')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('website')->nullable();
            $table->text('profile_picture_url')->nullable();
            $table->unsignedInteger('followers_count')->default(0);
            $table->unsignedInteger('follows_count')->default(0);
            $table->unsignedInteger('media_count')->default(0);
            $table->text('biography')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_profiles');
    }
};
