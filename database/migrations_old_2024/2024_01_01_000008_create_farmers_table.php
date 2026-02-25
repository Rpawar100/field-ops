<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Farmer Registration with crop details and preferences
     */
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync');
            $table->string('farmer_code', 50)->unique();

            // Personal Details
            $table->string('name', 255);
            $table->string('father_name', 255)->nullable();
            $table->string('mobile', 15);
            $table->string('alternate_mobile', 15)->nullable();
            $table->string('whatsapp_number', 15)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('date_of_birth')->nullable();

            // KYC
            $table->string('aadhaar_number', 20)->nullable();
            $table->string('aadhaar_document', 500)->nullable();

            // Address (SDTV Location)
            $table->text('address')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('landmark', 255)->nullable();

            // ZRTH Assignment
            $table->unsignedBigInteger('zrth_hierarchy_id')->nullable();

            // Beat Association
            $table->unsignedBigInteger('beat_id')->nullable();

            // Farm Details
            $table->decimal('total_landholding_acres', 8, 2)->nullable();
            $table->decimal('cultivable_land_acres', 8, 2)->nullable();
            $table->enum('landholding_type', ['owned', 'leased', 'mixed'])->nullable();

            // Irrigation
            $table->enum('irrigation_type', [
                'rainfed',
                'canal',
                'tubewell',
                'borewell',
                'drip',
                'sprinkler',
                'mixed',
                'none'
            ])->nullable();
            $table->json('irrigation_sources')->nullable()->comment('Array of irrigation types');
            $table->decimal('irrigated_land_acres', 8, 2)->nullable();

            // Farmer Classification
            $table->enum('farmer_type', ['pda', 'demo', 'user', 'non_user'])->default('non_user')
                ->comment('PDA=Potential Demo Area, Demo=Demo Farmer, User=Product User, Non-User=Potential');
            $table->enum('farmer_category', ['small', 'influencer', 'non_user', 'large', 'progressive'])->nullable()
                ->comment('Farmer influence/size category');
            $table->boolean('is_progressive_farmer')->default(false);
            $table->boolean('is_kol')->default(false)->comment('Key Opinion Leader');

            // Preferences
            $table->unsignedBigInteger('preferred_retailer_id')->nullable();
            $table->unsignedBigInteger('reference_farmer_id')->nullable()->comment('Referred by farmer');
            $table->string('preferred_language', 50)->nullable();
            $table->boolean('sms_consent')->default(true);
            $table->boolean('whatsapp_consent')->default(true);

            // Profile Photo
            $table->string('photo_path', 500)->nullable();

            // Registration
            $table->unsignedBigInteger('registered_by')->nullable();
            $table->enum('status', ['active', 'inactive', 'pending_verification'])->default('active');

            // Verification
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            // Sync
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onDelete('set null');

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('set null');

            $table->foreign('beat_id')
                ->references('id')
                ->on('beats')
                ->onDelete('set null');

            $table->foreign('preferred_retailer_id')
                ->references('id')
                ->on('retailers')
                ->onDelete('set null');

            $table->foreign('reference_farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('set null');

            $table->foreign('registered_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['zrth_hierarchy_id', 'status']);
            $table->index(['beat_id', 'status']);
            $table->index('mobile');
            $table->index(['farmer_type', 'status']);
            $table->index('sync_status');
        });

        // Farmer Crop Details (crops grown by farmer)
        Schema::create('farmer_crops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('crop_id');
            $table->decimal('area_acres', 8, 2)->nullable();
            $table->enum('season', ['kharif', 'rabi', 'zaid', 'perennial'])->nullable();
            $table->unsignedInteger('years_cultivating')->nullable();
            $table->decimal('average_yield_per_acre', 10, 2)->nullable();
            $table->string('yield_unit', 20)->nullable()->comment('e.g., quintal, ton, kg');
            $table->boolean('is_primary_crop')->default(false);
            $table->enum('status', ['current', 'past', 'planned'])->default('current');
            $table->timestamps();

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('cascade');

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('cascade');

            $table->unique(['farmer_id', 'crop_id', 'season']);
            $table->index('crop_id');
        });

        // Farmer Product Usage (products used by farmer)
        Schema::create('farmer_product_usage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('crop_id')->nullable();
            $table->enum('usage_frequency', ['always', 'sometimes', 'rarely', 'tried_once'])->nullable();
            $table->decimal('quantity_per_season', 10, 2)->nullable();
            $table->string('quantity_unit', 20)->nullable();
            $table->enum('satisfaction', ['very_satisfied', 'satisfied', 'neutral', 'unsatisfied'])->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('set null');

            $table->index(['farmer_id', 'product_id']);
        });

        // Farmer-Retailer Associations (can have multiple retailers)
        Schema::create('farmer_retailers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('retailer_id');
            $table->boolean('is_primary')->default(false);
            $table->decimal('annual_purchase_value', 12, 2)->nullable();
            $table->timestamps();

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('cascade');

            $table->foreign('retailer_id')
                ->references('id')
                ->on('retailers')
                ->onDelete('cascade');

            $table->unique(['farmer_id', 'retailer_id']);
        });

        // Farmer Visit History
        Schema::create('farmer_visit_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('farmer_id');
            $table->unsignedBigInteger('visited_by');
            $table->date('visit_date');
            $table->string('visit_purpose', 255)->nullable();
            $table->text('notes')->nullable();
            $table->text('next_action')->nullable();
            $table->date('next_visit_date')->nullable();
            $table->timestamps();

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('cascade');

            $table->foreign('visited_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['farmer_id', 'visit_date']);
            $table->index(['visited_by', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmer_visit_history');
        Schema::dropIfExists('farmer_retailers');
        Schema::dropIfExists('farmer_product_usage');
        Schema::dropIfExists('farmer_crops');
        Schema::dropIfExists('farmers');
    }
};
