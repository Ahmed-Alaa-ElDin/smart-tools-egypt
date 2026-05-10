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
        Schema::create('order_edit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('edited_by');
            $table->json('snapshot_before');
            $table->json('snapshot_after');
            $table->json('changes_summary');
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('edited_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_edit_logs');
    }
};
