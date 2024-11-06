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
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('admission_number')->nullable();
            $table->string('roll_number')->nullable();
            $table->string('class_id')->nullable();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact')->nullable();
            $table->date('admission_date')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: active, 1: inactive')->nullable();     
            $table->string('profile_picture')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->tinyInteger('user_type')->default(3)->comment('3:student')->nullable();
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted')->nullable(); // is_delete field
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('parent')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
