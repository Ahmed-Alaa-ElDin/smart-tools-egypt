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
            $table->unsignedBigInteger('old_order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('payment_amount')->default(0.00);
            $table->tinyInteger('payment_method')->nullable()->unsigned()->comment('1 => cash, 2 => card, 3 => installments, 4 => vodafone cash,10=>wallet');
            $table->tinyInteger('payment_status')->unsigned()->comment('1 => pending, 2 => paid, 3 => failed, 4 => refunded');
            $table->string('paymob_order_id', 20)->nullable();
            $table->text('payment_details')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('payments');
    }
};
