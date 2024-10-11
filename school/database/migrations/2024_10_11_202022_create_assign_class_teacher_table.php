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
            $table->id(); 
            $table->unsignedBigInteger('class_id')->nullable(); 
            $table->unsignedBigInteger('teacher_id')->nullable(); 
            $table->tinyInteger('status')->default(0); 
            $table->unsignedBigInteger('created_by')->nullable(); 
            $table->unsignedBigInteger('updated_by')->nullable(); 
            $table->timestamps(); // Created_at and updated_at timestamps
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted')->nullable(); // is_delete field

           
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
