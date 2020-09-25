<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWarehouse52530 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouse', function (Blueprint $table) {
            $table->increments('warehouse_id');
            $table->string("warehouse_name");
            $table->integer("warehouse_shop_id")->unsigned();
            $table->datetime("warehouse_created");
            $table->datetime("warehouse_last_transfer");

            $table->foreign('warehouse_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });

        Schema::create('tbl_warehouse_inventory', function (Blueprint $table) {
            $table->increments('inventory_id');
            $table->integer("inventory_item_id")->unsigned();
            $table->integer("warehouse_id")->unsigned();
            $table->datetime("inventory_created");
            $table->integer("inventory_count");

            $table->foreign('inventory_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_warehouse');
    }
}
