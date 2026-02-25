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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('attendance_date');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->json('check_in_location')->nullable();
            $table->string('check_in_selfie_path')->nullable();
            $table->enum('attendance_type', ['present', 'joint_work', 'individual_work', 'meeting', 'leave', 'week_off', 'holiday']);
            $table->text('leave_reason')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_regularized')->default(false);
            $table->timestamps();
            $table->index('user_id');
            $table->index('attendance_date');
            $table->index('attendance_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance');
    }
};
