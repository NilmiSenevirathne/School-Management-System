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
        Schema::create('class_subject_timetable', function (Blueprint $table) {
            $table->id(); // This is the auto-increment primary key

            // Foreign keys without auto-increment
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('week_id');

            // Additional fields
            $table->string('start_time');
            $table->string('end_time');
            $table->string('room_number');
            $table->timestamps();

            // Define foreign keys
            $table->foreign('class_id')->references('id')->on('class')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subject')->onDelete('cascade');
            $table->foreign('week_id')->references('id')->on('week')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject_timetable');
    }
};
