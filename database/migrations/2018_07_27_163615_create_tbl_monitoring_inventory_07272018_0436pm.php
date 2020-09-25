<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMonitoringInventory072720180436pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_monitoring_inventory', function (Blueprint $table) {
            $table->increments('invty_id');
            $table->integer('invty_shop_id')->unsigned();
            $table->integer('invty_warehouse_id')->unsigned();
            $table->integer('invty_item_id')->unsigned();

            $table->string('invty_transaction_name');
            $table->integer('invty_transaction_id');
            $table->integer('invty_qty')->default(0);
            $table->integer('invty_stock_on_hand')->default(0);
            $table->double('invty_cost_price')->default(0);
            $table->double('invty_total_cost_price')->default(0);
            $table->double('invty_sales_price')->default(0);
            $table->double('invty_total_sales_price')->default(0);
            $table->datetime('invty_date_created');
            
            $table->foreign('invty_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('invty_warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
            $table->foreign('invty_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
