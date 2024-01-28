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
        Schema::create('collection_collection_related', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('first_collection_id');
            $table->unsignedBigInteger('second_collection_id');
            $table->tinyInteger('rank')->default(0);
            $table->timestamps();

            $table->foreign('first_collection_id')
                ->references('id')
                ->on('collections')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('second_collection_id')
                ->references('id')
                ->on('collections')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_collection_related');
    }
};
