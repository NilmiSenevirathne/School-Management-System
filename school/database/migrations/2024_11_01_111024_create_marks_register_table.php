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
        Schema::create('marks_register', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable(); 
            $table->unsignedBigInteger('exam_id')->nullable(); 
            $table->unsignedBigInteger('class_id')->nullable(); 
            $table->unsignedBigInteger('subject_id')->nullable(); 
            $table->tinyInteger('home_work')->default(0);    
            $table->tinyInteger('test_work')->default(0);
            $table->tinyInteger('exam')->default(0);   
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marks_register');
    }
};
