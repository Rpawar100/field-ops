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
        Schema::create('demo_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_id');
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
            $table->integer('stage_sequence');
            $table->string('stage_name');
            $table->integer('days_after_sowing')->nullable();
            $table->json('attributes_json')->nullable();
            $table->boolean('default_photo_required')->default(false);
            $table->timestamps();
            $table->index('crop_id');
            $table->index('stage_sequence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_stages');
    }
};
