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
        Schema::create('teacher', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_join')->nullable();
            $table->string('contact')->nullable();
            $table->string('qualification')->nullable();
            $table->string('experience')->nullable();
            $table->string('note')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: active, 1: inactive')->nullable();     
            $table->string('profile_picture')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->tinyInteger('user_type')->default(2)->comment('2:teacher')->nullable();
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted')->nullable(); // is_delete field
            $table->timestamps();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher');
    }
};
