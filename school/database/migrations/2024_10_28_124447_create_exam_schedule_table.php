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
        Schema::create('exam_schedule', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id')->nullable(); 
            $table->integer('class_id')->nullable(); 
            $table->integer('subject_id')->nullable(); 
            $table->date('exam_date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('full_marks')->nullable(); 
            $table->string('passing_marks')->nullable(); 
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_schedule');
    }
};
