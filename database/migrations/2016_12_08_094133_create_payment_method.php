<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payment_method', function (Blueprint $table) {
            $table->increments('payment_method_id');
            $table->integer('shop_id')->unsigned()->nullable();
            $table->string('payment_name');
            $table->tinyInteger('isDefault');
            $table->tinyInteger('archived');
            
        });
        Schema::table('tbl_payment_method', function($table) {
           $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
           
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payment_method');
    }
}
