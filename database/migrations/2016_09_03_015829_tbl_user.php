<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user', function (Blueprint $table)
        {
            $table->engine = 'InnoDB';
            $table->increments('user_id');
            // $table->string('user_email')->unique();
            $table->string('user_email');
            $table->string('user_level')->default('sub');
            $table->string('user_first_name');
            $table->string('user_last_name');
            $table->string('user_contact_number');
            $table->text('user_password');
            $table->dateTime('user_date_created')->default('1000-01-01 00:00:00');
            $table->dateTime('user_last_active_date')->default('1000-01-01 00:00:00');
            $table->integer('user_shop')->unsigned();
            $table->foreign('user_shop')->references('shop_id')->on('tbl_shop');
            $table->tinyInteger('IsWalkin');
            $table->tinyInteger('archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_user');
    }
}
