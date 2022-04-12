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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->string('phone', 11)->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->decimal('subtotal_base', 8, 2);
            $table->decimal('subtotal_final', 8, 2);
            $table->decimal('delivery_fees', 8, 2);
            $table->decimal('total_weight', 8, 2)->default(1)->comment('on Kg');
            $table->string('payment_method_id')->nullable();
            $table->tinyInteger('payment_status')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('order_status')->nullOnDelete()->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
