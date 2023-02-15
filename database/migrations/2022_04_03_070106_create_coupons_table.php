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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->decimal('value')->default(0)->nullable();
            $table->tinyInteger('type')->default(0)->nullable()->comment('0 -> percentage , 1 -> fixed, 2 -> points');
            $table->tinyInteger('on_orders')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->string('free_shipping')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->integer('number')->nullable();
            $table->date('expire_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
};
