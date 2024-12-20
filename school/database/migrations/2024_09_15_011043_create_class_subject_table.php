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
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable(); 
            $table->unsignedBigInteger('subject_id')->nullable(); 
            $table->integer('created_by');
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted'); // is_delete field      
            $table->tinyInteger('status')->default(0)->comment('0: active, 1: inactive');   
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('class')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subject')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject');
    }
};
