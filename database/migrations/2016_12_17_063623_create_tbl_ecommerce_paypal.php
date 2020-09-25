<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblEcommercePaypal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_ecommerce_paypal', function (Blueprint $table) {
            $table->increments('ecommerce_paypal_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->integer('ecommerce_setting_id');
            $table->text('paypal_clientid');
            $table->text('paypal_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_ecommerce_paypal');
    }
}
