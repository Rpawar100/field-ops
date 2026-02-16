<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beat_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beat_id');
            $table->foreign('beat_id')->references('id')->on('beat')->onDelete('cascade');
            $table->enum('farmerretailer_type', ['farmer', 'retailer', 'distributor']);
            $table->unsignedBigInteger('farmerretailer_id');
            $table->timestamps();
            $table->index('beat_id');
            $table->index(['farmerretailer_type', 'farmerretailer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beat_items');
    }
};
