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
        Schema::create('delivery_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->string('phone',11);
            $table->tinyInteger('default')->default(0)->comment('0 --> Not default , 1 --> Default');
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_phones');
    }
};
