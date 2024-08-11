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
        Schema::create('instagram_api_calls', function (Blueprint $table) {
            $table->id();
            $table->text('url')->nullable();
            $table->string('entity')->nullable();
            $table->text('params')->nullable();
            $table->string('object_id')->nullable();
            $table->string('status')->nullable();
            $table->string('error_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instagram_api_calls');
    }
};
