<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Activity Module: Types, Master, and Activity Records
     */
    public function up(): void
    {
        // Activity Types Master
        Schema::create('activity_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->enum('category', [
                'psa',           // Product Sample Activity
                'pda',           // Potential Demo Area
                'demo_distribution',
                'demo_cycle',
                'farmer_visit',
                'retailer_visit',
                'meeting',
                'training',
                'field_day',
                'market_survey',
                'collection',
                'other'
            ])->default('other');
            $table->text('description')->nullable();
            $table->boolean('requires_farmer')->default(false);
            $table->boolean('requires_retailer')->default(false);
            $table->boolean('requires_beat')->default(false);
            $table->boolean('requires_photo')->default(true);
            $table->unsignedInteger('min_photos')->default(0);
            $table->unsignedInteger('max_photos')->default(10);
            $table->boolean('requires_gps')->default(true);
            $table->boolean('counts_for_attendance')->default(true)->comment('Counts towards daily activity');
            $table->unsignedInteger('points')->default(1)->comment('Points for gamification');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'status']);
        });

        // Activity Master (templates with custom attributes)
        Schema::create('activity_masters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_type_id');
            $table->string('code', 30)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();

            // Attribute Schema (JSON Schema for dynamic fields)
            $table->json('attributes_schema')->nullable()->comment('JSON Schema defining custom fields');

            // Validation Rules
            $table->json('validation_rules')->nullable();

            // Applicable To
            $table->json('applicable_roles')->nullable()->comment('Roles that can perform this activity');
            $table->json('applicable_crop_ids')->nullable();
            $table->json('applicable_product_ids')->nullable();

            // Time Constraints
            $table->time('allowed_start_time')->nullable();
            $table->time('allowed_end_time')->nullable();
            $table->boolean('allow_backdated')->default(false);
            $table->unsignedInteger('backdate_days_limit')->default(0);

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('activity_type_id')
                ->references('id')
                ->on('activity_types')
                ->onDelete('restrict');

            $table->index(['activity_type_id', 'status']);
        });

        // Activities (actual activity records)
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync identification');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('activity_type_id');
            $table->unsignedBigInteger('activity_master_id')->nullable();

            // Target Entity (one of these)
            $table->unsignedBigInteger('farmer_id')->nullable();
            $table->unsignedBigInteger('retailer_id')->nullable();
            $table->unsignedBigInteger('distributor_id')->nullable();

            // Location Context
            $table->unsignedBigInteger('beat_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            $table->unsignedBigInteger('zrth_hierarchy_id')->nullable();

            // Execution Details
            $table->date('execution_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();

            // Dynamic Attributes (based on activity master)
            $table->json('attributes')->nullable()->comment('Custom field values');

            // Products Involved
            $table->json('products')->nullable()->comment('Array of {product_id, quantity, remarks}');

            // GPS Location
            $table->decimal('gps_latitude', 10, 8)->nullable();
            $table->decimal('gps_longitude', 11, 8)->nullable();
            $table->decimal('gps_accuracy', 8, 2)->nullable()->comment('Accuracy in meters');
            $table->string('address', 500)->nullable()->comment('Reverse geocoded address');

            // Photos
            $table->json('photos')->nullable()->comment('Array of photo paths');

            // Voice Note
            $table->string('voice_note_path', 500)->nullable();
            $table->unsignedInteger('voice_note_duration')->nullable()->comment('Duration in seconds');

            // Remarks
            $table->text('remarks')->nullable();
            $table->text('next_action')->nullable();
            $table->date('next_visit_date')->nullable();

            // Attendance Link
            $table->unsignedBigInteger('attendance_id')->nullable();

            // Demo Link (if activity is part of demo cycle)
            $table->unsignedBigInteger('demo_farmer_assignment_id')->nullable();
            $table->unsignedBigInteger('demo_stage_activity_id')->nullable();

            // Approval (if required)
            $table->enum('approval_status', ['not_required', 'pending', 'approved', 'rejected'])->default('not_required');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();

            // Sync Status (for offline)
            $table->enum('sync_status', ['pending', 'synced', 'failed', 'conflict'])->default('pending');
            $table->timestamp('synced_at')->nullable();
            $table->unsignedInteger('sync_attempts')->default(0);
            $table->text('sync_error')->nullable();

            // Device Info
            $table->string('device_id', 100)->nullable();
            $table->string('app_version', 20)->nullable();

            // Status
            $table->enum('status', ['draft', 'submitted', 'completed', 'cancelled'])->default('submitted');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('activity_type_id')
                ->references('id')
                ->on('activity_types')
                ->onDelete('restrict');

            $table->foreign('activity_master_id')
                ->references('id')
                ->on('activity_masters')
                ->onDelete('set null');

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('set null');

            $table->foreign('retailer_id')
                ->references('id')
                ->on('retailers')
                ->onDelete('set null');

            $table->foreign('distributor_id')
                ->references('id')
                ->on('distributors')
                ->onDelete('set null');

            $table->foreign('beat_id')
                ->references('id')
                ->on('beats')
                ->onDelete('set null');

            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onDelete('set null');

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('set null');

            $table->foreign('attendance_id')
                ->references('id')
                ->on('attendances')
                ->onDelete('set null');

            $table->foreign('demo_farmer_assignment_id')
                ->references('id')
                ->on('demo_farmer_assignments')
                ->onDelete('set null');

            $table->foreign('demo_stage_activity_id')
                ->references('id')
                ->on('demo_stage_activities')
                ->onDelete('set null');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Performance Indexes
            $table->index(['user_id', 'execution_date']);
            $table->index(['activity_type_id', 'execution_date']);
            $table->index(['farmer_id', 'execution_date']);
            $table->index(['retailer_id', 'execution_date']);
            $table->index(['beat_id', 'execution_date']);
            $table->index('sync_status');
            $table->index(['execution_date', 'status']);
        });

        // Activity Photos (separate table for better management)
        Schema::create('activity_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->string('photo_path', 500);
            $table->string('photo_type', 50)->nullable()->comment('e.g., before, after, product, signature');
            $table->string('caption', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('taken_at')->nullable();
            $table->string('file_size', 20)->nullable();
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');

            $table->index('activity_id');
        });

        // Activity Products (products discussed/sampled during activity)
        Schema::create('activity_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('quantity', 10, 2)->nullable();
            $table->string('quantity_unit', 20)->nullable();
            $table->enum('action', ['discussed', 'demonstrated', 'sampled', 'sold', 'ordered'])->default('discussed');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            $table->index(['activity_id', 'product_id']);
        });

        // Activity Comments/Follow-ups
        Schema::create('activity_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('user_id');
            $table->text('comment');
            $table->enum('comment_type', ['note', 'follow_up', 'escalation', 'resolution'])->default('note');
            $table->boolean('is_internal')->default(false)->comment('Only visible to managers');
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')
                ->on('activities')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['activity_id', 'created_at']);
        });

        // Daily Activity Summary (for quick reporting)
        Schema::create('daily_activity_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('summary_date');

            // Counts
            $table->unsignedInteger('total_activities')->default(0);
            $table->unsignedInteger('farmer_visits')->default(0);
            $table->unsignedInteger('retailer_visits')->default(0);
            $table->unsignedInteger('demo_activities')->default(0);
            $table->unsignedInteger('psa_activities')->default(0);
            $table->unsignedInteger('other_activities')->default(0);

            // Points
            $table->unsignedInteger('total_points')->default(0);

            // Coverage
            $table->unsignedInteger('villages_covered')->default(0);
            $table->unsignedInteger('beats_covered')->default(0);
            $table->decimal('distance_covered_km', 8, 2)->nullable();

            // Time
            $table->unsignedInteger('productive_minutes')->default(0);

            // Attendance Link
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->boolean('attendance_valid')->default(false);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('attendance_id')
                ->references('id')
                ->on('attendances')
                ->onDelete('set null');

            $table->unique(['user_id', 'summary_date']);
            $table->index(['summary_date', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activity_summaries');
        Schema::dropIfExists('activity_comments');
        Schema::dropIfExists('activity_products');
        Schema::dropIfExists('activity_photos');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('activity_masters');
        Schema::dropIfExists('activity_types');
    }
};
