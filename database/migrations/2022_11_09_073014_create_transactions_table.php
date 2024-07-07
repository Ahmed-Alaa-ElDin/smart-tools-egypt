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
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('old_order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('payment_amount')->default(0.00);
            $table->tinyInteger('payment_method_id')->nullable()->unsigned();
            $table->tinyInteger('payment_status_id')->unsigned();
            $table->string('service_provider_transaction_id', 20)->nullable();
            $table->text('payment_details')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onUpdate('cascade')->nullOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses')->onUpdate('cascade')->onDelete('restrict');
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
