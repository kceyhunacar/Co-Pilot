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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->softDeletes(); 
            $table->integer('charter')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('country_code')->nullable();
            $table->string('user')->nullable();
            $table->string('pax')->nullable();
            $table->string('total_price')->nullable();
            $table->string('price')->nullable();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->json('dates')->nullable();
            $table->json('status')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
