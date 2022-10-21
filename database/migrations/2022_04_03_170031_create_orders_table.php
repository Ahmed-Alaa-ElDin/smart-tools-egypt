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
            $table->string('phone1', 11)->nullable();
            $table->string('phone2', 255)->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->integer('num_of_items')->default(1)->nullable();
            $table->tinyInteger('allow_opening')->default(1)->comment('0 -> No , 1 -> Yes')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->integer('items_points')->default(0);
            $table->integer('offers_items_points')->default(0);
            $table->integer('offers_order_points')->default(0);
            $table->integer('coupon_items_points')->default(0);
            $table->integer('coupon_order_points')->default(0);
            $table->integer('gift_points')->default(0);
            $table->decimal('total_weight', 8, 2)->default(1)->comment('on Kg');
            $table->string('tracking_number')->nullable();
            $table->string('package_type')->default('parcel')->nullable();
            $table->text('package_desc')->nullable();
            $table->string('order_delivery_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->unsignedBigInteger('old_order_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->nullOnDelete()->onUpdate('cascade');
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
