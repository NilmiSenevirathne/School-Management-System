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
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable(); 
            $table->unsignedBigInteger('subject_id')->nullable(); 
            $table->date('homework_date')->nullable();
            $table->date('submission_date')->nullable();
            $table->string('document_file')->nullable(); 
            $table->string('description')->nullable(); 
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted'); // is_delete field
            $table->integer('created_by');
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
        Schema::dropIfExists('homework');
    }
};


            


           