<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblInventorySerialNumberV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inventory_serial_number', function (Blueprint $table) 
        {
            $table->increments('serial_id');
            $table->integer('inventory_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('serial_number');
            $table->datetime('serial_created');
            $table->integer('item_count');
            $table->tinyInteger('item_consumed');

            $table->foreign('inventory_id')->references('inventory_id')->on('tbl_warehouse_inventory')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_inventory_serial_number');
    }
}
