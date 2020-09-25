<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblInventoryAdjustmentLine060720181129am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_inventory_adjustment_line', function (Blueprint $table) {
            $table->integer("itemline_sub_wh_id")->unsigned()->nullable()->after("itemline_item_description");
            $table->foreign("itemline_sub_wh_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_inventory_adjustment_line', function (Blueprint $table) {
            //
        });
    }
}
