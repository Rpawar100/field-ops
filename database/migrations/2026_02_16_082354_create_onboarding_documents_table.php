<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('onboarding_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('onboarding_request_id');
            $table->foreign('onboarding_request_id')->references('id')->on('onboarding_requests')->onDelete('cascade');
            $table->enum('document_type', ['aadhar', 'pan', 'bank_statement', 'driving_license', 'photo', 'salary_approval', 'qualification']);
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('mime_type');
            $table->string('merged_pdf_path')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
            $table->index('onboarding_request_id');
            $table->index('document_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('onboarding_documents');
    }
};
