<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Attendance tracking with GPS, selfie, and sync support
     */
    public function up(): void
    {
        // Holidays Master
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->date('holiday_date');
            $table->enum('type', ['national', 'regional', 'company'])->default('company');
            $table->unsignedBigInteger('zrth_hierarchy_id')->nullable()->comment('Region-specific holiday');
            $table->boolean('is_optional')->default(false);
            $table->timestamps();

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('set null');

            $table->index(['holiday_date', 'type']);
            $table->unique(['holiday_date', 'zrth_hierarchy_id'], 'holiday_date_region_unique');
        });

        // Leave Types Master
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->unsignedInteger('annual_quota')->default(0);
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_approval')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Leave Requests
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 4, 1);
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('leave_type_id')
                ->references('id')
                ->on('leave_types')
                ->onDelete('restrict');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['user_id', 'start_date', 'end_date']);
            $table->index(['status', 'start_date']);
        });

        // Attendance Records
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->comment('For offline sync identification');
            $table->unsignedBigInteger('user_id');
            $table->date('attendance_date');

            // Check-in Details
            $table->timestamp('check_in_time')->nullable();
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->string('check_in_selfie_path', 500)->nullable();
            $table->string('check_in_address', 500)->nullable()->comment('Reverse geocoded address');

            // Check-out Details
            $table->timestamp('check_out_time')->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            $table->string('check_out_selfie_path', 500)->nullable();
            $table->string('check_out_address', 500)->nullable();

            // Attendance Type
            $table->enum('attendance_type', [
                'joint_work',
                'individual_work',
                'meeting',
                'leave',
                'week_off',
                'holiday',
                'half_day',
                'work_from_home'
            ])->default('individual_work');

            // For Joint Work
            $table->unsignedBigInteger('joint_work_with_user_id')->nullable();

            // Leave Reference
            $table->unsignedBigInteger('leave_request_id')->nullable();

            // Validation
            $table->boolean('is_valid')->default(false)->comment('Based on activity count rule');
            $table->unsignedInteger('activity_count')->default(0);
            $table->unsignedInteger('minimum_activity_required')->default(0);

            // Working Hours
            $table->unsignedInteger('total_working_minutes')->nullable();
            $table->decimal('distance_covered_km', 8, 2)->nullable();

            // Remarks and Notes
            $table->text('remarks')->nullable();
            $table->text('manager_remarks')->nullable();

            // Sync Status for Offline
            $table->enum('sync_status', ['pending', 'synced', 'failed', 'conflict'])->default('pending');
            $table->timestamp('synced_at')->nullable();
            $table->unsignedInteger('sync_attempts')->default(0);
            $table->text('sync_error')->nullable();

            // Device Info
            $table->string('device_id', 100)->nullable();
            $table->string('device_model', 100)->nullable();
            $table->string('app_version', 20)->nullable();

            // Approval (if required)
            $table->enum('approval_status', ['not_required', 'pending', 'approved', 'rejected'])->default('not_required');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('joint_work_with_user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('leave_request_id')
                ->references('id')
                ->on('leave_requests')
                ->onDelete('set null');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // Unique constraint - one attendance per user per day
            $table->unique(['user_id', 'attendance_date']);

            $table->index(['attendance_date', 'attendance_type']);
            $table->index(['sync_status', 'user_id']);
            $table->index('is_valid');
        });

        // Attendance Location Trail (for tracking during the day)
        Schema::create('attendance_location_trails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_id');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy', 8, 2)->nullable()->comment('GPS accuracy in meters');
            $table->decimal('speed', 8, 2)->nullable()->comment('Speed in km/h');
            $table->string('address', 500)->nullable();
            $table->timestamp('recorded_at');
            $table->enum('sync_status', ['pending', 'synced'])->default('pending');
            $table->timestamps();

            $table->foreign('attendance_id')
                ->references('id')
                ->on('attendances')
                ->onDelete('cascade');

            $table->index(['attendance_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_location_trails');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('leave_types');
        Schema::dropIfExists('holidays');
    }
};
