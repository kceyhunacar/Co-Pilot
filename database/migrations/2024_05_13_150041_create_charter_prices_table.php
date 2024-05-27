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
        Schema::create('charter_prices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->softDeletes();

            $table->float('1')->default(0);
            $table->float('2')->default(0);
            $table->float('3')->default(0);
            $table->float('4')->default(0);
            $table->float('5')->default(0);
            $table->float('6')->default(0);
            $table->float('7')->default(0);
            $table->float('8')->default(0);
            $table->float('9')->default(0);
            $table->float('10')->default(0);
            $table->float('11')->default(0);
            $table->float('12')->default(0);
            $table->integer('charter')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charter_prices');
    }
};
