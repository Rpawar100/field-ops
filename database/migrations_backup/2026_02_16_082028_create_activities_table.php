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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['PSA', 'PDA', 'Demo', 'Other']);
            $table->unsignedBigInteger('activity_type_id')->nullable();
            $table->unsignedBigInteger('location_village_id')->nullable();
            $table->foreign('location_village_id')->references('id')->on('villages')->onDelete('set null');
            $table->unsignedBigInteger('location_taluka_id')->nullable();
            $table->foreign('location_taluka_id')->references('id')->on('talukas')->onDelete('set null');
            $table->unsignedBigInteger('location_district_id')->nullable();
            $table->foreign('location_district_id')->references('id')->on('districts')->onDelete('set null');
            $table->unsignedBigInteger('location_state_id')->nullable();
            $table->foreign('location_state_id')->references('id')->on('states')->onDelete('set null');
            $table->date('planned_date');
            $table->date('actual_date')->nullable();
            $table->unsignedBigInteger('executed_by_user_id')->nullable();
            $table->foreign('executed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->json('attributes_json')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->timestamps();
            $table->index('code');
            $table->index('type');
            $table->index('planned_date');
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
        Schema::dropIfExists('activities');
    }
};
