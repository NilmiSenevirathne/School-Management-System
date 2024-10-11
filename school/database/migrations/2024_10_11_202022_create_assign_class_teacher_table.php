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
        Schema::create('assign_class_teacher', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('class_id')->nullable(); // Class ID, nullable
            $table->unsignedBigInteger('teacher_id')->nullable(); // Teacher ID, nullable
            $table->tinyInteger('status')->default(0); // Status field with default value of 0
            $table->unsignedBigInteger('created_by')->nullable(); // User ID who created the record, nullable
            $table->unsignedBigInteger('updated_by')->nullable(); 
            $table->timestamps(); // Created_at and updated_at timestamps
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted')->nullable(); // is_delete field

            // Foreign key constraints (optional, uncomment if you have related tables)
            // $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            // $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade'); // Assuming you have a users table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_class_teacher');
    }
};
