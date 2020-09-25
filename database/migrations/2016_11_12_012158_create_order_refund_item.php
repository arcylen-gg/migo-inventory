<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_order_refund_item', function (Blueprint $table) {
            $table->increments('tbl_order_refund_item_id');
            $table->integer('tbl_order_id');
            $table->integer('tbl_order_refund_id')->unsigned();
            $table->integer('tbl_order_item_id')->unsigned();
            $table->double('item_amount',18,2);
            $table->integer('refund_quantity');
        });
        Schema::table('tbl_order_refund_item', function($table) {
           $table->foreign('tbl_order_refund_id')->references('tbl_order_refund_id')->on('tbl_order_refund');
           $table->foreign('tbl_order_item_id')->references('tbl_order_item_id')->on('tbl_order_item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_order_refund_item');
    }
}
