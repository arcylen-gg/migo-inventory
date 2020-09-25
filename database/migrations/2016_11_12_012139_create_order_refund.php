<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_order_refund', function (Blueprint $table) {
            $table->increments('tbl_order_refund_id');
            $table->integer('tbl_order_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->text('refund_reason');
            $table->datetime('refund_date');
            $table->tinyInteger('archived');
        });
         Schema::table('tbl_order_refund', function($table) {
           $table->foreign('tbl_order_id')->references('tbl_order_id')->on('tbl_order');
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
        Schema::drop('tbl_order_refund');
    }
}
