<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no');
            $table->string('plate_number');
            $table->string('driver_name')->nullable();
            $table->string('representative_name')->nullable();
            $table->string('material')->nullable();
            $table->string('product')->nullable();
            $table->string('company')->nullable();
            $table->float('moisture_content')->nullable();
            $table->float('net_weight')->nullable();
            $table->float('tare_weight')->nullable();
            $table->float('gross_weight')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
