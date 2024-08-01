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
        Schema::create('structures', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->string('name');
            $table->string('level');
            $table->string('nip');
            $table->string('image_path');
            $table->uuid('upper_level_uuid')->nullable();
            $table->foreign('upper_level_uuid')->references('uuid')->on('structures');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('structures');
    }
};
