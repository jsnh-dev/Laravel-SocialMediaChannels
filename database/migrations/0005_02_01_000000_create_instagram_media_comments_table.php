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
        Schema::create('instagram_media_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instagram_id')->nullable();
            $table->unsignedBigInteger('instagram_parent_id')->nullable();
            $table->unsignedBigInteger('instagram_media_id')->nullable();
            $table->unsignedBigInteger('instagram_user_id')->nullable();
            $table->text('text')->nullable();
            $table->unsignedInteger('like_count')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_media_comments');
    }
};
