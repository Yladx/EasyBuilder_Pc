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
        Schema::create('learning_modules', function (Blueprint $table) {
            $table->id();                   // Auto-incrementing ID
            $table->string('tag', 50);      // Tag for categorizing (e.g., "math", "science")
            $table->string('title', 255);   // Title of the tutorial
            $table->text('description')->nullable(); // Detailed description, can be nullable
            $table->text('Information')->nullable(); // Detailed description, can be nullable
            $table->string('video_src', 255)->nullable(); // Path or URL to the video source, can be nullable
            $table->timestamps();           // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_modules');
    }
};
