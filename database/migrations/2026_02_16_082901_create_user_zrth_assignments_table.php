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
        Schema::create('user_zrth_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->unsignedBigInteger('territory_id')->nullable();
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->date('assigned_date');
            $table->date('released_date')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('zone_id');
            $table->index('region_id');
            $table->index('territory_id');
            $table->index('assigned_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_zrth_assignments');
    }
};
