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
        Schema::create('demo_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('demo_lot_id');
            $table->foreign('demo_lot_id')->references('id')->on('demo_lots')->onDelete('cascade');
            $table->text('from_location')->nullable();
            $table->text('to_location')->nullable();
            $table->unsignedBigInteger('from_user_id')->nullable();
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedBigInteger('to_user_id')->nullable();
            $table->foreign('to_user_id')->references('id')->on('users')->onDelete('set null');
            $table->date('dispatch_date');
            $table->date('received_date')->nullable();
            $table->string('received_by')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->string('receipt_acknowledgement')->nullable();
            $table->string('dispatch_photo')->nullable();
            $table->timestamps();
            $table->index('demo_lot_id');
            $table->index('dispatch_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_distributions');
    }
};
