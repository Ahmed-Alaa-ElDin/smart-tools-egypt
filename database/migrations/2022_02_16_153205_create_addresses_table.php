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
            $table->integer('user_id')->unsigned()->onDelete('cascade')->onUpdate('cascade');;
            $table->integer('country_id')->unsigned()->onDelete('cascade')->onUpdate('cascade');;
            $table->integer('governorate_id')->unsigned()->onDelete('cascade')->onUpdate('cascade');;
            $table->integer('city_id')->unsigned()->onDelete('cascade')->onUpdate('cascade');;
            $table->text('details');
            $table->text('special_marque');
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
        Schema::dropIfExists('addresses');
    }
};
