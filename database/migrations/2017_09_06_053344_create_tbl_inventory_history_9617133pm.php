<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblInventoryHistory9617133pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inventory_history', function (Blueprint $table) {
            $table->increments('history_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('warehouse_id')->unsigned();
            $table->longtext('history_description')->comment('developer remarks');
            $table->longtext('history_remarks')->comment('user remarks');
            $table->string('history_type')->comment('WIS or RR');
            $table->string('history_reference')->comment('vendor, customer, warehouse, etc');
            $table->integer('history_reference_id')->comment('id of history reference');
            $table->string('history_number');
            $table->datetime('history_date');
            $table->integer('history_user');

            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
        });

        Schema::create('tbl_inventory_history_items', function(Blueprint $table)
        {
            $table->increments('history_item_id');

            $table->integer('history_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('quantity');
            $table->longtext('item_remarks')->comment('Remarks per Item');
            $table->integer('initial_quantity');
            $table->integer('running_quantity');

            $table->foreign('history_id')->references('history_id')->on('tbl_inventory_history')->onDelete('cascade');
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
        Schema::dropIfExists('tbl_inventory_history');
    }
}
