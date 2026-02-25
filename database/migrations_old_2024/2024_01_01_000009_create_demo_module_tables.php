<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Demo Module: Master, Distribution, Farmer Assignment, Stage Activities, Templates
     */
    public function up(): void
    {
        // Demo Stage Templates (defines stages for each crop)
        Schema::create('demo_stage_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('variety_id')->nullable();
            $table->string('stage_name', 100);
            $table->unsignedInteger('stage_order')->default(1);
            $table->unsignedInteger('days_after_sowing')->comment('Expected days after sowing');
            $table->unsignedInteger('tolerance_days_before')->default(3)->comment('Can be done N days before');
            $table->unsignedInteger('tolerance_days_after')->default(3)->comment('Can be done N days after');
            $table->json('required_attributes')->nullable()->comment('JSON schema of required fields');
            $table->json('optional_attributes')->nullable()->comment('JSON schema of optional fields');
            $table->boolean('photo_required')->default(true);
            $table->unsignedInteger('min_photos')->default(1);
            $table->unsignedInteger('max_photos')->default(5);
            $table->text('instructions')->nullable();
            $table->text('observation_guidelines')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('cascade');

            $table->foreign('variety_id')
                ->references('id')
                ->on('varieties')
                ->onDelete('set null');

            $table->unique(['crop_id', 'variety_id', 'stage_name'], 'demo_stage_template_unique');
            $table->index(['crop_id', 'stage_order']);
        });

        // Demo Stage Plan (collection of stages for a demo cycle)
        Schema::create('demo_stage_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_code', 50)->unique();
            $table->string('name', 255);
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('variety_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('total_stages')->default(0);
            $table->unsignedInteger('total_duration_days')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('restrict');

            $table->foreign('variety_id')
                ->references('id')
                ->on('varieties')
                ->onDelete('set null');

            $table->index(['crop_id', 'status']);
        });

        // Link between Stage Plan and Stage Templates
        Schema::create('demo_stage_plan_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stage_plan_id');
            $table->unsignedBigInteger('stage_template_id');
            $table->unsignedInteger('stage_order');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();

            $table->foreign('stage_plan_id')
                ->references('id')
                ->on('demo_stage_plans')
                ->onDelete('cascade');

            $table->foreign('stage_template_id')
                ->references('id')
                ->on('demo_stage_templates')
                ->onDelete('cascade');

            $table->unique(['stage_plan_id', 'stage_template_id']);
            $table->index(['stage_plan_id', 'stage_order']);
        });

        // Demo Master (Lot management)
        Schema::create('demo_masters', function (Blueprint $table) {
            $table->id();
            $table->string('lot_no', 50)->unique();
            $table->unsignedBigInteger('crop_id');
            $table->unsignedBigInteger('variety_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable()->comment('SKU for demo');
            $table->string('product_name', 255)->nullable()->comment('For quick reference');
            $table->decimal('total_quantity', 12, 2);
            $table->string('quantity_unit', 20)->default('kg');
            $table->decimal('available_quantity', 12, 2);
            $table->unsignedBigInteger('stage_plan_id')->nullable();

            // Season and Year
            $table->enum('season', ['kharif', 'rabi', 'zaid'])->nullable();
            $table->string('year', 10)->nullable()->comment('e.g., 2024-25');

            // Source Location
            $table->unsignedBigInteger('source_zrth_id')->nullable()->comment('Where lot originated');
            $table->string('source_location_name', 255)->nullable();

            // Batch Details
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('batch_number', 100)->nullable();

            // Target
            $table->unsignedInteger('target_farmers')->default(0);
            $table->unsignedInteger('assigned_farmers')->default(0);

            $table->enum('status', ['available', 'partially_distributed', 'fully_distributed', 'expired', 'recalled'])->default('available');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('crop_id')
                ->references('id')
                ->on('crops')
                ->onDelete('restrict');

            $table->foreign('variety_id')
                ->references('id')
                ->on('varieties')
                ->onDelete('set null');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('set null');

            $table->foreign('stage_plan_id')
                ->references('id')
                ->on('demo_stage_plans')
                ->onDelete('set null');

            $table->foreign('source_zrth_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('set null');

            $table->index(['season', 'year', 'status']);
            $table->index(['crop_id', 'status']);
        });

        // Demo Distribution (movement of demo material)
        Schema::create('demo_distributions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync');
            $table->unsignedBigInteger('demo_master_id');

            // From Location
            $table->enum('from_location_type', ['warehouse', 'zrth', 'user'])->default('warehouse');
            $table->unsignedBigInteger('from_zrth_id')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->string('from_location_name', 255)->nullable();

            // To Location
            $table->enum('to_location_type', ['zrth', 'user', 'farmer'])->default('user');
            $table->unsignedBigInteger('to_zrth_id')->nullable();
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->unsignedBigInteger('to_farmer_id')->nullable();
            $table->string('to_location_name', 255)->nullable();

            // Distribution Details
            $table->date('distribution_date');
            $table->decimal('quantity', 12, 2);
            $table->string('quantity_unit', 20)->default('kg');

            // Receiver Details
            $table->string('receiver_name', 255)->nullable();
            $table->string('receiver_mobile', 15)->nullable();
            $table->string('receiver_designation', 100)->nullable();

            // Acknowledgment
            $table->boolean('is_acknowledged')->default(false);
            $table->timestamp('acknowledged_at')->nullable();
            $table->string('acknowledgment_signature', 500)->nullable()->comment('Signature image path');
            $table->string('acknowledgment_photo', 500)->nullable();
            $table->text('acknowledgment_remarks')->nullable();

            // GPS at distribution
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Distributed By
            $table->unsignedBigInteger('distributed_by')->nullable();

            // Reference
            $table->string('challan_number', 100)->nullable();
            $table->text('remarks')->nullable();

            $table->enum('status', ['pending', 'in_transit', 'delivered', 'acknowledged', 'returned'])->default('pending');

            // Sync
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('demo_master_id')
                ->references('id')
                ->on('demo_masters')
                ->onDelete('cascade');

            $table->foreign('from_zrth_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('set null');

            $table->foreign('from_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('to_zrth_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('set null');

            $table->foreign('to_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('to_farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('set null');

            $table->foreign('distributed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['demo_master_id', 'status']);
            $table->index(['distribution_date', 'status']);
            $table->index('sync_status');
        });

        // Demo Farmer Assignment
        Schema::create('demo_farmer_assignments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync');
            $table->unsignedBigInteger('demo_master_id');
            $table->unsignedBigInteger('demo_distribution_id')->nullable();
            $table->unsignedBigInteger('farmer_id');
            $table->string('lot_no', 50)->comment('Denormalized for quick access');

            // Assignment Details
            $table->decimal('quantity_given', 10, 2);
            $table->string('quantity_unit', 20)->default('kg');
            $table->date('assignment_date');
            $table->date('sowing_date')->nullable();
            $table->date('expected_harvest_date')->nullable();

            // Demo Plot Details
            $table->decimal('plot_size_acres', 8, 2)->nullable();
            $table->decimal('plot_latitude', 10, 8)->nullable();
            $table->decimal('plot_longitude', 11, 8)->nullable();
            $table->text('plot_address')->nullable();

            // Comparison Details
            $table->string('comparison_variety', 255)->nullable();
            $table->string('comparison_brand', 255)->nullable();

            // Linked User (FA/TSL who assigned)
            $table->unsignedBigInteger('assigned_by');

            // Current Stage Tracking
            $table->unsignedInteger('current_stage')->default(0);
            $table->unsignedInteger('total_stages')->default(0);
            $table->unsignedInteger('completed_stages')->default(0);

            // Status
            $table->enum('status', [
                'assigned',
                'sowing_done',
                'in_progress',
                'completed',
                'failed',
                'abandoned',
                'harvested'
            ])->default('assigned');

            $table->text('status_remarks')->nullable();
            $table->date('status_date')->nullable();

            // Outcome
            $table->decimal('actual_yield', 10, 2)->nullable();
            $table->string('yield_unit', 20)->nullable();
            $table->enum('farmer_satisfaction', ['excellent', 'good', 'average', 'poor'])->nullable();
            $table->text('farmer_feedback')->nullable();
            $table->boolean('will_repurchase')->nullable();

            // Sync
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('demo_master_id')
                ->references('id')
                ->on('demo_masters')
                ->onDelete('cascade');

            $table->foreign('demo_distribution_id')
                ->references('id')
                ->on('demo_distributions')
                ->onDelete('set null');

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('cascade');

            $table->foreign('assigned_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->unique(['demo_master_id', 'farmer_id'], 'demo_farmer_unique');
            $table->index(['farmer_id', 'status']);
            $table->index(['assigned_by', 'status']);
            $table->index('sowing_date');
            $table->index('sync_status');
        });

        // Demo Stage Activities (actual stage executions)
        Schema::create('demo_stage_activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync');
            $table->unsignedBigInteger('demo_farmer_assignment_id');
            $table->unsignedBigInteger('stage_template_id')->nullable();
            $table->string('stage_name', 100);
            $table->unsignedInteger('stage_order');

            // Dates
            $table->date('planned_date');
            $table->date('actual_date')->nullable();
            $table->unsignedInteger('days_after_sowing')->nullable();

            // Observations (dynamic attributes)
            $table->json('attributes')->nullable()->comment('Stage-specific observations');
            $table->json('measurements')->nullable()->comment('Numeric measurements');

            // Photos
            $table->json('photos')->nullable()->comment('Array of photo paths');

            // GPS
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Recorded By
            $table->unsignedBigInteger('recorded_by');

            // Status
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'skipped',
                'failed',
                'rescheduled'
            ])->default('pending');

            $table->text('status_reason')->nullable()->comment('Reason for failed/skipped/rescheduled');
            $table->date('rescheduled_date')->nullable();

            // Quality/Observation Score
            $table->enum('crop_condition', ['excellent', 'good', 'average', 'poor', 'failed'])->nullable();
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();

            // Manager Review
            $table->boolean('is_reviewed')->default(false);
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_remarks')->nullable();

            // Sync
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('demo_farmer_assignment_id')
                ->references('id')
                ->on('demo_farmer_assignments')
                ->onDelete('cascade');

            $table->foreign('stage_template_id')
                ->references('id')
                ->on('demo_stage_templates')
                ->onDelete('set null');

            $table->foreign('recorded_by')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');

            $table->foreign('reviewed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->unique(['demo_farmer_assignment_id', 'stage_order'], 'demo_stage_activity_unique');
            $table->index(['demo_farmer_assignment_id', 'status']);
            $table->index(['planned_date', 'status']);
            $table->index('sync_status');
        });

        // Demo Stage Activity Photos (separate table for multiple photos)
        Schema::create('demo_stage_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demo_stage_activity_id');
            $table->string('photo_path', 500);
            $table->string('photo_type', 50)->nullable()->comment('e.g., crop, pest, comparison');
            $table->string('caption', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('taken_at')->nullable();
            $table->timestamps();

            $table->foreign('demo_stage_activity_id')
                ->references('id')
                ->on('demo_stage_activities')
                ->onDelete('cascade');

            $table->index('demo_stage_activity_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_stage_photos');
        Schema::dropIfExists('demo_stage_activities');
        Schema::dropIfExists('demo_farmer_assignments');
        Schema::dropIfExists('demo_distributions');
        Schema::dropIfExists('demo_masters');
        Schema::dropIfExists('demo_stage_plan_templates');
        Schema::dropIfExists('demo_stage_plans');
        Schema::dropIfExists('demo_stage_templates');
    }
};
