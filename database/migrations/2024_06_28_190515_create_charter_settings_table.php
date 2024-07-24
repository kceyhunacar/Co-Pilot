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
        Schema::create('charter_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->softDeletes(); 
            $table->integer('charter')->nullable();
            $table->integer('status')->nullable();
            $table->integer('quick')->nullable();
            $table->integer('contact')->nullable();
            $table->integer('min_day')->nullable();
            $table->integer('notification')->nullable();
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charter_settings');
    }
};
