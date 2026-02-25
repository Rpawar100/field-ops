<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Product/SKU Master
     */
    public function up(): void
    {
        // Crop Master
        Schema::create('crops', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 255);
            $table->string('local_name', 255)->nullable();
            $table->enum('crop_type', ['kharif', 'rabi', 'zaid', 'perennial'])->nullable();
            $table->unsignedInteger('typical_duration_days')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path', 500)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['crop_type', 'status']);
        });

        // Brand Master
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 255);
            $table->string('company_name', 255)->nullable();
            $table->string('logo_path', 500)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 255);
            $table->enum('type', ['seed', 'pesticide', 'bio_product', 'fertilizer', 'equipment', 'other'])->default('other');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parent_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('set null');

            $table->index(['type', 'status']);
        });

        // Variety Master (for seeds)
        Schema::create('varieties', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedInteger('maturity_days')->nullable();
            $table->text('features')->nullable();
            $table->text('suitable_conditions')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('restrict');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('set null');

            $table->index(['crop_id', 'status']);
        });

        // Products/SKU Master
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code', 50)->unique();
            $table->string('sku_name', 255);
            $table->string('display_name', 255)->nullable();

            // Category
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('product_type', ['seed', 'pesticide', 'bio_product', 'fertilizer', 'equipment', 'other'])->default('other');

            // Brand and Variety (for seeds)
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('variety_id')->nullable();
            $table->unsignedBigInteger('crop_id')->nullable();

            // Pack Details
            $table->string('pack_size', 50)->nullable()->comment('e.g., 500g, 1kg, 1L');
            $table->string('pack_unit', 20)->nullable()->comment('e.g., g, kg, ml, L, pcs');
            $table->decimal('pack_quantity', 10, 2)->nullable();
            $table->unsignedInteger('packs_per_case')->nullable();

            // Pricing
            $table->decimal('mrp', 12, 2)->nullable();
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->decimal('distributor_price', 12, 2)->nullable();
            $table->decimal('retailer_margin_percent', 5, 2)->nullable();

            // Product Details
            $table->string('hsn_code', 20)->nullable();
            $table->string('gst_rate', 10)->nullable();
            $table->text('description')->nullable();
            $table->text('usage_instructions')->nullable();
            $table->text('safety_instructions')->nullable();
            $table->string('image_path', 500)->nullable();

            // Applicable Crops (for pesticides/bio-products)
            $table->json('applicable_crop_ids')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->date('launch_date')->nullable();
            $table->date('discontinue_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('set null');

            $table->foreign('brand_id')
                ->references('id')
                ->on('brands')
                ->onDelete('set null');

            $table->foreign('variety_id')
                ->references('id')
                ->on('varieties')
                ->onDelete('set null');

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('set null');

            $table->index(['product_type', 'status']);
            $table->index(['brand_id', 'status']);
            $table->index(['crop_id', 'status']);
        });

        // Product Price History
        Schema::create('product_price_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->decimal('mrp', 12, 2)->nullable();
            $table->decimal('selling_price', 12, 2)->nullable();
            $table->decimal('distributor_price', 12, 2)->nullable();
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->foreign('changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['product_id', 'effective_from']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_price_history');
        Schema::dropIfExists('products');
        Schema::dropIfExists('varieties');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('crops');
    }
};
