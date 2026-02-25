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
        Schema::create('headquarters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('territory_id');
            $table->foreign('territory_id')->references('id')->on('territories')->onDelete('cascade');
            $table->string('name');
            $table->text('location');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('territory_id');
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
        Schema::dropIfExists('headquarters');
    }
};
