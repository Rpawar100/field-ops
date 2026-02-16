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
        Schema::create('beat', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedBigInteger('territory_id');
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');
            $table->unsignedBigInteger('headquarters_id')->nullable();
            $table->foreign('headquarters_id')->references('id')->on('headquarters')->onDelete('set null');
            $table->unsignedBigInteger('village_id');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('code');
            $table->index('territory_id');
            $table->index('village_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('beat');
    }
};
