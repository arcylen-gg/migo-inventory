<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRefillableTransactionBinLocation4242018557PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            $table->integer("poline_sub_wh_id")->unsigned()->nullable()->after("poline_description");
            $table->foreign("poline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_receive_inventory_line', function (Blueprint $table) {
            $table->integer("riline_sub_wh_id")->unsigned()->nullable()->after("riline_description");
            $table->foreign("riline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_bill_item_line', function (Blueprint $table) {
            $table->integer("itemline_sub_wh_id")->unsigned()->nullable()->after("itemline_description");
            $table->foreign("itemline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_write_check_line', function (Blueprint $table) {
            $table->integer("wcline_sub_wh_id")->unsigned()->nullable()->after("wcline_description");
            $table->foreign("wcline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_credit_memo_line', function (Blueprint $table) {
            $table->integer("cmline_sub_wh_id")->unsigned()->nullable()->after("cmline_description");
            $table->foreign("cmline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_purchase_order_line', function (Blueprint $table) {
            //
        });
    }
}
