<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * User Master with roles: NSM, ZM, RM, TSL, FA
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 50)->unique()->comment('Employee ID / User Code');
            $table->string('name', 255);
            $table->string('mobile', 15)->unique();
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Role and Designation
            $table->enum('role', ['nsm', 'zm', 'rm', 'tsl', 'fa'])->comment('NSM, ZM, RM, TSL, FA');
            $table->string('designation', 100)->nullable();
            $table->enum('fa_type', ['permanent', 'temporary'])->nullable()->comment('Only for FA role');

            // Reporting Structure
            $table->unsignedBigInteger('reporting_manager_id')->nullable();

            // Status and Access
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');
            $table->boolean('app_access_enabled')->default(true);
            $table->date('date_of_joining')->nullable();
            $table->date('date_of_leaving')->nullable();

            // Profile
            $table->string('profile_photo', 500)->nullable();
            $table->text('address')->nullable();

            // Authentication
            $table->rememberToken();
            $table->string('device_token', 500)->nullable()->comment('For push notifications');
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('reporting_manager_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->index(['role', 'status']);
            $table->index('reporting_manager_id');
            $table->index('date_of_joining');
        });

        // FA Onboarding Details (extended info for Field Assistants)
        Schema::create('fa_onboarding_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();

            // KYC Documents
            $table->string('pan_number', 20)->nullable();
            $table->string('pan_document', 500)->nullable()->comment('File path');
            $table->string('aadhaar_number', 20)->nullable();
            $table->string('aadhaar_document', 500)->nullable()->comment('File path');
            $table->string('driving_license_number', 50)->nullable();
            $table->string('driving_license_document', 500)->nullable();

            // Qualifications
            $table->string('qualification', 255)->nullable();
            $table->string('qualification_document', 500)->nullable();
            $table->text('experience_details')->nullable();

            // Bank Details
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_ifsc_code', 20)->nullable();
            $table->string('cancelled_cheque', 500)->nullable()->comment('File path');

            // Salary Details
            $table->decimal('proposed_salary', 12, 2)->nullable();
            $table->decimal('approved_salary', 12, 2)->nullable();
            $table->enum('salary_status', ['proposed', 'approved', 'rejected'])->default('proposed');

            // Emergency Contact
            $table->string('emergency_contact_name', 255)->nullable();
            $table->string('emergency_contact_phone', 15)->nullable();
            $table->string('emergency_contact_relation', 100)->nullable();

            // Verification Status
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_remarks')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('verified_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        // FA Additional Documents
        Schema::create('fa_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('document_type', 100)->comment('e.g., ID Proof, Address Proof, Photo');
            $table->string('document_name', 255);
            $table->string('document_path', 500);
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable()->comment('Size in bytes');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->index(['user_id', 'document_type']);
        });

        // User ZRTH Assignments (Many-to-Many)
        Schema::create('user_zrth_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('zrth_hierarchy_id');
            $table->boolean('is_primary')->default(false)->comment('Primary assignment');
            $table->date('assigned_from')->nullable();
            $table->date('assigned_to')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('zrth_hierarchy_id')
                ->references('id')
                ->on('zrth_hierarchies')
                ->onDelete('cascade');

            $table->unique(['user_id', 'zrth_hierarchy_id', 'status'], 'user_zrth_unique');
            $table->index(['zrth_hierarchy_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_zrth_assignments');
        Schema::dropIfExists('fa_documents');
        Schema::dropIfExists('fa_onboarding_details');
        Schema::dropIfExists('users');
    }
};
