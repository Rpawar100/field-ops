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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('designation'); // FA, TSL, RM, ZM, Admin
            $table->unsignedBigInteger('reporting_manager_id')->nullable();
            $table->foreign('reporting_manager_id')->references('id')->on('users')->onDelete('set null');
            $table->boolean('is_permanent')->default(true); // For FA: true=Permanent, false=Temporary/Seasonal
            $table->date('date_of_joining');
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->boolean('app_access_enabled')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('aadhar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('driving_license')->nullable();
            $table->date('driving_license_valid_till')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
