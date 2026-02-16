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
        Schema::create('onboarding_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('mobile')->unique();
            $table->string('email')->unique();
            $table->string('designation')->default('FA');
            $table->string('driving_license_number')->nullable();
            $table->date('driving_license_valid_till')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('aadhar_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('qualification')->nullable();
            $table->string('previous_company')->nullable();
            $table->decimal('total_experience', 5, 2)->nullable();
            $table->decimal('proposed_salary', 12, 2)->nullable();
            $table->text('complete_address')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
            $table->unsignedBigInteger('territory_id')->nullable();
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('set null');
            $table->json('multiple_territories_json')->nullable();
            $table->unsignedBigInteger('initiated_by_user_id');
            $table->foreign('initiated_by_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('approved_by_user_id')->nullable();
            $table->foreign('approved_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('requested_status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->text('request_remarks')->nullable();
            $table->unsignedBigInteger('generated_user_id')->nullable();
            $table->foreign('generated_user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->index('mobile');
            $table->index('email');
            $table->index('zone_id');
            $table->index('region_id');
            $table->index('territory_id');
            $table->index('requested_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('onboarding_requests');
    }
};
