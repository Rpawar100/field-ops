<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Retailer Registration with KYC and distributor links
     */
    public function up(): void
    {
        // Distributors Master
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('distributor_code', 50)->unique();
            $table->string('name', 255);
            $table->string('contact_person', 255)->nullable();
            $table->string('mobile', 15)->nullable();
            $table->string('email', 255)->nullable();
            $table->enum('type', ['super_stockist', 'distributor', 'sub_distributor'])->default('distributor');

            // Address
            $table->text('address')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // KYC
            $table->string('gst_number', 20)->nullable();
            $table->string('pan_number', 20)->nullable();

            // ZRTH Assignment
            $table->unsignedBigInteger('zrth_hierarchy_id')->nullable();

            // Business Details
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->unsignedInteger('credit_days')->nullable();

            $table->enum('status', ['active', 'inactive', 'blacklisted'])->default('active');
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

            $table->index(['zrth_hierarchy_id', 'status']);
        });

        // Retailers Master
        Schema::create('retailers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync');
            $table->string('retailer_code', 50)->unique();

            // Shop Details
            $table->string('shop_name', 255);
            $table->string('owner_name', 255);
            $table->string('contact_person', 255)->nullable();
            $table->string('mobile', 15);
            $table->string('alternate_mobile', 15)->nullable();
            $table->string('whatsapp_number', 15)->nullable();
            $table->string('email', 255)->nullable();

            // Business Type
            $table->enum('business_type', ['proprietary', 'partnership', 'llp', 'company', 'cooperative'])->default('proprietary');
            $table->string('firm_name', 255)->nullable()->comment('For partnership/LLP/Company');

            // Address
            $table->text('address');
            $table->unsignedBigInteger('village_id')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('landmark', 255)->nullable();

            // ZRTH Assignment
            $table->unsignedBigInteger('zrth_hierarchy_id')->nullable();

            // Beat Association
            $table->unsignedBigInteger('beat_id')->nullable();

            // KYC Documents
            $table->string('aadhaar_number', 20)->nullable();
            $table->string('aadhaar_document', 500)->nullable();
            $table->string('pan_number', 20)->nullable();
            $table->string('pan_document', 500)->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('gst_document', 500)->nullable();
            $table->string('fssai_number', 20)->nullable();
            $table->string('fssai_document', 500)->nullable();
            $table->string('pesticide_license_number', 50)->nullable();
            $table->string('pesticide_license_document', 500)->nullable();
            $table->date('pesticide_license_expiry')->nullable();
            $table->string('seed_license_number', 50)->nullable();
            $table->string('seed_license_document', 500)->nullable();
            $table->date('seed_license_expiry')->nullable();
            $table->string('fertilizer_license_number', 50)->nullable();
            $table->string('fertilizer_license_document', 500)->nullable();
            $table->date('fertilizer_license_expiry')->nullable();

            // Bank Details
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_ifsc_code', 20)->nullable();

            // Business Metrics
            $table->decimal('annual_business_value', 15, 2)->nullable();
            $table->decimal('monthly_potential', 15, 2)->nullable();
            $table->enum('retailer_grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->json('product_categories_dealt')->nullable()->comment('Array of category types');

            // Shop Images
            $table->string('shop_photo', 500)->nullable();
            $table->string('shop_interior_photo', 500)->nullable();
            $table->string('owner_photo', 500)->nullable();

            // Verification
            $table->enum('kyc_status', ['pending', 'partial', 'verified', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_remarks')->nullable();

            // Registration
            $table->unsignedBigInteger('registered_by')->nullable();
            $table->enum('status', ['active', 'inactive', 'blacklisted', 'pending_approval'])->default('pending_approval');

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

            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('registered_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['zrth_hierarchy_id', 'status']);
            $table->index(['beat_id', 'status']);
            $table->index('mobile');
            $table->index('sync_status');
        });

        // Retailer-Distributor Links (Many-to-Many)
        Schema::create('retailer_distributors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retailer_id');
            $table->unsignedBigInteger('distributor_id');
            $table->boolean('is_primary')->default(false);
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->unsignedInteger('credit_days')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('retailer_id')
                ->references('id')
                ->on('retailers')
                ->onDelete('cascade');

            $table->foreign('distributor_id')
                ->references('id')
                ->on('distributors')
                ->onDelete('cascade');

            $table->unique(['retailer_id', 'distributor_id']);
            $table->index('distributor_id');
        });

        // Retailer Additional Documents
        Schema::create('retailer_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('retailer_id');
            $table->string('document_type', 100);
            $table->string('document_name', 255);
            $table->string('document_path', 500);
            $table->string('document_number', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('retailer_id')
                ->references('id')
                ->on('retailers')
                ->onDelete('cascade');

            $table->index(['retailer_id', 'document_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retailer_documents');
        Schema::dropIfExists('retailer_distributors');
        Schema::dropIfExists('retailers');
        Schema::dropIfExists('distributors');
    }
};
