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
        Schema::create('orderables', function (Blueprint $table) {
            $table->id();
            $table->string('orderable_type');
            $table->unsignedBigInteger('orderable_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('quantity')->default(0);
            $table->decimal('original_price', 10, 2)->default(0.00);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->decimal('coupon_discount', 10, 2)->default(0.00);
            $table->integer('points')->default(0);
            $table->integer('coupon_points')->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderable');
    }
};
