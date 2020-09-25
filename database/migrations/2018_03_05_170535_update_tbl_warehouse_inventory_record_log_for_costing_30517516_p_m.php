<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouseInventoryRecordLogForCosting30517516PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_inventory_record_log', function (Blueprint $table) {
            $table->double("record_sales_price")->default(0);
            $table->double("record_cost_price")->default(0);
        });

        Schema::create('tbl_item_pricing_history', function (Blueprint $table) {
            $table->increments("pricing_history_id");
            $table->integer("pricing_shop_id")->unsigned();
            $table->integer("pricing_item_id")->unsigned();
            $table->integer("pricing_user_id")->unsigned();
            $table->double("pricing_sales_price");
            $table->double("pricing_cost_price");
            $table->datetime("pricing_created");

            $table->foreign("pricing_shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
            $table->foreign("pricing_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
            $table->foreign("pricing_user_id")->references("user_id")->on("tbl_user")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouse_inventory_record_log', function (Blueprint $table) {
            //
        });
    }
}
