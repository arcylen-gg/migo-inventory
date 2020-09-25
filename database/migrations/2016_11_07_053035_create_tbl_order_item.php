<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblOrderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_order_item', function (Blueprint $table) {
            $table->increments('tbl_order_item_id');
            $table->integer('tbl_order_id')->unsigned();
            $table->integer('variant_id')->unsigned();
            $table->double('item_amount',18,2);
            $table->integer('quantity');
            $table->double('discount',18,2);
            $table->string('discount_reason');
            $table->string('discount_var',50);
            $table->tinyInteger('IsCustom');
            $table->tinyInteger('archived');
            $table->tinyInteger('refunded');
        });
        Schema::table('tbl_order_item', function($table) {
           $table->foreign('tbl_order_id')->references('tbl_order_id')->on('tbl_order');
           $table->foreign('variant_id')->references('variant_id')->on('tbl_variant');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_order_item');
    }
}
