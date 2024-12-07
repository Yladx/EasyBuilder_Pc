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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();  // Primary key
            // Reference the correct table name
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');  // Ensure it references the correct table
            $table->timestamp('activity_timestamp');  // Activity timestamp
            $table->string('activity');  // Description of the activity
            $table->timestamps();  // Automatically created 'created_at' and 'updated_at' fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('admin_sessions');
        Schema::dropIfExists('admin_password_reset_tokens');
        Schema::dropIfExists('admins');  // Make sure to drop the correct table name
    }
};
