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
        Schema::create('subject', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); 
            $table->string('type')->nullable(); 
            $table->tinyInteger('status')->default(0)->comment('0: active, 1: inactive');   
            $table->integer('created_by');   
            $table->tinyInteger('is_delete')->default(0)->comment('0:not deleted, 1:deleted'); // is_delete field      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject');
    }
};
