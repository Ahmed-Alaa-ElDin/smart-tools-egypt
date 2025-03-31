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
        Schema::create('back_to_stock_notifiables', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable');
            $table->unsignedBigInteger('notification_id');
            $table->timestamps();

            $table->foreign('notification_id')->references('id')->on('back_to_stock_notifications')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_to_stock_notifiables');
    }
};
