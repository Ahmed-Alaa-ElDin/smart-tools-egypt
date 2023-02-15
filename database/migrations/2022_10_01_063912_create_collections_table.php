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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('video')->nullable();
            $table->string('barcode')->nullable();
            $table->decimal('weight')->default(1)->comment('on Kg');
            $table->decimal('original_price')->nullable()->default(0.00);
            $table->decimal('base_price')->nullable()->default(0.00);
            $table->decimal('final_price')->nullable()->default(0.00);
            $table->integer('points')->nullable();
            $table->text('description')->nullable();
            $table->string('model')->nullable();
            $table->json('specs')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->tinyInteger('refundable')->default(1)->comment('0 -> No , 1 -> Yes');
            $table->tinyInteger('free_shipping')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->tinyInteger('publish')->default(1)->comment('0 -> No , 1 -> Yes');
            $table->tinyInteger('under_reviewing')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('collections');
    }
};
