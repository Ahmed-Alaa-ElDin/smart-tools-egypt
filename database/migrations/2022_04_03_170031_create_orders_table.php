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
            $table->decimal('total_base',8,2);
            $table->decimal('total_final',8,2);
            $table->decimal('total_weight',8,2)->default(1)->comment('on Kg');
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('address_id')->nullable();
            $table->unsignedBigInteger('destination_id')->nullable();
            $table->string('phone',11)->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('destination_id')->references('id')->on('destinations')->nullOnDelete()->onUpdate('cascade');
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
