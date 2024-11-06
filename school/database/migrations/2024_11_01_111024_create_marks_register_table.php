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
            $table->string('total_marks')->default(0); 
            $table->string('grade')->nullable();
            $table->string('full_marks')->default(0); 
            $table->string('passing_marks')->default(0);    
            $table->integer('created_by');
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('class')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subject')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');

            
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
