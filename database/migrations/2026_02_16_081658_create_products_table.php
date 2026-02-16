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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code')->unique();
            $table->string('sku_name');
            $table->string('category');
            $table->unsignedBigInteger('crop_id');
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
            $table->string('variety_name')->nullable();
            $table->string('pack_size')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->index('sku_code');
            $table->index('crop_id');
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
        Schema::dropIfExists('products');
    }
};
