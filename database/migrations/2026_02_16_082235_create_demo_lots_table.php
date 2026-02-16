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
        Schema::create('demo_lots', function (Blueprint $table) {
            $table->id();
            $table->string('lot_number')->unique();
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedBigInteger('crop_id');
            $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
            $table->decimal('quantity', 10, 2);
            $table->date('dispatch_date');
            $table->text('dispatch_from_location')->nullable();
            $table->enum('status', ['created', 'dispatched', 'received', 'closed'])->default('created');
            $table->timestamps();
            $table->index('lot_number');
            $table->index('product_id');
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
        Schema::dropIfExists('demo_lots');
    }
};
