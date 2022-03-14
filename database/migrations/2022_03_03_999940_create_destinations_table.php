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

            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('governorate_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->timestamps();

            $table->foreign('delivery_id')->references('id')->on('deliveries')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('zone_id')->references('id')->on('zones')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('governorate_id')->references('id')->on('governorates')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->nullOnDelete()->onUpdate('cascade');
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
