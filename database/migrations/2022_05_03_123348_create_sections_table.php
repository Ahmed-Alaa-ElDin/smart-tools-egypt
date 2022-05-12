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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyInteger('type')->default(0)->comment('0 -> Products, 1 -> Offers, 2 -> Flash Sale , 3 -> Banners');
            $table->tinyInteger('active')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->tinyInteger('rank')->default(127);
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
        Schema::dropIfExists('sections');
    }
};
