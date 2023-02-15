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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('old_order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('payment_amount')->default(0.00);
            $table->tinyInteger('payment_method')->nullable()->unsigned()->comment('1 => cash, 2 => card, 3 => installments, 4 => vodafone cash, 10 => wallet, 11 => points');
            $table->tinyInteger('payment_status')->unsigned()->comment('1 => pending, 2 => paid, 3 => failed, 4 => refund_pending, 5 => refunded, 6 => refund_failed');
            $table->string('paymob_order_id', 20)->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onUpdate('cascade')->nullOnDelete();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->nullOnDelete();
            $table->foreign('old_order_id')->references('id')->on('orders')->onUpdate('cascade')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
