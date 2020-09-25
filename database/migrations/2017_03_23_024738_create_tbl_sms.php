<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sms_key', function (Blueprint $table) {
            $table->increments('sms_id');
            $table->integer('sms_shop_id')->unsigned();
            $table->string('sms_authorization_key');
            $table->dateTime('date_created');

            $table->foreign('sms_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });

        Schema::create('tbl_sms_template', function (Blueprint $table) {
            $table->increments('sms_temp_id');
            $table->integer('sms_temp_shop_id')->unsigned();
            $table->string('sms_temp_key');
            $table->string('sms_temp_content');
            $table->tinyInteger('sms_temp_is_on');
            $table->dateTime('date_created');

            $table->foreign('sms_temp_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_sms');
    }
}
