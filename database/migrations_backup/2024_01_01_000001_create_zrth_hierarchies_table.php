<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ZRTH Hierarchy: Zone > Region > Territory > HQ
     * Self-referential hierarchy with parent-child relationships
     */
    public function up(): void
    {
        Schema::create('zrth_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique()->comment('Unique code for the hierarchy level');
            $table->string('name', 255)->comment('Name of the zone/region/territory/HQ');
            $table->enum('level', ['zone', 'region', 'territory', 'hq'])->comment('Hierarchy level type');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Self-referential parent');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Self-referential foreign key
            $table->foreign('parent_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('restrict');

            // Indexes for performance
            $table->index(['level', 'status']);
            $table->index('parent_id');
        });

        // Audit trail table for ZRTH changes
        Schema::create('zrth_hierarchy_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zrth_hierarchy_id');
            $table->string('action', 50)->comment('created, updated, deleted, restored');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable()->comment('User who made the change');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('cascade');

            $table->index(['zrth_hierarchy_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zrth_hierarchy_audits');
        Schema::dropIfExists('zrth_hierarchies');
    }
};
