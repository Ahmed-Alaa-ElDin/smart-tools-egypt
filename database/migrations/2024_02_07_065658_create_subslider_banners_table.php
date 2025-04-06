<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subslider_banners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id');
            $table->tinyInteger('rank')->default(127);
            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

            $table->foreign('banner_id')->references('id')->on('banners')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subslider_banners');
    }
};
