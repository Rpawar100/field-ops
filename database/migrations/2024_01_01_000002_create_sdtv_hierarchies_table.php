<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SDTV Hierarchy: State > District > Taluka > Village
     * Geographic hierarchy for location tagging
     */
    public function up(): void
    {
        // States table
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('State code (e.g., MH, GJ)');
            $table->string('name', 255);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });

        // Districts table
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('state_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onDelete('restrict');

            $table->index(['state_id', 'status']);
        });

        // Talukas table
        Schema::create('talukas', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('district_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onDelete('restrict');

            $table->index(['district_id', 'status']);
        });

        // Villages table
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('taluka_id');
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('taluka_id')
                ->references('id')
                ->on('talukas')
                ->onDelete('restrict');

            $table->index(['taluka_id', 'status']);
            $table->index('pincode');
        });

        // SDTV to ZRTH Mapping table
        Schema::create('sdtv_zrth_mappings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('village_id');
            $table->unsignedBigInteger('zrth_hierarchy_id')->comment('Maps to Territory or HQ level');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onDelete('cascade');

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('cascade');

            $table->unique(['village_id', 'zrth_hierarchy_id']);
            $table->index('zrth_hierarchy_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdtv_zrth_mappings');
        Schema::dropIfExists('villages');
        Schema::dropIfExists('talukas');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('states');
    }
};
