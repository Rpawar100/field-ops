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
        Schema::create('retailers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('shop_name')->nullable();
            $table->enum('type', ['Proprietary', 'Firm', 'LLP', 'Company'])->nullable();
            $table->string('contact_person')->nullable();
            $table->string('mobile')->unique();
            $table->string('whatsapp')->nullable();
            $table->string('shop_photo')->nullable();
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
            $table->enum('kyc_status', ['pending', 'completed'])->default('pending');
            $table->timestamp('kyc_updated_at')->nullable();
            $table->decimal('annual_business_value', 12, 2)->nullable();
            $table->decimal('kaveri_business_percentage', 5, 2)->nullable();
            $table->json('major_crops')->nullable();
            $table->json('major_varieties')->nullable();
            $table->decimal('returns_percentage', 5, 2)->nullable();
            $table->string('outlet_type')->nullable();
            $table->unsignedBigInteger('beat_id')->nullable();
            $table->foreign('beat_id')->references('id')->on('beat')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('mobile');
            $table->index('village_id');
            $table->index('taluka_id');
            $table->index('district_id');
            $table->index('state_id');
            $table->index('kyc_status');
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
        Schema::dropIfExists('retailers');
    }
};
