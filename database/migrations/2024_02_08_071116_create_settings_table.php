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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('back_pagination')->default(10);
            $table->integer('front_pagination')->default(12);
            $table->decimal('points_conversion_rate', 8, 2)->default(0.1);
            $table->integer('points_expiry')->default(90);
            $table->integer('return_period')->default(14);
            $table->string('last_box_name')->default('{"ar":"عرض آخر كرتونة","en":"Last Box Offer"}');
            $table->integer('last_box_quantity')->default(0);
            $table->string('new_arrival_name')->default('{"ar":"عرض جديد الموقع","en":"New Arrival Offer"}');
            $table->integer('new_arrival_period')->default(0);
            $table->string('max_price_offer_name')->default('{"ar":"منتجات أقل من 500 جنية","en":"Products less than 500 EGP"}');
            $table->decimal('max_price_offer')->default(0);
            $table->string('whatsapp_number')->default('01010097248');
            $table->string('facebook_link')->default('https://www.facebook.com/SmartToolsEgypt/');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
