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
        Schema::create('homework_submit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('homework_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('description')->nullable();
            $table->string('document_file')->nullable();
            $table->timestamps();

            $table->foreign('homework_id')->references('id')->on('homework')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework_submit');
    }
};

