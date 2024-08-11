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
        Schema::create('instagram_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instagram_id')->nullable();
            $table->unsignedBigInteger('instagram_parent_id')->nullable();
            $table->string('media_product_type')->nullable();
            $table->string('media_type')->nullable();
            $table->text('media_url')->nullable();
            $table->text('permalink')->nullable();
            $table->string('shortcode')->nullable();
            $table->string('is_shared_to_feed')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('caption')->nullable();
            $table->unsignedInteger('comments_count')->nullable();
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
        Schema::dropIfExists('instagram_media');
    }
};
