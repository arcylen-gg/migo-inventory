<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWarehouseInventoryItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouse_inventory_record_log', function (Blueprint $table) {
            $table->increments('record_log_id');
            $table->integer('record_shop_id')->unsigned();
            $table->integer('record_item_id')->unsigned();
            $table->integer('record_warehouse_id')->unsigned();
            $table->longtext('record_item_remarks')->nullable();
            $table->integer('record_warehouse_slip_id')->default(0);

            $table->tinyInteger('record_inventory_status')->default(0)->comment("0 for not use and 1 for consume");

            $table->string('record_source_ref_name')->nullable();
            $table->integer('record_source_ref_id')->default(0);

            $table->string('record_consume_ref_name')->nullable();
            $table->integer('record_consume_ref_id')->default(0);

            $table->string('record_serial_number')->nullable();
            $table->longtext('record_log_history')->nullable()->comment("Item history on warehouse (serialize)");

            $table->foreign('record_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
            $table->foreign('record_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('record_warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_warehouse_inventory_item');
    }
}
