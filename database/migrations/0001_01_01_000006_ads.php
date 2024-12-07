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
        // Create LearningModules table
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();                   // Auto-incrementing ID
            $table->string('label', 255);   // Title of the tutorial
            $table->text('caption')->nullable(); // Detailed description, can be nullable
            $table->text('access_link')->nullable();
            $table->string('brand', 255)->nullable();
            $table->enum('type', ['video', 'image'])->default('image');
            $table->boolean('advertise')->default(0);
            $table->string('src', 255)->nullable(); // Path or URL to the video source, can be nullable
            $table->timestamps();           // Created and updated timestamps
            $table->integer('sort')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
