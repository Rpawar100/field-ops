<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add foreign keys that couldn't be added earlier due to table creation order
     * Add additional indexes for performance optimization
     */
    public function up(): void
    {
        // Add foreign key for zrth_hierarchy_audits.changed_by to users
        Schema::table('zrth_hierarchy_audits', function (Blueprint $table) {
            $table->foreign('changed_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        // Add beat associations to farmers and retailers tables
        Schema::create('beat_farmers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beat_id');
            $table->unsignedBigInteger('farmer_id');
            $table->boolean('is_primary')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('beat_id')
                ->references('id')
                ->on('beats')
                ->onDelete('cascade');

            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('cascade');

            $table->unique(['beat_id', 'farmer_id']);
            $table->index(['farmer_id', 'status']);
        });

        Schema::create('beat_retailers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beat_id');
            $table->unsignedBigInteger('retailer_id');
            $table->boolean('is_primary')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('beat_id')
                ->references('id')
                ->on('beats')
                ->onDelete('cascade');

            $table->foreign('retailer_id')
                ->references('id')
                ->on('retailers')
                ->onDelete('cascade');

            $table->unique(['beat_id', 'retailer_id']);
            $table->index(['retailer_id', 'status']);
        });

        // Password Reset Tokens (Laravel default)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Personal Access Tokens (for Sanctum) - skip if already created by vendor
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
            });
        }

        // Failed Jobs (Laravel default)
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Jobs (for queue)
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // Job Batches (for batch processing)
        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        // Sync Queue (for offline data sync)
        Schema::create('sync_queue', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('entity_type', 50)->comment('e.g., attendance, activity, farmer');
            $table->string('entity_uuid', 50)->comment('UUID of the entity');
            $table->enum('action', ['create', 'update', 'delete'])->default('create');
            $table->json('payload');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'conflict'])->default('pending');
            $table->unsignedInteger('attempts')->default(0);
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['user_id', 'status']);
            $table->index(['entity_type', 'entity_uuid']);
            $table->index(['status', 'created_at']);
        });

        // Notifications Table
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_id', 'notifiable_type', 'read_at']);
        });

        // App Settings/Configuration
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->enum('type', ['string', 'integer', 'boolean', 'json', 'array'])->default('string');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false)->comment('Accessible via mobile app');
            $table->timestamps();

            $table->index('group');
        });

        // Audit Log (general audit trail)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('auditable_type', 100);
            $table->unsignedBigInteger('auditable_id');
            $table->string('event', 20)->comment('created, updated, deleted, restored');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('url', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['user_id', 'created_at']);
            $table->index('event');
        });

        // App Version Management
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 20)->comment('android, ios');
            $table->string('version', 20);
            $table->string('build_number', 20)->nullable();
            $table->boolean('is_mandatory')->default(false);
            $table->boolean('is_latest')->default(false);
            $table->text('release_notes')->nullable();
            $table->string('download_url', 500)->nullable();
            $table->date('release_date')->nullable();
            $table->enum('status', ['active', 'deprecated', 'disabled'])->default('active');
            $table->timestamps();

            $table->index(['platform', 'status']);
            $table->unique(['platform', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_versions');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('app_settings');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('sync_queue');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('beat_retailers');
        Schema::dropIfExists('beat_farmers');

        Schema::table('zrth_hierarchy_audits', function (Blueprint $table) {
            $table->dropForeign(['changed_by']);
        });
    }
};
