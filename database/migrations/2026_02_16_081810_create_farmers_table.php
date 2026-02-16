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
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('aadhar')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->unique();
            $table->string('whatsapp')->nullable();
            $table->unsignedBigInteger('village_id');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
            $table->unsignedBigInteger('taluka_id');
            $table->foreign('taluka_id')->references('id')->on('talukas')->onDelete('cascade');
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
            $table->decimal('landholding_size', 8, 2)->nullable();
            $table->decimal('cultivation_area', 8, 2)->nullable();
            $table->string('irrigation_type')->nullable();
            $table->json('farming_practices')->nullable();
            $table->json('crop_area_json')->nullable();
            $table->enum('farmer_type', ['PDA', 'Demo', 'User', 'NonUser'])->nullable();
            $table->enum('farmer_category', ['small', 'influencer', 'non-user'])->nullable();
            $table->unsignedBigInteger('reference_farmer_id')->nullable();
            $table->foreign('reference_farmer_id')->references('id')->on('farmers')->onDelete('set null');
            $table->boolean('using_kaveri_seeds')->default(false);
            $table->unsignedBigInteger('beat_id')->nullable();
            $table->foreign('beat_id')->references('id')->on('beat')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('mobile');
            $table->index('village_id');
            $table->index('taluka_id');
            $table->index('district_id');
            $table->index('state_id');
            $table->index('farmer_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmers');
    }
};
