<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWarehouseInventoryXxxx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_inventory', function (Blueprint $table) {
           $table->dropColumn("item_reorder_point");
        });

        Schema::create('tbl_sub_warehouse', function (Blueprint $table) {
           $table->increments("sub_warehouse_item_id");
           $table->integer("warehouse_id")->unsigned();
           $table->integer("item_id")->unsigned();
           $table->integer("item_reorder_point");

           $table->foreign('warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
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
        Schema::table('tbl_warehouse_inventory', function (Blueprint $table) {
            //
        });
    }
}
