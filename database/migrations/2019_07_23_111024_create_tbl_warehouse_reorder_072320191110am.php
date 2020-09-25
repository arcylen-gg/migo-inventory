<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWarehouseReorder072320191110am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouse_reorder', function (Blueprint $table) {
            $table->increments('wr_id');
            $table->integer('wr_item_id')->unsigned();
            $table->integer('wr_warehouse_id')->unsigned();
            $table->double('warehouse_reorder');

            $table->foreign("wr_item_id")->references("item_id")->on("tbl_item")->onCascade("delete");
            $table->foreign("wr_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onCascade("delete");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_warehouse_reorder');
    }
}
