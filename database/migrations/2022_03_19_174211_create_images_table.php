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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('imagable_type');
            $table->unsignedBigInteger('imagable_id');
            $table->string('file_name');
            $table->tinyInteger('is_thumbnail')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->tinyInteger('featured')->default(0)->comment('0 -> No , 1 -> Yes');
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
        Schema::dropIfExists('product_images');
    }
};
