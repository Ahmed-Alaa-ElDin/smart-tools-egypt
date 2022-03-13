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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('delivery_id');
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('governorate_id');
            $table->unsignedBigInteger('city_id');
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('destinations');
    }
};
