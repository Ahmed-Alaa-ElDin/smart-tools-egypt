<?php

use Carbon\Carbon;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name')->nullable();
            $table->string('email',50)->unique();
            $table->string('password');
            $table->string('phone',20)->nullable();
            $table->tinyInteger('gender')->default(0)->comment('0 -> Male , 1 -> Female');
            $table->date('birth_date')->default(Carbon::now());
            $table->string('profile_photo_path', 2048)->nullable();
            $table->decimal('balance')->default(0);
            $table->integer('points')->default(0);
            $table->integer('visit_num')->default(1)->unsigned();
            $table->timestamp('last_visit_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
