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
        Schema::create('atp_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atp_id');
            $table->foreign('atp_id')->references('id')->on('atps')->onDelete('cascade');
            $table->unsignedBigInteger('beat_id');
            $table->foreign('beat_id')->references('id')->on('beat')->onDelete('cascade');
            $table->integer('sequence_number');
            $table->time('planned_time')->nullable();
            $table->time('actual_time')->nullable();
            $table->enum('status', ['pending', 'completed', 'skipped'])->default('pending');
            $table->timestamps();
            $table->index('atp_id');
            $table->index('beat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atp_items');
    }
};
