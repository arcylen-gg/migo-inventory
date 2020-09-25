<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemPerWarehouse082220181159am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_average_cost_per_warehouse', function (Blueprint $table) {
            $table->increments('iacpw_id');
            $table->integer('iacpw_item_id')->unsigned();
            $table->integer('iacpw_warehouse_id')->unsigned();
            $table->integer('iacpw_shop_id')->unsigned();
            $table->integer('iacpw_qty')->default(0);
            $table->double('iacpw_ave_cost')->default(0);
            $table->datetime('iacpw_date');

            $table->foreign('iacpw_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('iacpw_warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
            $table->foreign('iacpw_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_per_warehouse');
    }
}
