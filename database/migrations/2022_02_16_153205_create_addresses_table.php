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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->unsigned();
            $table->unsignedBigInteger('country_id')->nullable()->unsigned();
            $table->unsignedBigInteger('governorate_id')->nullable()->unsigned();
            $table->unsignedBigInteger('city_id')->nullable()->unsigned();
            $table->text('details')->nullable();
            $table->text('special_marque')->nullable();
            $table->tinyInteger('default')->default(0)->comment('0 --> Not default , 1 --> Default');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->onUpdate('cascade');
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
        Schema::dropIfExists('addresses');
    }
};
