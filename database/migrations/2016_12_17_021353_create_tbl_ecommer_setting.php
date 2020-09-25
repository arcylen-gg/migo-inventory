<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcommerSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ecommerce_setting', function (Blueprint $table) {
            $table->increments('ecommerce_setting_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->string('ecommerce_setting_code');
            $table->tinyInteger('ecommerce_setting_enable');
            $table->string('ecommerce_setting_url');
            $table->dateTime('ecommerce_setting_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_ecommerce_setting');
    }
}
