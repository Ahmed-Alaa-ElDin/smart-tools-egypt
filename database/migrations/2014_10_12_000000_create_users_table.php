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
            $table->string('email',50)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('gender')->default(0)->comment('0 -> Male , 1 -> Female');
            $table->tinyInteger('banned')->default(0)->comment('0 -> No , 1 -> Yes');
            $table->date('birth_date')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->decimal('balance')->default(0);
            $table->string('auth_id')->nullable();
            $table->string('auth_type')->nullable();
            $table->integer('visit_num')->default(1)->unsigned();
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
