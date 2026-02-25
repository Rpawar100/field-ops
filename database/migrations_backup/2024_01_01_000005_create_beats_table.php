<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Beat/Market Master with ZRTH and SDTV links
     */
    public function up(): void
    {
        Schema::create('beats', function (Blueprint $table) {
            $table->id();
            $table->string('beat_code', 50)->unique();
            $table->string('name', 255);
            $table->text('description')->nullable();

            // ZRTH Assignment (Territory/HQ level)
            $table->unsignedBigInteger('zrth_hierarchy_id');

            // Primary Location (SDTV)
            $table->unsignedBigInteger('village_id')->nullable();

            // Coverage Area (can span multiple villages)
            $table->json('coverage_village_ids')->nullable()->comment('Array of village IDs');

            // Schedule
            $table->enum('beat_day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])->nullable();
            $table->unsignedInteger('visit_frequency_days')->default(7)->comment('Visit every N days');

            // Statistics
            $table->unsignedInteger('total_farmers')->default(0);
            $table->unsignedInteger('total_retailers')->default(0);
            $table->decimal('estimated_distance_km', 8, 2)->nullable();
            $table->unsignedInteger('estimated_time_minutes')->nullable();

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('restrict');

            $table->foreign('village_id')
                ->references('id')
                ->on('villages')
                ->onDelete('set null');

            $table->index(['zrth_hierarchy_id', 'status']);
            $table->index('beat_day');
        });

        // Beat User Assignments (Many-to-Many)
        Schema::create('beat_user_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beat_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_primary')->default(false)->comment('Primary owner of the beat');
            $table->date('assigned_from')->nullable();
            $table->date('assigned_to')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('beat_id')
                ->references('id')
                ->on('beats')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->unique(['beat_id', 'user_id', 'status'], 'beat_user_unique');
            $table->index(['user_id', 'status']);
        });

        // Beat Schedule (for planned visits)
        Schema::create('beat_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beat_id');
            $table->unsignedBigInteger('user_id');
            $table->date('scheduled_date');
            $table->enum('status', ['planned', 'completed', 'missed', 'rescheduled'])->default('planned');
            $table->unsignedBigInteger('attendance_id')->nullable()->comment('Linked attendance for completed');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('beat_id')
                ->references('id')
                ->on('beats')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('attendance_id')
                ->references('id')
                ->on('attendances')
                ->onDelete('set null');

            $table->unique(['beat_id', 'user_id', 'scheduled_date'], 'beat_schedule_unique');
            $table->index(['user_id', 'scheduled_date']);
            $table->index(['scheduled_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beat_schedules');
        Schema::dropIfExists('beat_user_assignments');
        Schema::dropIfExists('beats');
    }
};
