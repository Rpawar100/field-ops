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
        Schema::create('atps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->date('plan_date');
            $table->string('month', 20)->nullable();
            $table->string('status', 50)->default('draft');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('atp_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atp_id')->constrained('atps')->onDelete('cascade');
            $table->foreignId('beat_id')->constrained('beats');
            $table->datetime('planned_time')->nullable();
            $table->string('description', 500)->nullable();
            $table->string('status', 50)->default('planned');
            $table->timestamps();
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
        Schema::dropIfExists('atps');
    }
};
