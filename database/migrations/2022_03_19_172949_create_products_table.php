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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('barcode')->nullable();
            $table->decimal('weight')->default(1)->comment('on Kg');
            $table->integer('quantity')->default(0);
            $table->integer('low_stock')->default(0);
            $table->decimal('base_price');
            $table->decimal('final_price')->nullable();
            $table->integer('points')->nullable();
            $table->text('description')->nullable();
            $table->string('model')->nullable();
            $table->tinyInteger('refundable')->default(1)->comment('0 -> No , 1 -> Yes');
            $table->string('video')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->tinyInteger('free_shipping')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->tinyInteger('publish')->default(1)->comment('0 -> No , 1 -> Yes');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('brand_id')->references('id')->on('brands')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
