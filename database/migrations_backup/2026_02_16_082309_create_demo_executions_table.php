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
        Schema::create('demo_executions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demo_lot_id');
            $table->foreign('demo_lot_id')->references('id')->on('demo_lots')->onDelete('cascade');
            $table->unsignedBigInteger('farmer_id');
            $table->foreign('farmer_id')->references('id')->on('farmers')->onDelete('cascade');
            $table->unsignedBigInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('demo_stages')->onDelete('cascade');
            $table->unsignedBigInteger('territory_id');
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');
            $table->date('sowing_date');
            $table->date('planned_visit_date');
            $table->date('actual_visit_date')->nullable();
            $table->date('rescheduled_visit_date')->nullable();
            $table->unsignedBigInteger('assigned_user_id');
            $table->foreign('assigned_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('inspector_user_id')->nullable();
            $table->foreign('inspector_user_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('stage_status', ['pending', 'completed', 'failed', 'rescheduled'])->default('pending');
            $table->text('failure_reason')->nullable();
            $table->text('failure_remarks')->nullable();
            $table->json('attributes_json')->nullable();
            $table->json('photo_paths')->nullable();
            $table->timestamps();
            $table->index('demo_lot_id');
            $table->index('farmer_id');
            $table->index('territory_id');
            $table->index('stage_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_executions');
    }
};
