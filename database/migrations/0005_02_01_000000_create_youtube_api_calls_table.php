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
        Schema::create('youtube_api_calls', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable();
            $table->text('params')->nullable();
            $table->string('status')->nullable();
            $table->integer('quota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youtube_api_calls');
    }
};
