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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->decimal('subtotal_base', 8, 2)->default(0.00);
            $table->decimal('items_discount', 8, 2)->default(0.00);
            $table->decimal('offers_items_discount', 8, 2)->default(0.00);
            $table->decimal('offers_order_discount', 8, 2)->default(0.00);
            $table->decimal('coupon_items_discount', 8, 2)->default(0.00);
            $table->decimal('coupon_order_discount', 8, 2)->default(0.00);
            $table->decimal('delivery_fees', 8, 2)->default(0.00);
            $table->decimal('total', 8, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
